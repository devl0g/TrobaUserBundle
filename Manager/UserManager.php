<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/17/14
 * Time: 11:23 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Manager;

use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    private $userClass;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct($userClass, EncoderFactoryInterface $encoderFactory)
    {
        $this->userClass = $userClass;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return User
     */
    public function createUser()
    {
        $userClass = $this->userClass;
        return new $userClass();
    }

    /**
     * @param User $user
     * @return string
     */
    public function password(User $user)
    {
        return $this->encoderFactory
            ->getEncoder($user)
            ->encodePassword($user->getPassword(), $user->getSalt());
    }
} 