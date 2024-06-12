<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyStore extends Model
{
    use HasFactory;

    protected $table = 'shopify_store';

    protected $fillable = [
        'shop_url',
        'shop_token'
    ];

    protected $casts = [
        'shop_token' => 'encrypted',
    ];
}
