<?php

namespace Erelke\FacebookMessengerBundle\Core\Template;

use Erelke\FacebookMessengerBundle\Core\Payload\TemplatePayload;

/**
 * Class GenericTemplatePayload.
 */
class GenericTemplatePayload extends TemplatePayload
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * GenericTemplatePayload constructor.
     *
     * @param array $elements
     */
    public function __construct($elements = [])
    {
        // Invoke parent constructor and force type value
        parent::__construct(TemplatePayload::TYPE_GENERIC);

        // Set GenericTemplatePayload specific properties
        $this->elements = $elements;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param array $elements
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
    }
}
