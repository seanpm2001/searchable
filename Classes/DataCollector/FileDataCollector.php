<?php
namespace PAGEmachine\Searchable\DataCollector;

use Doctrine\DBAL\Connection;

/*
 * This file is part of the PAGEmachine Searchable project.
 */

/**
 * Class for fetching pages data
 */
class FileDataCollector extends TcaDataCollector implements DataCollectorInterface
{
    /**
     * @var array
     */
    protected static $defaultConfiguration = [
        'pid' => null,
        'sysLanguageOverlay' => 1,
        'mode' => 'whitelist',
        'table' => 'sys_file_metadata',
        'fields' => [
            'title',
            'description',
            'file',
        ],
        'subCollectors' => [
            'file' => [
                'className' => \PAGEmachine\Searchable\DataCollector\TcaDataCollector::class,
                'config' => [
                    'field' => 'file',
                    'fields' => [],
                ],
            ],
        ],
        'mimetypes' => [
            '"text/plain"',
            '"application/pdf"',
        ],
    ];

    /**
     * Bulds query parts for the record selection query
     * Adds mimetype restriction via mm query
     *
     * @param  string  $additionalWhere
     * @param  bool $applyLanguageRestriction
     * @return array
     */
    public function buildUidListQueryParts($additionalWhere, $applyLanguageRestriction = false)
    {
        /**
         * @var array
         */
        $statement = parent::buildUidListQueryParts($additionalWhere, $applyLanguageRestriction);
        $statement['from'][] = 'sys_file';

        $statement['where'][] = ' AND sys_file_metadata.file = sys_file.uid';

        if (!empty($this->config['mimetypes'])) {
            $statement['where'][] = ' AND sys_file.mime_type IN(' . implode(',', $this->config['mimetypes']) . ')';
        }

        return $statement;
    }

    /**
     * Returns a QueryBuilder object for the record selection query
     * Modify this method if you want to apply custom restrictions
     *
     * @param  bool $applyLanguageRestriction
     * @return queryBuilder $subCollector
     */
    public function buildUidListQueryBuilder($applyLanguageRestriction = false)
    {
        $queryBuilder = parent::buildUidListQueryBuilder($applyLanguageRestriction);

        $queryBuilder->join(
            'sys_file_metadata',
            'sys_file',
            'sys_file',
            $queryBuilder->expr()->eq('sys_file_metadata.file', $queryBuilder->quoteIdentifier('sys_file.uid'))
        );

        if (!empty($this->config['mimetypes'])) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->in(
                    'sys_file.mime_type',
                    $this->config['mimetypes'],
                    Connection::PARAM_STR_ARRAY
                )
            );
        }

        return $queryBuilder;
    }
}
