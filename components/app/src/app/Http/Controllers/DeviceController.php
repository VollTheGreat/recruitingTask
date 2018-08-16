<?php

namespace  App\Http\Controllers;

use App\Domain\Device\Exceptions\DeviceCreationFailed;
use App\Domain\Device\Repositories\DeviceRepository;
use App\Domain\Device\Repositories\TypeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    /**
     * @var \App\Domain\Device\Repositories\DeviceRepository
     */
    private $deviceRepository;
    /**
     * @var \App\Domain\Device\Repositories\TypeRepository
     */
    private $typeRepository;

    /**
     * DeviceController constructor.
     *
     * @param \App\Domain\Device\Repositories\DeviceRepository $deviceRepository
     * @param \App\Domain\Device\Repositories\TypeRepository $typeRepository
     */
    public function __construct(
        DeviceRepository $deviceRepository,
        TypeRepository $typeRepository
    ) {
        $this->deviceRepository = $deviceRepository;
        $this->typeRepository = $typeRepository;
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
     * @param \App\Domain\Device\Controllers\DeviceStoreRequest $deviceStoreRequest
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DeviceStoreRequest $deviceStoreRequest): JsonResponse
    {
        try {
            $this->deviceRepository->create($deviceStoreRequest->all());
        } catch (DeviceCreationFailed $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while saving new device',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'device created successfully',
        ]);
    }
}
