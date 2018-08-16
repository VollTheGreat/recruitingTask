<?php

namespace App\Http\Controllers;

use App\Domain\Device\Exceptions\DeviceApprovingFailedException;
use App\Domain\Device\Exceptions\DeviceCanNoteBeDeleted;
use App\Domain\Device\Exceptions\DeviceCreationFailed;
use App\Domain\Device\Repositories\DeviceRepository;
use App\Domain\Device\Requests\DeviceStoreRequest;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';

    /**
     * @var \App\Domain\Device\Repositories\DeviceRepository
     */
    private $deviceRepository;

    /**
     * DeviceController constructor.
     *
     * @param \App\Domain\Device\Repositories\DeviceRepository $deviceRepository
     */
    public function __construct(
        DeviceRepository $deviceRepository
    ) {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * GET /device
     *
     * Display list of confirmed devices
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'devices' => $this->deviceRepository->getConfirmed(),
        ], 200);
    }

    /**
     * PUT /api/device
     *
     * @param \App\Domain\Device\Requests\DeviceStoreRequest $deviceStoreRequest
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Domain\Device\Exceptions\NewDeviceMailSendFailedException
     */
    public function store(DeviceStoreRequest $deviceStoreRequest): JsonResponse
    {
        try {
            $device = $this->deviceRepository->create($deviceStoreRequest->all());
            $this->deviceRepository->reportNewDevice($device, $deviceStoreRequest->get('report_email'));
        } catch (DeviceCreationFailed $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while saving new device',
            ], 500);
        }

        return response()->json([
            'status' => self::STATUS_SUCCESS,
            'message' => 'device created successfully',
        ], 200);
    }

    /**
     * PUT /api/device/:id/approve
     *
     * Approve Device for displaying in list
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(int $id)
    {
        try {
            $this->deviceRepository->approve($id);
        } catch (DeviceApprovingFailedException $exception) {
            return response()->json([
                'status' => self::STATUS_ERROR,
                'message' => 'Error occurred while Approving new device',
            ], 500);
        }

        return response()->json([
            'status' => self::STATUS_SUCCESS,
            'message' => 'Device Approved successfully',
        ], 200);
    }

    /**
     * DELETE /api/device/id
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Domain\Device\Exceptions\DeviceApprovingFailedException
     */
    public function delete(int $id)
    {
        try {
            $this->deviceRepository->delete($id);
        } catch (DeviceCanNoteBeDeleted $exception) {
            return response()->json([
                'status' => self::STATUS_ERROR,
                'message' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => self::STATUS_SUCCESS,
            'message' => 'Device deleted successfully',
        ], 200);
    }
}
