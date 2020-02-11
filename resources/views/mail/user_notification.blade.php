<h1>Thank you for creating a quote {{ $name }}</h1>

<p>Please register <a href="{{ route('mail_callback' , ['author_name' => $name]) }}">here</a></p>