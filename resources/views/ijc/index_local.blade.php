@extends('ijc.layout')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>name</th>
                <th>model</th>
                <th>brand</th>
                <th>status</th>
                <th>qty</th>
                <th>qty lazada</th>
                <th>url</th>
                <th>action</th>
            </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        ajax: 'api/local-products',
        columns: [
            { data: 'name' },
            { data: 'model' },
            { data: 'brand' },
            { data: 'status' },
            { data: 'qty_local' },
            { data: 'qty' },
            { data: 'url' ,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a href='"+oData.url+"' target='_blank'>Link Web</a>");
                }
            },
            { data: 'id' ,
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a href='ijclocal/edit/"+oData.id+"'>Edit</a>");
                }
            },
        ]
    });
});
</script>
@endpush