<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Model\ResourceModel\Log;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use CreenSight\LoginAsCustomer\Model\ResourceModel\Log;

/**
 * Class Collection
 * @package CreenSight\LoginAsCustomer\Model\ResourceModel\Log
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'lac_log_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'log_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\CreenSight\LoginAsCustomer\Model\Log::class, Log::class);
    }
}
