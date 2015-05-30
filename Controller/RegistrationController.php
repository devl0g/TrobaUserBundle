<?php
namespace SikIndustries\Bundles\TrobaUserBundle\Controller;

use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Form\UserRegistrationForm;
use SikIndustries\Bundles\TrobaUserBundle\Manager\UserManager;
use SikIndustries\Bundles\TrobaUserBundle\Salt\UserSalter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use troba\EQM\EQM;

/**
 * Class DefaultController
 * @package SikIndustries\Bundles\TrobaUserBundle\Controller
 * @Route("/users")
 */
class RegistrationController extends Controller
{
    /**
     * @Template()
     * @Route("/register", name="users_register")
     * @Method("get")
     *
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new UserRegistrationForm());

        return ['form' => $form->createView()];
    }

    /**
     * @Template("SikIndustriesBundlesTrobaUserBundle:Registration:create.html.twig")
     * @Route("/register", name="users_register_finish")
     * @Method("post")
     *
     * @param Request $request
     * @return array
     */
    public function registerAction(Request $request)
    {
        /** @var UserManager $userManager */
        $userManager = $this->get('sik_industries.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(new UserRegistrationForm(), $user);
        $form->submit($request);

        if ($form->isValid()) {
            /** @var EQM $eqm */
            $userManager->insert($user);

            $response = $this->redirect($this->generateUrl('dashboard'));
        } else {
            $response = ['form' => $form->createView()];
        }

        return $response;
    }
} 