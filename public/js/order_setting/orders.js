'use strict'

$(document).ready(function() {

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    const table = new DataTable('#DataTable', {
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: '/admin/order_setting/orders-list',
        columns: [{
                data: 'plus-icon',
                name: 'plus-icon'
            },
            {
                data: 'date',
                data: 'date',
            },
            {
                data: 'order_no',
                data: 'order_no',
            },
            {
                data: 'user_id',
                name: 'user_id'
            },
            {
                data: 'slip_image',
                data: 'slip_image',
            },
            {
                data: 'status',
                data: 'status',
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

    $(document).on("click", '.status-btn', function() {
        let status = $(this).data('status');
        let order_id = $(this).data('order_id');

        ask_confirm("Confirm order ?", "Yes, Confirm").then(result => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "/admin/order_setting/order-confirm",
                    data: {status, order_id},
                    success: function(res) {
                        if(res == 'success') {
                            table.ajax.reload();
                        }
                    }
                })
            }
        })
    })

    $(document).on('click', '.done-btn', function() {
        let order_id = $(this).data('order_id');

        ask_confirm("Cancel order ?", "Yes, Cancel").then(result => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "/admin/order_setting/order-cancel",
                    data: {order_id},
                    success: function(res) {
                        if(res == 'success') {
                            table.ajax.reload();
                        }
                    }
                })
            }
        })
    })

    $(document).on('click', '.order-detail', function() {
        let route = $(this).data('route');

        if(route) {
            $.ajax({
                url: route,
                success: function(res) {
                    console.log(res);
                    let order = res.order;
                    $('.date').html(`: ${order.date}`);
                    $('.order-no').html(`: ${order.order_no}`);
                    $('.customer').html(`: ${order.customer}`);
                    $('.phone').html(`: ${order.phone}`);
                    $('.address').html(`: ${order.delivery_city} , ${order.delivery_town}`)
                    $('.delivery-address').html(`: ${order.delivery_address}`)

                    let row;
                    for(const item of order.order_items) {
                        row += `
                        <tr>
                            <td>${item.code}</td>
                            <td>${item.size}</td>
                            <td>${item.color}</td>
                            <td>${item.quantity}</td>
                            <td>${numberWithCommas(item.price)}</td>
                        </tr>
                        `;
                    }
                    $('.orderDetailGroup').html(row);
                    $('.sub-total').html(numberWithCommas(order.sub_total ?? 0))
                    $('.point-discount').html('- '+ numberWithCommas(order.used_point ?? 0))
                    $('.delivery-charges').html('+ '+ numberWithCommas(order.delivery_charges))
                    $('.grand-total').html(numberWithCommas(order.grand_total ?? 0))

                    let img = '';
                    if(order.img == null) {
                        img = "<span class='text-danger'>No Photo Available</span>"
                    } else {
                        img = `<img src='${order.img}' style="max-width: 500px;" />`
                    }

                    $('.img').html(img)
                }
            })
        }
    })
})
