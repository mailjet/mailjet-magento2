<?php

namespace Mailjet\Mailjet\Helper;

use Mailjet\Mailjet\Helper\Api\Webhook;
use Mailjet\Mailjet\Helper\Api\Contact;
use Mailjet\Mailjet\Helper\Api\ContactList;
use Mailjet\Mailjet\Helper\Api\Message;
use Mailjet\Mailjet\Helper\Api\ContactProperty;
use Mailjet\Mailjet\Helper\Api\Segment;
use Mailjet\Mailjet\Helper\Api\ApiToken;
use Mailjet\Mailjet\Helper\Api\Template;
use Mailjet\Mailjet\Helper\Api\Sender;
use Mailjet\Mailjet\Helper\Api\Email;

class MailjetAPI
{
    use Webhook;
    use Contact;
    use ContactList;
    use Message;
    use ContactProperty;
    use Segment;
    use ApiToken;
    use Template;
    use Sender;
    use Email;

    public const    TO = 'To';
    public const    CC = 'Cc';
    public const    ID = 'ID';
    public const    BCC = 'Bcc';
    public const    URL = 'Url';
    public const    VARS = 'Vars';
    public const    DATA = 'Data';
    public const    BODY = 'body';
    public const    NAME = 'Name';
    public const    FROM = 'From';
    public const    LIMIT = 'Limit';
    public const    TOKEN = 'Token';
    public const    VALUE = 'Value';
    public const    EMAIL = 'Email';
    public const    OFFSET = 'Offset';
    public const    SENDER = 'Sender';
    public const    LOCALE = 'Locale';
    public const    AUTHOR = 'Author';
    public const    ACTION = 'Action';
    public const    STATUS = 'Status';
    public const    FILTERS = 'Filters';
    public const    CONTENT = 'Content';
    public const    SUBJECT = 'Subject';
    public const    HEADERS = 'Headers';
    public const    MJ_PRIO = 'Mj-prio';
    public const    PRESETS = 'Presets';
    public const    GLOBALS = 'Globals';
    public const    URL_TAGS = 'URLTags';
    public const    MESSAGES = 'Messages';
    public const    PRIORITY = 'Priority';
    public const    FILENAME = 'Filename';
    public const    PURPOSES = 'Purposes';
    public const    CONTACTS = 'Contacts';
    public const    REPLY_TO = 'Reply-to';
    public const    TEXTPART = 'TextPart';
    public const    HTMLPART = 'HTMLPart';
    public const    CUSTOM_ID = 'CustomID';
    public const    FROM_NAME = 'FromName';
    public const    EDIT_MODE = 'EditMode';
    public const  DATA_TYPE = 'Datatype';
    public const  IS_BACKUP = 'IsBackup';
    public const  VALID_FOR = 'ValidFor';
    public const  IS_ACTIVE = 'IsActive';
    public const  TEXT_PART = 'Text-Part';
    public const  HTML_PART = 'Html-part';
    public const  VARIABLES = 'Variables';
    public const  COPYRIGHT = 'Copyright';
    public const  CONTENT_ID = 'ContentID';
    public const  FROM_EMAIL = 'FromEmail';
    public const  OWNER_TYPE = 'OwnerType';
    public const  IS_STARRED = 'IsStarred';
    public const  TOKEN_TYPE = 'TokenType';
    public const  NAME_SPACE = 'NameSpace';
    public const  CONTACT_ID = 'ContactID';
    public const  MESSAGE_ID = 'MessageID';
    public const  EVENT_TYPE = 'EventType';
    public const  IS_DELETED = 'IsDeleted';
    public const  RECIPIENTS = 'Recipients';
    public const  CATEGORIES = 'Categories';
    public const  API_KEY_ALT = 'APIKeyALT';
    public const  PROPERTIES = 'Properties';
    public const  EXPRESSION = 'Expression';
    public const  TEMPLATE_ID = 'TemplateID';
    public const  SENDER_NAME = 'SenderName';
    public const  TRACK_OPENS = 'TrackOpens';
    public const  ATTACHMENTS = 'Attachments';
    public const  MJ_CAMPAIGN = 'Mj-campaign';
    public const  DESCRIPTION = 'Description';
    public const  MJ_CUSTOMID = 'Mj-CustomID';
    public const  SANDBOX_MODE = 'SandboxMode';
    public const  TRACK_CLICKS = 'TrackClicks';
    public const  SENDER_EMAIL = 'SenderEmail';
    public const  MJML_CONTENT = 'MJMLContent';
    public const  CONTENT_TYPE = 'Content-type';
    public const  MJ_TRACKOPEN = 'Mj-trackopen';
    public const  EVENT_PAYLOAD = 'EventPayload';
    public const  BASE64_CONTENT = 'Base64Content';
    public const  MJ_TEMPLATE_ID = 'Mj-TemplateID';
    public const  ALLOWED_ACCESS = 'AllowedAccess';
    public const  BASE_64_CONTENT = 'Base64Content';
    public const  CUSTOM_CAMPAIGN = 'CustomCampaign';
    public const  MJ_EVENT_PAYLOAD = 'Mj-EventPayload';
    public const  SUBSCRIBER_COUNT = 'SubscriberCount';
    public const  TEMPLATE_LANGUAGE = 'TemplateLanguage';
    public const  INLINE_ATTACHMENTS = 'Inline_attachments';
    public const  INLINED_ATTACHMENTS = 'InlinedAttachments';
    public const  MONITORING_CATEGORY = 'MonitoringCategory';
    public const  DEDUPLICATE_CAMPAIGN = 'DeduplicateCampaign';
    public const  MJ_TEMPLATE_LANGUAGE = 'Mj-TemplateLanguage';
    public const  ADVANCE_ERROR_HANDLING = 'AdvanceErrorHandling';
    public const  TEMPLATE_ERROR_DELIVER = 'TemplateErrorDeliver';
    public const  MJ_MONITORING_CATEGORY = 'Mj-MonitoringCategory';
    public const  MJ_DEDUPLICATE_CAMPAIGN = 'Mj-deduplicatecampaign';
    public const  TEMPLATE_ERROR_REPORTING = 'TemplateErrorReporting';
    public const  MJ_TEMPLATE_ERROR_DELIVER = 'Mj-TemplateErrorDeliver';
    public const  MJ_TEMPLATE_ERROR_REPORTING = 'Mj-TemplateErrorReporting';
    public const  IS_TEXT_PART_GENERATION_ENABLED = 'IsTextPartGenerationEnabled';

    /**
     * @var     String
     * @example | subscribe | subscribe_only_new | delete | unsubscribe
     */
    public const  CONTACT_ACTIONS = [
        'subscribe'          => 'addforce',
        'subscribe_only_new' => 'addnoforce',
        'delete'             => 'remove',
        'unsubscribe'        => 'unsub',
    ];

    /**
     * @var     String
     * @example | open | click | bounce | spam | blocked | unsub | sent
     */
    public const  WEBHOOK_EVENTS = [
        'open'         => 'open',
        'click'        => 'click',
        'bounce'       => 'bounce',
        'spam'         => 'spam',
        'blocked'      => 'blocked',
        'unsub'        => 'unsub',
        'sent'         => 'sent'
    ];

    /**
     * @var     String
     * @example | dead | alive
     */
    public const  WEBHOOK_STATUS = [
        'dead'        => 'dead',
        'alive'       => 'alive',
    ];

    /**
     * @var     String
     * @example | string | integer | float | boolean | datetime |
     */
    public const  PROPERTY_DATA_TYPE = [
        'string'        => 'str',
        'integer'       => 'int',
        'float'         => 'float',
        'boolean'       => 'bool',
        'datetime'      => 'datetime',
    ];

    /**
     * @var     String
     * @example | static | historic
     */
    public const  PROPERTY_NAME_SPACE = [
        'static'        => 'static',
        'historic'      => 'historic'
    ];

    /**
     * @var     String
     * @example | drag_and_drop_builder | html_builder | saved_section | mjml
     */
    public const  TEMPLATE_EDIT_MODE = [
        'drag_and_drop_builder' => 1,
        'html_builder'          => 2,
        'saved_section'         => 3,
        'mjml'                  => 4,
    ];

    /**
     * @var     String
     * @example | marketing | transactional | automation
     */
    public const  TEMPLATE_PURPOSE = [
        'marketing'     => 'marketing',
        'transactional' => 'transactional',
        'automation'    => 'automation'
    ];

    /**
     * @var     String
     * @example | apikey | user | global
     */
    public const  TEMPLATE_OWNER_TYPE = [
        'apikey' => 'apikey',
        'user'   => 'user',
        'global' => 'global'
    ];

    public const  TEMPLATE_RESULT_LIMIT = 100;

    /**
     * @var \Mailjet\Client
     */
    private $client = null;

    /**
     * @var array
     */
    private $responce = [];

    /**
     * @var array
     */
    private $error = [];

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Set credentials
     *
     * @param string $apiKey
     * @param string $secretKey
     * @return void
     */
    public function setCredentials($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new \Mailjet\Client($apiKey, $secretKey);
    }

    /**
     * Get api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Validate credentials
     *
     * @return mixed
     */
    public function validateCredentials()
    {
        $this->responce = $this->client->get(\Mailjet\Resources::$Myprofile, [], ['version' => 'v3']);

        return $this->responce->success();
    }

    /**
     * Get responce
     *
     * @return array
     */
    public function getResponce()
    {
        return $this->responce;
    }

    /**
     * Get error
     *
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set response
     *
     * @param array $responce
     * @return void
     */
    protected function setResponce($responce)
    {
        $this->responce = $responce;

        if (!$this->responce->success()) {
            $this->setError($this->responce->getBody());
        }
    }

    /**
     * Set error
     *
     * @param array $error
     * @return void
     */
    protected function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Get client
     *
     * @return \Mailjet\Client|null
     */
    protected function getClient()
    {
        return $this->client;
    }
}
