<?php

namespace Mailjet\Mailjet\Model\Api;

class Email
{
    /**
     * @var \Mailjet\Mailjet\Model\Api\Connection
     */
    protected $apiConnection;

    /**
     * @var \Mailjet\Mailjet\Api\ConfigRepositoryInterface
     */
    protected $configRepository;

    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Sales\Model\Order\Email\Container\OrderIdentity
     */
    protected $orderIdentity;

    /**
     * @var \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity
     */
    protected $shipmentIdentity;

    /**
     * @var \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity
     */
    protected $creditmemoIdentity;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Mailjet\Mailjet\Model\Api\Email\Data
     */
    protected $emailData;

    /**
     *
     * @param \Mailjet\Mailjet\Model\Api\Connection $apiConnection
     * @param \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Sales\Model\Order\Email\Container\OrderIdentity $orderIdentity
     * @param \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity $shipmentIdentity
     * @param \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $creditmemoIdentity
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mailjet\Mailjet\Model\Api\Email\Data $emailData
     */
    public function __construct(
        \Mailjet\Mailjet\Model\Api\Connection $apiConnection,
        \Mailjet\Mailjet\Api\ConfigRepositoryInterface $configRepository,
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Sales\Model\Order\Email\Container\OrderIdentity $orderIdentity,
        \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity $shipmentIdentity,
        \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $creditmemoIdentity,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mailjet\Mailjet\Model\Api\Email\Data $emailData
    ) {
        $this->apiConnection                     = $apiConnection;
        $this->configRepository                  = $configRepository;
        $this->dataHelper                        = $dataHelper;
        $this->orderIdentity                     = $orderIdentity;
        $this->shipmentIdentity                  = $shipmentIdentity;
        $this->creditmemoIdentity                = $creditmemoIdentity;
        $this->storeManager                      = $storeManager;
        $this->emailData                         = $emailData;
    }

    /**
     * Send new credit memo email
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Creditmemo $creditMemo
     * @param Int $storeId
     * @return Bool
     */
    public function newCreditMemo($creditMemo, $storeId)
    {
        $order = $creditMemo->getOrder();
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);

        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_REFUND_CONFIRMATION_TEMPLATE_ID, $storeId);

        $data = [
            'from_name'   => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/name', $storeId),
            'from_email'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/email', $storeId),
            'template_id' => $templateId,
            'error_receiver' => [
                'name'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/name', $storeId),
                'email' => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/email', $storeId),
            ],
            'to'          => [[
                'name'  => $order->getCustomerName(),
                'email' => $order->getCustomerEmail(),
            ]]
        ];

        if ($this->creditmemoIdentity->getCopyMethod() == 'bcc') {
            $bccs = $this->creditmemoIdentity->getEmailCopyTo() ? $this->creditmemoIdentity->getEmailCopyTo() : [];

            foreach ($bccs as $bcc) {
                $data['bcc'][] = [
                    'email' => $bcc,
                    'name' => '',
                ];
            }
        } else {
            $ccs = $this->creditmemoIdentity->getEmailCopyTo() ? $this->creditmemoIdentity->getEmailCopyTo() : [];

            foreach ($ccs as $cc) {
                $data['cc'][] = [
                    'email' => $cc,
                    'name' => '',
                ];
            }
        }

        $this->emailData->setStore($order->getStore())
            ->setOrder($order)
            ->setCreditMemo($creditMemo)
            ->setItems($creditMemo->getAllItems())
            ->setTotals($creditMemo);

        $data['vars'] = $this->emailData->getParams();

        $result = $connection->sendEmailV31([$data]);

        return (bool)$result;
    }

    /**
     * Send cancel order email
     *
     * @param \Magento\Sales\Model\Order $order
     * @param Int $storeId
     * @return Bool
     */
    public function cancelOrder($order, $storeId)
    {
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);

        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CANCELLATION_TEMPLATE_ID, $storeId);

        $data = [
            'from_name'   => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/name', $storeId),
            'from_email'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email', $storeId),
            'template_id' => $templateId,
            'error_receiver' => [
                'name'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/name', $storeId),
                'email' => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email', $storeId),
            ],
            'to'          => [[
                'name'  => $order->getCustomerName(),
                'email' => $order->getCustomerEmail(),
            ]]
        ];

        if ($this->orderIdentity->getCopyMethod() == 'bcc') {
            $bccs = $this->orderIdentity->getEmailCopyTo() ? $this->orderIdentity->getEmailCopyTo() : [];

            foreach ($bccs as $bcc) {
                $data['bcc'][] = [
                    'email' => $bcc,
                    'name' => '',
                ];
            }
        } else {
            $ccs = $this->orderIdentity->getEmailCopyTo() ? $this->orderIdentity->getEmailCopyTo() : [];

            foreach ($ccs as $cc) {
                $data['cc'][] = [
                    'email' => $cc,
                    'name' => '',
                ];
            }
        }

        $this->emailData->setStore($order->getStore())
            ->setOrder($order)
            ->setItems($order->getAllVisibleItems())
            ->setTotals($order);

        $data['vars'] = $this->emailData->getParams();

        $connection->sendEmailV31([$data]);
    }

    /**
     * Send new order email
     *
     * @param \Magento\Sales\Model\Order $order
     * @param Int $storeId
     * @return Bool
     */
    public function newOrder($order, $storeId)
    {
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);

        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_ORDER_CONFIRMATION_TEMPLATE_ID, $storeId);

        $data = [
            'from_name'   => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/name', $storeId),
            'from_email'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email', $storeId),
            'template_id' => $templateId,
            'error_receiver' => [
                'name'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/name', $storeId),
                'email' => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email', $storeId),
            ],
            'to'          => [[
                'name'  => $order->getCustomerName(),
                'email' => $order->getCustomerEmail(),
            ]]
        ];

        if ($this->orderIdentity->getCopyMethod() == 'bcc') {
            $bccs = $this->orderIdentity->getEmailCopyTo() ? $this->orderIdentity->getEmailCopyTo() : [];

            foreach ($bccs as $bcc) {
                $data['bcc'][] = [
                    'email' => $bcc,
                    'name' => '',
                ];
            }
        } else {
            $ccs = $this->orderIdentity->getEmailCopyTo() ? $this->orderIdentity->getEmailCopyTo() : [];

            foreach ($ccs as $cc) {
                $data['cc'][] = [
                    'email' => $cc,
                    'name' => '',
                ];
            }
        }

        $this->emailData->setStore($order->getStore())
            ->setOrder($order)
            ->setItems($order->getAllVisibleItems())
            ->setTotals($order);

        $data['vars'] = $this->emailData->getParams();

        $result = $connection->sendEmailV31([$data]);

        return (bool)$result;
    }

    /**
     * Send new shipment email
     *
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @param Int $storeId
     * @return Bool
     */
    public function newShipment($shipment, $storeId)
    {
        $order = $shipment->getOrder();
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);

        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ORDER_NOTIFICATION_SHIPPING_CONFIRMATION_TEMPLATE_ID, $storeId);

        $data = [
            'from_name'   => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/name', $storeId),
            'from_email'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/email', $storeId),
            'template_id' => $templateId,
            'error_receiver'          => [
                'name'  => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/name', $storeId),
                'email' => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/email', $storeId),
            ],
            'to'          => [[
                'name'  => $order->getCustomerName(),
                'email' => $order->getCustomerEmail(),
            ]]
        ];

        if ($this->shipmentIdentity->getCopyMethod() == 'bcc') {
            $bccs = $this->shipmentIdentity->getEmailCopyTo() ? $this->shipmentIdentity->getEmailCopyTo() : [];

            foreach ($bccs as $bcc) {
                $data['bcc'][] = [
                    'email' => $bcc,
                    'name' => '',
                ];
            }
        } else {
            $ccs = $this->shipmentIdentity->getEmailCopyTo() ? $this->shipmentIdentity->getEmailCopyTo() : [];

            foreach ($ccs as $cc) {
                $data['cc'][] = [
                    'email' => $cc,
                    'name' => '',
                ];
            }
        }

        $this->emailData->setStore($order->getStore())
            ->setOrder($order)
            ->setItems($shipment->getAllItems())
            ->setShipment($shipment)
            ->setShipmentTracking($order, $shipment);

        $data['vars'] = $this->emailData->getParams();

        $result = $connection->sendEmailV31([$data]);

        return (bool)$result;
    }

    /**
     * Send remind wishlist email
     *
     * @param \Magento\Customer\Model\Customer $customer
     * @param Array $productsIds
     * @param Int $storeId
     * @return Void
     */
    public function remindWishlist($customer, $productsIds, $storeId)
    {
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);

        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_WISHLIST_REMINDER_TEMPLATE_ID, $storeId);

        $data = [
            'from_name'   => $this->dataHelper->getConfigValue('trans_email/ident_sales/name', $storeId),
            'from_email'  => $this->dataHelper->getConfigValue('trans_email/ident_sales/email', $storeId),
            'template_id' => $templateId,
            'error_receiver'          => [
                'name'  => $this->dataHelper->getConfigValue('trans_email/ident_sales/name', $storeId),
                'email' => $this->dataHelper->getConfigValue('trans_email/ident_sales/email', $storeId),
            ],
            'to'          => [[
                'name'  => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'email' => $customer->getEmail(),
            ]]
        ];

        $this->emailData->setStore($this->storeManager->getStore($storeId))
            ->setCustomer($customer)
            ->setProductData($productsIds);

        $data['vars'] = $this->emailData->getParams();

        $connection->sendEmailV31([$data]);
    }

    /**
     * Send notify abandoned cart
     *
     * @param \Magento\Quote\Api\Data\CartInterface[] $carts
     * @param Int $storeId
     * @return Void
     */
    public function notifyAbandonedCart($carts, $storeId)
    {
        $config = $this->configRepository->getByStoreId($storeId);
        $connection = $this->apiConnection->getConnection($config);
        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ABANDONED_CART_ABANDONED_CART_TEMPLATE_ID, $storeId);
        $salesEmail = $this->dataHelper->getConfigValue('trans_email/ident_sales/email', $storeId);
        $salesName = $this->dataHelper->getConfigValue('trans_email/ident_sales/name', $storeId);
        $messages = [];

        foreach ($carts as $cart) {
            $customer = $cart->getCustomer();

            $message = [
                'from_name'   => $salesName,
                'from_email'  => $salesEmail,
                'template_id' => $templateId,
                'error_receiver'          => [
                    'name'  => $salesName,
                    'email' => $salesEmail,
                ],
                'to'          => [[
                    'name'  => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    'email' => $customer->getEmail(),
                ]]
            ];

            $this->emailData->setStore($this->storeManager->getStore($storeId))
                ->setItems($cart->getItemsCollection());

            $message['vars'] = $this->emailData->getParams();
            $messages[] = $message;
        }

        if ($messages) {
            $connection->sendEmailV31($messages);
        }
    }

    /**
     * Send notify product is on sale
     *
     * @param Array $customers
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $connection
     * @param Int $storeId
     * @return Void
     */
    public function notifySale($customers, $connection, $storeId)
    {
        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_ON_SALE_TEMPLATE_ID, $storeId);
        $salesEmail = $this->dataHelper->getConfigValue('trans_email/ident_sales/email', $storeId);
        $salesName = $this->dataHelper->getConfigValue('trans_email/ident_sales/name', $storeId);
        $messages = [];

        foreach ($customers as $customer) {
            $message = [
                'from_name'   => $salesName,
                'from_email'  => $salesEmail,
                'template_id' => $templateId,
                'error_receiver'          => [
                    'name'  => $salesName,
                    'email' => $salesEmail,
                ],
                'to'          => [[
                    'name'  => $customer[\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::NAME],
                    'email' => $customer[\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::EMAIL],
                ]]
            ];

            $property = json_decode($customer['property'], true);
            $productsIds = $property['productIds'];

            $this->emailData->setStore($this->storeManager->getStore($storeId))
                ->setProductData($productsIds);

            $message['vars'] = $this->emailData->getParams();
            $messages[] = $message;
        }

        if ($messages) {
            $connection->sendEmailV31($messages);
        }
    }

    /**
     * Send notify product is in stock
     *
     * @param Array $customers
     * @param \Mailjet\Mailjet\Api\Data\ConfigInterface $connection
     * @param Int $storeId
     * @return Void
     */
    public function notifyStock($customers, $connection, $storeId)
    {
        $templateId = $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_WISHLIST_NOTIFICATIONS_ITEM_BACK_IN_STOCK_TEMPLATE_ID, $storeId);
        $salesEmail = $this->dataHelper->getConfigValue('trans_email/ident_sales/email', $storeId);
        $salesName = $this->dataHelper->getConfigValue('trans_email/ident_sales/name', $storeId);
        $messages = [];

        foreach ($customers as $customer) {
            $message = [
                'from_name'   => $salesName,
                'from_email'  => $salesEmail,
                'template_id' => $templateId,
                'error_receiver'          => [
                    'name'  => $salesName,
                    'email' => $salesEmail,
                ],
                'to'          => [[
                    'name'  => $customer[\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::NAME],
                    'email' => $customer[\Mailjet\Mailjet\Api\Data\SubscriberQueueInterface::EMAIL],
                ]]
            ];

            $property = json_decode($customer['property'], true);
            $productsIds = $property['productIds'];

            $this->emailData->setStore($this->storeManager->getStore($storeId))
                ->setProductData($productsIds);

            $message['vars'] = $this->emailData->getParams();
            $messages[] = $message;
        }

        if ($messages) {
            $connection->sendEmailV31($messages);
        }
    }
}
