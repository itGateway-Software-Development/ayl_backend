<div class="my-3">
    <span class="error text-danger"></span>
</div>
<form id="{{request()->is('admin/product_setting/categories/create') ? 'category_create_form' : 'category_edit_form'}}">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="form-group mb-4">
                <label for="">Name</label>
                <input type="text" name="category_name" class="form-control category_name" value="">
                <span class="text-danger category_name_err"></span>
            </div>
        </div>

    </div>
    <div class="mt-5">
        @if (request()->is('admin/product_setting/categories/create'))
            <button class="btn btn-secondary back-btn" onclick="location.href='{{route('admin.categories.index')}}'">Cancel</button>
        @else
            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
        @endif
        <button class="btn btn-primary">Create</button>

    </div>
</form>
