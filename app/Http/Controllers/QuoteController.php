<?php 

namespace App\Http\Controllers;

use App\Author;
use App\Quote;
use Illuminate\Http\Request;
use App\Events\QuoteCreated;
use Illuminate\Support\Facades\Event;
use App\AuthorLog;

class QuoteController extends Controller {

    public function getIndex($author = null) {
        if(!is_null($author)) {
            $quote_author = Author::where('name', $author)->first();
            if($quote_author) {
                $quotes = $quote_author->quotes()->orderBy('created_at', 'desc')->paginate(3);
            }

        }else {
            $quotes = Quote::orderBy('created_at', 'desc')->paginate(3);
        }

        return view('index', ['quotes' => $quotes]);
    }   


    public function postQuote(Request $request) {

        $this->validate($request, [
            'author' => 'required | max:60 | alpha',
            'quote' => 'required | max:500',
            'email' => 'required | email'
        ]);

        $quoteText = $request['quote'];
        $authorText = ucfirst($request['author']);

        $author = Author::where('name', $authorText)->first();

        if(!$author) {
            $author = new Author();
            $author->name = $authorText;
            $author->email = $request['email'];
            $author->save();
        }

        $quote = new Quote();
        $quote->quote = $quoteText;
        $author->quotes()->save($quote);

        Event::fire(new QuoteCreated($author));

        return redirect()->route('index')->with([
            'success' => "Quote successfully saved!!"
        ]);
    }


    public function getDeleteQuote($quote_id) {
        //$quote = Quote::where('id', $quote_id)->first()
        $quote = Quote::find($quote_id);
        $author_deleted = false;

        if(count($quote->author->quotes) === 1) {
            $quote->author->delete();
            $author_deleted = true;
        }
        $quote->delete();

        $msg = $author_deleted ? 'Quote and Author successfully deleted' : 'Quote successfully deleted';  
        return redirect()->route('index')->with(['success' => $msg]);
    }

    public function gotMailCallback($author_name) {

        $author_log = new AuthorLog();
        $author_log->author = $author_name;
        $author_log->save();

        return view('mail.callback', ['author' => $author_name] );
    }

}

