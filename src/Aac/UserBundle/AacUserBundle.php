<?php

namespace Aac\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AacUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }       
}
