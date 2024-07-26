'use strict'

$(document).ready(function() {
    let edit_id = null;

    const table = new DataTable('#DataTable', {
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: '/admin/product_setting/categories-list',
        columns: [{
                data: 'plus-icon',
                name: 'plus-icon'
            },
            {
                data: 'image',
                data: 'image',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'description',
                data: 'description',
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

    //show edit popup
    $(document).on('click', '.edit-category', function() {
        let route = $(this).data('route');

        $.ajax({
            url: route,
            type: "GET",
            success: function(res) {
                edit_id = res.category.id;
                $('.category_name').val(res.category.name);
                $('.description').val(res.category.description);
                $('#category_preview_img').attr('src', res.category.media[0].original_url).width(150).height(150)
            }
        })
    })

    // show preview image
    $(document).on('change', '.image', function(e) {
        if (this.files && this.files[0]) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#category_preview_img').attr('src', e.target.result).width(150).height(150);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    //submit create form
    $(document).on('submit', '#category_create_form', function(e) {
        e.preventDefault();

        let names = [
            "category_name",
            "image",
            "description"
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
            let formData = new FormData($("#category_create_form")[0]);
            ask_confirm().then(result => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "/admin/product_setting/categories",
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
                            if (res == "success") {
                                window.location.href = "/admin/product_setting/categories";
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
    $(document).on('submit', '#category_edit_form', function(e) {
        e.preventDefault();
        let names = [
            "category_name",
            "description"
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
            let formData = new FormData($("#category_edit_form")[0]);
            ask_confirm().then(result => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "/admin/product_setting/categories/update-data/" + edit_id,
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function (res) {
                            if (res.status == "success") {
                                toast_success('Success !');
                                $('#categoryModal').modal('hide');
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
                    url: "/admin/product_setting/categories/" + id,
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
