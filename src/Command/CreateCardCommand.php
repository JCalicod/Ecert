<?php


namespace App\Command;


use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCardCommand extends Command
{
    protected static $defaultName = 'ecert:create-card';
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
            ->setDescription('Crée les cartes e-cert.')
            ->setHelp('Cette commande crée les différentes cartes e-cert.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln([
            'Card creator',
            '============',
            ''
        ]);

        foreach ($this->getCardsType() as $type) {
            $this->createCard($type);
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
    public function getCardsType(): array
    {
        return [
            '1DM - 1', '1DM - 2', '1DM - 3', '1DM - 4', '1DM - 5',
            '1DL - 1', '1DL - 2', '1DL - 3', '1DL - 4', '1DL - 5',
            '2DM - 0', '2DM - 1', '2DM - 2', '2DM - 3', '2DM - 4', '2DM - 5', '2DM - 6', '2DM - 7'
        ];
    }

    /**
     * @param string $type
     */
    private function createCard(string $type): void
    {
        $card = new Card();
        $card->setType($type);

        $this->em->persist($card);

        $this->output->writeln([
            'Création de la carte ' . $type . '.'
        ]);
    }
}