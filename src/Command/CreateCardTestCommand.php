<?php


namespace App\Command;


use App\Entity\Card;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCardTestCommand extends Command
{
    protected static $defaultName = 'ecert:create-card-test';
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
            ->setDescription('Crée les tests de carte e-cert.')
            ->setHelp('Cette commande crée les différents tests de carte e-cert.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln([
            'Card creator',
            '============',
            ''
        ]);

        foreach ($this->getAllData() as $card_type => $tests) {
            if ($card = $this->em->getRepository(Card::class, 'ecert')->findOneBy(['type' => $card_type])) {
                foreach ($tests as $test) {
                    $this->createCardTest($card, $test);
                }
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
            '1DM - 1' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence',
                    'expected_result' => 'QZ_Fail'
                ]
            ],
            '1DM - 2' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence',
                    'expected_result' => 'QZ_Pass'
                ]
            ],
            '1DM - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Défauts',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '1DM - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Décodabilité',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '1DM - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '1DL - 1' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence',
                    'expected_result' => 'QZ_Fail'
                ]
            ],
            '1DL - 2' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence',
                    'expected_result' => 'QZ_Pass'
                ]
            ],
            '1DL - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Défauts',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '1DL - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Décodabilité',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '1DL - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '2DM - 0' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Code reset pixel size',
                    'expected_result' => 'N/A'
                ]
            ],
            '2DM - 1' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'X - Dim',
                    'unit' => 'microns',
                    'expected_result' => '500 microns +/- 10'
                ],
                2 => [
                    'measure' => false,
                    'test_result' => 'Modulation',
                    'unit' => 'Grade numérique',
                    'expected_result' => '4'
                ]
            ],
            '2DM - 2' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur ANU',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 2%'
                ]
            ],
            '2DM - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur GNU',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 6%'
                ]
            ],
            '2DM - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%'
                ]
            ],
            '2DM - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contrast Uniformity',
                    'unit' => '%',
                    'expected_result' => 'Toujours valide'
                ]
            ],
            '2DM - 6' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Valeur UEC',
                    'expected_result' => '43%'
                ]
            ],
            '2DM - 7' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Grade FPD',
                    'expected_result' => '2'
                ]
            ]
        ];
    }

    /**
     * @param Card $card
     * @param array $test
     */
    private function createCardTest(Card $card, array $test): void
    {
        $card_test = new Test();
        $card_test->setCard($card);
        $card_test->setMeasure($test['measure']);
        $card_test->setTestResult($test['test_result']);
        if (array_key_exists('unit', $test)) {
            $card_test->setUnit($test['unit']);
        }
        $card_test->setExpectedResult($test['expected_result']);

        $this->em->persist($card_test);

        $this->output->writeln([
            'Création du test ' . $card_test->getTestResult() . ' pour la carte ' . $card->getType() . '.'
        ]);
    }
}