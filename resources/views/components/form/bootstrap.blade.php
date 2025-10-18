@props(['action' => '', 'method' => 'GET', 'enctype' => "application/x-www-form-urlencoded"] )

<form action="{{ $action }}" method="{{ $method }}" enctype="{{ $enctype }}">
    @csrf
    @if (!in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif
    {{ $slot }}
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
