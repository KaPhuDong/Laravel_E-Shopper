<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use  App\Models\Product;
use  App\Models\TypeProduct;
use  App\Models\Comment;
class PageController extends Controller
{
    public function getIndex(){
        $slide = Slide::all();
        $new_product= Product::where('new',1)->paginate(4);
        $promotion_product= Product::where('promotion_price',">",0)->orderBy('promotion_price','desc')->paginate(4);
        return view('Page.trangchu',compact('slide','new_product','promotion_product'));
    }

    public function getproduct(){
        $products = Product::all();
        return view('Page.showProduct',compact('products'));
    }

    public function getDetail($id){
        $sanpham = Product::where('id',$id)->first();
        $splienquan = Product::where('id_type',$sanpham->id_type)->paginate(3);
        $comments = Comment::where('id_product',$id)->paginate(4);
        return view("Page.detail")->with(['sanpham'=>$sanpham,'splienquan'=>$splienquan,'comments'=>$comments]);
    }

    public function newComment($id, Request $request){
        $comment = new Comment;
        $comment->id_product = $id;
        $comment->username = "android 1";
        $comment->comment = $request->comment;
        $comment->save();
        return redirect()->back();
    }

    public function getTypeProduct($type_id){
        $slide = Slide::all();
        $exist = false;
        $type_products = TypeProduct::all();
        foreach($type_products as $type){
            if($type->id==$type_id){
                $exist=true;
            }
        }
        if($exist){
            $new_product= Product::where('new',1)->where('id_type',$type_id)->paginate(4);
            $promotion_product= Product::where('promotion_price',">",0)->where('id_type',$type_id)->orderBy('promotion_price','desc')->paginate(4);
            return view('Page.typeProduct',compact('slide','type_products','new_product','promotion_product'));
        }
    }

    public function getAbout(){
        return view('Page.about');
    }

    public function getContact(){
        return view('Page.contact');
    }

    public function postContact(Request $request)
    {
        // Xử lý form contact ở đây
        return "Form contact đã được gửi thành công!";
    }

}
