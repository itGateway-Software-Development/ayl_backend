@extends('layouts.app')
@section('title', 'Create Series')

@section('content')
    <div class="card-head-icon">
        <i class='bx bx-slider-alt' style="color: rgb(19, 76, 141);"></i>
        <div>Series Creation</div>
    </div>
    <div class="card mt-3 p-4">
        <span class="mb-4">Series Creation</span>
        @include('admin.product_setting.series.form')

    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/product_setting/series.js')}}"></script>
@endsection
