<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Product;
use App\Models\TypeProduct;
use App\Models\Comment;
use App\Jobs\SendMail; 
use Illuminate\Support\Facades\Session;
use App\Models\Cart;

class PageController extends Controller
{
    public function getIndex() {
        $slide = Slide::all();
        $new_product = Product::where('new', 1)->paginate(4);
        $promotion_product = Product::where('promotion_price', ">", 0)
            ->orderBy('promotion_price', 'desc')->paginate(4);

        return view('page.trangchu', compact('slide', 'new_product', 'promotion_product'));
    }

    public function getProduct() {
        $products = Product::all();
        return view('page.showProduct', compact('products'));
    }

    public function getDetail($id) {
        $bestSellers = Product::bestSellers();
        $sanpham = Product::where('id', $id)->first();
        $splienquan = Product::where('id_type', $sanpham->id_type)->paginate(3);
        $comments = Comment::where('id_product', $id)->paginate(4);

        return view("page.detail")->with([
            'sanpham' => $sanpham,
            'splienquan' => $splienquan,
            'comments' => $comments,
            'bestSellers' => $bestSellers
        ]);
    }

    public function newComment($id, Request $request) {
        $comment = new Comment();
        $comment->id_product = $id;
        $comment->username = "android 1";
        $comment->comment = $request->comment;
        $comment->save();

        return redirect()->back();
    }

    public function getTypeProduct($type_id) {
        $exist = TypeProduct::where('id', $type_id)->exists();

        if ($exist) {
            $new_product = Product::where('new', 1)
                ->where('id_type', $type_id)->paginate(4);

            $promotion_product = Product::where('promotion_price', ">", 0)
                ->where('id_type', $type_id)
                ->orderBy('promotion_price', 'desc')->paginate(4);

            return view('page.typeProduct', compact('new_product', 'promotion_product'));
        }

        return redirect()->back()->with('error', 'Loại sản phẩm không tồn tại.');
    }

    public function getAbout() {
        return view('page.about');
    }

    public function getContact() {
        return view('page.contact');
    }

    public function postContact(Request $request) {
        // Xử lý form contact ở đây
        return "Form contact đã được gửi thành công!";
    }

    public function getSearch(Request $request) {
        $key = $request->input('search'); 
        $products = Product::where('name', 'LIKE', "%$key%")->paginate(4);
        return view('page.search', compact('products', 'key'));
    }

    /**
     * Gửi email xác nhận đơn hàng
     */
    public function sendOrderEmail(Request $req, $cart) {
        if (!$req->has('email')) {
            return redirect()->back()->with('error', 'Không có email để gửi.');
        }

        // Chuẩn bị dữ liệu email
        $message = [
            'type'    => 'Email thông báo đặt hàng thành công',
            'thanks'  => 'Cảm ơn ' . $req->name . ' đã đặt hàng.',
            'cart'    => $cart,
            'content' => 'Đơn hàng sẽ tới tay bạn sớm nhất có thể.',
        ];

        // Gửi email với độ trễ 1 phút
        SendMail::dispatch($message, $req->email)->delay(now()->addMinute(1));

        return redirect()->back()->with('success', 'Email xác nhận đơn hàng đã được gửi.');
    }

    public function getAddToCart(Request $req, $id) {
        $product = Product::find($id);
        $oldCart = Session('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart', $cart);
        return redirect()->back();
    }

    public function getDelItemCart($id) {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0 && Session::has('cart')) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->back();
    }

    // ----------- PAYMENT WITH VNPAY -----------
    function processVNPayPayment($req, $bill) {
        if ($req->payment_method == "vnpay") {
            $cost_id = time(); // Số hóa đơn
            
            // Cấu hình thông tin VNPAY
            $vnp_TmnCode = "57U1FZ9V"; // Mã website tại VNPAY
            $vnp_HashSecret = "TQIBCZEXUERWJKGJGLWFQHCLSWWOCXVZ"; // Chuỗi bí mật
            $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://localhost:8000/return-vnpay";
            $vnp_TxnRef = date("YmdHis"); // Mã đơn hàng
            $vnp_OrderInfo = "Thanh toán hóa đơn phí dịch vụ";
            $vnp_OrderType = "billpayment";
            $vnp_Amount = $bill->total * 100;
            $vnp_Locale = "vn";
            $vnp_IpAddr = request()->ip();
            $vnp_BankCode = "NCB";
        
            // Mảng dữ liệu đầu vào
            $inputData = [
                "vnp_Version" => "2.0.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];
        
            if (!empty($vnp_BankCode)) {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
        
            // Sắp xếp dữ liệu và tạo chuỗi hash
            ksort($inputData);
            $query = "";
            $hashdata = "";
            $i = 0;
            
            foreach ($inputData as $key => $value) {
                $hashdata .= ($i ? '&' : '') . $key . "=" . $value;
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
                $i = 1;
            }
            
            $vnp_Url .= "?" . $query;
            
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
                $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
            }
            
            echo '<script>location.assign("' . $vnp_Url . '");</script>';
            
            app()->apSer->thanhtoanonline($cost_id);
            return redirect('success')->with('data', $inputData);
        } else {
            echo "<script>alert('Đặt hàng thành công')</script>";
            return redirect('trangchu');
        }
    }    
}
