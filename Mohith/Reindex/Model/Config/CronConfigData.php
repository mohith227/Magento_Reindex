<?php
/**
 * Created by PhpStorm.
 * User: mohith
 * Date: 17/8/23
 * Time: 11:58 AM
 */

namespace Mohith\Reindex\Model\Config;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class CronConfigData
{
    const XML_GROUP = "Cron";

    const XML_SECTION = "reindex";

    const XML_FIELD = "enable";

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Array
     *
     * @var array
     */
    private $_data;

    /**
     * UserTrackingConfig constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_data = $data;
    }

    /**
     * GetValue
     *
     * @param $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return false|mixed
     */
    public function getValue(
        $field,
        $group = self::XML_GROUP,
        $section = self::XML_SECTION,
        $scope = ScopeInterface::SCOPE_STORE,
        $validateIsActive = true
    ) {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path . $scope, $this->_data)) {
            $this->_data[$path . $scope] = $validateIsActive &&
            !$this->getIsActive() ? false : $this->scopeConfig
                ->getValue($path, $scope);
        }

        return $this->_data[$path . $scope];
    }

    /**
     * GetEnableManualSchedulerForReindexing
     *
     * @return String
     */
    public function getEnableManualSchedulerForReindexing()
    {
        return $this->getValue(
            'enable_manual_scheduler_for_reindexing',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }

    /**
     * GetManualSchedulerForReindexing
     *
     * @return String
     */
    public function getManualSchedulerForReindexing()
    {
        return $this->getValue(
            'manual_scheduler_for_reindexing',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
    /**
     * GetFrequency
     *
     * @return String
     */
    public function getFrequency()
    {
        return $this->getValue(
            'frequency',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }

    /**
     * GetTime
     *
     * @return String
     */
    public function getTime()
    {
        return $this->getValue(
            'time',
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
    /**
     * Is Active
     *
     * @return bool
     */
    public function getIsActive()
    {
        return (bool)$this->getValue(
            self::XML_FIELD,
            self::XML_GROUP,
            self::XML_SECTION,
            ScopeInterface::SCOPE_STORE,
            false
        );
    }
}
