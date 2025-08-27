<?php

namespace Tests\Unit\Services;

use App\Services\StarWarsApi;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class StarWarsApiTest extends TestCase
{
    private StarWarsApi $starWarsApi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->starWarsApi = new StarWarsApi();
    }

    public function test_query_returns_filtered_people_data()
    {
        $type = 'people';
        $query = 'Luke';
        $expectedResponse = [
            'result' => [
                [
                    'uid' => '1',
                    'properties' => [
                        'name' => 'Luke Skywalker'
                    ]
                ]
            ]
        ];

        Http::fake([
            'swapi.tech/api/people?name=Luke' => Http::response($expectedResponse)
        ]);

        $result = $this->starWarsApi->query($type, $query);

        $this->assertEquals([
            [
                'id' => '1',
                'name' => 'Luke Skywalker'
            ]
        ], $result);
    }

    public function test_query_returns_filtered_films_data()
    {
        $type = 'films';
        $query = 'Hope';
        $expectedResponse = [
            'result' => [
                [
                    'uid' => '1',
                    'properties' => [
                        'title' => 'A New Hope'
                    ]
                ]
            ]
        ];

        Http::fake([
            'swapi.tech/api/films?title=Hope' => Http::response($expectedResponse)
        ]);

        $result = $this->starWarsApi->query($type, $query);

        $this->assertEquals([
            [
                'id' => '1',
                'title' => 'A New Hope'
            ]
        ], $result);
    }

    public function test_query_throws_exception_when_http_fails()
    {
        Log::shouldReceive('error')->once();
        Http::fake([
            'https://swapi.tech/api/people?name=Luke' => Http::throw()
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error fetching data from Star Wars API');

        $this->starWarsApi->query('people', 'Luke');
    }

    public function test_get_one_returns_detailed_person_data()
    {
        $type = 'people';
        $id = 1;
        $expectedDetailResponse = [
            'result' => [
                'uid' => '1',
                'properties' => [
                    'name' => 'Luke Skywalker',
                    'birth_year' => '19BBY',
                    'gender' => 'male',
                    'eye_color' => 'blue',
                    'hair_color' => 'blond',
                    'height' => '172',
                    'mass' => '77',
                    'films' => [
                        'https://swapi.tech/api/films/1',
                        'https://swapi.tech/api/films/2'
                    ]
                ]
            ]
        ];

        $expectedFilmResponse = [
            'result' => [
                'uid' => '1',
                'properties' => [
                    'title' => 'A New Hope'
                ]
            ]
        ];

        Http::fake([
            'https://swapi.tech/api/people/1' => Http::response($expectedDetailResponse),
            'https://swapi.tech/api/films/1' => Http::response($expectedFilmResponse)
        ]);


        $result = $this->starWarsApi->getOne($type, $id);


        $this->assertEquals('1', $result['id']);
        $this->assertEquals('people', $result['type']);
        $this->assertEquals('Luke Skywalker', $result['info']['name']);
        $this->assertArrayHasKey('info', $result);
        $this->assertArrayHasKey('references', $result);
        $this->assertCount(2, $result['references']);
    }

    public function test_custom_base_url_is_used()
    {
        $customBaseUrl = 'https://custom-api.com';
        $customApi = new StarWarsApi($customBaseUrl);

        Http::fake([
            'custom-api.com/people?name=Luke' => Http::response(['result' => []])
        ]);


        $customApi->query('people', 'Luke');


        Http::assertSent(function ($request) use ($customBaseUrl) {
            return str_starts_with($request->url(), $customBaseUrl);
        });
    }

    public function test_get_one_throws_exception_when_http_fails()
    {
        Log::shouldReceive('error')->once();
        Http::fake([
            'https://swapi.tech/api/people/1' => Http::throw()
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error fetching data from Star Wars API');

        $this->starWarsApi->getOne('people', 1);
    }


    public function test_get_one_detail_throws_exception_when_http_fails()
    {
        Log::shouldReceive('error')->once();

        $expectedDetailResponse = [
            'result' => [
                'uid' => '1',
                'properties' => [
                    'name' => 'Luke Skywalker',
                    'birth_year' => '19BBY',
                    'gender' => 'male',
                    'eye_color' => 'blue',
                    'hair_color' => 'blond',
                    'height' => '172',
                    'mass' => '77',
                    'films' => [
                        'https://swapi.tech/api/films/1',
                        'https://swapi.tech/api/films/2'
                    ]
                ]
            ]
        ];

        Http::fake([
            'https://swapi.tech/api/people/1' => Http::response($expectedDetailResponse),
            'https://swapi.tech/api/films/1' => Http::throw()
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error fetching data from Star Wars API');

        $this->starWarsApi->getOne('people', 1);
    }
}
