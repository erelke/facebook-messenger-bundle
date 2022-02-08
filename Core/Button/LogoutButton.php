<?php

namespace Erelke\FacebookMessengerBundle\Core\Button;

use Erelke\FacebookMessengerBundle\Core\Button;
use Erelke\FacebookMessengerBundle\Core\ButtonInterface;

/**
 * Class LogoutButton
 */
class LogoutButton implements ButtonInterface
{
    /**
     * @var string
     */
    protected $type;


    /**
     * LogoutButton constructor.
     */
    public function __construct()
    {
        $this->type = Button::TYPE_ACCOUNT_UNLINK;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
