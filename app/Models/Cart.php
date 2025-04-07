<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $items = [];
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart = null)
    {
        parent::__construct();
        if ($oldCart) {
            $this->items = $oldCart->items ?? [];
            $this->totalQty = $oldCart->totalQty ?? 0;
            $this->totalPrice = $oldCart->totalPrice ?? 0;
        }
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add($item, $id, $qty = 1)
    {
        $price = $item->promotion_price > 0 ? $item->promotion_price : $item->unit_price;

        $giohang = ['qty' => 0, 'price' => $price, 'item' => $item];

        if (isset($this->items[$id])) {
            $giohang = $this->items[$id];
        }

        $giohang['qty'] += $qty;
        $giohang['price'] = $price * $giohang['qty'];
        $this->items[$id] = $giohang;
        $this->totalQty += $qty;
        $this->totalPrice += $price * $qty;
    }

    // Giảm số lượng 1 sản phẩm
    public function reduceByOne($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $price = $this->items[$id]['item']->promotion_price > 0 
                 ? $this->items[$id]['item']->promotion_price 
                 : $this->items[$id]['item']->unit_price;

        $this->items[$id]['qty']--;
        $this->items[$id]['price'] = $price * $this->items[$id]['qty'];
        $this->totalQty--;
        $this->totalPrice -= $price;

        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }

    // Xóa toàn bộ một sản phẩm khỏi giỏ hàng
    public function removeItem($id)
    {
        if (!isset($this->items[$id])) {
            return;
        }

        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }

    // Lấy danh sách sản phẩm trong giỏ hàng
    public function getItems()
    {
        return $this->items;
    }
}
