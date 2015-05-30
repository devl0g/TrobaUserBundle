<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 29.05.2015
 * Time: 23:50
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Manager;


use Scandio\TrobaBridgeBundle\Manager\TrobaManager;
use SikIndustries\Bundles\TrobaUserBundle\Entity\Role;
use SikIndustries\Bundles\TrobaUserBundle\Entity\User;

class RoleManager
{
    /**
     * @var TrobaManager
     */
    private $trobaManager;

    public function __construct(TrobaManager $trobaManager)
    {
        $this->trobaManager = $trobaManager;
    }

    /**
     * @return array
     */
    public function all()
    {
        $roles = ['ROLE_USER' => new Role("ROLE_USER")];

        $dbRoles = $this->trobaManager->query(new Role())
            ->innerJoin('roles_users ru', 'Role.id = ru.role_id')
            ->all()
        ;

        /** @var Role $role */
        foreach ($dbRoles as $role) {
            $roles[$role->getRole()] = $role;
        }

        return $roles;
    }

    /**
     * @param User $user
     * @param $role
     */
    public function toggle(User $user, $role)
    {
        if ($user->hasRole($role)) {
            $user->removeRole($role);
        } else {
            $user->addRole($role);
        }
    }
}