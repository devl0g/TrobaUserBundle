<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 1/12/14
 * Time: 10:37 PM
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Controller;

use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Form\UserLoginForm;
use SikIndustries\Bundles\TrobaUserBundle\Form\UserReenterPasswordForm;
use SikIndustries\Bundles\TrobaUserBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AuthenticationController
 * @package SikIndustries\Bundles\TrobaUserBundle\Controller
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

    }

    /**
     * @Route("/logout", name="users_logout")
     * @param Request $request
     */
    public function logoutAction(Request $request)
    {

    }

    /**
     * @Route("/reset/{authenticationKey}", name="users_reset_password")
     * @Template("@SikIndustriesBundlesTrobaUserBundle/Resources/views/Authentication/reset_password.html.twig")
     * @param Request $request
     */
    public function resetPasswordAction(Request $request, $authenticationKey)
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('sik_industries.user_manager');

        $user = $userManager->findOneBy("password_reset_key", $authenticationKey);
        if (!$user instanceof User) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new UserReenterPasswordForm());
        $form->handleRequest($request);

        $isValid = false;
        if ($request->getMethod() == Request::METHOD_POST && $form->isValid()) {
            $user->setPassword($request->get(UserReenterPasswordForm::NAME)['password']['first']);
            $user->setPassword($userManager->password($user));
            $user->setPasswordResetKey(null);
            $userManager->update($user);
            $isValid = true;
        }

        if ($isValid) {
            $response = $this->redirect($this->generateUrl('homepage'));
        } else {
            $response = ['form' => $form->createView()];
        }

        return $response;
    }
} 