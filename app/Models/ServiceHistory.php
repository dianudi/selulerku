<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceHistory extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceHistoryFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['details'];

    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    protected function casts(): array
    {
        return [
            'warranty_expired_at' => 'datetime',
        ];
    }
}
