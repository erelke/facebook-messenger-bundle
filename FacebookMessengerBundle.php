<?php

namespace Erelke\FacebookMessengerBundle;

use Erelke\FacebookMessengerBundle\DependencyInjection\FacebookMessengerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FacebookMessengerBundle.
 */
class FacebookMessengerBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new FacebookMessengerExtension();
    }
}
