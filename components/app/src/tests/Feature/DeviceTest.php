<?php

namespace Tests\Feature;

use App\Domain\Device\Models\Device;
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
        $response = $this->json('get','/api/device');
        //Assertions
        $response
            ->assertStatus(200)
            ->assertJson([
                'devices'=> []
            ]);
    }
}
