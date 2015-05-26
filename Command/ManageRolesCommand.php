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
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ManageRolesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('roles:manage-role')
            ->setDescription('Manages roles for a specific user')
            ->addArgument(
                'action',
                InputArgument::REQUIRED,
                'set|remove a role to|from a user'
            )
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Username of the user in db'
            )
            ->addArgument(
                'role',
                InputArgument::REQUIRED,
                'The role to set. I.e. ROLE_ADMIN'
            )
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var UserManager $userManager */
        $userManager = $this->getContainer()->get("sik_industries.user_manager");
        $action = $input->getArgument('action');
        $username = $input->getArgument('username');
        $role = $input->getArgument("role");
        if (!empty($username) && !empty($role) && in_array($action, ['set', 'remove'])) {
            $output->writeln("{$action} role: {$role} > {$username}");
            try {
                $user = $userManager->getUser($username);
                if ($user instanceof User) {
                    if ($action == "set") {
                        $userManager->addRole($user, $role);
                    } else {
                        $userManager->removeRole($user, $role);
                    }
                    $output->writeln("{$action} role > user {$user->getId()}");
                }
            } catch (\Exception $e) {
                $output->writeln("User {$username} not found!");
                $output->writeln("Error: {$e->getMessage()}");
            }
        }
    }
}