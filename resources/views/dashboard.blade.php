@extends('layouts.app')

@section('style')
    <style>

        .dashboard .card .row {
            padding: 25px 20px;
        }

        .dashboard .user-card {
            background: #17a2b8;
        }

        .dashboard .user-card .more-info {
            background: #0d8496;
        }

        .dashboard .category-card {
            background: #28a745;
        }

        .dashboard .category-card .more-info {
            background: #1d682f;
        }

        .dashboard .product-card {
            background: #ffc107;
        }

        .dashboard .product-card .more-info {
            background: #977c2b;
        }

        .dashboard .order-card {
            background: #dc3545;
        }

        .dashboard .order-card .more-info {
            background: #88323b;
        }

        .dashboard .card h1 {
            font-weight: bold;
            color: rgb(206, 195, 195);
        }

        .dashboard .card h6 {
            margin-top: -10px;
            color: rgb(231, 224, 224);
        }

        .dashboard .card .img {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard .card .img img {
            width: 80px;
        }

        .dashboard .more-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            padding: 2px 0;
            background: cyan;
        }

        .dashboard .more-info span {
            font-weight: bold;
            color: rgb(231, 224, 224);
        }

        .dashboard .more-info i {
            color: rgb(231, 224, 224);
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper dashboard">
        <h2 class="fw-bold">Welcome to AYL Dashboard</h2>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card user-card">
                    <div class="row">
                        <div class="col-6">
                            <div class="info">
                                <h1>6</h1>
                                <h6>System Users</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="img">
                                <img src="{{asset('assets/images/dashboard/user.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="more-info">
                        <span>More Info</span>
                        <i class='bx bxs-right-arrow-circle'></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card category-card">
                    <div class="row">
                        <div class="col-6">
                            <div class="info">
                                <h1>30</h1>
                                <h6>Cateogry</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="img">
                                <img src="{{asset('assets/images/dashboard/category.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="more-info">
                        <span>More Info</span>
                        <i class='bx bxs-right-arrow-circle'></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card product-card">
                    <div class="row">
                        <div class="col-6">
                            <div class="info">
                                <h1 class="text-dark">30</h1>
                                <h6 class="text-dark">Products</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="img">
                                <img src="{{asset('assets/images/dashboard/product.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="more-info">
                        <span>More Info</span>
                        <i class='bx bxs-right-arrow-circle'></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card order-card">
                    <div class="row">
                        <div class="col-6">
                            <div class="info">
                                <h1>30</h1>
                                <h6>Orders</h6>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="img">
                                <img src="{{asset('assets/images/dashboard/order.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="more-info">
                        <span>More Info</span>
                        <i class='bx bxs-right-arrow-circle'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
