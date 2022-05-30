define(
    [
    'jquery'
    ], function ($) {
        'use strict';
        return function (payloadExtender) {
            payloadExtender.addressInformation['extension_attributes'] = {
                newsletter_subscribe: Boolean($('[name="newsletter-subscribe"]').attr('checked'))
            };

            return payloadExtender;
        };
    }
);