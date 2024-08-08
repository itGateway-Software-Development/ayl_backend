<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class OrderController extends Controller
{
    public function index() {
        return view('admin.order_setting.order.index');
    }

    public function getOrders() {
        $data = Order::with('customer');

        return Datatables::of($data)
            ->editColumn('plus-icon', function ($each) {
                return null;
            })

            ->editColumn('date', function($each) {
                return date('d-m-Y', strtotime($each->created_at));
            })

            ->editColumn('user_id', function($each) {
                $guest_user = "<span class='text-sm bg-info badge rounded-pill'>Guest User</span>";
                return $each->customer ? ucwords($each->customer->name) : $guest_user;
            })

            ->editColumn('slip_image', function($each) {
                $url = url('/storage/images');
                $img = "<img style='width: 150px; height: 150px; object-fit:cover;' src='$url/$each->slip_image' />";
                return $each->slip_image ? $img : '<span class="text-danger">No Image Available</span>';
            })

            ->editColumn('status', function($each) {
                $status_btn = '';

                if($each->status == 'pending') {
                    $status_btn = "<span class='cursor-pointer bg-warning px-3 py-1 status-btn' data-status='pending' data-order_id='$each->id'>".ucwords($each->status)."</span>";
                } elseif($each->status == 'done') {
                    $status_btn = "<span class='cursor-pointer bg-success px-3 py-1 ' data-status='done'>".ucwords($each->status)."</span>";
                }
                return $status_btn;
            })

            ->addColumn('action', function ($each) {
                $detail_icon = '<a href="#" data-bs-toggle="modal" data-bs-target="#orderDetailModal" data-route="'.route('admin.orders.show', $each->id).'" class="text-info order-detail me-3"><i class="bx bxs-comment-detail fs-4"></i></a>';

                return '<div class="action-icon">' . $detail_icon . '</div>';
            })
            ->rawColumns(['user_id', 'slip_image', 'status', 'action'])
            ->make(true);
    }

    public function show($id) {
        $order = Order::with('customer')->find($id);

        if($order) {
            return response()->json(['order' => new OrderResource($order)]);
        }
    }

    public function confirmOrder(Request $request) {
        if($request->order_id) {
            if($request->status == 'pending') {
                $order = Order::find($request->order_id);
                if($order) {
                    $order->status = 'done';
                    $order->update();

                    return 'success';
                }
            }
        }
    }
}
