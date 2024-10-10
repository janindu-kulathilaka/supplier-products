<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify the fillable attributes for the Product model
    protected $fillable = [
        'supplier_id', // Foreign key to associate with Supplier
        'product_name',
        'product_price',
        // 'image', // Include image if you plan to store it
    ];

    // Define the relationship with Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
