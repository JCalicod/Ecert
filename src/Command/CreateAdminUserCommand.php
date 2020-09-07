<?php


namespace App\Command;


use App\Entity\Card;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'ecert:create-admin';
    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Crée l\'utilisateur admin.')
            ->setHelp('Cette commande crée l\'utilisateur qui permettra de s\'identifier à la création d\'un kit.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User creator',
            '============',
            ''
        ]);

        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'z;y#3-*KGp,CSG]J'));
        $user->setUsername('@xicon-m@ster');

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln([
            'Utilisateur admin créé.',
            '',
            '============',
            'Terminé'
        ]);
    }
}