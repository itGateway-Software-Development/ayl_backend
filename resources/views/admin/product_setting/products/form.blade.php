<div class="my-3">
    <span class="error text-danger"></span>
</div>
<form id="{{request()->is('admin/product_setting/products/create') ? 'product_create_form' : 'product_edit_form'}}">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Name <span class="text-danger">*</span></label>
                <input type="text" name="product_name" class="form-control product_name" value="">
                <span class="text-danger product_name_err"></span>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12" id="series_select">
            <div class="form-group mb-4">
                <label for="">Series <span class="text-danger">*</span></label>
                <select name="series_id" id="" class="form-select series_id" data-placeholder="---Please Select---">
                    <option value=""></option>
                    @foreach ($series as $sery)
                        <option value="{{$sery->id}}">{{$sery->name}}</option>
                    @endforeach
                </select>
                <span class="text-danger product_name_err"></span>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Category</label>
                <input type="hidden" name="category_id" class="category_id">
                <input type="text" name="category_name" readonly class="form-control category_name" value="">
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Product Info <span class="text-danger">*</span></label>
                <input type="text" name="product_info" class="form-control product_info" value="" placeholder="Eg. 1 Box in 2 PCS">
                <span class="text-danger product_info_err"></span>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Product Price <span class="text-danger">*</span></label>
                <input type="number" min="1" name="product_price" class="form-control product_price" value="">
                <span class="text-danger product_price_err"></span>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="border row p-3 rounded">
            <h4 class="text-warning mb-3">Stock for sizes</h4>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">Medium Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="m_size_stock" class="form-control m_size_stock" value="">
                    <span class="text-danger m_size_stock_err"></span>
                </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">Large Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="lg_size_stock" class="form-control lg_size_stock" value="">
                    <span class="text-danger lg_size_stock_err"></span>
                </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">XL Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="xl_size_stock" class="form-control xl_size_stock" value="">
                    <span class="text-danger xl_size_stock_err"></span>
                </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">2XL Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="xxl_size_stock" class="form-control xxl_size_stock" value="">
                    <span class="text-danger xxl_size_stock_err"></span>
                </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">3XL Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="xxxl_size_stock" class="form-control xxxl_size_stock" value="">
                    <span class="text-danger xxxl_size_stock_err"></span>
                </div>
            </div>
            <div class="col-6 col-sm-3 col-md-2">
                <div class="form-group mb-4">
                    <label for="">4XL Stock <span class="text-danger">*</span></label>
                    <input type="number" min="0" value="0" name="xxxxl_size_stock" class="form-control xxxxl_size_stock" value="">
                    <span class="text-danger xxxxl_size_stock_err"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <h4 class="text-warning mb-3">Images for product showcase</h4>
        <div class="col-md-4 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Main Image <span class="text-danger">*</span></label>
                <input type="file" name="main_image" class="form-control main_image" value="" >
                <span class="text-danger main_image_err"></span>
                <img src="" class="mt-3" style="object-fit: cover;" alt="" id="preview_img">
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="form-group mb-4">
                <label for="">Photos for details</label>
                <div class="needslick dropzone edit-product-image-dropzone" id="product-image-dropzone">

                </div>
            </div>
        </div>
    </div>
    <div class="mt-5">
        @if (request()->is('admin/product_setting/products/create'))
            <button class="btn btn-secondary back-btn" onclick="location.href='{{route('admin.products.index')}}'">Cancel</button>
            <button class="btn btn-primary">Create</button>
        @else
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button class="btn btn-primary">Update</button>
        @endif

    </div>
</form>
