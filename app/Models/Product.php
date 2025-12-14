<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->stock()->sum('quantity');
    }
}
