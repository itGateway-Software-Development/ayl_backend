@extends('layouts.app')
@section('title', 'Points')

@section('content')
    <div class="card-head-icon">
        <i class='bx bx-slider-alt' style="color: rgb(19, 76, 141);"></i>
        <div>Points</div>
    </div>

    <div class="card mt-3">
        <div class="d-flex justify-content-between m-3">
            <span>Points List</span>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="DataTable">
                <thead>
                    <th class="no-sort"></th>
                    <th>Customers</th>
                    <th>Phone</th>
                    <th>Created Date</th>
                    <th class="no-sort">Total Points</th>
                    <th class="no-sort text-nowrap">Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="pointDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
                    @include('admin.point_system.point.detail')
                </div>
                </div>
            </div>
        </div>
        <!-- detail popup -->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/point/point.js')}}"></script>
@endsection
