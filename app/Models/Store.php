<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $with = ['user'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'vat_percentage',
        'shipping_cost'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
