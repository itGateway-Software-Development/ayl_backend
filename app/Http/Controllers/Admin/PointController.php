<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class PointController extends Controller
{
    public function index() {
        return view('admin.point_system.point.index');
    }

    public function getPointList() {
        $data = User::with('points')->where('type', 'user');

        return Datatables::of($data)
            ->editColumn('plus-icon', function ($each) {
                return null;
            })

            ->editColumn('created_at', function($each) {
                return date('d-m-Y', strtotime($each->created_at));
            })

            ->editColumn('point', function($each) {
                return $each->points()->latest()->first()->total;
            })

            ->filterColumn('point', function($query, $keyword) {
                $query->whereHas('points', function($q) use ($keyword) {
                    $q->where('total', 'like', "%$keyword%");
                });
            })

            ->addColumn('action', function ($each) {

                $detail_icon = '';

                $detail_icon = '<a href="#" data-bs-toggle="modal" data-bs-target="#pointDetailModal" data-route="'.route('admin.points.show', $each->id).'" class="text-info point-detail me-3"><i class="bx bxs-comment-detail fs-4"></i></a>';

                return '<div class="action-icon">' . $detail_icon . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id) {
        $user = User::with(['points' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return response()->json(['user' => $user]);
    }
}
