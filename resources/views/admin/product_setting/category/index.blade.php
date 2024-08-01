@extends('layouts.app')
@section('title', 'Category')

@section('content')
    <div class="card-head-icon">
        <i class='bx bxs-pie-chart-alt-2' style="color: rgb(19, 76, 141);"></i>
        <div>Category</div>
    </div>

    <div class="card mt-3">
        <div class="d-flex justify-content-between m-3">
            <span>Category List</span>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary text-decoration-none text-white"><i
                class='bx bxs-plus-circle me-2'></i>
            Create New Category</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="DataTable">
                <thead>
                    <th class="no-sort"></th>
                    <th class="no-sort">Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="no-sort text-nowrap">Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel4">Edit Category</h4>
                    <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.product_setting.category.form')
                </div>
                </div>
            </div>
        </div>
        <!-- detail popup -->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/product_setting/category.js')}}"></script>
@endsection
