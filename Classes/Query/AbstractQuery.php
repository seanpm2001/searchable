<?php
namespace PAGEmachine\Searchable\Query;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;


/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * Abstract helper class for elasticsearch querying
 */
abstract class AbstractQuery {

    /**
     * The array that is filled and later sent to the elasticsearch client for bulk indexing
     * 
     * @var array $parameters
     */
    protected $parameters = [];
    
    /**
     * @return array
     */
    public function getParameters() {
      return $this->parameters;
    }
    
    /**
     * @param array $parameters
     * @return void
     */
    public function setParameters($parameters) {
      $this->parameters = $parameters;
    }

    /**
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
     * Execute method, should be overriden with the concrete command to the client
     * and return the response
     * 
     * @return array
     */
    public function execute() {

        return [];
    }

}