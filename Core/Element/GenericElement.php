<?php

namespace Erelke\FacebookMessengerBundle\Core\Element;

use Erelke\FacebookMessengerBundle\Core\Element;

/**
 * Class GenericElement.
 */
class GenericElement extends Element
{
    /**
     * @var string
     */
    protected $itemUrl;

    /**
     * @var array
     */
    protected $buttons = [];

    /**
     * GenericElement constructor.
     *
     * @param string $title
     * @param string $subtitle
     * @param string $imageUrl
     * @param string $itemUrl
     * @param array  $buttons
     */
    public function __construct($title = '', $subtitle = '', $imageUrl = '', $itemUrl = '', $buttons = [])
    {
        // Invoke parent constructor
        parent::__construct($title, $subtitle, $imageUrl);

        // Set GenericElement specific property values
        $this->itemUrl = $itemUrl;
        $this->buttons = $buttons;
    }

    /**
     * @return string
     */
    public function getItemUrl()
    {
        return $this->itemUrl;
    }

    /**
     * @param string $itemUrl
     */
    public function setItemUrl($itemUrl)
    {
        $this->itemUrl = $itemUrl;
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param array $buttons
     */
    public function setButtons($buttons)
    {
        $this->buttons = $buttons;
    }
}
