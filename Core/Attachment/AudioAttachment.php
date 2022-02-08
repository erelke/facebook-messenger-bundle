<?php

namespace Erelke\FacebookMessengerBundle\Core\Attachment;

use Erelke\FacebookMessengerBundle\Core\Attachment;

/**
 * Class AudioAttachment.
 */
class AudioAttachment extends Attachment
{
    /**
     * AudioAttachment constructor.
     *
     * @param $payload
     */
    public function __construct($payload = null)
    {
        // Invoke parent constructor and force type value
        parent::__construct(Attachment::TYPE_AUDIO, $payload);
    }
}
