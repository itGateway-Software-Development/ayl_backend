<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSeriesRequest;
use App\Http\Requests\Admin\UpdateSeriesRequest;
use App\Models\Series;
use Illuminate\Http\Request;
use DataTables;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.product_setting.series.index');
    }

    public function getSeriesList() {
        $data = Series::query();

        return Datatables::of($data)
            ->editColumn('plus-icon', function ($each) {
                return null;
            })
            ->addColumn('action', function ($each) {
                $show_icon = '';
                $edit_icon = '';
                $del_icon = '';

                $edit_icon = '<a href="#" data-bs-toggle="modal" data-bs-target="#seriesModal" data-route="'.route('admin.series.edit', $each->id).'" class="text-info edit-series me-3"><i class="bx bx-edit fs-4" ></i></a>';

                $del_icon = '<a href="" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="bx bxs-trash-alt fs-4" ></i></a>';

                return '<div class="action-icon">' . $edit_icon . $del_icon . '</div>';
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product_setting.series.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeriesRequest $request)
    {
        try {
            $series = new Series();
            $series->name = $request->series_name;
            $series->save();

            session()->flash('success', 'Successfully Created');
            return 'success';

        }catch(\Exception $error) {
            logger($error->getMessage());
            return back()->with('fail', 'Something Wrong !')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Series $series)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Series $series)
    {
        return response()->json(['series' => $series]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(UpdateSeriesRequest $request, Series $series)
    {
        $series->name = $request->series_name;
        $series->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Series $series)
    {
        $series->delete();

        return response()->json(['status' => 'success']);
    }
}
