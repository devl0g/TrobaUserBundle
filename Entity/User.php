<?php
namespace SikIndustries\Bundles\TrobaUserBundle\Entity;

use SikIndustries\Bundles\TrobaUserBundle\Database\MysqlDateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use troba\EQM\EQM;
use troba\Model\Finders;
use troba\Model\Getters;
use troba\Model\Persisters;
use troba\Model\Setters;

class User implements UserInterface
{
    use Persisters, Finders;

    protected $__table = "users";

    protected $id;
    protected $username;
    protected $email;
    protected $enabled;
    protected $salt;
    protected $password;
    protected $last_login;
    protected $locked;
    protected $expired;
    protected $expires_at;
    protected $confirmation_token;
    protected $password_requested_at;
    protected $credentials_expired;
    protected $credentials_expire_at;
    protected $created_at;
    protected $modified_at;

    public function __construct()
    {
        $this->created_at = new MysqlDateTime();
        $this->modified_at = new MysqlDateTime();
        $this->last_login = new MysqlDateTime();
        $this->enabled = true;
        $this->locked = false;
        $this->expired = false;
        $this->credentials_expired = false;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $confirmation_token
     */
    public function setConfirmationToken($confirmation_token)
    {
        $this->confirmation_token = $confirmation_token;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmation_token;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $credentials_expire_at
     */
    public function setCredentialsExpireAt($credentials_expire_at)
    {
        $this->credentials_expire_at = $credentials_expire_at;
    }

    /**
     * @return mixed
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentials_expire_at;
    }

    /**
     * @param mixed $credentials_expired
     */
    public function setCredentialsExpired($credentials_expired)
    {
        $this->credentials_expired = $credentials_expired;
    }

    /**
     * @return mixed
     */
    public function getCredentialsExpired()
    {
        return $this->credentials_expired;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $expired
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return mixed
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param mixed $expires_at
     */
    public function setExpiresAt($expires_at)
    {
        $this->expires_at = $expires_at;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $last_login
     */
    public function setLastLogin($last_login)
    {
        $this->last_login = $last_login;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->last_login;
    }

    /**
     * @param mixed $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return mixed
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param mixed $modified_at
     */
    public function setModifiedAt($modified_at)
    {
        $this->modified_at = $modified_at;
    }

    /**
     * @return mixed
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password_requested_at
     */
    public function setPasswordRequestedAt($password_requested_at)
    {
        $this->password_requested_at = $password_requested_at;
    }

    /**
     * @return mixed
     */
    public function getPasswordRequestedAt()
    {
        return $this->password_requested_at;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $roles = ["ROLE_USER"];

        $dbRoles = EQM::query(new Role())
            ->innerJoin('roles_users ru', 'Role.id = ru.role_id')
            ->where('ru.user_id = :userId', ['userId' => $this->getId()])
            ->all();

        /** @var Role $role */
        foreach ($dbRoles as $role) {
            $roles[] = $role->getRole();
        }

        return $roles;
    }

    /**
     * @param $role
     */
    public function addRole($role)
    {
        !$role instanceof Role && $role = new Role($role);

        $duplicate = Role::findBy('name', $role->getName())->one();
        if ($duplicate instanceof Role) {
            $role = $duplicate;
        } else {
            $role->save();
        }

        if (!$this->hasRole($role->getName())) {
            $this->associateRole($role);
        }
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return array_search($role, $this->getRoles()) !== false;
    }

    /**
     * @param Role $role
     */
    public function associateRole(Role $role)
    {
        $sql = <<<SQL
INSERT INTO roles_users VALUES (:userId, :roleId)
  ON DUPLICATE KEY UPDATE user_id=user_id;
SQL;

        EQM::nativeExecute($sql, ['userId' => $this->id, 'roleId' => $role->getId()]);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: REMOVE ESSENTIAL DATA
    }

    /**
     * @param MysqlDateTime $lastLogin
     */
    public function savesLastLogin(MysqlDateTime $lastLogin)
    {
        $this->last_login = $lastLogin;
        $this->save();
    }
}