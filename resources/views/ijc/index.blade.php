@extends('ijc.layout')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>name</th>
                <th>model</th>
                <th>brand</th>
                <th>url</th>
            </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        ajax: 'api/products',
        columns: [
            { data: 'name' },
            { data: 'model' },
            { data: 'brand' },
            { data: 'url' ,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a href='"+oData.url+"' target='_blank'>Link Web</a>");
                }
            },
        ]
    });
});
</script>
@endpush