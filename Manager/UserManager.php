<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/17/14
 * Time: 11:23 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Manager;

class UserManager
{
    private $userClass;

    public function __construct($userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @return User
     */
    public function createUser()
    {
        $userClass = $this->userClass;
        return new $userClass();
    }
} 