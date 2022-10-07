<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Settings;

class Item extends Model
{
    use HasFactory;

    public $keyType = 'string';

    protected $fillable = [
        'item',
        'description',
        'price',        
        'balance',
        'category',
    ];
}
