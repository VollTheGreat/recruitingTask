<?php
namespace App\Domain\Device\Repositories;

use App\Domain\Device\Exceptions\DeviceCreationFailed;
use App\Domain\Device\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DeviceRepository implements DeviceRepositoryInterface
{
    /**
     * Get All Unconfirmed Devices
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnconfirmed(): Collection
    {
        return Device::whereNull('accepted_at')->get();
    }

    /**
     * Get All Confirmed Devices
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConfirmed(): Collection
    {
        return Device::whereNotNull('accepted_at')->get();
    }

    /**
     * Create new Device
     *
     * @param array $allPostData
     *
     * @return \App\Domain\Device\Models\Device
     * @throws \App\Domain\Device\Exceptions\DeviceCreationFailed
     */
    public function create(array $allPostData)
    {
        DB::beginTransaction();
        try{
            $device = new Device();
            $device->fill($allPostData);
            $device->save();
            $device->type()->update($allPostData['type']);
            DB::commit();
        }catch (\Throwable $exception){
            DB::rollBack();
            throw new DeviceCreationFailed('Device creation failed',$exception->getCode(),$exception->getTrace());
        }

        return $device;
    }
}