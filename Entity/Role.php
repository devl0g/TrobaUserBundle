<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/27/15
 * Time: 11:24 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Entity;


use SikIndustries\Bundles\TrobaUserBundle\Database\MysqlDateTime;
use Symfony\Component\Security\Core\Role\RoleInterface;
use troba\Model\Finders;
use troba\Model\Persisters;

class Role implements RoleInterface
{
    use Persisters, Finders;

    const TABLE = "roles";

    protected $__table = self::TABLE;
    protected $id;
    protected $name;
    protected $created_at;
    protected $modified_at;

    public function __construct($name = null)
    {
        $this->setName($name);
        $this->created_at = new MysqlDateTime();
        $this->modified_at = new MysqlDateTime();
    }

    public function __toString()
    {
        return $this->getRole();
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return 'ROLE_'.strtoupper($this->name);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name, $disableRolePregmatch = false)
    {
        if (!$disableRolePregmatch) {
            preg_match('/ROLE_(.*)/i', $name, $result);
            count($result) && $name = $result[1];
        }
        $this->name = mb_strtolower($name);
    }

    /**
     * @return MysqlDateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param MysqlDateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return MysqlDateTime
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param MysqlDateTime $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
    }
}