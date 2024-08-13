'use strict'

$(document).ready(function() {
    let edit_id = null;

    $('.series_id').select2({
        theme: "bootstrap-5",
        placeholder: $(this).data('placeholder'),
        dropdownParent: $('#series_select')
    })

    const table = new DataTable('#DataTable', {
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: '/admin/product_setting/products-list',
        columns: [{
                data: 'plus-icon',
                name: 'plus-icon'
            },
            {
                data: 'main_photo',
                name: 'main_photo'
            },
            {
                data: 'series_id',
                name: 'series_id'
            },
            {
                data: 'category_id',
                name: 'category_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'photos',
                name: 'photos'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'm_size_stock',
                name: 'm_size_stock'
            },
            {
                data: 'lg_size_stock',
                name: 'lg_size_stock'
            },
            {
                data: 'xl_size_stock',
                name: 'xl_size_stock'
            },
            {
                data: 'xxl_size_stock',
                name: 'xxl_size_stock'
            },
            {
                data: 'xxxl_size_stock',
                name: 'xxxl_size_stock'
            },
            {
                data: 'xxxxl_size_stock',
                name: 'xxxxl_size_stock'
            },
            {
                data: 'action',
                data: 'action',
            }
        ],
        columnDefs: [{
            targets: 'no-sort',
            sortable: false,
            searchable: false
        }, {
            targets: [0],
            class: 'control'
        }]
    })

    $(document).on('change', '.series_id', function() {
        let id = $(this).val();

        let select_series = series.find(sery => sery.id == id);

        $('.category_name').val(select_series.category.name);
        $('.category_id').val(select_series.category.id);
    })

    // show main image
    $(document).on('change', '.main_image', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#preview_img').attr('src', e.target.result).width(150).height(150);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    //for create form
    let uploadedImageMap = {}
    $("#product-image-dropzone").dropzone({
        url: dropzone_store_url,
        maxFilesize: 10,
        addRemoveLinks: true,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                "content"
            ),
        },
        success: function (file, response) {
            $("form").append(
                '<input type="hidden" name="files[]" value="' +
                    response.name +
                    '">'
            );
            uploadedImageMap[file.name] = response.name;
        },
        removedfile: function (file) {
            file.previewElement.remove();
            file.previewElement.remove();
            let name =
                file.file_name || uploadedImageMap[file.name];
            $(
                'input[name="files[]"][value="' + name + '"]'
            ).remove();

            $.ajax({
                url: dropzone_del_url,
                method: "POST",
                data: {
                    file_name: name,
                },
                headers: {
                    "X-CSRF-TOKEN": $(
                        'meta[name="csrf-token"]'
                    ).attr("content"),
                },
                success: function (response) {
                    console.log(
                        "File deleted successfully:",
                        response
                    );
                },
                error: function (xhr, status, error) {
                    console.error(
                        "Error deleting file:",
                        error
                    );
                },
            });
        },
        init: function () {
        },
    });

    //show edit popup
    $(document).on('click', '.edit-product', function() {
        let route = $(this).data('route');

        $.ajax({
            url: route,
            type: "GET",
            success: function(res) {
                edit_id = res.product.id;
                $('.product_name').val(res.product.name);
                $('.series_id').val(res.product.series_id).trigger('change');
                $('.category_id').val(res.product.category_id);
                $('.product_info').val(res.product.product_info);
                $('.product_price').val(res.product.price);
                $('.m_size_stock').val(res.product.m_size_stock);
                $('.lg_size_stock').val(res.product.lg_size_stock);
                $('.xl_size_stock').val(res.product.xl_size_stock);
                $('.xxl_size_stock').val(res.product.xxl_size_stock);
                $('.xxxl_size_stock').val(res.product.xxxl_size_stock);
                $('.xxxxl_size_stock').val(res.product.xxxxl_size_stock);

                let detail_images = [];

                for(const image of res.product.media) {
                    if(image.collection_name == "product_main_image") {
                        $('#preview_img').attr('src', image.original_url).width(150).height(150);
                    } else {
                        detail_images.push(image);
                    }
                }

                // Destroy existing Dropzone instance if it exists
                if (Dropzone.instances.length > 0) {
                    Dropzone.instances.forEach(dz => dz.destroy());
                }

                // edit dropzone show
                let uploadedImageMap = {}
                $("#product-image-dropzone").dropzone({
                    url: dropzone_store_url,
                    maxFilesize: 10,
                    addRemoveLinks: true,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (file, response) {
                        $("form").append(
                            '<input type="hidden" name="files[]" value="' +
                                response.name +
                                '">'
                        );
                        uploadedImageMap[file.name] = response.name;
                    },
                    removedfile: function (file) {
                        file.previewElement.remove();
                        file.previewElement.remove();
                        let name =
                            file.file_name || uploadedImageMap[file.name];
                        $(
                            'input[name="files[]"][value="' + name + '"]'
                        ).remove();

                        $.ajax({
                            url: dropzone_del_url,
                            method: "POST",
                            data: {
                                file_name: name,
                            },
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            success: function (response) {
                                console.log(
                                    "File deleted successfully:",
                                    response
                                );
                            },
                            error: function (xhr, status, error) {
                                console.error(
                                    "Error deleting file:",
                                    error
                                );
                            },
                        });
                    },
                    init: function () {
                        if (detail_images.length > 0) {
                            for (var i in detail_images) {
                                var file = {
                                    name: detail_images[i].file_name,
                                    size: detail_images[i].size,
                                    accepted: true,
                                };

                                this.files.push(file); // Add the file to Dropzone's files array
                                this.emit("addedfile", file); // Emit the "addedfile" event
                                this.emit(
                                    "thumbnail",
                                    file,
                                    detail_images[i].original_url
                                ); // Emit the "thumbnail" event
                                this.emit("complete", file); // Emit the "complete" event

                                $("form").append(
                                    '<input type="hidden" name="files[]" value="' +
                                        detail_images[i].file_name +
                                        '">'
                                );
                                uploadedImageMap[file.name] =
                                    detail_images[i].file_name;

                                // Adjust thumbnail image styles to fit within a container
                                var thumbnailElement =
                                    this.files[
                                        i
                                    ].previewElement.querySelector(
                                        ".dz-image img"
                                    );
                                thumbnailElement.style.maxWidth = "100%";
                                thumbnailElement.style.height = "100%";
                                thumbnailElement.style.objectFit = "cover";
                            }
                        }
                    },
                });
            }
        })
    })

    //submit create form
    $(document).on('submit', '#product_create_form', function(e) {
        e.preventDefault();

        let names = [
            "product_name",
            "series_id",
            "category_name",
            "product_info",
            "product_price",
            "main_image"
        ];

        let err = [];

        names.forEach((name) => {
            if ($(`.${name}`).val() == "" || $(`.${name}`).val() == null) {
                $(`.${name}_err`).html("Need to be filled");
                err.push(name);
            } else {
                $(`.${name}_err`).html(" ");
                if (err.includes(name)) {
                    err.splice(err.indexOf(`${name}`), 1);
                }
            }
        });

        if (err.length > 0) {
            toast_error("Please fill require fields !");
            window.scrollTo(0, 0);
        }

        if(err.length == 0) {
            let formData = new FormData($("#product_create_form")[0]);
            ask_confirm().then(result => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "/admin/product_setting/products",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        success: function (res) {
                            console.log(res);
                            if (res == "success") {
                                window.location.href = "/admin/product_setting/products";
                            }
                        },
                        error: function (xhr, status, err) {
                            //validation error
                            if (xhr.status === 422) {
                                let noti = ``;
                                for (const key in xhr.responseJSON.errors) {
                                    console.log(xhr.responseJSON.errors[key][0]);
                                    noti += `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${xhr.responseJSON.errors[key][0]}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                `;
                                }

                                $(".error").html(noti);

                                toast_error("Something went wrong !");

                                // Scroll to the top of the browser window
                                $("html, body").animate({ scrollTop: 0 });
                            } else {
                                toast_error("Something wrong");
                            }
                        },
                    })
                }
            })
        }

    })

    //submit edit form
    $(document).on('submit', '#product_edit_form', function(e) {
        e.preventDefault();

        let names = [
            "product_name",
            "series_id",
            "category_name",
            "product_info",
            "product_price",
        ];

        let err = [];

        names.forEach((name) => {
            if ($(`.${name}`).val() == "" || $(`.${name}`).val() == null) {
                $(`.${name}_err`).html("Need to be filled");
                err.push(name);
            } else {
                $(`.${name}_err`).html(" ");
                if (err.includes(name)) {
                    err.splice(err.indexOf(`${name}`), 1);
                }
            }
        });

        if (err.length > 0) {
            toast_error("Please fill require fields !");
            window.scrollTo(0, 0);
        }

        if(err.length == 0) {
            let formData = new FormData($("#product_edit_form")[0]);
            ask_confirm().then(result => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "/admin/product_setting/products/update-data/" + edit_id,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function (res) {
                            if (res.status == "success") {
                                toast_success('Successfully Updated !');
                                $('#productModal').modal('hide');
                                table.ajax.reload();
                            }
                        },
                        error: function (xhr, status, err) {
                            //validation error
                            if (xhr.status === 422) {
                                let noti = ``;
                                for (const key in xhr.responseJSON.errors) {
                                    console.log(xhr.responseJSON.errors[key][0]);
                                    noti += `
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        ${xhr.responseJSON.errors[key][0]}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                `;
                                }

                                $(".error").html(noti);

                                toast_error("Something went wrong !");

                                // Scroll to the top of the browser window
                                $("html, body").animate({ scrollTop: 0 });
                            } else {
                                toast_error("Something wrong");
                            }
                        },
                    })
                }
            })
        }
    })

    //delete function
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();

        let id = $(this).data('id');

        ask_delete().then(result => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "/admin/product_setting/products/" + id,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(res) {
                        res.status == 'success' && table.ajax.reload();
                    }
                })
            }
        })

    })
})
