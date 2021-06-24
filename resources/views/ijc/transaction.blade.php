@extends('ijc.layout')

@section('content')
    <input type="date" id="datefilter" name="datefilter" style="margin-right: 270px;"> list data "Transaction"
    <br>
    <br>
    <table class="table table-bordered" id="transaction-table">
        <thead>
            <tr>
                <th>akun</th>
                <th>no order</th>
                <th>date</th>
                <th>lazada sku</th>
                <th>seller sku</th>
                <th>detail</th>
                <th>amount</th>
            </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
    let dateset = "2021-01-01";
    $("#datefilter").val(dateset);
    $("#datefilter").change(function(e){
        dateset = e.target.value
        var table = $('#transaction-table').DataTable();
        table.ajax.url('/api/transactions?date=' + dateset).load();
    });

    $(function() {
        $('#transaction-table').DataTable({
            processing: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'info': true,
            ajax: '/api/transactions?date=' + dateset,
            columns: [
                { data: 'nama_akun' },
                { data: 'order_no' },
                { data: 'transaction_date' },
                { data: 'lazada_sku' },
                { data: 'seller_sku' },
                { data: 'details' },
                { data: 'amount' },
            ]
        });
    });
</script>
@endpush