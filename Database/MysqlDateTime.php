<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/12/14
 * Time: 11:33 PM
 */

namespace SikIndustries\Bundles\UserBundle\Database;


class MysqlDateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format("Y-m-d H:i:s");
    }
} 