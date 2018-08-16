<?php

namespace Tests\Feature;

use App\Domain\Device\Models\Device;
use App\Domain\Device\Models\Type;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    //TODO:: Unit Tests
    /** @test */
    public function user_can_not_see_unconfirmed_devices_on_list()
    {
        //Prepare
        factory(Device::class)->create();
        //Act
        $response = $this->json('get', route('device.index'));
        //Assertions
        $response
            ->assertStatus(200)
            ->assertJson([
                'devices' => [],
            ])
            ->assertJsonCount(0, 'devices');;
    }

    /** @test */
    public function user_can_create_new_device()
    {
        //Prepare
        $userJson = [
            'user_id' => factory(User::class)->create()->id,
            'type_id' => factory(Type::class)->create()->id,
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
            'report_email' => 'spavljuk@gmail.com',
        ];
        //Act
        $response = $this->json('put', route('device.store'), $userJson);
        $devices = Device::where([
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
        ])->get();
        //Assertions
        $this->assertCount(1, $devices);
        $response->assertStatus(200)->assertJson([
            'status' => 'success',
            'message' => 'device created successfully',
        ]);;
    }

    /** @test */
    public function new_device_report_email_was_send()
    {
        //Prepare
        $userJson = [
            'user_id' => factory(User::class)->create()->id,
            'type_id' => factory(Type::class)->create()->id,
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
            'report_email' => 'spavljuk@gmail.com',
        ];
        //Act
        $response = $this->json('put', route('device.store'), $userJson);
        //Assertions
        $response->assertStatus(200);
        $device = Device::where([
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
        ])->first();
        if (!($device->mailed_at and $device->mailed_to)) {
            $this->fail('There are no email sending information in device data');
        }
    }

    /** @test */
    public function user_can_not_create_new_device_without_email()
    {
        //Prepare
        $userJson = [
            'user_id' => factory(User::class)->create()->id,
            'type_id' => factory(Type::class)->create()->id,
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',

        ];
        //Act
        $response = $this->json('put', route('device.store'), $userJson);
        //Assertions
        $response->assertStatus(422);
        $this->assertArrayHasKey('report_email', $response->decodeResponseJson('errors'));
    }

    /** @test */
    public function user_can_not_create_new_device_without_type()
    {
        //Prepare
        $userJson = [
            'user_id' => factory(User::class)->create()->id,
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
            'report_email' => 'report_email@test.com',
        ];
        //Act
        $response = $this->json('put', route('device.store'), $userJson);
        //Assertions
        $response->assertStatus(422);
        $this->assertArrayHasKey('type_id', $response->decodeResponseJson('errors'));
    }

    /** @test */
    public function user_can_approve_device()
    {
        //Prepare
        $device = factory(Device::class)->create();
        //Act
        $response = $this->put(route('device.approve', ['id' => $device->id]));
        //Assertions

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Device Approved successfully',
            ]);
    }

    /** @test */
    public function user_can_note_approve_already_approved_device()
    {
        //Prepare
        $device = factory(Device::class)->create();
        //Act
        $this->put(route('device.approve', ['id' => $device->id]));
        $response = $this->put(route('device.approve', ['id' => $device->id]));
        //Assertions
        $response
            ->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'message' => 'Error occurred while Approving new device',
            ]);
    }

    /** @test */
    public function user_can_see_approved_devices()
    {
        //Prepare
        $device = factory(Device::class)->create();
        //Act
        $this->put(route('device.approve', ['id' => $device->id]));
        //Act
        $response = $this->json('get', route('device.index'));
        //Assertions
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'devices');
    }

    /** @test */
    public function user_can_delete_device()
    {
        //Prepare
        $device = factory(Device::class)->create();
        //Act
        $response = $this->delete(route('device.delete', ['id' => $device->id]));
        //Assertions
        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Device deleted successfully',
            ]);
        $this->json('get', route('device.index'))
            ->assertStatus(200)
            ->assertJsonCount(0, 'devices');
    }

    /** @test */
    public function user_can_note_delete_approved_device()
    {
        //Prepare
        $device = factory(Device::class)->create();
        //Act
        $this->put(route('device.approve', ['id' => $device->id]));
        $response = $this->delete(route('device.delete', ['id' => $device->id]));
        //Assertions
        $response
            ->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'message' => 'Can not delete already approved device',
            ]);
        $this->json('get', route('device.index'))
            ->assertStatus(200)
            ->assertJsonCount(1, 'devices');
    }
}
