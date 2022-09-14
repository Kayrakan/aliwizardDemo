<html>
<head>

    <title></title>
</head>
<body>
<h1 style="text-align:right; font-weight:lighter; " >INVOICE</h1>
<div class='parent' style="">
    <div class='child' >
        <div style=" text-align: right; width: 10rem; " >Invoice number:</div>
    </div>
    <div class='child'>
        <div  style=" width: 10rem;  " >{{ $data['invoice_number']}}</div>
    </div>

</div>
<div class='parent' style="">
    <div class='child' >
        <div style=" text-align: right; width: 10rem;" >Date issued:</div>
    </div>
    <div class='child'>
        <div  style="width: 10rem;">{{ $data['date_issued']}}</div>
    </div>

</div>

<div style="margin-top:20px;">
    <div style=" font-size:large;"> Billing To: </div>

    <div style="line-height:5px; " >
        <p>{{$data['billing_to']}}</p>
        <p>{{$data['customer_tel']}}</p>
    </div>
    <div style="line-height:5px;">
        <p>{{$data['customer_address']}}</p>
        <p>{{$data['customer_city']}} - {{$data['customer_postcode']}}</p>
        <p>{{$data['customer_country_code']}}</p>
    </div>
</div>

<div style="text-align:right; margin-top:10px;">
    <table>
        <tr>
            <th style="text-align: center;" width="15%">Hs Code</th>
            <th width="50%" >Product</th>
            <th width="10%">Qty</th>
            <th width="10%">Total</th>

        </tr>
        @foreach ($data['order_items'] as $item)
            <tr>
                <td>{{$item->hsCode}}</td>
                <td >{{$item->productTitle}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{$item->amount}} USD</td>
            </tr>
        @endforeach

    </table>
    <div class='' style="margin-top:20px; text-align:right;">
        <div class='child'  style="text-align: right;">
            <div style=" text-align: center; width: 8rem; font-size:medium;" >TOTAL:</div>
        </div>
        <div class='child' >
            <div  style=" text-align: center; width: 8rem; font-size:medium; " >{{ $data['total_amount']}} USD</div>
        </div>

    </div>
</div>
<div style="margin-top:100px;">
    <div>
        <p>NOTES:</p>
    </div>
    <div style="font-weight: lighter; font-size:small">
        We declare that above information is true and correct to the best of our knowledge and the goods are of Turkish origin.
        PTS Worldwide Express or its associates are authorized to clear Customs on behalf of us.
    </div>

</div>

{{--    <p style="text-align:right; font-weight:bold;" >Invoice number: {{ $data['invoice_number'] }}</p>--}}
{{--    <p style="text-align:right; font-weight:bold; " >Date issued: {{ $data['date_issued'] }}</p>--}}

</body>
</html>
<style>
    .parent {
        text-align:right;
    }
    .child {
        display: inline-block;
        vertical-align: middle;
    }
    table {
        /*font-family: arial, sans-serif;*/
        border-collapse: collapse;
        width: 100%;
    }
    td, th {
        font-weight: lighter;
        font-size: medium;
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }

</style>
