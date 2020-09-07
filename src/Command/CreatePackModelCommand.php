<?php


namespace App\Command;


use App\Entity\Model;
use App\Entity\Pack;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePackModelCommand extends Command
{
    protected static $defaultName = 'ecert:create-pack-model';
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
            ->setDescription('Crée les modèles e-cert.')
            ->setHelp('Cette commande crée les différents modèles associés à leur jeu e-cert.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln([
            'Model creator',
            '============',
            ''
        ]);

        foreach ($this->getAllData() as $pack_name => $models) {
            if ($pack = $this->em->getRepository(Pack::class, 'ecert')->findOneBy(['name' => $pack_name])) {
                foreach ($models as $model) {
                    $this->createModel($pack, $model);
                }
                $this->output->writeln([
                    ''
                ]);
            }
        }

        $this->em->flush();

        $output->writeln([
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
            '6000' => [
                '6015', '6025'
            ],
            '7000' => [
                '6515', '6525', '7015', '7025', 'BV7000'
            ],
            '12000' => [
                '12000'
            ],
            '15000' => [
                '15000', '15200', '15500', '12500', '12600', '12700', 'OMNI'
            ],
            'DPM' => [
                '12800', '12900'
            ]
        ];
    }

    /**
     * @param Pack $pack
     * @param string $model_name
     */
    private function createModel(Pack $pack, string $model_name): void
    {
        $model = new Model();
        $model->setName($model_name);
        $model->setPack($pack);
        $this->em->persist($model);

        $this->output->writeln([
            'Création du modèle ' . $model->getName() . ' pour le pack ' . $pack->getFullName() .'.'
        ]);
    }
}