<?php


namespace App\Command;


use App\Entity\Pack;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePackCommand extends Command
{
    protected static $defaultName = 'ecert:create-pack';
    private $em;
    private $output;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Crée les jeux e-cert.')
            ->setHelp('Cette commande crée les différents jeux e-cert.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln([
            'Pack creator',
            '============',
            ''
        ]);

        foreach ($this->getAllData() as $pack) {
            $this->createPack($pack['name'], $pack['full_name']);
        }

        $this->em->flush();

        $output->writeln([
            '',
            '============',
            'Terminé'
        ]);
    }

    /**
     * @return array
     */
    public function getAllData(): array
    {
        return [
            1 => [
                'name' => '6000',
                'full_name' => 'Séries 6000'
            ],
            2 => [
                'name' => '7000',
                'full_name' => 'Séries 6500/7000',
            ],
            3 => [
                'name' => '12000',
                'full_name' => 'Axicon 12000',
            ],
            4 => [
                'name' => '15000',
                'full_name' => 'Séries 15000/12700',
            ],
            5 => [
                'name' => 'DPM',
                'full_name' => 'Séries DPM',
            ]
        ];
    }

    /**
     * @param string $name
     * @param string $full_name
     */
    private function createPack(string $name, string $full_name): void
    {
        $pack = new Pack();
        $pack->setName($name);
        $pack->setFullName($full_name);

        $this->em->persist($pack);

        $this->output->writeln([
            'Création du pack ' . $full_name . '.'
        ]);
    }
}