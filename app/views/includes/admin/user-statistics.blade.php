<table id="example2" class="table table-bordered table-hover dataTable">
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{$row['value']}}</td>
            <td>{{$row['string']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>