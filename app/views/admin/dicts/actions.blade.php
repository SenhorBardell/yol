@foreach($actions as $action)
    <a href="{{ $action['link'] }}" class="btn btn-primary btn-md margin">{{ $action['text'] }}</a>
@endforeach