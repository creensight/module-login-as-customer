<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Model\Helper;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GetCustomerStore
 * @package CreenSight\LoginAsCustomer\Model\Helper
 */
class GetCustomerStore
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * GetCustomerStore constructor.
     *
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @param Customer $customer
     *
     * @return StoreInterface|null
     * @throws NoSuchEntityException
     */
    public function execute($customer)
    {
        if ($storeId = $customer->getStoreId()) {
            return $this->storeManager->getStore($storeId);
        }

        return $this->storeManager->getDefaultStoreView();
    }
}
