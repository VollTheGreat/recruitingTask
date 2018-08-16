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
        $this->withoutExceptionHandling();
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
        $response->assertStatus(200);
    }
}
