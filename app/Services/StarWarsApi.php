<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StarWarsApi
{
    const BASE_URL = 'https://swapi.tech/api';

    const TYPE_QUERY = [
        'films' => [
            'search_key' => 'title',
            'search_index' => 'title',
            'path' => '/films',
            'detail_index' => [
                'opening_crawl',
                'characters',
                'title'
            ],
            'relation' => 'people',
            'relation_index' => 'characters'
        ],
        'people' => [
            'search_key' => 'name',
            'search_index' => 'name',
            'path' => '/people',
            'detail_index' => [
                'name',
                'birth_year',
                'gender',
                'eye_color',
                'hair_color',
                'height',
                'mass',
                'films'
            ],
            'relation' => 'films',
            'relation_index' => 'films'
        ],
    ];
    private string $baseUrl;

    public function __construct(string $baseUrl = self::BASE_URL)
    {
        $this->baseUrl = $baseUrl;
    }

    public function query(string $type, string $query): array
    {
        $typeQuery = self::TYPE_QUERY[$type];
        $url = $this->getQueryUrl($typeQuery['path'], $typeQuery['search_key'], $query);

        try {
            $response = Http::get($url);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('Error fetching data from Star Wars API');
        }

        $data = $response->json();

        return $this->clearQueryData($data['result'], $typeQuery['search_index']);
    }

    public function getOne(string $type, int $id): array
    {
        $typeQuery = self::TYPE_QUERY[$type];
        $url = $this->getDetailUrl($typeQuery['path'], $id);

        try {
            $response = Http::get($url);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('Error fetching data from Star Wars API');
        }

        $data = $response->json();

        $result = $data['result'];

        $dataCleaned = $this->clearDetailData($result, $typeQuery['detail_index']);

        $relation = $typeQuery['relation'];
        $relationIndex = $typeQuery['relation_index'];

        foreach ($dataCleaned['info'][$relationIndex] as $key => $url) {
            $dataCleaned['references'][] = $this->getOneRelation($relation, $url);
            unset($dataCleaned['info'][$relationIndex][$key]);
        }

        unset($dataCleaned['info'][$relationIndex]);

        $dataCleaned['name'] = $dataCleaned['info'][$typeQuery['search_index']];
        unset($dataCleaned[$typeQuery['search_index']]);

        $dataCleaned['type'] = $type;

        return $dataCleaned;
    }

    private function getQueryUrl(string $path, string $searchKey, string $query): string
    {
        return sprintf('%s%s?%s=%s', $this->baseUrl, $path, $searchKey, $query);
    }

    private function clearQueryData(array $data, string $index): array
    {
        return array_map(function (array $item) use ($index) {
            return [
                'id' => $item['uid'],
                $index => $item['properties'][$index]
            ];
        }, $data);
    }

    private function getDetailUrl(string $path, int $id): string
    {
        return sprintf('%s%s/%d', $this->baseUrl, $path, $id);
    }

    private function clearDetailData(array $data, array $detailIndex): array
    {
        $details = array_intersect_key($data['properties'], array_flip($detailIndex));
        return [
            'id' => $data['uid'],
            'info' => $details
        ];
    }

    private function getOneRelation(string $relation, string $referenceUrl): array
    {
        $typeQuery = self::TYPE_QUERY[$relation];

        try {
            $response = Http::get($referenceUrl);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception('Error fetching data from Star Wars API');
        }

        $data = $response->json();
        $result = $data['result'];

        $index = $typeQuery['search_index'];
        return [
            $index => $result['properties'][$index],
            'url' => route(
                'details',
                [
                    'type' => $relation,
                    'id' => $result['uid'],
                ]
            ),
        ];
    }

}
