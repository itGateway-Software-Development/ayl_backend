@extends('layouts.app')
@section('title', 'Create Products')

@section('content')
    <div class="card-head-icon">
        <i class='bx bx-package' style="color: rgb(131, 53, 8);"></i>
        <div>Products Creation</div>
    </div>
    <div class="card mt-3 p-4">
        <span class="mb-4">Products Creation</span>
        @include('admin.product_setting.products.form')

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
