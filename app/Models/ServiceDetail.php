<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceDetailFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function history()
    {
        return $this->belongsTo(ServiceHistory::class);
    }
}
