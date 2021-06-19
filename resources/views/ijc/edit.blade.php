@extends('ijc.layout')

@section('content')

{{ $data->name }}

<br>
<br>
<form method="post" action="/ijclocal/update">
    {{ csrf_field() }}
    <input type="text" id="id" name="id" value="{{ $data->id }}" style="display: none;">
    <input type="text" id="itemid" name="itemid" value="{{ $data->itemid }}" style="display: none;">
    <input type="text" id="skuid" name="skuid" value="{{ $data->skuid }}" style="display: none;">
    quantity local 
    <br>
    <input type="number" id="qtylk" name="qtylk" min="1" value="{{ $data->qty_local }}">
    <br>
    quantity lazada
    <br>
    <input type="number" id="qtylz" name="qtylz" min="1" value="{{ $data->qty }}">
    <br>
    <br>
    <button type="submit" class="btn btn-primary">Update Qty</button>
</form>
@endsection