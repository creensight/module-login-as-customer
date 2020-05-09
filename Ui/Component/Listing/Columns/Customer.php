<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Ui\Component\Listing\Columns;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Customer
 * @package CreenSight\LoginAsCustomer\Ui\Component\Listing\Columns
 */
class Customer extends Column
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * Customer constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CustomerRepository $customerRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepository $customerRepository,
        array $components = [],
        array $data = []
    ) {
        $this->customerRepository = $customerRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     *
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $customerId = $item['customer_id'];

                $customer = $this->customerRepository->getById($customerId);
                if ($customer && $customer->getId()) {
                    $item['customer_id'] = $customer->getFirstname() . ' ' . $customer->getLastname() . ' <' . $customer->getEmail() . '>';
                } else {
                    $item['customer_id'] = $item['customer_name'] . ' <' . $item['customer_email'] . '>';
                }
            }
        }

        return $dataSource;
    }
}
