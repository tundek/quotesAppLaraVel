@extends('layouts/master')

@section('title')
    Trending Quotes
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')

    @if(!empty(Request::segment(1)))
        <section class="filter-bar">
            A filter has been set <a href="{{ route('index') }}">Show all quotes</a>
        </section>
    @endif

    @if(Session::has('success'))   
        <section class="info-box success">
            {{ Session::get('success') }}
        </section>
    @endif

    @if(count($errors) > 0)
        <section class="info-box fail">
            @foreach($errors->all() as $error)
                {{ $error }}
            @endforeach
        </section>
    @endif
    <section class="quotes">
        <h1>Latest Quotes</h1>
        @for($i=0; $i < count($quotes); $i++)
            <!-- <article class="quote{{ $i % 3 === 0 ? ' first-in-line' : (($i+1) % 3 === 0 ? ' last-in-line' : '') }}"> -->
            <article class="quote">
                <div class="delete"><a href="{{ route('delete', ['quote_id' => $quotes[$i]->id ]) }}">x</a></div>
                {{ $quotes[$i]->quote }}
                <div class="info">Created By <a href="{{ route('index', ['author' => $quotes[$i]->author->name ]) }}"> {{ $quotes[$i]->author->name }} </a> on {{ $quotes[$i]->created_at->format('h:i:s A d.M.y') }}</div>
            </article>
        @endfor
        <div class="pagination"> 
            @if($quotes->currentPage() !== 1)
                <a href="{{ $quotes->previousPageURL() }}"> <span class="fa fa-caret-left"></span></a>
            @endif

            @if($quotes->currentPage() !== $quotes->lastPage() && $quotes->hasPages())
                <a href="{{ $quotes->nextPageURL() }}"><span class="fa fa-caret-right"></span></a>
            @endif
        </div>
        
    </section>
    
    <section class="edit-quote">
        <h1>Add quotes</h1>

        <form method="post" action="{{ route('create') }}">
            <div class="input-group">
                <label for="author">Your name</label>
                <input type="text" name="author" id="author" placeholder="Enter your name">
            </div>

            <div class="input-group">
                <label for="email">Your E-Mail</label>
                <input type="text" name="email" id="email" placeholder="Enter your E-Mail">
            </div>

            <div class="input-group">
                <label for="quote">Your Quote</label>
                <textarea name="quote" id="quote" cols="30" rows="5" placeholder="quotes"></textarea>
            </div>
            <button type="submit" class="btn">Submit Quote</button>
            <input type="hidden" name="_token" value="{{ Session::token() }}">
        </form>
    </section>
@endsection