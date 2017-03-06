<?php
namespace PAGEmachine\Searchable;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * Manages some index level functions such as clearing the index
 */
class IndexManager implements SingletonInterface {

    /**
     * Elasticsearch client
     * @var Client
     */
    protected $client;

    /**
     * @param Client|null $client
     */
    public function __construct(Client $client = null) {

        $this->client = $client ?: ClientBuilder::create()->build();
    }

    /**
     * @return IndexManager
     */
    public static function getInstance() {

        return GeneralUtility::makeInstance(IndexManager::class);

    }

    /**
     * Deletes and recreates an index
     * @param  string $index
     * @return array
     */
    public function resetIndex($index) {

        $deleteParams = [
            'index' => $index
        ];

        $response = $this->client->indices()->delete($deleteParams);

        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 2,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        $response = $this->client->indices()->create($params);
    }





}