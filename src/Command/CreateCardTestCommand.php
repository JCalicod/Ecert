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
                    'test_result' => 'Zone de silence Fail 1DM',
                    'expected_result' => 'QZ_Fail',
                    'field_name' => 'fail_quiet_area_1dm'
                ]
            ],
            '1DM - 2' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence Pass 1DM',
                    'expected_result' => 'QZ_Pass',
                    'field_name' => 'pass_quiet_area_1dm'
                ]
            ],
            '1DM - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Défauts 1DM',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'default_1dm'
                ]
            ],
            '1DM - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Décodabilité 1DM',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'decodability_1dm'
                ]
            ],
            '1DM - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste 1DM',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'contrast_1dm'
                ]
            ],
            '1DL - 1' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence Fail 1DL',
                    'expected_result' => 'QZ_Fail',
                    'field_name' => 'fail_quiet_area_1dl'
                ]
            ],
            '1DL - 2' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Zone de silence Pass 1DL',
                    'expected_result' => 'QZ_Pass',
                    'field_name' => 'pass_quiet_area_1dl'
                ]
            ],
            '1DL - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Défauts 1DL',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'default_1dl'
                ]
            ],
            '1DL - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Décodabilité 1DL',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'decodability_1dl'
                ]
            ],
            '1DL - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste 1DL',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'contrast_1dl'
                ]
            ],
            '2DM - 0' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Code reset pixel size',
                    'expected_result' => 'N/A',
                    'field_name' => 'reset_pixel_size'
                ]
            ],
            '2DM - 1' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'X - Dim',
                    'unit' => 'microns',
                    'expected_result' => '500 microns +/- 10',
                    'field_name' => 'xdim'
                ],
                2 => [
                    'measure' => false,
                    'test_result' => 'Modulation',
                    'unit' => 'Grade numérique',
                    'expected_result' => '4',
                    'field_name' => 'modulation'
                ]
            ],
            '2DM - 2' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur ANU',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 2%',
                    'field_name' => 'anu'
                ]
            ],
            '2DM - 3' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur GNU',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 6%',
                    'field_name' => 'gnu'
                ]
            ],
            '2DM - 4' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contraste 2DM',
                    'unit' => '%',
                    'expected_result' => 'Selon la mesure du code +/- 8%',
                    'field_name' => 'contrast_2dm'
                ]
            ],
            '2DM - 5' => [
                1 => [
                    'measure' => true,
                    'test_result' => 'Valeur Contrast Uniformity',
                    'unit' => '%',
                    'expected_result' => 'Toujours valide',
                    'field_name' => 'contrast_uniformity'
                ]
            ],
            '2DM - 6' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Valeur UEC',
                    'expected_result' => '43%',
                    'field_name' => 'uec'
                ]
            ],
            '2DM - 7' => [
                1 => [
                    'measure' => false,
                    'test_result' => 'Grade FPD',
                    'expected_result' => '2',
                    'field_name' => 'fpd'
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
        $card_test->setFieldName($test['field_name']);

        $this->em->persist($card_test);

        $this->output->writeln([
            'Création du test ' . $card_test->getTestResult() . ' pour la carte ' . $card->getType() . '.'
        ]);
    }
}