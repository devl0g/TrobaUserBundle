<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/17/14
 * Time: 11:23 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Manager;

use Scandio\TrobaBridgeBundle\Manager\TrobaManager;
use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use troba\EQM\EQM;

class UserManager
{
    private $userClass;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var TrobaManager
     */
    private $trobaManager;

    public function __construct($userClass, EncoderFactoryInterface $encoderFactory, TrobaManager $trobaManager)
    {
        $this->userClass = $userClass;
        $this->encoderFactory = $encoderFactory;
        $this->trobaManager = $trobaManager;
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

    /**
     * @param $username
     * @throws UsernameNotFoundException
     */
    public function getUser($username)
    {
        $user = EQM::query($this->userClass)
            ->where("username = :username", ['username' => $username])
            ->one()
        ;

        if (!$user instanceof UserInterface) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * @param User $user
     * @param $role
     */
    public function addRole(User $user, $role)
    {
        $user->addRole($role);
    }

    /**
     * @param User $user
     * @param $role
     */
    public function removeRole(User $user, $role)
    {
        $user->removeRole($role);
    }
} 