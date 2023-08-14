<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 7/8/23
 * Time: 1:43 PM
 */

namespace Mohith\Reindex\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mohith\Reindex\Model\Config\ReindexConfig;

class Config implements ObserverInterface
{
    const INDEXER_ACTION = "adminhtml.indexer.index.action";
    const INDEXER_MASS_ACTION = "adminhtml.indexer.grid.grid.massaction";

    /**
     * @var ReindexConfig
     */
    private $reindexConfig;

    /**
     * Config constructor.
     *
     * @param ReindexConfig $reindexConfig
     */
    public function __construct(ReindexConfig $reindexConfig)
    {
        $this->reindexConfig = $reindexConfig;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $layout = $observer->getLayout();
        $action = $layout->getBlock(self::INDEXER_ACTION);
        $massAction = $layout->getBlock(self::INDEXER_MASS_ACTION);
        if ($this->reindexConfig->getIsActive()) {
            if ($action && !($this->reindexConfig->getIndividualReindexing())) {
                $layout->unsetElement(self::INDEXER_ACTION);
            }
            if ($massAction && !($this->reindexConfig->getMassReindexing())) {
                $layout->unsetElement(self::INDEXER_MASS_ACTION);
            }
        } else {
            $layout->unsetElement(self::INDEXER_ACTION);
            $layout->unsetElement(self::INDEXER_MASS_ACTION);
        }
    }
}
