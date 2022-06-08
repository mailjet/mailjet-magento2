<?php

namespace Mailjet\Mailjet\Model\Api\Email;

class Data extends \Magento\Framework\DataObject
{
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
     * @var \Magento\Sales\Model\Order\Email\Container\OrderIdentity
     */
    protected $addressRenderer;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $imageHelperFactory;

    /**
     * @var \Magento\Sales\Block\Order\TotalsFactory
     */
    protected $blockTotalsFactory;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\Total\Factory
     */
    protected $pdfTotalFactory;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\Config
     */
    protected $pdfConfig;

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

    /**
     * @var \Magento\Sales\Block\DataProviders\Email\Shipment\TrackingUrl
     */
    protected $trackingUrl;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $configurableProductResourceModel;

    /**
     * @var \Magento\Store\Api\Data\StoreInterface
     */
    protected $store = null;

    /**
     *
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     * @param \Magento\Sales\Model\Order\Email\Container\OrderIdentity $orderIdentity
     * @param \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity $shipmentIdentity
     * @param \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $creditmemoIdentity
     * @param \Magento\Sales\Model\Order\Address\Renderer $addressRenderer
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \Magento\Sales\Block\Order\TotalsFactory $blockTotalsFactory
     * @param \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory
     * @param \Magento\Sales\Model\Order\Pdf\Config $pdfConfig
     * @param \Magento\Directory\Model\Currency $currency
     * @param \Magento\Sales\Block\DataProviders\Email\Shipment\TrackingUrl $trackingUrl
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductResourceModel
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper,
        \Magento\Sales\Model\Order\Email\Container\OrderIdentity $orderIdentity,
        \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity $shipmentIdentity,
        \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $creditmemoIdentity,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Sales\Block\Order\TotalsFactory $blockTotalsFactory,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\Config $pdfConfig,
        \Magento\Directory\Model\Currency $currency,
        \Mailjet\Mailjet\Sales\Block\DataProviders\Email\Shipment\TrackingUrl $trackingUrl,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableProductResourceModel
    ) {
        $this->dataHelper                        = $dataHelper;
        $this->orderIdentity                     = $orderIdentity;
        $this->shipmentIdentity                  = $shipmentIdentity;
        $this->creditmemoIdentity                = $creditmemoIdentity;
        $this->addressRenderer                   = $addressRenderer;
        $this->paymentHelper                     = $paymentHelper;
        $this->urlBuilder                        = $urlBuilder;
        $this->imageHelperFactory                = $imageHelperFactory;
        $this->blockTotalsFactory                = $blockTotalsFactory;
        $this->pdfTotalFactory                   = $pdfTotalFactory;
        $this->pdfConfig                         = $pdfConfig;
        $this->currency                          = $currency;
        $this->trackingUrl                       = $trackingUrl;
        $this->productRepository                 = $productRepository;
        $this->configurableProductResourceModel  = $configurableProductResourceModel;
    }

    public function setStore($store)
    {
        $this->store = $store;

        $data = [
            'store_name'             => $store->getName(),
            'store_code'             => $store->getCode(),
            'store_base_url'         => $this->dataHelper->getConfigValue(\Magento\Store\Model\Store::XML_PATH_SECURE_BASE_URL, $store->getId()),
            'store_sales_email'      => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email', $store->getId()),
            'store_shipment_email'   => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/email', $store->getId()),
            'store_creditmemo_email' => $this->dataHelper->getConfigValue('trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/email', $store->getId()),
            'wishlist_url'           => $this->urlBuilder->getUrl('wishlist/index/index'),
            'checkout_url'           => $this->urlBuilder->getUrl('checkout/index/index'),
        ];

        $this->addData($data);
        return $this;
    }

    public function setOrder($order)
    {
        $orderShipping = $order->getIsVirtual() ? '' : $this->addressRenderer->format($order->getShippingAddress(), 'html');
        $orderBilling  = $this->addressRenderer->format($order->getBillingAddress(), 'html');
        $orderPayment  = $this->paymentHelper->getInfoBlockHtml($order->getPayment(), $order->getStoreId());

        $data = [
            'order_increment_id'          => $order->getIncrementId(),
            'order_created_date'          => $order->getCreatedAt(),
            'order_coupon_code'           => $order->getCouponCode(),
            'order_shipping_address_html' => $orderShipping,
            'order_billing_address_html'  => $orderBilling,
            'order_payment_html'          => $orderPayment,
            'order_billing_description'   => $order->getShippingDescription(),
            'order_currency_code'         => $order->getOrderCurrencyCode(),
            'customer_email'              => $order->getCustomerEmail(),
            'customer_prefix'             => $order->getCustomerPrefix(),
            'customer_sufix'              => $order->getCustomerSuffix(),
            'customer_firstname'          => $order->getCustomerFirstname(),
            'customer_middlename'         => $order->getCustomerMiddlename(),
            'customer_Lastname'           => $order->getCustomerLastname(),
            'customer_gender'             => $order->getCustomerGender(),
            'customer_is_guest'           => $order->getCustomerIsGuest(),
        ];

        $this->addData($data);
        return $this;
    }

    public function setShipment($shipment)
    {
        $data = [
            'shipment_id'                   => $shipment->getIncrementId(),
            'shipment_customer_note_notify' => $shipment->getCustomerNoteNotify() ? $shipment->getCustomerNoteNotify() : '',
            'shipment_customer_note'        => (int)$shipment->getCustomerNote(),
        ];

        $this->addData($data);
        return $this;
    }

    public function setCustomer($customer)
    {
        $data = [
            'customer_email'      => $customer->getEmail(),
            'customer_prefix'     => $customer->getPrefix(),
            'customer_sufix'      => $customer->getSuffix(),
            'customer_firstname'  => $customer->getFirstname(),
            'customer_middlename' => $customer->getMiddlename(),
            'customer_Lastname'   => $customer->getLastname(),
            'customer_gender'     => $customer->getGender(),
            'customer_is_guest'   => false
        ];

        $this->addData($data);
        return $this;
    }

    public function setShipmentTracking($order, $shipment)
    {
        $shipmentTrackings = [];
        $trackings = $order->getTracksCollection($shipment->getId());

        foreach ($trackings as $tracking) {
            $shipmentTrackings[] = [
                'title'           => $tracking->getTitle(),
                'tracking_url'    => $this->trackingUrl->getUrl($tracking),
                'tracking_number' => $tracking->getNumber(),
            ];
        }

        $this->addData(['shipment_trackings' => $shipmentTrackings, 'has_shipment_trackings' => (bool)$shipmentTrackings]);
        return $this;
    }

    public function setCreditMemo($creditMemo)
    {
        $data = [
            'creditmemo_increment_id' => $creditMemo->getIncrementId()
        ];

        $this->addData($data);
        return $this;
    }

    public function getParams()
    {
        $data = $this->convertToArray();
        $data = $this->cleanVars($data);
        $this->unsetData();

        return $data;
    }

    // Magento\Sales\Model\Order\Item
    // Magento\Sales\Model\Order\Creditmemo\Item
    // Magento\Sales\Model\Order\Shipment\Item
    // String
    public function setItems($items)
    {
        $productInfo = [];
        $currency = $this->dataHelper->getConfigValue(\Magento\Directory\Model\Currency::XML_PATH_CURRENCY_DEFAULT, $this->store->getId());
        $currencySymbol = $this->currency->load($currency)->getCurrencySymbol();

        foreach ($items as $item) {
            if (!($item instanceof \Magento\Sales\Model\Order\Item || $item instanceof \Magento\Quote\Model\Quote\Item) && $item->getOrderItem()->getParentItem()) {
                continue;
            }
            if ($item instanceof \Magento\Quote\Model\Quote\Item && $item->getParentItem()) {
                continue;
            } // Magento\Quote\Model\Quote\Item

            $productData = [];

            if ($item->getProduct()) {
                $productData = $this->getProductImages($item->getProduct());
            } else {
                $product = $this->productRepository->getById($item->getProductId());
                $productData = $this->getProductImages($product);
            }

            $productOptions = $item->getProductOptions();

            $productData['product_options']    = !empty($productOptions['attributes_info']) ? $productOptions['attributes_info'] : []; // all
            $productData['sku']                = $item->getSku(); // all
            $productData['name']               = $item->getName(); // all
            $productData['qty_ordered']        = (int)$item->getQtyOrdered(); // Magento\Sales\Model\Order\Item
            $productData['price']              = $this->formatPrice($item->getPrice(), $currencySymbol); // Magento\Sales\Model\Order\Item, Magento\Quote\Model\Quote\Item
            $productData['row_total']          = $this->formatPrice($item->getRowTotal(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['discount_amount']    = $this->formatPrice($item->getDiscountAmount(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['qty']                = (int)$item->getQty(); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item, Magento\Quote\Model\Quote\Item
            $productData['tax_amount']         = $this->formatPrice($item->getTaxAmount(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['row_total_incl_tax'] = $this->formatPrice($item->getRowTotal(0) + $item->getTaxAmount(0) - $item->getDiscountAmount(0), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item

            $productInfo[] = $productData;
        }

        $this->addData(['products' => $productInfo]);
        return $this;
    }

    public function setProductData($productsIds)
    {
        $productInfo = [];
        $maxDiscount = 0;
        $currency = $this->dataHelper->getConfigValue(\Magento\Directory\Model\Currency::XML_PATH_CURRENCY_DEFAULT, $this->store->getId());
        $currencySymbol = $this->currency->load($currency)->getCurrencySymbol();

        foreach ($productsIds as $productsId) {
            $product = $this->productRepository->getById($productsId);
            $price = $this->currency->convert($product->getPrice(), $currency);
            $specialPrice = $this->currency->convert($product->getSpecialPrice(), $currency);
            $discountPercent = $price ? round(100 - ((100 / $price) * $specialPrice)) : 0;

            if ($maxDiscount < $discountPercent) {
                $maxDiscount = $discountPercent;
            }

            $productData = $this->getProductImages($product);
            $productData['store_id']         = $product->getStoreId();
            $productData['product_type']     = $product->getTypeId();
            $productData['is_virtual']       = $product->getIsVirtual();
            $productData['special_price']    = $this->formatPrice($specialPrice, $currencySymbol);
            $productData['discount_percent'] = $discountPercent;
            $productData['sku']              = $product->getSku();
            $productData['name']             = $product->getName();
            $productData['price']            = $this->formatPrice($price, $currencySymbol);
            $productData['url']              = $this->getProductUrl($product);

            $productInfo[] = $productData;
        }

        $data = [
            'products' => $productInfo,
            'discount_up_to' => $maxDiscount,
        ];

        $this->addData($data);
        return $this;
    }

    public function setTotals($object)
    {
        if ($object instanceof \Magento\Sales\Model\Order) {
            $totals = [];
            $totalBlock = $this->blockTotalsFactory->create();
            $totalBlock->setOrder($object);
            $totalBlock->toHtml();
            $currencySymbol = $this->currency->load($object->getOrderCurrencyCode())->getCurrencySymbol();

            foreach ($totalBlock->getTotals() as $total) {
                $totals[] = [
                    'value' => $this->formatPrice($total->getValue(), $currencySymbol),
                    'label' => (string)$total->getLabel(),
                ];
            }

            $this->addData(['totals' => $totals]);
        } else {
            $result = [];
            $totals = $this->pdfConfig->getTotals();
            usort($totals, [$this, '_sortTotalsList']);

            foreach ($totals as $totalInfo) {
                if (!empty($totalInfo['model'])) {
                    $totalModel = $this->pdfTotalFactory->create($totalInfo['model']);
                    $totalModel->setData($totalInfo);
                    $totalModel->setOrder($object->getOrder())->setSource($object);

                    if ($totalModel->canDisplay()) {
                        $data = $totalModel->getTotalsForDisplay();
                        array_push($result, array_pop($data));
                    }
                }
            }

            $this->addData(['totals' => $result]);
        }

        return $this;
    }

    private function getProductImages($product)
    {
        return [
            'swatch_image'    => $this->getProductImage($product, 'product_swatch_image'),
            'thumbnail_image' => $this->getProductImage($product, 'product_thumbnail_image'),
            'base_image'      => $this->getProductImage($product, 'product_base_image'),
            'small_image'     => $this->getProductImage($product, 'product_small_image'),
            'swatch_image'    => $this->getProductImage($product, 'product_swatch_image'),
        ];
    }

    protected function _sortTotalsList($a, $b)
    {
        if (!isset($a['sort_order']) || !isset($b['sort_order'])) {
            return 0;
        }

        return $a['sort_order'] <=> $b['sort_order'];
    }

    private function formatPrice($price, $currency)
    {
        if (isset($price)) {
            return $this->currency->format($price, ['currency' => $currency, 'precision' => 2], false, false);
        }
    }

    private function getProductImage($product, $type, $width = 400, $height = 400)
    {
        try {
            $url = $this->imageHelperFactory->create()->init($product, $type, ['width' => $width, 'height' => $height, 'keep_aspect_ratio' => true]);
            $url->getResizedImageInfo();

            return $url->getUrl();
        } catch (\Magento\Catalog\Model\Product\Image\NotLoadInfoImageException $e) {
            return '';
        }
    }

    private function getProductUrl($product)
    {
        if (!$product->isVisibleInSiteVisibility()) {
            $parentProduct = $this->configurableProductResourceModel->getParentIdsByChild($product->getId());

            if (!empty($parentProduct[0])) {
                return $this->productRepository->getById($parentProduct[0])->getProductUrl();
            }
        }

        return $product->getProductUrl();
    }

    private function cleanVars($vars)
    {
        foreach ($vars as $key => $var) {
            if (is_array($var)) {
                $vars[$key] = $this->cleanVars($var);
            } elseif (!isset($var)) {
                unset($vars[$key]);
            }
        }

        return $vars;
    }
}
