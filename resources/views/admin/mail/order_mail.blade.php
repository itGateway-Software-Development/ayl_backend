<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .order-email-form {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .customer-info {
            display: flex;
            justify-content: start;
            align-items: center;
            margin-bottom: 10px;
        }

        .customer-info .col {
            width: 40%;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        thead {
            background: rgb(156, 194, 212);
        }

        tr, th, td {
            padding: 10px 30px;
            border: 1px solid #dee2e6;
        }

        th {
            text-align: left;
            font-weight: bold;
        }

        tbody tr:nth-child(odd) {
            background: rgba(128, 128, 128, 0.1);
        }

        tbody tr:nth-child(even) {
            background: rgba(128, 128, 128, 0.2);
        }

        tbody tr:hover {
            background: rgba(128, 128, 128, 0.3);
        }

        tfoot tr td {
            font-weight: bold;
        }

        .photo {
            margin-top: 50px;
            max-width: 500px;
            border:none;
        }

        .photo img {
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="order-email-form">
        <h1>Order Detail</h1>
        <div class="customer-info">
            <div class="col">Name</div>
            <div class="col">: &nbsp; {{$data['name']}}</div>
        </div>
        <div class="customer-info">
            <div class="col">Phone</div>
            <div class="col">: &nbsp; {{$data['phone']}}</div>
        </div>
        <div class="customer-info">
            <div class="col">Address</div>
            <div class="col">: &nbsp; {{$data['address']}}</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['products'] as $product)
                    <tr>
                        <td>{{$product['cat']}} - {{ $product['code']}}</td>
                        <td>{{$product['size']}}</td>
                        <td>{{$product['color']}}</td>
                        <td>{{$product['quantity']}}</td>
                        <td>{{$product['price']}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Sub Total</td>
                    <td>{{ number_format($data['subTotal'], 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Delivery Charges</td>
                    <td>{{ number_format($data['deliveryPrice'], 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Point Charges</td>
                    <td>- &nbsp; {{ number_format($data['pointsUse'], 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Total</td>
                    <td>{{ number_format($data['subTotal'] + $data['deliveryPrice'] - $data['pointsUse'], 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="photo">
            @if($data['slip_image'])
                <img src="{{$data['slip_image']}}" alt="">
            @endif
        </div>
    </div>
</body>
</html>
