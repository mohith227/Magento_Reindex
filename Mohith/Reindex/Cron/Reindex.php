<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 17/8/23
 * Time: 11:54 AM
 */

namespace Mohith\Reindex\Cron;

use Magento\Framework\App\Filesystem\DirectoryList;
use Mohith\Reindex\Model\Config\ReindexConfig;
use Mohith\Reindex\Model\Config\CronConfigData;
use Psr\Log\LoggerInterface;

class Reindex
{
    /**
     * @var CronConfigData
     */
    private $cronConfigData;
    /**
     * @var ReindexConfig
     */
    private $config;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * Directory List
     *
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * Reindex constructor.
     *
     * @param CronConfigData $cronConfigData
     * @param ReindexConfig $config
     * @param DirectoryList $directoryList
     * @param LoggerInterface $logger
     */
    public function __construct(
        CronConfigData $cronConfigData,
        ReindexConfig $config,
        DirectoryList $directoryList,
        LoggerInterface $logger
    ) {
        $this->cronConfigData = $cronConfigData;
        $this->config = $config;
        $this->logger = $logger;
        $this->directoryList = $directoryList;
    }

    /**
     * Execute Method
     */
    public function execute()
    {
        try {
            if ($this->config->getIsActive() && $this->cronConfigData->getIsActive()) {
                $rootPath = $this->directoryList->getRoot();
                $command = "php " . $rootPath . "/bin/magento indexer:reindex";
                $access_log = $rootPath . "/var/log/mohith_reindexing_access.log";
                $error_log = $rootPath . "/var/log/mohith_reindexing_error.log";
                shell_exec($command . " > $access_log 2> $error_log &");
            }
        } catch (\Exception $e) {
            $this->logger->critical(sprintf('Reindexing cron error: %s', $e->getMessage()));
        }
    }
}
