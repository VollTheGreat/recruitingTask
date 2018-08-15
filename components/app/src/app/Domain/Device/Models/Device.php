<?php

namespace App\Domain\Device\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'model',
        'brand',
        'system',
        'version',
        'mailed_to',
        'mailed_at',
        'accepted_at',
        'accepted_by',
    ];

    protected $dates = [
        'accepted_at',
        'mailed_at'
    ];

    /**
     * Device belongs to type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(){
        return $this->belongsTo(Type::class);
    }
}
