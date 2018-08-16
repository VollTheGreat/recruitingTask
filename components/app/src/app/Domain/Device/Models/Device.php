<?php

namespace App\Domain\Device\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'type_id',
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

    /**
     * Device belongs to User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
