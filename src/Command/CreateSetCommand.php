<?php


namespace App\Command;


use App\Entity\Card;
use App\Entity\Pack;
use App\Entity\Set;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSetCommand extends Command
{
    protected static $defaultName = 'ecert:create-set';
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
            ->setDescription('Crée les sets e-cert.')
            ->setHelp('Cette commande crée les différents sets e-cert.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln([
            'Set creator',
            '============',
            ''
        ]);

        foreach ($this->getAllData() as $pack_name => $data) {
            if ($pack = $this->em->getRepository(Pack::class)->findOneBy(['name' => $pack_name])) {
                $this->createSet($pack, $data);

                $this->output->writeln([
                    ''
                ]);
            }
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
            '6000' => [
                'set_name' => '1DM Full',
                'cards' => [
                    '1DM - 1',
                    '1DM - 2',
                    '1DM - 3',
                    '1DM - 4',
                    '1DM - 5'
                ]
            ],
            '7000' => [
                'set_name' => '1DL Full',
                'cards' => [
                    '1DL - 1',
                    '1DL - 2',
                    '1DL - 3',
                    '1DL - 4',
                    '1DL - 5'
                ]
            ],
            '12000' => [
                'set_name' => '2DM Full',
                'cards' => [
                    '2DM - 0',
                    '2DM - 1',
                    '2DM - 2',
                    '2DM - 3',
                    '2DM - 4',
                    '2DM - 5',
                    '2DM - 6',
                    '2DM - 7'
                ]
            ],
            '15000' => [
                'set_name' => '1DM Full + 2DM Cartes 1 à 7',
                'cards' => [
                    '1DM - 1',
                    '1DM - 2',
                    '1DM - 3',
                    '1DM - 4',
                    '1DM - 5',
                    '2DM - 1',
                    '2DM - 2',
                    '2DM - 3',
                    '2DM - 4',
                    '2DM - 5',
                    '2DM - 6',
                    '2DM - 7'
                ]
            ],
            'DPM' => [
                'set_name' => '2DM Cartes 1 à 7',
                'cards' => [
                    '2DM - 1',
                    '2DM - 2',
                    '2DM - 3',
                    '2DM - 4',
                    '2DM - 5',
                    '2DM - 6',
                    '2DM - 7'
                ]
            ]
        ];
    }

    /**
     * @param Pack $pack
     * @param array $data
     */
    private function createSet(Pack $pack, array $data): void
    {
        $set = new Set();
        $set->setName($data['set_name']);
        $set->setPack($pack);

        foreach ($data['cards'] as $card_type) {
            if ($card = $this->em->getRepository(Card::class)->findOneBy(['type' => $card_type])) {
                $set->addCard($card);
            }
        }

        $this->em->persist($set);

        $this->output->writeln([
            'Création du set ' . $set->getName() . '.'
        ]);
    }
}