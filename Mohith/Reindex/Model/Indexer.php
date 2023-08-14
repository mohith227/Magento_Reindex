<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 7/8/23
 * Time: 12:53 PM
 */

namespace Mohith\Reindex\Model;

use Magento\Indexer\Model\IndexerFactory;
use Psr\Log\LoggerInterface;

class Indexer
{
    /**
     * $indexerFactory
     *
     * @var IndexerFactory
     */
    private $indexerFactory;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Indexer constructor.
     *
     * @param IndexerFactory $indexerFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        IndexerFactory $indexerFactory,
        LoggerInterface $logger
    )
    {
        $this->indexerFactory = $indexerFactory;
        $this->logger = $logger;
    }

    /**
     * Reindex All
     *
     * @param $indexIds
     */
    public function reindexAll($indexIds)
    {
        try {
            if ($indexIds) {
                foreach ($indexIds as $indexId) {
                    $this->reindexById($indexId);
                }
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * Reindex By Id
     *
     * @param $indexerId
     */
    public function reindexById($indexerId)
    {
        try {
            $indexer = $this->indexerFactory->create()->load($indexerId);
            if ($indexer && $indexer->getId()) {
                $indexer->reindexAll();
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
