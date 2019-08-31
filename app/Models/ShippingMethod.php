<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasPrice;
    
    /**
     * Model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
