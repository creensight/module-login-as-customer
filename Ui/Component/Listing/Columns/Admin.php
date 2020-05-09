<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\User\Model\UserFactory;

/**
 * Class Admin
 * @package CreenSight\LoginAsCustomer\Ui\Component\Listing\Columns
 */
class Admin extends Column
{
    /**
     * @var UserFactory
     */
    protected $userFactory;

    /**
     * Admin constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UserFactory $userFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UserFactory $userFactory,
        array $components = [],
        array $data = []
    ) {
        $this->userFactory = $userFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $adminId = $item['admin_id'];

                $admin = $this->userFactory->create()->load($adminId);
                if ($admin && $admin->getId()) {
                    $item['admin_id'] = $admin->getName() . ' <' . $admin->getEmail() . '>';
                } else {
                    $item['admin_id'] = $item['admin_name'] . ' <' . $item['admin_email'] . '>';
                }
            }
        }

        return $dataSource;
    }
}
