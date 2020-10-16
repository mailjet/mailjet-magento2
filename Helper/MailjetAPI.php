<?php

namespace Mailjet\Mailjet\Helper;

use \Mailjet\Mailjet\Helper\Api\Webhook;
use \Mailjet\Mailjet\Helper\Api\Contact;
use \Mailjet\Mailjet\Helper\Api\ContactList;
use \Mailjet\Mailjet\Helper\Api\Message;
use \Mailjet\Mailjet\Helper\Api\ContactProperty;
use \Mailjet\Mailjet\Helper\Api\Segment;
use \Mailjet\Mailjet\Helper\Api\ApiToken;
use \Mailjet\Mailjet\Helper\Api\Template;
use \Mailjet\Mailjet\Helper\Api\Sender;
use \Mailjet\Mailjet\Helper\Api\Email;

class MailjetAPI
{
    use Webhook, Contact, ContactList, Message, ContactProperty, Segment, ApiToken, Template, Sender, Email;

    const TO = 'To';
    const CC = 'Cc';
    const ID = 'ID';
    const BCC = 'Bcc';
    const URL = 'Url';
    const VARS = 'Vars';
    const DATA = 'Data';
    const BODY = 'body';
    const NAME = 'Name';
    const FROM = 'From';
    const LIMIT = 'Limit';
    const TOKEN = 'Token';
    const VALUE = 'Value';
    const EMAIL = 'Email';
    const OFFSET = 'Offset';
    const SENDER = 'Sender';
    const LOCALE = 'Locale';
    const AUTHOR = 'Author';
    const ACTION = 'Action';
    const STATUS = 'Status';
    const FILTERS = 'Filters';
    const CONTENT = 'Content';
    const SUBJECT = 'Subject';
    const HEADERS = 'Headers';
    const MJ_PRIO = 'Mj-prio';
    const PRESETS = 'Presets';
    const GLOBALS = 'Globals';
    const URL_TAGS = 'URLTags';
    const MESSAGES = 'Messages';
    const PRIORITY = 'Priority';
    const FILENAME = 'Filename';
    const PURPOSES = 'Purposes';
    const CONTACTS = 'Contacts';
    const REPLY_TO = 'Reply-to';
    const TEXTPART = 'TextPart';
    const HTMLPART = 'HTMLPart';
    const CUSTOM_ID = 'CustomID';
    const FROM_NAME = 'FromName';
    const EDIT_MODE = 'EditMode';
    const DATA_TYPE = 'Datatype';
    const IS_BACKUP = 'IsBackup';
    const VALID_FOR = 'ValidFor';
    const IS_ACTIVE = 'IsActive';
    const TEXT_PART = 'Text-Part';
    const HTML_PART = 'Html-part';
    const VARIABLES = 'Variables';
    const COPYRIGHT = 'Copyright';
    const CONTENT_ID = 'ContentID';
    const FROM_EMAIL = 'FromEmail';
    const OWNER_TYPE = 'OwnerType';
    const IS_STARRED = 'IsStarred';
    const TOKEN_TYPE = 'TokenType';
    const NAME_SPACE = 'NameSpace';
    const CONTACT_ID = 'ContactID';
    const MESSAGE_ID = 'MessageID';
    const EVENT_TYPE = 'EventType';
    const IS_DELETED = 'IsDeleted';
    const RECIPIENTS = 'Recipients';
    const CATEGORIES = 'Categories';
    const API_KEY_ALT = 'APIKeyALT';
    const PROPERTIES = 'Properties';
    const EXPRESSION = 'Expression';
    const TEMPLATE_ID = 'TemplateID';
    const SENDER_NAME = 'SenderName';
    const TRACK_OPENS = 'TrackOpens';
    const ATTACHMENTS = 'Attachments';
    const MJ_CAMPAIGN = 'Mj-campaign';
    const DESCRIPTION = 'Description';
    const MJ_CUSTOMID = 'Mj-CustomID';
    const SANDBOX_MODE = 'SandboxMode';
    const TRACK_CLICKS = 'TrackClicks';
    const SENDER_EMAIL = 'SenderEmail';
    const MJML_CONTENT = 'MJMLContent';
    const CONTENT_TYPE = 'Content-type';
    const MJ_TRACKOPEN = 'Mj-trackopen';
    const EVENT_PAYLOAD = 'EventPayload';
    const BASE64_CONTENT = 'Base64Content';
    const MJ_TEMPLATE_ID = 'Mj-TemplateID';
    const ALLOWED_ACCESS = 'AllowedAccess';
    const BASE_64_CONTENT = 'Base64Content';
    const CUSTOM_CAMPAIGN = 'CustomCampaign';
    const MJ_EVENT_PAYLOAD = 'Mj-EventPayload';
    const SUBSCRIBER_COUNT = 'SubscriberCount';
    const TEMPLATE_LANGUAGE = 'TemplateLanguage';
    const INLINE_ATTACHMENTS = 'Inline_attachments';
    const INLINED_ATTACHMENTS = 'InlinedAttachments';
    const MONITORING_CATEGORY = 'MonitoringCategory';
    const DEDUPLICATE_CAMPAIGN = 'DeduplicateCampaign';
    const MJ_TEMPLATE_LANGUAGE = 'Mj-TemplateLanguage';
    const ADVANCE_ERROR_HANDLING = 'AdvanceErrorHandling';
    const TEMPLATE_ERROR_DELIVER = 'TemplateErrorDeliver';
    const MJ_MONITORING_CATEGORY = 'Mj-MonitoringCategory';
    const MJ_DEDUPLICATE_CAMPAIGN = 'Mj-deduplicatecampaign';
    const TEMPLATE_ERROR_REPORTING = 'TemplateErrorReporting';
    const MJ_TEMPLATE_ERROR_DELIVER = 'Mj-TemplateErrorDeliver';
    const MJ_TEMPLATE_ERROR_REPORTING = 'Mj-TemplateErrorReporting';
    const IS_TEXT_PART_GENERATION_ENABLED = 'IsTextPartGenerationEnabled';

    /**
     * @var String
     * @example | subscribe | subscribe_only_new | delete | unsubscribe
     */
    const CONTACT_ACTIONS = [
        'subscribe'          => 'addforce',
        'subscribe_only_new' => 'addnoforce',
        'delete'             => 'remove',
        'unsubscribe'        => 'unsub',
    ];

    /**
     * @var String
     * @example | open | click | bounce | spam | blocked | unsub | sent
     */
    const WEBHOOK_EVENTS = [
        'open'         => 'open',
        'click'        => 'click',
        'bounce'       => 'bounce',
        'spam'         => 'spam',
        'blocked'      => 'blocked',
        'unsub'        => 'unsub',
        'sent'         => 'sent'
    ];

    /**
     * @var String
     * @example | dead | alive
     */
    const WEBHOOK_STATUS = [
        'dead'        => 'dead',
        'alive'       => 'alive',
    ];

    /**
     * @var String
     * @example | string | integer | float | boolean | datetime |
     */
    const PROPERTY_DATA_TYPE = [
        'string'        => 'str',
        'integer'       => 'int',
        'float'         => 'float',
        'boolean'       => 'bool',
        'datetime'      => 'datetime',
    ];

    /**
     * @var String
     * @example | static | historic
     */
    const PROPERTY_NAME_SPACE = [
        'static'        => 'static',
        'historic'      => 'historic'
    ];

    /**
     * @var String
     * @example | drag_and_drop_builder | html_builder | saved_section | mjml
     */
    const TEMPLATE_EDIT_MODE = [
        'drag_and_drop_builder' => 1,
        'html_builder'          => 2,
        'saved_section'         => 3,
        'mjml'                  => 4,
    ];

    /**
     * @var String
     * @example | marketing | transactional | automation
     */
    const TEMPLATE_PURPOSE = [
        'marketing'     => 'marketing',
        'transactional' => 'transactional',
        'automation'    => 'automation'
    ];

    /**
     * @var String
     * @example | apikey | user | global
     */
    const TEMPLATE_OWNER_TYPE = [
        'apikey' => 'apikey',
        'user'   => 'user',
        'global' => 'global'
    ];

    const TEMPLATE_RESULT_LIMIT = 100;

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

    public function setCredentials($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new \Mailjet\Client($apiKey, $secretKey);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function validateCredentials()
    {
        $this->responce = $this->client->get(\Mailjet\Resources::$Myprofile, [], ['version' => 'v3']);

        return $this->responce->success();
    }

    public function getResponce()
    {
        return $this->responce;
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setResponce($responce)
    {
        $this->responce = $responce;

        if (!$this->responce->success()) {
            $this->setError($this->responce->getBody());
        }
    }

    protected function setError($error)
    {
        $this->error = $error;
    }

    protected function getClient()
    {
        return $this->client;
    }
}
