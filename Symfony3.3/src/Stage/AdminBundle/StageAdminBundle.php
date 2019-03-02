<?php

namespace Stage\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class StageAdminBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
