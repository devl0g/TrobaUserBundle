<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/13/14
 * Time: 11:28 PM
 */
namespace SikIndustries\Bundles\UserBundle\Listener;

use SikIndustries\Bundles\UserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Database\MysqlDateTime;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $user->savesLastLogin(new MysqlDateTime());
    }
}