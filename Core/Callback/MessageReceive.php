<?php

namespace Erelke\FacebookMessengerBundle\Core\Callback;

use Erelke\FacebookMessengerBundle\Core\Attachment;
use Erelke\FacebookMessengerBundle\Core\AttachmentInterface;

/**
 * Class MessageReceive.
 */
class MessageReceive
{
    /**
     * @var string
     */
    protected $mid;

    /**
     * @var int
     */
    protected $seq;

    /**
     * @var string|null
     */
    protected $text = null;

    /**
     * @var Attachment[]
     */
    protected $attachments = array();

    /**
     * @var QuickReplyPayloadReceive
     */
    protected $quickReply = null;

    /**
     * @return string
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param string $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * @param int $seq
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param AttachmentInterface $attachment
     */
    public function addAttachment(AttachmentInterface $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return QuickReplyPayloadReceive
     */
    public function getQuickReply()
    {
        return $this->quickReply;
    }

    /**
     * @param QuickReplyPayloadReceive $quickReply
     */
    public function setQuickReply($quickReply)
    {
        $this->quickReply = $quickReply;
    }
}
