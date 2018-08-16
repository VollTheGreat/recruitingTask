<?php

namespace Tests\Feature;

use App\Domain\Device\Models\Device;
use App\Domain\Device\Models\Type;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_not_see_unconfirmed_devices_on_list()
    {
        //Prepare
        factory(Device::class)->create();
        //Act
        $response = $this->json('get', '/api/device');
        //Assertions
        $response
            ->assertStatus(200)
            ->assertJson([
                'devices' => [],
            ]);
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
        $response = $this->json('put', '/api/device', $userJson);
        $devices = Device::where([
            'model' => 'testModel',
            'brand' => 'testBrand',
            'system' => 'testSystem',
            'version' => 'testVersion',
        ])->get();
        //Assertions
        $this->assertCount(1, $devices);
        $response->assertStatus(200);
    }

    /** @test */
    public function new_device_report_email_was_send()
    {
        $this->withoutExceptionHandling();
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
        $response = $this->json('put', '/api/device', $userJson);
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
        $response = $this->json('put', '/api/device', $userJson);
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
        $response = $this->json('put', '/api/device', $userJson);
        //Assertions
        $response->assertStatus(422);
        $this->assertArrayHasKey('type_id', $response->decodeResponseJson('errors'));
    }
}
