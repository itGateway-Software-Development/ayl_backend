@extends('layouts.app')
@section('title', 'Orders')
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div class="card-head-icon">
        <i class='bx bxs-cart' style="color: rgb(121, 19, 19);"></i>
        <div>Orders</div>
    </div>

    <div class="card mt-3">
        <div class="d-flex justify-content-between m-3">
            <span>Orders List</span>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="DataTable">
                <thead>
                    <th class="no-sort"></th>
                    <th class="no-sort">Date</th>
                    <th>Order No</th>
                    <th class="no-sort">Customer Name</th>
                    <th class="no-sort">Payment Image</th>
                    <th class="no-sort">Status</th>
                    <th class="no-sort text-nowrap">Detail</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-6 fw-bold" id="exampleModalLabel4">Point History</h4>
                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.order_setting.order.detail')
                </div>
                </div>
            </div>
        </div>
        <!-- detail popup -->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/order_setting/orders.js')}}"></script>
@endsection
