<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Product;
use  App\Models\TypeProduct;
use  App\Models\Bill;
use  App\Models\BillDetail;
use App\Http\Requests\AddProduct;
use App\Http\Requests\EditProduct;
class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $sumSold = BillDetail::count();
        return view('admin.admin',compact('products','sumSold'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.formAddProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProduct $request)
    {
        $nameImage=null;
        if($request->hasFile('inputImage')){
            $image = $request->file('inputImage');
            $nameImage = time().$image->getClientOriginalName();
            $destinationPath = public_path('source/image/product');// trả về địa chỉ tuyệt đối trong thư mục gộc public bằng public_path
            $image->move($destinationPath,$nameImage);// Di chuyển file ảnh từ vị trí lưu trữ tạm thời khi tải đến thư mục chit định
            // $pathImage = $image->storeAs('source/image/product',$nameImage,'public');
        }
        if(isset($nameImage)){
            $product = new product;
            $product->name = $request->inputName;
            $product->id_type = $request->inputType;
            $product->image = $nameImage;
            $product->unit_price = $request->inputPrice;
            $product->promotion_price = $request->inputPromotionPrice;
            $product->unit = $request->inputUnit;
            $product->new = $request->inputNew;
            $product->description = $request->inputDescription;
            $product->save();
            return redirect('/admin');
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
    public function edit(string $id)
    {
        $product = product::find($id);
        return view('admin.formEditProduct',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditProduct $request)
    {
        $product = product::find($request->editId);
        $imageName = null;
        if($request->hasFile('editImage')){
            $destinationPath = public_path('source/image/product');
            $oldName = "err.png";
            if(!empty($product->image)){
                $oldName = $product->image;
            }
            
            $oldFile = $destinationPath."/".$oldName;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }

            $image = $request->file('editImage');
            $imageName = time().$image->getClientOriginalName();
            $destinationPath = public_path('source/image/product');// trả về địa chỉ tuyệt đối trong thư mục gộc public bằng public_path
            $image->move($destinationPath,$imageName);
        }
        if(isset($imageName)){
            $product->name = $request->editName;
            $product->id_type = $request->editType;
            $product->image = $imageName;
            $product->unit_price = $request->editPrice;
            $product->promotion_price = $request->editPromotionPrice;
            $product->unit = $request->editUnit;
            $product->new = $request->editNew;
            $product->description = $request->editDescription;
            $product->save();
            return redirect('/admin');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $product = product::find($id);
        $product->delete();
        return redirect()->back();
    }
}
