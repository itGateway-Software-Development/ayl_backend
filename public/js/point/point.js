'use strict'

$(document).ready(function() {
    const table = new DataTable('#DataTable', {
        processing: true,
        responsive: true,
        serverSide: true,
        ajax: '/admin/point_system/points-list',
        columns: [{
                data: 'plus-icon',
                name: 'plus-icon'
            },
            {
                data: 'name',
                data: 'name',
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'created_at',
                data: 'created_at',
            },
            {
                data: 'point',
                data: 'point',
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

    $(document).on('click', '.point-detail', function() {
        let route = $(this).data('route');

        if(route) {
            $.ajax({
                url: route,
                success: function(res) {
                    let user = res.user;
                    console.log(user);
                    $('.user-info').html(user.name +' - ' + user.phone);
                    $('.available-point').html('Available Points - '+user.points[0].total)

                    let row;
                    for(const point of user.points) {
                        row += `
                        <tr>
                            <td>${new Date(point.created_at).getDate()}-${new Date(point.created_at).getMonth() + 1}-${new Date(point.created_at).getFullYear()}</td>
                            <td>${point.reason}</td>
                            <td><span class="badge ${point.type == 'in' ? 'bg-primary' : 'bg-danger'} rounded-pill">${point.type == 'in' ? 'In' : 'Out'}</span></td>
                            <td> ${point.type == 'in' ? "<i class='bx bx-plus text-info' ></i>" : "<i class='bx bx-minus text-danger' ></i>"} ${point.points}</td>
                            <td>${point.total}</td>
                        </tr>
                        `;
                    }
                    $('.pointDetailGroup').html(row)
                }
            })
        }
    })
})
