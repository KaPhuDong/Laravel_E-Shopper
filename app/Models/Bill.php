<?php

namespace App\Models;
use App\Models\BillDetail;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    protected $table = "bills";
    public function bill_detail() : hasOne {
        return $this->hasOne(BillDetail::class,'id_bill');
    }
    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class,'id_customer');
    }
}
