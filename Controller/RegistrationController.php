<?php
namespace SikIndustries\Bundles\TrobaUserBundle\Controller;

use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Form\UserRegistrationForm;
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
     * @Template("SikIndustriesBundlesUserBundle:Registration:create.html.twig")
     * @Route("/register", name="users_register_finish")
     * @Method("post")
     *
     * @param Request $request
     * @return array
     */
    public function registerAction(Request $request)
    {
        $user = $this->get('sik_industries.user_manager')->createUser();
        $form = $this->createForm(new UserRegistrationForm(), $user);
        $form->submit($request);

        if ($form->isValid()) {
            /** @var EQM $eqm */
            $eqm = $this->get('troba.entity_manager');

            $factory = $this->get('security.encoder_factory');
            $password = $factory->getEncoder($user)->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $user->setSalt(UserSalter::getSalt());

            $eqm->insert($user);

            $response = $this->redirect($this->generateUrl('dashboard'));
        } else {
            $response = ['form' => $form->createView()];
        }

        return $response;
    }
} 