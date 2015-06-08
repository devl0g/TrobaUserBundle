<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 26.05.2015
 * Time: 21:20
 */

namespace SikIndustries\Bundles\TrobaUserBundle\Command;

use SikIndustries\Bundles\TrobaUserBundle\Entity\User;
use SikIndustries\Bundles\TrobaUserBundle\Manager\UserManager;
use SikIndustries\Bundles\TrobaUserBundle\Salt\UserSalter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ManageUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('users:manage-user')
            ->setDescription('Manages users')
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'add|remove a user'
            )
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username of the user in db'
            )
            ->addOption("password", null, InputOption::VALUE_OPTIONAL)
            ->addOption("email", null, InputOption::VALUE_OPTIONAL)
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UserManager $userManager */
        $userManager = $this->getContainer()->get("sik_industries.user_manager");
        $action = $input->getArgument('action');
        $username = $input->getArgument('username');
        $email = $input->getOption("email") ? $input->getOption("email") : "example-".substr(md5(uniqid()), 0, 8)."@example.com";
        if (!empty($username) && in_array($action, ['add', 'remove'])) {
            $output->writeln("{$action} {$username}");
            try {
                $user = $userManager->getUser($username);
            } catch (\Exception $e) {
                $user = null;
            }

            $user = $userManager->findOneBy("email", $email);
            if ($action == "add" && empty($user)) {
                $password = $input->getOption("password") ? $input->getOption("password") : substr(md5(uniqid()), 0, 8);

                $user = $userManager->createUser();
                $user->setUsername($username);
                $user->setSalt(UserSalter::getSalt());
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setPassword($userManager->password($user));

                $user->save();

                $output->writeln("Added user with password {$password} and email {$email}");
            } else if ($action == "remove" && $user instanceof User) {
                $userManager->delete($user);
            }
        }
    }
}