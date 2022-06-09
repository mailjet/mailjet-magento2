<?php
// phpcs:disable Generic.Files.LineLength.TooLong
namespace Mailjet\Mailjet\Helper;

use Mailjet\Mailjet\Helper\MailjetAPI as MailjetAPI;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const MAILJET_API_CREDENTIALS_URL = 'https://app.mailjet.com/account/api_keys';
    public const MAILJET_LOGIN_URL = 'https://app.mailjet.com/signup';

    public const REST_API_UNSUB_URL = '/V1/mailjet/unsub';
    public const REST_API_EVENTS = [
        MailjetAPI::WEBHOOK_EVENTS['unsub']
    ];

    public const REST_API_CONTACT_PROPERTIES = [
        'total_orders_count' => [
            'name' => 'magento2_total_orders_count',
            'data_type' => MailjetAPI::PROPERTY_DATA_TYPE['integer'],
            'name_space' => MailjetAPI::PROPERTY_NAME_SPACE['static']
        ],
        'total_spent' => [
            'name' => 'magento2_total_spent',
            'data_type' => MailjetAPI::PROPERTY_DATA_TYPE['float'],
            'name_space' => MailjetAPI::PROPERTY_NAME_SPACE['static']
        ],
        'last_order_date' => [
            'name' => 'magento2_last_order_date',
            'data_type' => MailjetAPI::PROPERTY_DATA_TYPE['datetime'],
            'name_space' => MailjetAPI::PROPERTY_NAME_SPACE['static']
        ],
        'account_creation_date' => [
            'name' => 'magento2_account_creation_date',
            'data_type' => MailjetAPI::PROPERTY_DATA_TYPE['datetime'],
            'name_space' => MailjetAPI::PROPERTY_NAME_SPACE['static']
        ]
    ];

    public const REST_API_TEMPLATES = [
        'order_confirmation' => [
            'name' => 'Order confirmation',
            'subject' => 'Thank you for shopping at {{var:store_name:""}}',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/order_confirmation.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/order_confirmation.html',
        ],
        'shipping_confirmation' => [
            'name' => 'Shipping confirmation',
            'subject' => 'Shipping confirmation',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/shipping_confirmation.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/shipping_confirmation.html',
        ],
        'refund_confirmation' => [
            'name' => 'Refund confirmation',
            'subject' => 'Refund confirmation',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/refund_confirmation.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/refund_confirmation.html',
        ],
        'order_cancellation' => [
            'name' => 'Cancellation confirmation',
            'subject' => 'Cancellation confirmation',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/order_cancellation.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/order_cancellation.html',
        ],
        'wishlist_reminder' => [
            'name' => 'Wishlist reminder',
            'subject' => 'Wishlist reminder',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/wishlist_reminder.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/wishlist_reminder.html',
        ],
        'items_back_in_stock' => [
            'name' => 'Items back in stock',
            'subject' => 'Items back in stock',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/items_back_in_stock.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/items_back_in_stock.html',
        ],
        'items_on_sale' => [
            'name' => 'Items on sale',
            'subject' => 'Items on sale',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['transactional'],
            'config' => self::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/items_on_sale.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/items_on_sale.html',
        ],
        'abandoned_cart' => [
            'name' => 'Abandoned cart',
            'subject' => 'Abandoned cart',
            'purpose' => MailjetAPI::TEMPLATE_PURPOSE['automation'],
            'config' => self::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TEMPLATE_ID,
            'json_file' => 'Mailjet_Mailjet::template/email/json/abandoned_cart.json',
            'html_file' => 'Mailjet_Mailjet::template/email/html/abandoned_cart.html',
        ]
    ];

    public const REST_API_SEGMENTS = [
        'newcomers' =>
            [
                'name' => 'Newcomers',
                'description' => 'Customers who have created an account in the past 30 days',
                'expression' => '(IsInPreviousDays(' . self::REST_API_CONTACT_PROPERTIES['account_creation_date']['name'] . ',30))'
            ],
        'potential_customers' =>
            [
                'name' => 'Potential customers',
                'description' => 'Contacts that don\'t have any orders',
                'expression' => '(' . self::REST_API_CONTACT_PROPERTIES['total_orders_count']['name'] . '<1)'
            ],
        'first_time_customers' =>
            [
                'name' => 'First time customers',
                'description' => 'Customers who have made their first purchase in the past 30 days',
                'expression' => '(' . self::REST_API_CONTACT_PROPERTIES['total_orders_count']['name'] . '=1) and (IsInPreviousDays(' . self::REST_API_CONTACT_PROPERTIES['last_order_date']['name'] . ',30))'
            ],
        'recent_customers' =>
            [
                'name' => 'Recent customers',
                'description' => 'Customers who have purchased in the past 30 days',
                'expression' => '(IsInPreviousDays(' . self::REST_API_CONTACT_PROPERTIES['last_order_date']['name'] . ',30))'
            ],
        'repeat_customers' =>
            [
                'name' => 'Repeat customers',
                'description' => 'Customers who have purchased more than once',
                'expression' => '(' . self::REST_API_CONTACT_PROPERTIES['total_orders_count']['name'] . '>1)'
            ],
        'lapsed_customers' =>
            [
                'name' => 'Lapsed customers 2',
                'description' => 'Customers who haven\'t purchased in the past 6 months',
                'expression' => '(not IsInPreviousDays(' . self::REST_API_CONTACT_PROPERTIES['last_order_date']['name'] . ',180))'
            ],
    ];

    public const MODULE_NAME = 'Mailjet_Mailjet';

    public const SMTP_HOST = 'in-v3.mailjet.com';
    public const SMTP_SSL = ['ssl' => 'SSL', 'tls' => 'TLS'];
    public const SMTP_PORTS = [25, 80, 465, 587, 588, 2525];
    public const SSL_PORTS = [465];

    public const SYNC_PREFERENCE_ALL = 1;
    public const SYNC_PREFERENCE_ONLY_FUTURE = 2;

    public const CONFIG_PATH_ACCOUNT_ACTIVE = 'mailjet/account/active';
    public const CONFIG_PATH_ACCOUNT_API_KEY = 'mailjet/account/api_key';
    public const CONFIG_PATH_ACCOUNT_SECRET_KEY = 'mailjet/account/secret_key';
    public const CONFIG_PATH_ACCOUNT_SUBSCRIBER_SYNC = 'mailjet/account/subscriber_sync';
    public const CONFIG_PATH_ACCOUNT_MAILJET_LIST = 'mailjet/account/mailjet_list';
    public const CONFIG_PATH_ACCOUNT_SYNC_PREFERENCE = 'mailjet/account/sync_preference';
    public const CONFIG_PATH_ACCOUNT_UNSUBSCRIBE_EVENT = 'mailjet/account/unsubscribe_event';
    public const CONFIG_PATH_ACCOUNT_SMTP_ACTIVE = 'mailjet/account/smtp_active';
    public const CONFIG_PATH_ACCOUNT_SMTP_PORT = 'mailjet/account/smtp_port';
    public const CONFIG_PATH_ACCOUNT_USE_SSL_TLS = 'mailjet/account/use_ssl_tls';

    public const CONFIG_PATH_ECOMMERCE_DATA = 'mailjet/ecommerce/data';
    public const CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE = 'mailjet/ecommerce/checkout_page_subscribe';
    public const CONFIG_PATH_ECOMMERCE_CHECKOUT_CHECKBOX_TEXT = 'mailjet/ecommerce/checkout_checkbox_text';
    public const CONFIG_PATH_ECOMMERCE_SUCCSESS_PAGE_SUBSCRIBE = 'mailjet/ecommerce/succsess_page_subscribe';
    public const CONFIG_PATH_ECOMMERCE_SUCCSESS_BANNER_TEXT = 'mailjet/ecommerce/succsess_banner_text';
    public const CONFIG_PATH_ECOMMERCE_SUCCSESS_BUTTON_TEXT = 'mailjet/ecommerce/succsess_button_text';
    public const CONFIG_PATH_ECOMMERCE_SUCCSESS_MESSAGE = 'mailjet/ecommerce/succsess_message';

    public const CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_STATUS = 'transactional/order_notification/order_confirmation_status';
    public const CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID = 'transactional/order_notification/order_confirmation_template_id';
    public const CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_STATUS = 'transactional/order_notification/shipping_confirmation_status';
    public const CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID = 'transactional/order_notification/shipping_confirmation_template_id';
    public const CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_STATUS = 'transactional/order_notification/order_cancellation_status';
    public const CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_TEMPLATE_ID = 'transactional/order_notification/order_cancellation_template_id';
    public const CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_STATUS = 'transactional/order_notification/refund_confirmation_status';
    public const CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_TEMPLATE_ID = 'transactional/order_notification/refund_confirmation_template_id';

    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_STATUS = 'transactional/wishlist_notifications/wishlist_reminder_status';
    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID = 'transactional/wishlist_notifications/wishlist_reminder_template_id';
    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_STATUS = 'transactional/wishlist_notifications/item_back_in_stock_status';
    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_TEMPLATE_ID = 'transactional/wishlist_notifications/item_back_in_stock_template_id';
    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_STATUS = 'transactional/wishlist_notifications/item_on_sale_status';
    public const CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_TEMPLATE_ID = 'transactional/wishlist_notifications/item_on_sale_template_id';

    public const CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_STATUS = 'automation/abandoned_cart/abandoned_cart_status';
    public const CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TIME = 'automation/abandoned_cart/abandoned_cart_time';
    public const CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TIME_TYPE = 'automation/abandoned_cart/abandoned_cart_time_type';
    public const CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TEMPLATE_ID = 'automation/abandoned_cart/abandoned_cart_template_id';

    public const FIELD_ID_ACCOUNT_ACTIVE = 'mailjet_account_active';
    public const FIELD_ID_ACCOUNT_API_BUTTON = 'mailjet_account_api_button';
    public const FIELD_ID_ACCOUNT_API_KEY = 'mailjet_account_api_key';
    public const FIELD_ID_ACCOUNT_SECRET_KEY = 'mailjet_account_secret_key';
    public const FIELD_ID_ACCOUNT_SUBSCRIBER_SYNC = 'mailjet_account_subscriber_sync';
    public const FIELD_ID_ACCOUNT_MAILJET_LIST = 'mailjet_account_mailjet_list';
    public const FIELD_ID_ACCOUNT_MAILJET_NEW_LIST_NAME = 'mailjet_account_mailjet_new_list_name';
    public const FIELD_ID_ACCOUNT_MAILJET_NEW_LIST_BUTTON = 'mailjet_account_mailjet_new_list_button';
    public const FIELD_ID_ACCOUNT_MAILJET_CANCEL_LIST_BUTTON = 'mailjet_account_mailjet_cancel_list_button';
    public const FIELD_ID_ACCOUNT_SYNC_PREFERENCE = 'mailjet_account_sync_preference';
    public const FIELD_ID_ACCOUNT_SYNC_RESYNC_BUTTON = 'mailjet_account_sync_resync_button';
    public const FIELD_ID_ACCOUNT_UNSUBSCRIBE_EVENT = 'mailjet_account_unsubscribe_event';
    public const FIELD_ID_ACCOUNT_SMTP_ACTIVE = 'mailjet_account_smtp_active';
    public const FIELD_ID_ACCOUNT_SMTP_PORT = 'mailjet_account_smtp_port';
    public const FIELD_ID_ACCOUNT_USE_SSL_TLS = 'mailjet_account_use_ssl_tls';
    public const FIELD_ID_ACCOUNT_TEST_SMTP_BUTTON = 'mailjet_account_test_smtp_button';
    public const FIELD_ID_ACCOUNT_TEST_SMTP_EMAIL = 'mailjet_account_test_smtp_email';
    public const FIELD_ID_ACCOUNT_TEST_SMTP_SEND_BUTTON = 'mailjet_account_test_smtp_send_button';
    public const FIELD_ID_ACCOUNT_TEST_SMTP_CANCEL_BUTTON = 'mailjet_account_test_smtp_cancel_button';

    public const FIELD_ID_ECOMMERCE_DATA = 'mailjet_ecommerce_data';
    public const FIELD_ID_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE = 'mailjet_ecommerce_checkout_page_subscribe';
    public const FIELD_ID_ECOMMERCE_CHECKOUT_CHECKBOX_TEXT = 'mailjet_ecommerce_checkout_checkbox_text';
    public const FIELD_ID_ECOMMERCE_SUCCSESS_PAGE_SUBSCRIBE = 'mailjet_ecommerce_succsess_page_subscribe';
    public const FIELD_ID_ECOMMERCE_SUCCSESS_BANNER_TEXT = 'mailjet_ecommerce_succsess_banner_text';
    public const FIELD_ID_ECOMMERCE_SUCCSESS_BUTTON_TEXT = 'mailjet_ecommerce_succsess_button_text';
    public const FIELD_ID_ECOMMERCE_SUCCSESS_MESSAGE = 'mailjet_ecommerce_succsess_message';

    public const FIELD_ID_ORDER_NOTIFICATION_ORDER_CONFIRMATION_STATUS = 'transactional_order_notification_order_confirmation_status';
    public const FIELD_ID_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID = 'transactional_order_notification_order_confirmation_template_id';
    public const FIELD_ID_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_STATUS = 'transactional_order_notification_shipping_confirmation_status';
    public const FIELD_ID_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID = 'transactional_order_notification_shipping_confirmation_template_id';
    public const FIELD_ID_ORDER_NOTIFICATION_ORDER_CANCELLATION_STATUS = 'transactional_order_notification_order_cancellation_status';
    public const FIELD_ID_ORDER_NOTIFICATION_ORDER_CANCELLATION_TEMPLATE_ID = 'transactional_order_notification_order_cancellation_template_id';
    public const FIELD_ID_ORDER_NOTIFICATION_REFUND_CONFIRMATION_STATUS = 'transactional_order_notification_refund_confirmation_status';
    public const FIELD_ID_ORDER_NOTIFICATION_REFUND_CONFIRMATION_TEMPLATE_ID = 'transactional_order_notification_refund_confirmation_template_id';

    public const FIELD_ID_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_STATUS = 'transactional_wishlist_notifications_wishlist_reminder_status';
    public const FIELD_ID_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID = 'transactional_wishlist_notifications_wishlist_reminder_template_id';
    public const FIELD_ID_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_STATUS = 'transactional_wishlist_notifications_item_back_in_stock_status';
    public const FIELD_ID_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_TEMPLATE_ID = 'transactional_wishlist_notifications_item_back_in_stock_template_id';
    public const FIELD_ID_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_STATUS = 'transactional_wishlist_notifications_item_on_sale_status';
    public const FIELD_ID_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_TEMPLATE_ID = 'transactional_wishlist_notifications_item_on_sale_template_id';

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    private $config;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \Magento\Framework\Encryption\Encryptor
     */
    private $encryptor;

    /**
     * @var \Zend\Http\Client\Adapter\Socket
     */
    private $zendHttpClient;

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Encryption\Encryptor $encryptor
     * @param \Zend\Http\Client\Adapter\Socket $zendHttpClient
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context                        $context,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface               $cacheTypeList,
        \Magento\Framework\UrlInterface                              $urlBuilder,
        \Magento\Framework\Encryption\Encryptor                      $encryptor,
        \Zend\Http\Client\Adapter\Socket                             $zendHttpClient
    ) {
        $this->config = $config;
        $this->cacheTypeList = $cacheTypeList;
        $this->urlBuilder = $urlBuilder;
        $this->encryptor = $encryptor;
        $this->zendHttpClient = $zendHttpClient;

        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $path
     * @param string $storeId
     * @param null|int|string $scope
     * @return mixed
     */
    public function getConfigValue($path, $storeId = null, $scope = null)
    {
        if ($scope) {
            return $this->scopeConfig->getValue($path, $scope, $storeId);
        } else {
            return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORES, $storeId);
        }
    }

    /**
     * Delete config
     *
     * @param string $path
     * @param string $storeId
     * @param null|int|string $scope
     * @return void
     */
    public function deleteConfig($path, $storeId = null, $scope = null)
    {
        $this->config->deleteConfig($path, $scope, $storeId);
    }

    /**
     * Save config value
     *
     * @param string $path
     * @param string $value
     * @param int $storeId
     * @param string $scope
     * @return void
     */
    public function saveConfigValue($path, $value, $storeId = null, $scope = null)
    {
        if ($scope) {
            $this->config->saveConfig($path, $value, $scope, $storeId);
        } else {
            $this->config->saveConfig($path, $value, \Magento\Store\Model\ScopeInterface::SCOPE_STORES, $storeId);
        }
        $this->cacheTypeList->cleanType('config');
    }

    /**
     * Get rest Api url
     *
     * @param string $type
     * @return string
     */
    public function getRestApiUrl($type)
    {
        switch ($type) {
            case MailjetAPI::WEBHOOK_EVENTS['unsub']:
                $url = $this->urlBuilder->getBaseUrl() . 'rest/all' . self::REST_API_UNSUB_URL;
                break;
            default:
                $url = '';
        }

        return $url;
    }

    /**
     * Get event status
     *
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $config
     * @param string $event
     * @return int
     */
    public function getEventStatus($config, $event)
    {
        switch ($event) {
            case MailjetAPI::WEBHOOK_EVENTS['unsub']:
                $status = $config->getUnsubscribeEvent();
                break;
            default:
                $status = 0;
        }

        return $status;
    }

    /**
     * Test Smtp connection
     *
     * @param int $port
     * @param string $ssl
     * @return bool
     */
    public function testSmtpConnection($port, $ssl)
    {
        try {
            $this->zendHttpClient->setOptions(['ssltransport' => $ssl, 'timeout' => 5]);

            if ($ssl == 'tls') {
                $this->zendHttpClient->connect(\Mailjet\Mailjet\Helper\Data::SMTP_HOST, $port);
            } else {
                $this->zendHttpClient->connect('ssl://' . \Mailjet\Mailjet\Helper\Data::SMTP_HOST, $port);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Smtp configs
     *
     * @param string $storeId
     * @return array
     * @throws \Exception
     */
    public function getSmtpConfigs($storeId)
    {
        $ssl = $this->getConfigValue(self::CONFIG_PATH_ACCOUNT_USE_SSL_TLS, $storeId);
        $config['host'] = self::SMTP_HOST;
        $config['port'] = $this->getConfigValue(self::CONFIG_PATH_ACCOUNT_SMTP_PORT, $storeId);
        $config['ssl'] = in_array($ssl, self::SSL_PORTS) ? $ssl : 'tls';
        $config['username'] = $this->getConfigValue(self::CONFIG_PATH_ACCOUNT_API_KEY, $storeId);
        $config['password'] = $this->encryptor->decrypt($this->getConfigValue(
            self::CONFIG_PATH_ACCOUNT_SECRET_KEY,
            $storeId
        ));
        $config['auth_type'] = 'login';

        return $config;
    }
}
// phpcs:enable Generic.Files.LineLength.TooLong
