<?php

namespace Mailjet\Mailjet\Model\Checkout;

use Mailjet\Mailjet\Helper\Data;

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
        Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
    }

    /**
     * Process
     *
     * @param array $layout
     * @return array
     */
    public function process($layout)
    {
        if ($this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_PAGE_SUBSCRIBE)) {
            $checkoutLabel = $this->dataHelper->getConfigValue(Data::CONFIG_PATH_ECOMMERCE_CHECKOUT_CHECKBOX_TEXT);
            $templateMame = 'Mailjet_Mailjet/form/element/newsletter-subscribe';
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
                                                                    'checkoutLabel' => $checkoutLabel,
                                                                    'template' => $templateMame
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
                                                                    'checkoutLabel' => $checkoutLabel,
                                                                    'template' => $templateMame
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
