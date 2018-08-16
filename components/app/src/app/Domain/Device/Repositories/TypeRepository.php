<?php

namespace App\Domain\Device\Repositories;

use App\Domain\Device\Models\Type;
use Illuminate\Database\Eloquent\Collection;

class TypeRepository implements TypeRepositoryInterface
{
    /**
     * Get All available Types
     *
     * @return \App\Domain\Device\Models\Type[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll():?Collection
    {
        return Type::all();
    }
}