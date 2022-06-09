<?php

namespace Mailjet\Mailjet\Helper;

class Iframe
{
    public const MAILJET_URL = 'https://app.mailjet.com/';
    public const LOCALES = ['fr_FR', 'en_US', 'en_GB', 'en_EU', 'de_DE', 'es_ES', 'it_IT'];
    public const DEFAULT_LOCALE = 'en_US';
    public const SESSION_EXPIRATION = 3600;

    public const PAGES = [
        'campaigns'      => 'campaigns',
        'contacts'       => 'contacts',
        'stats'          => 'stats',
        'reports'        => 'reports',
        'property'       => 'property',
        'contact_filter' => 'contact_filter'
    ];

    public const URLS = [
        'campaigns'      => 'campaigns',
        'contacts'       => 'contacts',
        'stats'          => 'stats',
        'template'       => 'template/%d/build',
    ];

    /**
     * @var string
     */
    private $tokenAccess             = '';

    /**
     * @var string
     */
    private $id                      = '';

    /**
     * @var bool
     */
    private $segmentation            = false;
    /**
     * @var bool
     */
    private $personalization         = false;
    /**
     * @var bool
     */
    private $campaingComparison      = false;
    /**
     * @var bool
     */
    private $newContactListCreation  = false;
    /**
     * @var bool
     */
    private $menu                    = false;
    /**
     * @var bool
     */
    private $showBar                 = true;
    /**
     * @var bool
     */
    private $logos                   = false;
    /**
     * @var string
     */
    private $initialPage             = self::PAGES['stats'];
    /**
     * @var string
     */
    private $callback                = '';
    /**
     * @var string
     */
    private $locale                  = self::DEFAULT_LOCALE;
    /**
     * @var string
     */
    private $width                   = '100%';
    /**
     * @var string
     */
    private $height                  = '1500px';

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set width
     *
     * @param string $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Set height
     *
     * @param string $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Set token access
     *
     * @param string $tokenAccess
     * @return $this
     */
    public function setTokenAccess($tokenAccess)
    {
        $this->tokenAccess = $tokenAccess;
        return $this;
    }

    /**
     * Turn segmentation
     *
     * @param bool $flag
     * @return $this
     */
    public function turnSegmentation($flag = true)
    {
        $this->segmentation = $flag;
        return $this;
    }

    /**
     * Turn personalization
     *
     * @param bool $flag
     * @return $this
     */
    public function turnPersonalization($flag = true)
    {
        $this->personalization = $flag;
        return $this;
    }

    /**
     * Turn campaign comparison
     *
     * @param bool $flag
     * @return $this
     */
    public function turnCampaignComparison($flag = true)
    {
        $this->campaingComparison = $flag;
        return $this;
    }

    /**
     * Turn new contact list creation
     *
     * @param bool $flag
     * @return $this
     */
    public function turnNewContactListCreation($flag = true)
    {
        $this->newContactListCreation = $flag;
        return $this;
    }

    /**
     * Turn menu
     *
     * @param bool $flag
     * @return $this
     */
    public function turnMenu($flag = true)
    {
        $this->menu = $flag;
        return $this;
    }

    /**
     * Turn bar
     *
     * @param bool $flag
     * @return $this
     */
    public function turnBar($flag = true)
    {
        $this->showBar = $flag;
        return $this;
    }

    /**
     * Turn Mailjet Logos
     *
     * @param bool $flag
     * @return $this
     */
    public function turnMailjetLogos($flag = true)
    {
        $this->logos = $flag;
        return $this;
    }

    /**
     * Set initial page
     *
     * @param string $page
     * @return $this
     */
    public function setInitialPage($page = self::URLS['stats'])
    {
        if (in_array($page, self::URLS)) {
            $this->initialPage = $page;
        } else {
            $this->initialPage = self::URLS['stats'];
        }
        return $this;
    }

    /**
     * Set callback
     *
     * @param string $callback
     * @param bool $isEncoded
     * @return $this
     */
    public function setCallback($callback = '', $isEncoded = false)
    {
        if ($isEncoded) {
            $this->callback = $callback;
        } else {
            $this->callback = urldecode($callback);
        }

        return $this;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale = self::DEFAULT_LOCALE)
    {
        if (in_array($locale, self::LOCALES)) {
            $this->locale = $locale;
        }

        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
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

    /**
     * Get Iframe url
     *
     * @return string
     */
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
