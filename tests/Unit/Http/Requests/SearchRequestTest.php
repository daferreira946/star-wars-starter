<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SearchRequestTest extends TestCase
{
    public function test_authorize_always_returns_true()
    {
        $request = new SearchRequest();

        $result = $request->authorize();

        $this->assertTrue($result);
    }

    public function test_validation_passes_with_valid_data()
    {
        $data = [
            'type' => 'people',
            'query' => 'Luke'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertFalse($validator->fails());
        $this->assertEquals($data, $validator->validated());
    }

    public function test_validation_passes_with_films_type()
    {
        $data = [
            'type' => 'films',
            'query' => 'Hope'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertFalse($validator->fails());
    }

    public function test_validation_fails_with_invalid_type()
    {
        $data = [
            'type' => 'starships',
            'query' => 'Falcon'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('type', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_type_is_missing()
    {
        $data = [
            'query' => 'Luke'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('type', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_query_is_missing()
    {
        $data = [
            'type' => 'people'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('query', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_query_is_empty_string()
    {
        $data = [
            'type' => 'people',
            'query' => ''
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('query', $validator->errors()->toArray());
    }

    public function test_validation_passes_with_minimum_query_length()
    {
        $data = [
            'type' => 'people',
            'query' => 'a'
        ];

        $request = new SearchRequest();
        $validator = Validator::make($data, $request->rules());


        $this->assertFalse($validator->fails());
    }

    public function test_rules_method_returns_correct_validation_rules()
    {
        $request = new SearchRequest();

        $rules = $request->rules();

        $expectedRules = [
            'type' => 'required|in:people,films',
            'query' => 'required|string|min:1',
        ];

        $this->assertEquals($expectedRules, $rules);
    }
}
