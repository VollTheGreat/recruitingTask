<?php

namespace App\Domain\Device\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Types has many Devices
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
