<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's convention
    protected $table = 'suppliers'; // Optional, only if your table name isn't pluralized

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'supplier_name',
        'contact_person',
        'mobile_numbers',
    ];

    // Define the relationship with products if applicable
    public function products()
    {
        return $this->hasMany(Product::class); // Assuming you have a Product model
    }
}
