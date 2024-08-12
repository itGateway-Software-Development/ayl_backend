<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Series;
use Illuminate\Http\Request;
use DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $series = Series::all();
        return view('admin.product_setting.category.index', compact('series'));
    }

    public function getCategoryList() {
        $data = Category::query();

        return Datatables::of($data)
            ->editColumn('plus-icon', function ($each) {
                return null;
            })
            ->editColumn('image', function($each) {
                foreach($each->getMedia('category_image') as $image) {
                    $filePath = $image->getUrl();
                    $image = "<img src='$filePath' width='120' height='120' style='object-fit:cover;'/>";
                }

                return $image;
            })
            ->addColumn('action', function ($each) {
                $edit_icon = '';
                $del_icon = '';

                if(auth()->user()->can('category_edit')) {
                    $edit_icon = '<a href="#" data-bs-toggle="modal" data-bs-target="#categoryModal" data-route="'.route('admin.categories.edit', $each->id).'" class="text-info edit-category me-3"><i class="bx bx-edit fs-4" ></i></a>';
                }
                if(auth()->user()->can('category_delete')) {
                    $del_icon = '<a href="" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="bx bxs-trash-alt fs-4" ></i></a>';
                }
                return '<div class="action-icon">' . $edit_icon . $del_icon . '</div>';
            })
            ->rawColumns(['image', 'role', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $series = Series::all();

        return view('admin.product_setting.category.create', compact('series'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = new Category();
            $category->name = $request->category_name;
            $category->description = $request->description;
            $category->save();

            if ($request->file('image')) {
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $category->addMedia($request->file('image'))->usingFileName($fileName)->toMediaCollection('category_image');
            }

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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->json(['category' => $category->load('media')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(UpdateCategoryRequest $request, Category $category)
    {
        $category->name = $request->category_name;
        $category->description = $request->description;
        $category->update();

        if ($request->file('image')) {
            //delete old file
            if (count($category->categoryImage()) > 0) {
                foreach ($category->categoryImage() as $media) {
                    $media->delete();
                }
            }

            $fileName = uniqid() . $request->file('image')->getClientOriginalName();
            $category->addMedia($request->file('image'))->usingFileName($fileName)->toMediaCollection('category_image');
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['status' => 'success']);
    }
}
