<?php


namespace App\Service;


use App\Entity\Kit;
use App\Entity\KitValue;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class KitService
{
    private $em;
    private $translator;
    private $success;
    private $error;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @return string|null
     */
    public function getSuccess(): ?string
    {
        return $this->success;
    }

    /**
     * @param string $success
     */
    public function setSuccess(string $success): void
    {
        $this->success = $success;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function createKit(array $data): void
    {
        $today = new \DateTime('now', New \DateTimeZone('Europe/Paris'));
        $kit = new Kit();
        $kit->setCli($data['cli']->getData());
        $kit->setPack($data['pack']->getData());
        $kit->setCreation($today);
        $kit->setState('Attribué');
        $kit->setSerialNumber($this->generateSerialNumber($today));
        $kit->setRandomKey($this->generateRandomKey());

        $this->em->persist($kit);

        foreach ($data as $key => $value) {
            if (in_array($key, $this->getTestsFieldName())) {
                if ($value && $value->getData()) {
                    $kitValue = new KitValue();
                    $kitValue->setKit($kit);
                    $kitValue->setTest($this->em->getRepository(Test::class)->findOneBy(['field_name' => $key]));
                    $kitValue->setValue($value->getData());

                    $this->em->persist($kitValue);
                }
                else {
                    $this->setError($this->translator->trans('The form has errors.'));
                    return;
                }
            }
        }

        $this->em->flush();
    }

    /**
     * @param \DateTime $today
     * @return string
     */
    private function generateSerialNumber(\DateTime $today): string
    {
        $id = 1;
        $last_kit = $this->em->getRepository(Kit::class)->findOneBy([], ['id' => 'desc']);
        if ($last_kit) {
            $id = $last_kit->getId() + 1;
        }
        return $today->format('y') . $today->format('m') . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    private function generateRandomKey(): string
    {
        return implode( array_map(
            function() {
                return dechex( mt_rand( 0, 15 ) ); }, array_fill( 0, 4, null )
            )
        );
    }

    /**
     * @return array
     */
    private function getTestsFieldName(): array
    {
        $tests = [];
        $testEntities = $this->em->getRepository(Test::class)->findAll();

        foreach ($testEntities as $entity) {
            $tests[] = $entity->getFieldName();
        }

        return $tests;
    }
}