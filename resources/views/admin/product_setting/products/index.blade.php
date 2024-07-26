@extends('layouts.app')
@section('title', 'Products')

@section('content')
    <div class="card-head-icon">
        <i class='bx bx-package' style="color: rgb(131, 53, 8);"></i>
        <div>Products</div>
    </div>

    <div class="card mt-3">
        <div class="d-flex justify-content-between m-3">
            <span>Products List</span>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary text-decoration-none text-white"><i
                class='bx bxs-plus-circle me-2'></i>
            Create New Products</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped w-100" id="DataTable">
                <thead>
                    <th class="no-sort"></th>
                    <th class="no-sort">Main Photo</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th class="no-sort">Photos</th>
                    <th>Price</th>
                    <th class="no-sort text-nowrap">Action</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
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
                    @include('admin.product_setting.products.form')
                </div>
                </div>
            </div>
        </div>
        <!-- detail popup -->
    </div>
@endsection

@section('scripts')
    <script>
        Dropzone.autoDiscover = false;
        let series = {!! json_encode($series) !!};
        var dropzone_del_url = "{{ route('admin.products.deleteMedia') }}";
        var dropzone_store_url = "{{ route('admin.products.storeMedia') }}";
    </script>
    <script src="{{asset('js/product_setting/product.js')}}"></script>
@endsection
