<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;

class UserSecurity
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser()
    {
        $user = $this->security->getUser();
    }
}
