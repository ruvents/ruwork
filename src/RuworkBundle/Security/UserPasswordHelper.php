<?php

namespace Ruvents\RuworkBundle\Security;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserPasswordHelper
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function setPassword(UserInterface $user, string $password = null, string $property = 'password'): void
    {
        $password = $password ?? TokenGenerator::generate(10);

        $password = $this->encoder->encodePassword($user, $password);

        \Closure::bind(function ($object, $property, $value) {
            $object->$property = $value;
        }, null, get_class($user))($user, $property, $password);
    }
}
