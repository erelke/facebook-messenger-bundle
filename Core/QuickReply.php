<?php

namespace Erelke\FacebookMessengerBundle\Core;

use Erelke\FacebookMessengerBundle\Core\QuickReply\QuickReplies;

/**
 * Class QuickReply.
 */
class QuickReply extends Message
{
    /** @var QuickReplies[] */
    protected $quickReplies = array();

    /**
     * @return mixed
     */
    public function getQuickReplies()
    {
        return $this->quickReplies;
    }

    /**
     * @param mixed $quickReplies
     */
    public function addQuickReplies($quickReplies)
    {
        $this->quickReplies[] = $quickReplies;
    }
}
