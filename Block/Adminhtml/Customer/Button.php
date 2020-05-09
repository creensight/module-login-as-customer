<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Block\Adminhtml\Customer;

use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use CreenSight\LoginAsCustomer\Model\Helper\ConfigProvider;
use CreenSight\LoginAsCustomer\Model\Helper\Authorize;

/**
 * Class Button
 * @package CreenSight\LoginAsCustomer\Block\Adminhtml\Customer
 */
class Button extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Authorize
     */
    private $authorize;

    /**
     * Button constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ConfigProvider $configProvider
     * @param Authorize $authorize
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ConfigProvider $configProvider,
        Authorize $authorize
    ) {
        $this->configProvider = $configProvider;
        $this->authorize = $authorize;
        parent::__construct($context, $registry);
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $customerId = $this->getCustomerId(); $data = [];

        if ($customerId && $this->configProvider->execute(ConfigProvider::XML_PATH_GENERAL_ENABLED) && $this->authorize->execute()) {
            $data = [
                'label'      => __('Login as Customer'),
                'class'      => 'login-as-customer',
                'on_click'   => sprintf("window.open('%s');", $this->getLoginUrl()),
                'sort_order' => 60,
            ];
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->getUrl('loginascustomer/login/index', ['id' => $this->getCustomerId()]);
    }
}
