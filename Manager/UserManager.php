<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/17/14
 * Time: 11:23 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Manager;

use Scandio\TrobaBridgeBundle\Manager\TrobaManager;
use SikIndustries\Bundles\TrobaUserBundle\Database\MysqlDateTime;
use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Salt\UserSalter;
use Symfony\Component\HttpFoundation\Session\Session;
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

    /**
     * @var Session
     */
    private $session;

    public function __construct($userClass, EncoderFactoryInterface $encoderFactory, TrobaManager $trobaManager, Session $session)
    {
        $this->userClass = $userClass;
        $this->encoderFactory = $encoderFactory;
        $this->trobaManager = $trobaManager;
        $this->session = $session;
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
            ->andWhere("locked = :locked", ['locked' => false])
            ->one()
        ;

        if (!$user instanceof UserInterface) {
            $this->session->getFlashBag()->add("error", "User not found");
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

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        foreach ($user->getRoles() as $role) {
            $user->removeRole($role);
        }
        $user->delete();
    }

    /**
     * @return \troba\EQM\AbstractResultSet
     */
    public function all()
    {
        $className = $this->userClass;
        return $className::findAll(["username"]);
    }

    /**
     * @param User $user
     */
    public function anonymize(User $user)
    {
        $user->anonymize();
        $user->save();
    }

    /**
     * @param $userId
     * @return User
     */
    public function find($userId)
    {
        return $user = EQM::query($this->userClass)
            ->where("id = :id", ['id' => $userId])
            ->one()
        ;
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $user->setLastLogin(new MysqlDateTime());
        $user->save();
    }

    /**
     * @param User $user
     */
    public function insert(User $user, $encodePassword = true)
    {
        $user->setId(null);
        $user->setLastLogin(new MysqlDateTime());
        $user->setSalt(UserSalter::getSalt());
        $encodePassword && $user->setPassword($this->password($user));
        $user->save();
    }

    /**
     * @param User $user
     */
    public function toggle(User $user)
    {
        $user->toggleLocked();
        $user->save();
    }

    /**
     * @param User $user
     */
    public function resetPassword(User $user)
    {
        $user->setPasswordRequestedAt(new MysqlDateTime());
        $user->setPasswordResetKey(md5(uniqid().time()));
        $user->save();
    }

    /**
     * @param $field
     * @param $value
     * @return object
     */
    public function findOneBy($field, $value)
    {
        return $user = EQM::query($this->userClass)
            ->where("{$field} = :$field", [$field => $value])
            ->one()
            ;
    }
} 