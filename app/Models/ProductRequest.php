<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'reference_code',
        'photographer_id',
        'client_id',
        'status'
    ];

    use HasFactory;
}
