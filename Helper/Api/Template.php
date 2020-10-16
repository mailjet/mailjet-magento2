<?php

namespace Mailjet\Mailjet\Helper\Api;

use \Mailjet\Mailjet\Helper\MailjetAPI;

trait Template
{
    abstract public function getResponce();
    abstract public function getError();
    abstract protected function setResponce($responce);
    abstract protected function setError($error);
    abstract protected function getClient();

    public function createTemplate($data)
    {
        $body = [
            MailjetAPI::BODY => [
                MailjetAPI::AUTHOR                          => $data['author'],
                MailjetAPI::CATEGORIES                      => $data['categories'],
                MailjetAPI::COPYRIGHT                       => $data['copyright'],
                MailjetAPI::DESCRIPTION                     => $data['description'],
                MailjetAPI::EDIT_MODE                       => $data['edit_mode'],
                MailjetAPI::IS_STARRED                      => $data['is_starred'],
                MailjetAPI::IS_TEXT_PART_GENERATION_ENABLED => $data['is_text_part_generation_enabled'],
                MailjetAPI::LOCALE                          => $data['locale'],
                MailjetAPI::NAME                            => $data['name'],
                MailjetAPI::OWNER_TYPE                      => $data['owner_type'],
                MailjetAPI::PRESETS                         => $data['presets'],
                MailjetAPI::PURPOSES                        => $data['purposes'],
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$Template, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function getTemplateContent($templateId)
    {
        $data = [
            MailjetAPI::ID => (int)$templateId
        ];

        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$TemplateDetailcontent, $data, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function addTemplateContent($templateId, $data)
    {
        $body = [
            MailjetAPI::ID => (int)$templateId,
            MailjetAPI::BODY => [
                MailjetAPI::HEADERS      => json_encode($data['headers']),
                MailjetAPI::HTML_PART    => $data['html'],
                MailjetAPI::MJML_CONTENT => $data['mjml_content'],
                MailjetAPI::TEXT_PART    => $data['text'],
            ]
        ];

        $this->setResponce($this->getClient()->post(\Mailjet\Resources::$TemplateDetailcontent, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function updateTemplateContent($templateId, $data)
    {
        $body = [
            MailjetAPI::ID => (int)$templateId,
            MailjetAPI::BODY => [
                MailjetAPI::HEADERS      => json_encode($data['headers']),
                MailjetAPI::HTML_PART    => $data['html'],
                MailjetAPI::MJML_CONTENT => $data['mjml_content'],
                MailjetAPI::TEXT_PART    => $data['text'],
            ]
        ];

        $this->setResponce($this->getClient()->put(\Mailjet\Resources::$TemplateDetailcontent, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function getTemplate($templateId)
    {
        $body = [
            MailjetAPI::ID => (int)$templateId,
        ];

        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Template, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function getTemplates($offset, $purpose)
    {
        $body = [
            MailjetAPI::FILTERS => [
                MailjetAPI::PURPOSES => $purpose,
                MailjetAPI::OFFSET   => (int)$offset,
                MailjetAPI::LIMIT    => (int)MailjetAPI::TEMPLATE_RESULT_LIMIT,
            ]
        ];

        $this->setResponce($this->getClient()->get(\Mailjet\Resources::$Template, $body, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }

    public function deleteTemplate($id)
    {
        $data = [
            MailjetAPI::ID => $id
        ];

        $this->setResponce($this->getClient()->delete(\Mailjet\Resources::$Template, $data, ['version' => 'v3']));

        if ($this->getResponce()->success()) {
            return $this->getResponce()->getData();
        } else {
            return [];
        }
    }
}
