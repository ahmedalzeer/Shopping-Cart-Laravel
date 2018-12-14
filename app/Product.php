<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[
        'name','slug','price','details','description'
    ];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }
    public function presentPrice($value)
    {
        return '$' . number_format($value);
    }

    public function scopeMightAlsoLike($query,$limit)
    {
        return $query->inRandomOrder()->take($limit);
    }
}
