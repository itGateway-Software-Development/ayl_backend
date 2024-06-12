@extends('layouts.app')
@section('title', 'Create Series')

@section('content')
    <div class="card-head-icon">
        <i class='bx bxs-pie-chart-alt-2' style="color: rgb(19, 76, 141);"></i>
        <div>Category Creation</div>
    </div>
    <div class="card mt-3 p-4">
        <span class="mb-4">Category Creation</span>
        @include('admin.product_setting.category.form')

    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/product_setting/category.js')}}"></script>
@endsection
