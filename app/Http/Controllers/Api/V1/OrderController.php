<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use App\Models\User;
use App\Models\Point;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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

            if($request->file('slip_image')) {
                $imageName = $request->file('slip_image')->getClientOriginalName();
                $request->file('slip_image')->storeAs('public/images', $imageName);

                $order = new Order();
                $order->slip_image = $imageName;
                $order->save();

            } else {
                $imageName = null;
            }


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

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Form is submitted successfully', 'point' => $user ? $user->points : 0]);

        }catch(\Exception $error) {
            DB::rollBack();
            logger($error->getMessage());
            return response()->json(['status' => 'error', 'message' => $error->getMessage()]);
        }

    }
}
