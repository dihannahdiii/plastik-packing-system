<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'description'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
