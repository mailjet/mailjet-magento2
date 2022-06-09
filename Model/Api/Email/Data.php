<?php

namespace Mailjet\Mailjet\Model\Api\Email;

use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Customer\Model\Customer;
use Magento\Directory\Model\Currency;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;

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
     * @var Configurable
     */
    protected $configurableProductResourceModel;

    /**
     * @var StoreInterface
     */
    protected $store = null;

    /**
     * Data constructor.
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
     * @param Configurable $configurableProductResourceModel
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data                                  $dataHelper,
        \Magento\Sales\Model\Order\Email\Container\OrderIdentity      $orderIdentity,
        \Magento\Sales\Model\Order\Email\Container\ShipmentIdentity   $shipmentIdentity,
        \Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity $creditmemoIdentity,
        \Magento\Sales\Model\Order\Address\Renderer                   $addressRenderer,
        \Magento\Payment\Helper\Data                                  $paymentHelper,
        \Magento\Framework\UrlInterface                               $urlBuilder,
        \Magento\Catalog\Helper\ImageFactory                          $imageHelperFactory,
        \Magento\Sales\Block\Order\TotalsFactory                      $blockTotalsFactory,
        \Magento\Sales\Model\Order\Pdf\Total\Factory                  $pdfTotalFactory,
        \Magento\Sales\Model\Order\Pdf\Config                         $pdfConfig,
        Currency                                                      $currency,
        \Magento\Sales\Block\DataProviders\Email\Shipment\TrackingUrl $trackingUrl,
        \Magento\Catalog\Api\ProductRepositoryInterface               $productRepository,
        Configurable                                                  $configurableProductResourceModel
    ) {
        $this->dataHelper = $dataHelper;
        $this->orderIdentity = $orderIdentity;
        $this->shipmentIdentity = $shipmentIdentity;
        $this->creditmemoIdentity = $creditmemoIdentity;
        $this->addressRenderer = $addressRenderer;
        $this->paymentHelper = $paymentHelper;
        $this->urlBuilder = $urlBuilder;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->blockTotalsFactory = $blockTotalsFactory;
        $this->pdfTotalFactory = $pdfTotalFactory;
        $this->pdfConfig = $pdfConfig;
        $this->currency = $currency;
        $this->trackingUrl = $trackingUrl;
        $this->productRepository = $productRepository;
        $this->configurableProductResourceModel = $configurableProductResourceModel;
    }

    /**
     * Set store
     *
     * @param StoreInterface $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->store = $store;

        $data = [
            'store_name' => $store->getName(),
            'store_code' => $store->getCode(),
            'store_base_url' => $this->dataHelper->getConfigValue(
                Store::XML_PATH_SECURE_BASE_URL,
                $store->getId()
            ),
            'store_sales_email' => $this->dataHelper->getConfigValue(
                'trans_email/ident_' . $this->orderIdentity->getEmailIdentity() . '/email',
                $store->getId()
            ),
            'store_shipment_email' => $this->dataHelper->getConfigValue(
                'trans_email/ident_' . $this->shipmentIdentity->getEmailIdentity() . '/email',
                $store->getId()
            ),
            'store_creditmemo_email' => $this->dataHelper->getConfigValue(
                'trans_email/ident_' . $this->creditmemoIdentity->getEmailIdentity() . '/email',
                $store->getId()
            ),
            'wishlist_url' => $this->urlBuilder->getUrl('wishlist/index/index'),
            'checkout_url' => $this->urlBuilder->getUrl('checkout/index/index'),
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Set order
     *
     * @param Order $order
     * @return $this
     * @throws \Exception
     */
    public function setOrder($order)
    {
        $orderShipping = $order->getIsVirtual() ?
            '' : $this->addressRenderer->format($order->getShippingAddress(), 'html');
        $orderBilling = $this->addressRenderer->format($order->getBillingAddress(), 'html');
        $orderPayment = $this->paymentHelper->getInfoBlockHtml($order->getPayment(), $order->getStoreId());

        $data = [
            'order_increment_id' => $order->getIncrementId(),
            'order_created_date' => $order->getCreatedAt(),
            'order_coupon_code' => $order->getCouponCode(),
            'order_shipping_address_html' => $orderShipping,
            'order_billing_address_html' => $orderBilling,
            'order_payment_html' => $orderPayment,
            'order_billing_description' => $order->getShippingDescription(),
            'order_currency_code' => $order->getOrderCurrencyCode(),
            'customer_email' => $order->getCustomerEmail(),
            'customer_prefix' => $order->getCustomerPrefix(),
            'customer_sufix' => $order->getCustomerSuffix(),
            'customer_firstname' => $order->getCustomerFirstname(),
            'customer_middlename' => $order->getCustomerMiddlename(),
            'customer_Lastname' => $order->getCustomerLastname(),
            'customer_gender' => $order->getCustomerGender(),
            'customer_is_guest' => $order->getCustomerIsGuest(),
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Set shipment
     *
     * @param Shipment $shipment
     * @return $this
     */
    public function setShipment($shipment)
    {
        $data = [
            'shipment_id' => $shipment->getIncrementId(),
            'shipment_customer_note_notify' => $shipment->getCustomerNoteNotify() ?
                $shipment->getCustomerNoteNotify() : '',
            'shipment_customer_note' => (int)$shipment->getCustomerNote(),
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $data = [
            'customer_email' => $customer->getEmail(),
            'customer_prefix' => $customer->getPrefix(),
            'customer_sufix' => $customer->getSuffix(),
            'customer_firstname' => $customer->getFirstname(),
            'customer_middlename' => $customer->getMiddlename(),
            'customer_Lastname' => $customer->getLastname(),
            'customer_gender' => $customer->getGender(),
            'customer_is_guest' => false
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Set shipment tracking
     *
     * @param Order $order
     * @param Shipment $shipment
     * @return $this
     */
    public function setShipmentTracking($order, $shipment)
    {
        $shipmentTrackings = [];
        $trackings = $order->getTracksCollection($shipment->getId());

        foreach ($trackings as $tracking) {
            $shipmentTrackings[] = [
                'title' => $tracking->getTitle(),
                'tracking_url' => $this->trackingUrl->getUrl($tracking),
                'tracking_number' => $tracking->getNumber(),
            ];
        }

        $this->addData(['shipment_trackings' => $shipmentTrackings,
            'has_shipment_trackings' => (bool)$shipmentTrackings]);
        return $this;
    }

    /**
     * Set credit memo
     *
     * @param Creditmemo $creditMemo
     * @return $this
     */
    public function setCreditMemo($creditMemo)
    {
        $data = [
            'creditmemo_increment_id' => $creditMemo->getIncrementId()
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Get params
     *
     * @return mixed
     */
    public function getParams()
    {
        $data = $this->convertToArray();
        $data = $this->cleanVars($data);
        $this->unsetData();

        return $data;
    }

    /**
     * Set Items
     *
     * @param []|string $items
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setItems($items)
    {
        $productInfo = [];
        $currency = $this->dataHelper->getConfigValue(Currency::XML_PATH_CURRENCY_DEFAULT, $this->store->getId());
        $currencySymbol = $this->currency->load($currency)->getCurrencySymbol();

        foreach ($items as $item) {
            if (!($item instanceof Order\Item || $item instanceof Item) && $item->getOrderItem()->getParentItem()) {
                continue;
            }
            if ($item instanceof Item && $item->getParentItem()) {
                continue;
            } // Magento\Quote\Model\Quote\Item

            $productData = [];

            if ($item->getProduct()) {
                $productData = $this->getProductImages($item->getProduct());
            } else {
                $product = $this->productRepository->getById($item->getProductId());
                if ($product->getId()) {
                    $productData = $this->getProductImages($product);
                }
            }
// phpcs:disable Generic.Files.LineLength.TooLong
            $productOptions = $item->getProductOptions();

            $productData['product_options'] = !empty($productOptions['attributes_info']) ?
                $productOptions['attributes_info'] : []; // all
            $productData['sku'] = $item->getSku(); // all
            $productData['name'] = $item->getName(); // all
            $productData['qty_ordered'] = (int)$item->getQtyOrdered(); // Magento\Sales\Model\Order\Item
            $productData['price'] = $this->formatPrice($item->getPrice(), $currencySymbol); // Magento\Sales\Model\Order\Item, Magento\Quote\Model\Quote\Item
            $productData['row_total'] = $this->formatPrice($item->getRowTotal(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['discount_amount'] = $this->formatPrice($item->getDiscountAmount(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['qty'] = (int)$item->getQty(); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item, Magento\Quote\Model\Quote\Item
            $productData['tax_amount'] = $this->formatPrice($item->getTaxAmount(), $currencySymbol); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item
            $productData['row_total_incl_tax'] = $this->formatPrice(
                $item->getRowTotal(0) + $item->getTaxAmount(0) - $item->getDiscountAmount(0),
                $currencySymbol
            ); // Magento\Sales\Model\Order\Creditmemo\Item, Magento\Sales\Model\Order\Shipment\Item

            $productInfo[] = $productData;
        }

        $this->addData(['products' => $productInfo]);
        return $this;
    }

    /**
     * Set product data
     *
     * @param array $productsIds
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setProductData($productsIds)
    {
        $productInfo = [];
        $maxDiscount = 0;
        $currency = $this->dataHelper->getConfigValue(Currency::XML_PATH_CURRENCY_DEFAULT, $this->store->getId());
        $currencySymbol = $this->currency->load($currency)->getCurrencySymbol();

        foreach ($productsIds as $productsId) {
            $product = $this->productRepository->getById($productsId);
            if ($product->getId()) {
                $price = $this->currency->convert($product->getPrice(), $currency);
                $specialPrice = $this->currency->convert($product->getSpecialPrice(), $currency);
                $discountPercent = $price ? round(100 - ((100 / $price) * $specialPrice)) : 0;

                if ($maxDiscount < $discountPercent) {
                    $maxDiscount = $discountPercent;
                }

                $productData = $this->getProductImages($product);
                $productData['store_id'] = $product->getStoreId();
                $productData['product_type'] = $product->getTypeId();
                $productData['is_virtual'] = $product->getIsVirtual();
                $productData['special_price'] = $this->formatPrice($specialPrice, $currencySymbol);
                $productData['discount_percent'] = $discountPercent;
                $productData['sku'] = $product->getSku();
                $productData['name'] = $product->getName();
                $productData['price'] = $this->formatPrice($price, $currencySymbol);
                $productData['url'] = $this->getProductUrl($product);

                $productInfo[] = $productData;
            }
        }

        $data = [
            'products' => $productInfo,
            'discount_up_to' => $maxDiscount,
        ];

        $this->addData($data);
        return $this;
    }

    /**
     * Set totals
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Creditmemo|\Magento\Sales\Model\Order $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setTotals($object)
    {
        if ($object instanceof Order) {
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

    /**
     *  Get product images
     *
     * @param \Magento\Catalog\Model\Product|\Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     */
    private function getProductImages($product)
    {
        return [
            'swatch_image' => $this->getProductImage($product, 'product_swatch_image'),
            'thumbnail_image' => $this->getProductImage($product, 'product_thumbnail_image'),
            'base_image' => $this->getProductImage($product, 'product_base_image'),
            'small_image' => $this->getProductImage($product, 'product_small_image')
        ];
    }

    /**
     * Sort totals list
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function _sortTotalsList($a, $b)
    {
        if (!isset($a['sort_order']) || !isset($b['sort_order'])) {
            return 0;
        }

        return $a['sort_order'] <=> $b['sort_order'];
    }

    /**
     * Format price
     *
     * @param float|mixed|null $price
     * @param string $currency
     * @return string|void
     */
    private function formatPrice($price, $currency)
    {
        if (isset($price)) {
            return $this->currency->format(
                $price,
                ['currency' => $currency, 'precision' => 2],
                false,
            );
        }
    }

    /**
     * Get product image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $type
     * @param int $width
     * @param int $height
     * @return string
     */
    private function getProductImage($product, $type, int $width = 400, int $height = 400): string
    {
        try {
            $url = '';
            $image = $product->getImage();

            if ($image && $image != 'no_selection') {
                $imageHelper = $this->imageHelperFactory->create()->init(
                    $product,
                    $type,
                    ['width' => $width, 'height' => $height, 'keep_aspect_ratio' => true]
                )->setImageFile($image);
                $imageHelper->getResizedImageInfo();
                $url = $imageHelper->getUrl();
            }
            return $url;
        } catch (\Magento\Catalog\Model\Product\Image\NotLoadInfoImageException $e) {
            return '';
        }
    }

    /**
     * Get product url
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
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

    /**
     * Clean vars
     *
     * @param array $vars
     * @return mixed
     */
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
// phpcs:enable Generic.Files.LineLength.TooLong
