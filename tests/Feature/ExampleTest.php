<?php

namespace Tests\Feature;

use App\City;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/api/weatherData');
        $response->assertStatus(200);
    }
    public function test_the_app_returns_data_key_if_city_is_provided_in_cityname_parameter()
    {
        $response = $this->get('/api/weatherData?cityName=Delhi');
        $response->assertJson([
            'data' => true,
        ]);
    }
}
