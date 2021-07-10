<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class ProductSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image', 'thumbnail', 'product_request_id', 'status'];

    use HasFactory;

    public function scopeStatus(Builder $query, string $status = null): Builder
    {
        if ($status) {
            $query->where('status', '=', $status);
        }
        return $query;
    }

    public function productRequest()
    {
        return $this->belongsTo(ProductRequest::class);
    }
}
