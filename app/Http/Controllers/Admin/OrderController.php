<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

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
                $order_confirm_status = auth()->user()->can('order_confirm') ? 'status-btn' : '';

                if($each->status == 'pending') {
                    $status_btn = "<span class='cursor-pointer bg-warning px-3 py-1 $order_confirm_status' data-status='pending' data-order_id='$each->id'>".ucwords($each->status)."</span>";
                } elseif($each->status == 'done') {
                    $status_btn = "<span class='cursor-pointer bg-success px-3 py-1 done-btn' data-status='done' data-order_id='$each->id'>".ucwords($each->status)."</span>";
                } else {
                    $status_btn = "<span class='cursor-pointer bg-secondary px-3 py-1 cancel-btn' data-status='cancel' data-order_id='$each->id'>".ucwords($each->status)."</span>";
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
        DB::beginTransaction();

        try {
            if($request->order_id) {
                if($request->status == 'pending') {
                    $order = Order::find($request->order_id);
                    if($order) {
                        $user = User::find($order->user_id);
                        $bought_amount = $order->sub_total;
                        $used_point = $order->used_point;
                        $total_point = Point::where('user_id', $user->id)->latest()->first()->total;

                        if($bought_amount && $bought_amount > 0) {

                            $earn_points = round($bought_amount / 200, 1);

                            $point = new Point();
                            $point->type = 'in';
                            $point->name = 'Point Increase';
                            $point->reason = $user->name. ' has earned '. $earn_points. ' points for ordering';
                            $point->points = $earn_points;
                            $point->total = $total_point + $earn_points;
                            $point->user_id = $user->id;
                            $point->save();
                        }

                        $order->status = 'done';
                        $order->bonus_point = $earn_points;
                        $order->update();

                    }
                }
            }

            DB::commit();
            return 'success';
        } catch(\Exception $err) {
            DB::rollBack();
            return $err->getMessage();
        }
    }

    public function cancelOrder(Request $request) {
        DB::beginTransaction();

        try {
            if($request->order_id) {
                $order = Order::find($request->order_id);
                    if($order) {
                        $user = User::find($order->user_id);
                        $bonus_point = $order->bonus_point;
                        $used_point = $order->used_point;
                        $total_point = Point::where('user_id', $user->id)->latest()->first()->total;

                        if($used_point && $used_point > 0) {
                            $point = new Point();
                            $point->type = 'in';
                            $point->name = 'Point Increase';
                            $point->reason = $user->name. ' has reearned '. $used_point. ' points for canceling order';
                            $point->points = $used_point;
                            $point->total = $total_point + $used_point;
                            $point->user_id = $user->id;
                            $point->save();
                        }

                        if($bonus_point && $bonus_point > 0) {
                            $new_total_point = Point::where('user_id', $user->id)->latest()->first()->total;
                            $point = new Point();
                            $point->type = 'out';
                            $point->name = 'Point Decrease';
                            $point->reason = $user->name. ' has decrease '. $bonus_point. ' points for canceling order';
                            $point->points = $bonus_point;
                            $point->total = $new_total_point - $bonus_point;
                            $point->user_id = $user->id;
                            $point->save();
                        }

                        $order->status = 'cancel';
                        $order->bonus_point = 0;
                        $order->update();

                    }
            }

            DB::commit();
            return 'success';
        } catch(\Exception $err) {
            DB::rollBack();
            return $err->getMessage();
        }
    }
}
