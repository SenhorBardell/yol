@foreach($data as $row)
    @include('includes.admin.user-label', [
        'data' => $row
    ])
@endforeach