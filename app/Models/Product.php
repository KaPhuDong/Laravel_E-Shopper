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
}
