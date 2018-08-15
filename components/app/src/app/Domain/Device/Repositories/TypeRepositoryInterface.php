<?php
/**
 * Created by PhpStorm.
 * User: vollthegreat
 * Date: 16.08.18
 * Time: 00:17
 */

namespace App\Domain\Device\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface TypeRepositoryInterface
{
    /**
     * Get All model data
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getAll():?Collection;
}