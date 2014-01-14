<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/13/14
 * Time: 11:40 PM
 */

namespace SikIndustries\Bundles\UserBundle\Salt;


class UserSalter
{
    public static function getSalt()
    {
        return md5(uniqid().rand(0, 1000));
    }
} 