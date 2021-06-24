@extends('ijc.layout')

@section('content')
    <input type="date" id="datefilter" name="datefilter" style="margin-right: 270px;"> list data "Order"
    <br>
    <br>
    <table class="table table-bordered" id="orders-table">
        <thead>
            <tr>
                <th>akun</th>
                <th>no order</th>
                <th>date</th>
                <th>buyer name</th>
                <th>payment method</th>
                <th>price</th>
                <th>shipping_fee_original</th>
                <th>shipping_fee</th>
                <th>status</th>
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
        var table = $('#orders-table').DataTable();
        table.ajax.url('/api/orders?date=' + dateset).load();
    });

    $(function() {
        $('#orders-table').DataTable({
            processing: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'info': true,
            ajax: '/api/orders?date=' + dateset,
            columns: [
                { data: 'nama_akun' },
                { data: 'order_number' },
                { data: 'created_at' },
                { data: 'address_billing.first_name' },
                { data: 'payment_method' },
                { data: 'price' },
                { data: 'shipping_fee_original' },
                { data: 'shipping_fee' },
                { data: 'statuses' },
            ]
        });
    });
</script>
@endpush