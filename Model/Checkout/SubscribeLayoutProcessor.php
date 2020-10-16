<?php

namespace Mailjet\Mailjet\Model\Checkout;

class SubscribeLayoutProcessor
{
    /**
     * @var \Mailjet\Mailjet\Helper\Data
     */
    private $dataHelper;

    /**
     * Subscribe Layout Processor constructor.
     *
     * @param \Mailjet\Mailjet\Helper\Data $dataHelper
     */
    public function __construct(
        \Mailjet\Mailjet\Helper\Data $dataHelper
    ) {
        $this->dataHelper           = $dataHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function process($layout)
    {
        if ($this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
            $layoutSubscribe = [
                'components' => [
                    'checkout' => [
                        'children' => [
                            'steps' => [
                                'children' => [
                                    'billing-step' => [
                                        'children' => [
                                            'payment' => [
                                                'children' => [
                                                    'customer-email' => [
                                                        'config' => [
                                                            'template' => 'Mailjet_Mailjet/form/element/email'
                                                        ],
                                                        'children' => [
                                                            'newsletter-subscribe' => [
                                                                'config' => [
                                                                    'checkoutLabel' => $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_CHECKBOX_TEXT),
                                                                    'template' => 'Mailjet_Mailjet/form/element/newsletter-subscribe'
                                                                ],
                                                                'component' => 'Magento_Ui/js/form/form',
                                                                'displayArea' => 'newsletter-subscribe',
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'shipping-step' => [
                                        'children' => [
                                            'shippingAddress' => [
                                                'children' => [
                                                    'customer-email' => [
                                                        'config' => [
                                                            'template' => 'Mailjet_Mailjet/form/element/email'
                                                        ],
                                                        'children' => [
                                                            'newsletter-subscribe' => [
                                                                'config' => [
                                                                    'checkoutLabel' => $this->dataHelper->getConfigValue(\Mailjet\Mailjet\Helper\Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_CHECKBOX_TEXT),
                                                                    'template' => 'Mailjet_Mailjet/form/element/newsletter-subscribe'
                                                                ],
                                                                'component' => 'Magento_Ui/js/form/form',
                                                                'displayArea' => 'newsletter-subscribe',
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $layout = array_merge_recursive($layout, $layoutSubscribe);
        }

        return $layout;
    }
}
