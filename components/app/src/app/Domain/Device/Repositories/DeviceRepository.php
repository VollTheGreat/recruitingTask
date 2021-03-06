<?php

namespace App\Domain\Device\Repositories;

use App\Domain\Device\Exceptions\DeviceApprovingFailedException;
use App\Domain\Device\Exceptions\DeviceCanNoteBeDeleted;
use App\Domain\Device\Exceptions\DeviceCreationFailed;
use App\Domain\Device\Exceptions\NewDeviceMailSendFailedException;
use App\Domain\Device\Models\Device;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        try {
            $device = new Device();
            $device->fill($allPostData);
            $device->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new DeviceCreationFailed(
                'Device creation failed',
                $exception->getCode());
        }

        return $device;
    }

    /**
     * Report the new Device was Created
     *
     * @param \App\Domain\Device\Models\Device $device
     *
     * @return \App\Domain\Device\Models\Device
     * @throws \App\Domain\Device\Exceptions\NewDeviceMailSendFailedException
     */
    public function reportNewDevice(Device $device, string $reportEmail)
    {
        try {
            Mail::raw('new device need your confirm master', function ($message) use ($reportEmail) {
                $message->from('my@app.com');
                $message->to($reportEmail);
            });
            $device->update([
                'mailed_at' => Carbon::now(),
                'mailed_to' => $reportEmail,
            ]);
        } catch (\Exception $exception) {
            throw new NewDeviceMailSendFailedException($exception->getMessage(), $exception->getCode());
        }

        return $device;
    }

    /**
     * @param int $id
     *
     * @return \App\Domain\Device\Models\Device
     * @throws \App\Domain\Device\Exceptions\DeviceApprovingFailedException
     */
    public function approve(int $id)
    {
        try {
            $device = Device::find($id);
            if ($device->accepted_at) {
                throw new DeviceApprovingFailedException('Can not approve already approved device');
            }
            $device->approve();
        } catch (\RuntimeException $exception) {
            throw new DeviceApprovingFailedException($exception->getMessage(), $exception->getCode());
        }

        return $device;
    }

    /**
     * Delete Device
     *
     * @param int $id
     *
     * @return bool
     * @throws \App\Domain\Device\Exceptions\DeviceApprovingFailedException
     * @throws \App\Domain\Device\Exceptions\DeviceCanNoteBeDeleted
     */
    public function delete(int $id)
    {
        try {
            $device = Device::find($id);
            if ($device->accepted_at) {
                throw new DeviceCanNoteBeDeleted('Can not delete already approved device');
            }
            $device->delete();
        } catch (\RuntimeException $exception) {
            throw new DeviceApprovingFailedException($exception->getMessage(), $exception->getCode());
        }

        return true;
    }
}