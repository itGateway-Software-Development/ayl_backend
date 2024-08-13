<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use App\Mail\OrderMail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function order(Request $request) {
        DB::beginTransaction();

        try{
            $user = User::find($request->id);
            if($user) {
                $point = new Point();
                $point->type = 'out';
                $point->name = 'Point Decrease';
                $point->reason = $user->name. ' has used '. $request->pointsUse. ' points when order';
                $point->points = $request->pointsUse;
                $point->total = $request->totalPoint - $request->pointsUse;
                $point->user_id = $user->id;
                $point->save();
            }

            $ordered_products = json_decode($request->products, true);
            logger($ordered_products);
            foreach ($ordered_products as $order_product) {
                $product = Product::find($order_product['id']);
                if ($product) {
                    switch ($order_product['size']) {
                        case 'M':
                            $product->m_size_stock -= (int) $order_product['quantity'];
                            break;
                        case 'L':
                            $product->lg_size_stock -= (int) $order_product['quantity'];
                            break;
                        case 'XL':
                            $product->xl_size_stock -= (int) $order_product['quantity'];
                            break;
                        case 'XXL':
                            $product->xxl_size_stock -= (int) $order_product['quantity'];
                            break;
                        case '3XL':
                            $product->xxxl_size_stock -= (int) $order_product['quantity'];
                            break;
                        case '4XL':
                            $product->xxxxl_size_stock -= (int) $order_product['quantity'];
                            break;
                    }

                    $product->update();
                }
            }


            $maxOrder = Order::orderByRaw('CAST(SUBSTRING(order_no, 3) AS UNSIGNED) DESC')->first();
            if ($maxOrder) {
                $maxorderNo = (int) substr($maxOrder->order_no, 3);
                $newOrderNo = '# ' . sprintf("%05d", $maxorderNo + 1);

            } else {
                $newOrderNo = '# 00001';
            }

            if($request->file('slip_image')) {
                $imageName = $request->file('slip_image')->getClientOriginalName();
                $request->file('slip_image')->storeAs('public/images', $imageName);
            } else {
                $imageName = null;
            }

            $order = new Order();
            $order->order_no = $newOrderNo;
            $order->order_items = json_decode($request->products, true);
            $order->user_id = $user ? $user->id : null;
            $order->slip_image = $imageName;
            $order->delivery_city = $request->city;
            $order->delivery_town = $request->town;
            $order->delivery_charges = $request->deliveryPrice;
            $order->delivery_address = $request->address;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->used_point = $request->pointsUse;
            $order->sub_total = $request->totalPrice;
            $order->grand_total = $request->grandTotal;

            $order->save();

            $mailData = [
                'name'=>$request->name,
                'phone'=>$request->phone,
                'address'=>$request->address,
                'products'=>json_decode($request->products, true),
                'city'=>$request->city,
                'township'=>$request->town,
                'deliveryPrice'=>$request->deliveryPrice,
                'pointsUse'=>$request->pointsUse,
                'totalPoint'=>$request->totalPoint,
                'subTotal'=>$request->totalPrice,
                'slip_image' => $imageName ? url('storage/images/'.$imageName) : '',
            ];


            Mail::to('aylorder@gmail.com')->send(new OrderMail($mailData));
            // Mail::to('lin911460@gmail.com')->send(new OrderMail($mailData));
            // Mail::to('sawsantun147@gmail.com')->send(new OrderMail($mailData));

            if($user) {
                $order = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Form is submitted successfully',
                'point' => $user ? $user->points : 0,
                'order' => $user ? OrderResource::collection($order->load('customer')) : null
            ]);

        }catch(\Exception $error) {
            DB::rollBack();
            logger($error->getMessage());
            return response()->json(['status' => 'error', 'message' => $error->getMessage()]);
        }
    }

    public function show($id) {
        $order = Order::with('customer')->where('user_id', $id)->orderBy('created_at', 'desc')->get();
        if($order) {
            return response()->json(['order' => OrderResource::collection($order->load('customer'))]);
        }
    }
}
