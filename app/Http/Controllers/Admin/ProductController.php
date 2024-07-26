<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $series = Series::with('category')->get();

        return view('admin.product_setting.products.index', compact('series'));
    }

    public function getProductList()
    {
        $data = Product::with('series', 'category');

        return Datatables::of($data)
            ->editColumn('plus-icon', function ($each) {
                return null;
            })
            ->editColumn('main_photo', function($each) {
                foreach($each->getMedia('product_main_image') as $image) {
                    $filePath = $image->getUrl();
                    $image = "<img src='$filePath' width='120' height='120' style='object-fit:cover;'/>";
                }

                return $image;
            })
            ->editColumn('photos', function($each) {
                $image = '';
                $index = 0;
                foreach ($each->getMedia('product_detail_images') as $file) {
                    if ($index < 2) {
                        $filePath = $file->getUrl();
                        $style = "width: 60px; height: 60px; display: flex; justify-content:center; align-items:center ;border-radius: 100%; object-fit: cover; border: 1px solid #333;";
                        $style .= $index == 0 ? '' : 'margin-left: -15px;';

                        $image .= "<img src='$filePath' style='$style'/>";
                    }
                    $index++;
                }

                if ($index > 2) {
                    $index = $index - 2;
                    $image .= "<div style='$style background: #fff;'>+$index</div>";
                }

                return "<div class='d-flex align-items-center'> $image </div>";
            })
            ->editColumn('series_id', function($each) {
                return $each->series->name;
            })
            ->filterColumn('series_id', function($query, $keyword) {
                $query->whereHas('series', function($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->editColumn('category_id', function($each) {
                return $each->category->name;
            })
            ->filterColumn('category_id', function($query, $keyword) {
                $query->whereHas('category', function($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
            })
            ->addColumn('action', function ($each) {

                $show_icon = '';
                $edit_icon = '';
                $del_icon = '';

                // $show_icon = '<a href="' . route('admin.products.show', $each->id) . '" class="text-warning me-3"><i class="bx bxs-show fs-4"></i></a>';
                // if (auth()->user()->can('photo_gallery_show')) {
                // }

                $edit_icon = '<a href="#" data-bs-toggle="modal" data-bs-target="#productModal" data-route="'.route('admin.products.edit', $each->id).'" class="text-info edit-product me-3"><i class="bx bx-edit fs-4" ></i></a>';

                $del_icon = '<a href="" class="text-danger delete-btn" data-id="' . $each->id . '"><i class="bx bxs-trash-alt fs-4" ></i></a>';

                return '<div class="action-icon">' . $show_icon . $edit_icon . $del_icon . '</div>';
            })
            ->rawColumns(['main_photo', 'photos', 'action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $series = Series::with('category')->get();

        return view('admin.product_setting.products.create', compact('series'));
    }

    /**
     * store images from dropzone
     */
    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * delete dropzone photos
     */
    public function deleteMedia(Request $request)
    {
        $file = $request->file_name;

        File::delete(storage_path('tmp/uploads/' . $file));

        return 'success';

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        logger($request->all());
        try{
            $product = new Product();
            $product->name = $request->product_name;
            $product->series_id = $request->series_id;
            $product->category_id = $request->category_id;
            $product->product_info = $request->product_info;
            $product->price = $request->product_price;
            $product->m_size_stock = $request->m_size_stock;
            $product->lg_size_stock = $request->lg_size_stock;
            $product->xl_size_stock = $request->xl_size_stock;
            $product->xxl_size_stock = $request->xxl_size_stock;
            $product->xxxl_size_stock = $request->xxxl_size_stock;
            $product->xxxxl_size_stock = $request->xxxxl_size_stock;
            $product->save();


            if ($request->file('main_image')) {
                $fileName = uniqid() . $request->file('main_image')->getClientOriginalName();
                $product->addMedia($request->file('main_image'))->usingFileName($fileName)->toMediaCollection('product_main_image');
            }

            foreach ($request->input('files', []) as $image) {
                $product->addMedia(storage_path('tmp/uploads/' . $image))->toMediaCollection('product_detail_images');
            }

            DB::commit();
            session()->flash('success', 'Successfully Created !');
            return 'success';

        }catch(\Exception $error) {
            DB::rollBack();
            logger($error->getMessage());
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product = $product->load('media', 'series', 'category');

        return response()->json(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(Request $request, Product $product)
    {
        DB::beginTransaction();

        try{
            $product->name = $request->product_name;
            $product->series_id = $request->series_id;
            $product->category_id = $request->category_id;
            $product->product_info = $request->product_info;
            $product->price = $request->product_price;
            $product->m_size_stock = $request->m_size_stock;
            $product->lg_size_stock = $request->lg_size_stock;
            $product->xl_size_stock = $request->xl_size_stock;
            $product->xxl_size_stock = $request->xxl_size_stock;
            $product->xxxl_size_stock = $request->xxxl_size_stock;
            $product->xxxxl_size_stock = $request->xxxxl_size_stock;
            $product->save();


            if ($request->file('main_image')) {
                //delete old file
                if (count($product->productMainImage()) > 0) {
                    foreach ($product->productMainImage() as $media) {
                        $media->delete();
                    }
                }

                $fileName = uniqid() . $request->file('main_image')->getClientOriginalName();
                $product->addMedia($request->file('main_image'))->usingFileName($fileName)->toMediaCollection('product_main_image');
            }

            if (count($product->productDetailImages()) > 0) {
                foreach ($product->productDetailImages() as $media) {
                    if (!in_array($media->file_name, $request->input('files', []))) {
                        $media->delete();
                    }
                }
            }

            $media = $product->productDetailImages()->pluck('file_name')->toArray();

            foreach ($request->input('files', []) as $image) {
                if (count($media) === 0 || !in_array($image, $media)) {
                    $product->addMedia(storage_path('tmp/uploads/' . $image))->toMediaCollection('product_detail_images');
                }
            }

            DB::commit();
            return response()->json(['status' => 'success']);

        }catch(\Exception $error) {
            DB::rollBack();
            logger($error->getMessage());
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['status' => 'success']);
    }
}
