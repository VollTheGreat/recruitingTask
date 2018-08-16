<?php

namespace App\Domain\Device\Models;

use App\User;
use Carbon\Carbon;
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

    /**
     * Approve a device
     */
    public function approve()
    {
        return $this->update([
            'accepted_at' => Carbon::now(),
        ]);
    }
}
