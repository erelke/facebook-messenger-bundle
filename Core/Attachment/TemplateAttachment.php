<?php

namespace Erelke\FacebookMessengerBundle\Core\Attachment;

use Erelke\FacebookMessengerBundle\Core\Attachment;

/**
 * Class TemplateAttachment.
 */
class TemplateAttachment extends Attachment
{
    /**
     * TemplateAttachment constructor.
     *
     * @param null $payload
     */
    public function __construct($payload = null)
    {
        // Invoke parent constructor and force type value
        parent::__construct(Attachment::TYPE_TEMPLATE, $payload);
    }
}
