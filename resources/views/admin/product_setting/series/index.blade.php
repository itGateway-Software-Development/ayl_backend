@extends('layouts.app')
@section('title', 'Series')

@section('content')
    <div class="card-head-icon">
        <i class='bx bx-slider-alt' style="color: rgb(19, 76, 141);"></i>
        <div>Series</div>
    </div>

    <div class="card mt-3">
        <div class="d-flex justify-content-between m-3">
            <span>Series List</span>
            <a href="{{ route('admin.series.create') }}" class="btn btn-primary text-decoration-none text-white"><i
                class='bx bxs-plus-circle me-2'></i>
            Create New Series</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="DataTable">
                <thead>
                    <th class="no-sort"></th>
                    <th>Name</th>
                    <th>Category</th>
                    <th class="no-sort text-nowrap">Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="seriesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel4">Edit Series</h4>
                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.product_setting.series.form')
                </div>
                </div>
            </div>
        </div>
        <!-- detail popup -->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/product_setting/series.js')}}"></script>
@endsection
