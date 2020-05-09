<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Model\Helper;

use CreenSight\Core\Model\Helper\ConfigProvider as CoreConfigProvider;

/**
 * Class ConfigProvider
 * @package CreenSight\LoginAsCustomer\Model\Helper
 */
class ConfigProvider
{
    /**
     * @var string
     */
    const XML_PATH_GENERAL_ENABLED = 'loginascustomer/general/enabled';
    const XML_PATH_CUSTOMER_STARTUP_REDIRECT_DASHBOARD = 'customer/startup/redirect_dashboard';

    /**
     * @var CoreConfigProvider
     */
    protected $configProvider;

    /**
     * ConfigProvider constructor.
     *
     * @param CoreConfigProvider $configProvider
     */
    public function __construct(
        CoreConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function execute($path)
    {
        return $this->configProvider->execute($path);
    }
}
