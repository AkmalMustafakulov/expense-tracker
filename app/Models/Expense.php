<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'quantity',
        'user_id',
        'category_id',
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['category_id'] ?? null, function (Builder $query, $category_id) {
                return $query->where('category_id', $category_id);
            })->when($filters['time'] ?? null, function (Builder $query, $time) {
            return $query->where('created_at','like', "%$time%");
        })->when($filters['last_week'] ?? null, function(Builder $query) {
            $start_of_week = date('Y-m-d', strtotime('-2 week'));
            $end_of_week = date('Y-m-d', strtotime('-1 week'));
            return $query->whereBetween('created_at', [$start_of_week, $end_of_week]);
        })->when($filters['last_month'] ?? null, function (Builder $query) {
            $last_month = date('Y-m', strtotime('last month'));
            return $query->where('created_at','like', "%$last_month%");
        })->when($filters['last_3_month'] ?? null, function(Builder $query) {
            $start_of_month = date('Y-m-d', strtotime('-3 month'));
            $end = date('Y-m-d');
            return $query->whereBetween('created_at', [$start_of_month, $end]);
        })->when($filters['start_of_date'] ?? null, function(Builder $query, $start_of_date) {
                $end_of_date = date('Y-m-d');
                return $query->whereBetween('created_at', [$start_of_date, $end_of_date]);
            }
        );
    }

}

