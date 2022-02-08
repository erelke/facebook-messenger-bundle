<?php

namespace Erelke\FacebookMessengerBundle\Core\Callback;

/**
 * Class PayloadReceive.
 */
class PayloadReceive
{
    /**
     * @var string
     */
    protected $payload;

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }
}
