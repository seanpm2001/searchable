<?php
namespace PAGEmachine\Searchable\Indexer;

use PAGEmachine\Searchable\DataCollector\TcaDataCollector;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * Simple TCA based indexer reading fields and processing them
 */
class TcaIndexer extends Indexer {

    /**
     * Configuration array holding all options needed for this indexer
     *
     * @var array
     */
    protected $config  = [
        'excludeFields' => [],
        'subtypes' => []
    ];

    /**
     * Main function for indexing
     * 
     * @return array
     */
    public function run() {

        $dataCollector = $this->objectManager->get(TcaDataCollector::class, $this->config);

        $recordUidList = $dataCollector->getRecordList();

        foreach ($recordUidList as $item) {

            $fullRecord = $dataCollector->getRecord($item['uid']);
            $fullRecord['preview'] = $this->previewRenderer->render($fullRecord);

            $this->query->addRow($item['uid'], $fullRecord);
        }

        $response = $this->query->execute();

        return $response;

    }








}