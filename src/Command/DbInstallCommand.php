<?php

namespace App\Command;

use App\Entity\Company;
use App\Entity\User;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as UserPasswordEncoderInterfaceAlias;

class DbInstallCommand extends Command
{
    protected static $defaultName = 'app:db-install';
    protected static $defaultDescription = 'Add a short description for your command';
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
        $encoders = [
            User::class => $defaultEncoder,
        ];
        $encoderFactory = new EncoderFactory($encoders);
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $em = $this->container->get('doctrine')->getManager();

        //-------
        $company = new Company();
        $company->setName('PATHCompany');
        $company->setIsActive(true);
        $em->persist($company);

        $io->success('Company created.');

        //-------
        for ($i = 1;$i<3;$i++){
        $user = new User();
        $user->setName('User '.$i);
        $password = $encoderFactory->getEncoder($user)->encodePassword('1234', $user->getSalt());
        $user->setPassword($password);
        $user->setEmail('user'.$i.'@gmail.com');
        $user->setCompany($company);
        $user->setRoles(["ROLE_COMPANY_USER"]);
        $em->persist($user);
    }
        $user = new User();
        $user->setName('company_admin');
        $password = $encoderFactory->getEncoder($user)->encodePassword('1234', $user->getSalt());
        $user->setPassword($password);
        $user->setEmail('company_admin@gmail.com');
        $user->setCompany($company);
        $user->setRoles(["ROLE_COMPANY_ADMIN"]);
        $em->persist($user);
        $io->success('ROLE_COMPANY_ADMIN created.');

        $user = new User();
        $user->setName('super_admin');
        $password = $encoderFactory->getEncoder($user)->encodePassword('1234', $user->getSalt());
        $user->setPassword($password);
        $user->setEmail('super_admin@gmail.com');
        $user->setCompany($company);
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $em->persist($user);
        $io->success('ROLE_SUPER_ADMIN created.');
        $em->flush();


        return 0;
    }
}
