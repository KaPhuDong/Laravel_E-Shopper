<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeProducts;
use App\Models\TypeProduct;
use App\Models\BillDetail;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = "products";
    public function type_products() : BelongsTo {
        return $this->belongsTo(TypeProduct::class,'type_id');
    }

    public function bill_detail() : BelongsTo {
        return $this->belongsTo(BillDetail::class,'id_product');
    }

    public static function bestSellers($limit = 4) {
        return self::join('bill_detail', 'products.id', '=', 'bill_detail.id_product')
            ->select('products.id', 'products.name', 'products.image', 'products.unit_price')
            ->selectRaw('SUM(bill_detail.quantity) as total_sold')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.unit_price')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
}
