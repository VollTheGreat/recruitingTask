<?php

namespace App\Domain\Device\Controllers;

use App\Domain\Device\Exceptions\DeviceCreationFailed;
use App\Domain\Device\Repositories\DeviceRepository;
use App\Domain\Device\Repositories\TypeRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        return view('device.index', [
            'devices' => $this->deviceRepository->getConfirmed(),
        ]);
    }

    /**
     * GET /device/create
     *
     * Display list of confirmed devices
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('device.create', [
            'types' => $this->typeRepository->getAll(),
        ]);
    }

    /**
     * PUT /device
     * PUT /api/device
     *
     * @param \App\Domain\Device\Controllers\DeviceStoreRequest $deviceStoreRequest
     *
     * @return string
     */
    public function store(DeviceStoreRequest $deviceStoreRequest)
    {
        try{
            $this->deviceRepository->create($deviceStoreRequest->all());
        }catch (DeviceCreationFailed $exception){
            return response()->json([
                'message' => 'error occurred while saving new device'
            ],500);
        }

        return response()->json();
    }
}
