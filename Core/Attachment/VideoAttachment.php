<?php

namespace Erelke\FacebookMessengerBundle\Core\Attachment;

use Erelke\FacebookMessengerBundle\Core\Attachment;

/**
 * Class VideoAttachment.
 */
class VideoAttachment extends Attachment
{
    /**
     * VideoAttachment constructor.
     *
     * @param $payload
     */
    public function __construct($payload = null)
    {
        // Invoke parent constructor and force type value
        parent::__construct(Attachment::TYPE_VIDEO, $payload);
    }
}
