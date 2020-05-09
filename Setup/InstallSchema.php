<?php
/**
 * @copyright Copyright © 2020 CreenSight. All rights reserved.
 * @author CreenSight Development Team <magento@creensight.com>
 */

namespace CreenSight\LoginAsCustomer\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'login_as_customer'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('login_as_customer'))
            ->addColumn('entity_id', Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary'  => true
            ], 'Entity ID')
            ->addColumn('admin_id', Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Admin ID')
            ->addColumn('admin_email', Table::TYPE_TEXT, 255, [], 'Admin Email')
            ->addColumn('admin_name', Table::TYPE_TEXT, 255, [], 'Admin Name')
            ->addColumn('customer_id', Table::TYPE_INTEGER, null, ['unsigned' => true, 'nullable' => false, 'default' => '0'], 'Customer ID')
            ->addColumn('customer_email', Table::TYPE_TEXT, 255, [], 'Customer Email')
            ->addColumn('customer_name', Table::TYPE_TEXT, 255, [], 'Customer Name')
            ->addColumn('token', Table::TYPE_TEXT, 64, [], 'Token')
            ->addColumn('is_logged_in', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Is Logged In')
            ->addColumn('created_at', Table::TYPE_TIMESTAMP, null, ['nullable' => false, 'default' => Table::TIMESTAMP_INIT], 'Creation Time')
            ->setComment('Login As Customer Logs table');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
