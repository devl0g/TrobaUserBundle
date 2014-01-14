<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/12/14
 * Time: 10:37 PM
 */

namespace SikIndustries\Bundles\UserBundle\Controller;

use SikIndustries\Bundles\UserBundle\Form\UserLoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthenticationController
 * @package SikIndustries\Bundles\UserBundle\Controller
 * @Route("/users")
 */
class AuthenticationController extends Controller
{
    /**
     * @Route("/login", name="users_login")
     * @Method("get")
     * @Template()
     * @param Request $request
     */
    public function loginAction(Request $request)
    {
        $form = $this->createForm(new UserLoginForm());
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/check", name="users_check")
     * @Method("post")
     * @param Request $request
     */
    public function checkAction(Request $request)
    {
        print_r($request);exit();
    }

    /**
     * @Route("/logout", name="users_logout")
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {

    }
} 