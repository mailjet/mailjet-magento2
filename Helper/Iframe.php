<?php

namespace Mailjet\Mailjet\Helper;

class Iframe
{
    const MAILJET_URL = 'https://app.mailjet.com/';
    const LOCALES = ['fr_FR', 'en_US', 'en_GB', 'en_EU', 'de_DE', 'es_ES', 'it_IT'];
    const DEFAULT_LOCALE = 'en_US';
    const SESSION_EXPIRATION = 3600;

    const PAGES = [
        'campaigns'      => 'campaigns',
        'contacts'       => 'contacts',
        'stats'          => 'stats',
        'reports'        => 'reports',
        'property'       => 'property',
        'contact_filter' => 'contact_filter'
    ];

    const URLS = [
        'campaigns'      => 'campaigns',
        'contacts'       => 'contacts',
        'stats'          => 'stats',
        'template'       => 'template/%d/build',
    ];

    private $tokenAccess             = '';
    private $id                      = '';

    private $segmentation            = false;
    private $personalization         = false;
    private $campaingComparison      = false;
    private $newContactListCreation  = false;
    private $menu                    = false;
    private $showBar                 = true;
    private $logos                   = false;
    private $initialPage             = self::PAGES['stats'];
    private $callback                = '';
    private $locale                  = self::DEFAULT_LOCALE;

    private $width                   = '100%';
    private $height                  = '1500px';

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function setTokenAccess($tokenAccess)
    {
        $this->tokenAccess = $tokenAccess;
        return $this;
    }

    public function turnSegmentation($flag = true)
    {
        $this->segmentation = $flag;
        return $this;
    }

    public function turnPersonalization($flag = true)
    {
        $this->personalization = $flag;
        return $this;
    }

    public function turnCampaignComparison($flag = true)
    {
        $this->campaingComparison = $flag;
        return $this;
    }

    public function turnNewContactListCreation($flag = true)
    {
        $this->newContactListCreation = $flag;
        return $this;
    }

    public function turnMenu($flag = true)
    {
        $this->menu = $flag;
        return $this;
    }

    public function turnBar($flag = true)
    {
        $this->showBar = $flag;
        return $this;
    }

    public function turnMailjetLogos($flag = true)
    {
        $this->logos = $flag;
        return $this;
    }

    public function setInitialPage($page = self::URLS['stats'])
    {
        if (in_array($page, self::URLS)) {
            $this->initialPage = $page;
        } else {
            $this->initialPage = self::URLS['stats'];
        }
        return $this;
    }

    public function setCallback($callback = '', $isEncoded = false)
    {
        if ($isEncoded) {
            $this->callback = $callback;
        } else {
            $this->callback = urldecode($callback);
        }

        return $this;
    }

    public function setLocale($locale = self::DEFAULT_LOCALE)
    {
        if (in_array($locale, self::LOCALES)) {
            $this->locale = $locale;
        }

        return $this;
    }

    public function getHtml()
    {
        if ($this->tokenAccess) {
            $iframeUrl = $this->getIframeUrl();

            $html = <<<HTML
<iframe width="%s" height="%s" frameborder="0" style="border:0" src="%s">
</iframe>
HTML;

            return sprintf($html, $this->width, $this->height, $iframeUrl);
        } else {
            return '';
        }
    }

    private function getIframeUrl()
    {
        $url = self::MAILJET_URL . sprintf($this->initialPage, $this->id);

        $url .= '?t=' . $this->tokenAccess;
        $url .= '&locale=' . $this->locale;

        if ($this->callback !== '') {
            $url .= '&cb=' . $this->callback;
        }

        $featuresDisabled = [];

        if ($this->segmentation) {
            $featuresDisabled[] = 's';
        }

        if ($this->personalization) {
            $featuresDisabled[] = 'p';
        }
        if ($this->campaingComparison) {
            $featuresDisabled[] = 'c';
        }

        if ($this->newContactListCreation) {
            $featuresDisabled[] = 'l';
        }

        if (!empty($featuresDisabled)) {
            $url .= '&f=' . implode('', $featuresDisabled);
        }

        if ($this->menu) {
            $url .= '&show_menu=none';
        }

        if ($this->showBar) {
            $url .= '&show_bar=yes';
        }

        if ($this->logos) {
            $url .= '&mj=hidden';
        }

        return $url;
    }
}
