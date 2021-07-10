<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class ProductRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'reference_code',
        'photographer_id',
        'client_id',
        'status'
    ];

    use HasFactory;

    public function scopeStatus(Builder $query, string $status = null)
    {
        if ($status) {
            $query->where('status', '=', $status);
        }
        return $query;
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
