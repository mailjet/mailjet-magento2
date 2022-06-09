<?php

namespace Mailjet\Mailjet\Helper\Api;

use Mailjet\Mailjet\Helper\MailjetAPI;

trait Email
{
    /**
     * Get Api Key
     *
     * @return mixed
     */
    abstract public function getApiKey();

    /**
     * Get Response
     *
     * @return mixed
     */
    abstract public function getResponce();

    /**
     * Get Error
     *
     * @return mixed
     */
    abstract public function getError();

    /**
     * Set responce
     *
     * @param array $responce
     * @return mixed
     */
    abstract protected function setResponce($responce);

    /**
     * Set error
     *
     * @param array $error
     * @return mixed
     */
    abstract protected function setError($error);

    /**
     * Get client
     *
     * @return mixed
     */
    abstract protected function getClient();

    /**
     * Send email V3
     *
     * @param array $data
     * @param array|null $recipients
     * @param int|string $templateId
     * @param array $vars
     * @param array $files
     * @param array $inline_attachments
     * @return array
     */
    public function sendEmailV3($data, $recipients, $templateId, $vars, $files = [], $inline_attachments = [])
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::MESSAGES => [
                MailjetAPI::FROM_EMAIL => $data['from_email'],
                MailjetAPI::FROM_NAME => $data['from_name'],
                MailjetAPI::SUBJECT => $data['subject'],
                MailjetAPI::SENDER => true,
                MailjetAPI::MJ_TEMPLATE_ID => (int)$templateId,
                MailjetAPI::MJ_TEMPLATE_LANGUAGE => false,
                // MailjetAPI::MJ_TEMPLATE_ERROR_DELIVER => '',
                // MailjetAPI::MJ_TEMPLATE_ERROR_REPORTING => '',
                // MailjetAPI::MJ_PRIO => 0,
                // MailjetAPI::MJ_CAMPAIGN => '',
                // MailjetAPI::MJ_DEDUPLICATE_CAMPAIGN => 0,
                // MailjetAPI::MJ_TRACKOPEN => 0,
                // MailjetAPI::MJ_CUSTOMID => 0,
                // MailjetAPI::MJ_EVENT_PAYLOAD => '',
                // MailjetAPI::MJ_MONITORING_CATEGORY => '',
                MailjetAPI::HEADERS => json_encode(['Content-Type' => 'application/json']),
                MailjetAPI::VARS => json_encode($vars),
                ]
            ]
        ];

        if ($recipients) {
            foreach ($recipients as $recipient) {
                $body[MailjetAPI::BODY]['Messages'][MailjetAPI::RECIPIENTS][] = [
                    MailjetAPI::EMAIL => $recipient['email'],
                    MailjetAPI::NAME  => $recipient['name'],
                ];
            }
        } else {
            $body[MailjetAPI::BODY]['Messages'][MailjetAPI::TO] = $data['to_email'];
            $body[MailjetAPI::BODY][MailjetAPI::CC] = implode(',', $data['cc_emails']);
            $body[MailjetAPI::BODY][MailjetAPI::BCC] = implode(',', $data['bcc_emails']);
        }

        foreach ($files as $file) {
            $body[MailjetAPI::BODY]['Messages'][MailjetAPI::ATTACHMENTS][] = json_encode(
                [
                MailjetAPI::FILENAME      => $file['filename'],
                MailjetAPI::CONTENT_TYPE  => $file['content_type'], // application/pdf
                MailjetAPI::CONTENT       => $file['content'],
                ]
            );
        }

        foreach ($inline_attachments as $inline_attachment) {
            $body[MailjetAPI::BODY]['Messages'][MailjetAPI::INLINE_ATTACHMENTS][] = json_encode(
                [
                MailjetAPI::FILENAME      => $inline_attachment['filename'],
                MailjetAPI::CONTENT_TYPE  => $inline_attachment['content_type'], // application/pdf
                MailjetAPI::CONTENT       => $inline_attachment['content'],
                ]
            );
        }

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Email, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    /**
     * Send email V31
     *
     * @param array $data
     * @return array
     */
    public function sendEmailV31($data)
    {
        $body = [
            MailjetAPI::BODY => [
                // MailjetAPI::SANDBOX_MODE => false,
                // MailjetAPI::ADVANCE_ERROR_HANDLING => false,
                // MailjetAPI::GLOBALS => json_encode(''),
                MailjetAPI::MESSAGES => [
                ]
            ]
        ];

        foreach ($data as $message) {
            $newMessage = [
                MailjetAPI::FROM => [
                    MailjetAPI::EMAIL => $message['from_email'],
                    MailjetAPI::NAME  => $message['from_name']
                ],
                // MailjetAPI::HTML_PART => '',
                // MailjetAPI::SUBJECT => '',
                // MailjetAPI::TEXTPART => 'test',
                // MailjetAPI::HTML_PART => '<p>123</p>',
                MailjetAPI::TEMPLATE_ID => (int)$message['template_id'],
                MailjetAPI::TEMPLATE_LANGUAGE => true,
                // MailjetAPI::TEMPLATE_ERROR_DELIVER => '',
                // MailjetAPI::PRIORITY => 1,
                // MailjetAPI::CUSTOM_CAMPAIGN => '',
                // MailjetAPI::DEDUPLICATE_CAMPAIGN => '',
                // MailjetAPI::TRACK_OPENS => '',
                // MailjetAPI::TRACK_CLICKS => '',
                // MailjetAPI::CUSTOM_ID => '',
                // MailjetAPI::MONITORING_CATEGORY => '',
                // MailjetAPI::URL_TAGS => '',
                // MailjetAPI::HEADERS => json_decode(json_encode('')),
                MailjetAPI::VARIABLES => json_decode(json_encode($message['vars'])),
            ];

            if (!empty($message['reply_to'])) {
                $newMessage[MailjetAPI::REPLY_TO] = json_decode(
                    json_encode(
                        [
                        MailjetAPI::EMAIL => $message['reply_to']['email'],
                        MailjetAPI::NAME  => $message['reply_to']['name']
                        ]
                    )
                );
            }

            if (!empty($message['error_receiver'])) {
                $newMessage[MailjetAPI::TEMPLATE_LANGUAGE] = true;
                $newMessage[MailjetAPI::TEMPLATE_ERROR_REPORTING] = json_decode(
                    json_encode(
                        [
                        MailjetAPI::EMAIL => $message['error_receiver']['email'],
                        MailjetAPI::NAME  => $message['error_receiver']['name']
                        ]
                    )
                );
            }

            if (!empty($message['sender'])) {
                $newMessage[MailjetAPI::SENDER] = json_decode(
                    json_encode(
                        [
                        MailjetAPI::EMAIL => $message['sender']['email'],
                        MailjetAPI::NAME  => $message['sender']['name']
                        ]
                    )
                );
            }

            if (!empty($message['to'])) {
                foreach ($message['to'] as $receiver) {
                    $newMessage[MailjetAPI::TO][] = [
                        MailjetAPI::EMAIL => $receiver['email'],
                        MailjetAPI::NAME  => $receiver['name']
                    ];
                }
            }

            if (!empty($message['cc'])) {
                foreach ($message['cc'] as $ccReceiver) {
                    $newMessage[MailjetAPI::CC][] = json_decode(
                        json_encode(
                            [
                            MailjetAPI::EMAIL => $ccReceiver['email'],
                            MailjetAPI::NAME  => $ccReceiver['name']
                            ]
                        )
                    );
                }
            }

            if (!empty($message['bcc'])) {
                foreach ($message['bcc'] as $bccReceiver) {
                    $newMessage[MailjetAPI::BCC][] = json_decode(
                        json_encode(
                            [
                            MailjetAPI::EMAIL => $bccReceiver['email'],
                            MailjetAPI::NAME  => $bccReceiver['name']
                            ]
                        )
                    );
                }
            }

            if (!empty($message['files'])) {
                foreach ($message['files'] as $file) {
                    $newMessage[MailjetAPI::ATTACHMENTS][] = json_decode(
                        json_encode(
                            [
                            MailjetAPI::FILENAME         => $file['filename'],
                            MailjetAPI::CONTENT_TYPE     => $file['content_type'],
                            MailjetAPI::BASE_64_CONTENT  => $file['base_64_content']
                            ]
                        )
                    );
                }
            }

            if (!empty($message['inline_attachments'])) {
                foreach ($message['inline_attachments'] as $inline_attachment) {
                    $newMessage[MailjetAPI::ATTACHMENTS][] = json_decode(
                        json_encode(
                            [
                            MailjetAPI::FILENAME         => $inline_attachment['filename'],
                            MailjetAPI::CONTENT_TYPE     => $inline_attachment['content_type'],
                            MailjetAPI::BASE_64_CONTENT  => $inline_attachment['base_64_content']
                            ]
                        )
                    );
                }
            }

            $body[MailjetAPI::BODY][MailjetAPI::MESSAGES][] = $newMessage;
        }

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Email, $body, ['version' => 'v3.1']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
