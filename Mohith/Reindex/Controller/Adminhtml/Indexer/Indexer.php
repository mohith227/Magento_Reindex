<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 7/8/23
 * Time: 12:50 PM
 */

namespace Mohith\Reindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Mohith\Reindex\Model\Indexer as IndexerModel;

class Indexer extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'Mohith_Reindex::indexer_reindex';

    /**
     * @var IndexerModel
     */
     private  $indexer;

    /**
     * Indexer constructor.
     *
     * @param Context $context
     * @param IndexerModel $indexer
     */
    public function __construct(
        Context $context,
        IndexerModel $indexer
    )
    {
        parent::__construct($context);
        $this->indexer = $indexer;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');

        if ($indexerIds) {
            try {
                if (!is_array($indexerIds)) {
                    $this->indexer->reindexById($indexerIds);
                } else {
                    $this->indexer->reindexAll($indexerIds);
                }
                $this->messageManager->addSuccessMessage(__('index(es) processed.'));
            } catch
            (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Re-indexer process did not start.')
                );
            }
        } else {
            $this->messageManager->addErrorMessage(__('Please select indexers.'));
        }

        $this->_redirect('*/*/list');
    }
}
