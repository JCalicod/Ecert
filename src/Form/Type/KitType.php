<?php
/**
 * Created by PhpStorm.
 * User: Gianni GIUDICE
 * Date: 08/04/2020
 * Time: 15:12
 */

namespace App\Form\Type;

use App\Entity\Card;
use App\Entity\Kit;
use App\Entity\Model;
use App\Entity\Pack;
use App\Entity\Set;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class KitType extends AbstractType
{
    private $em;
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cli', TextType::class, [
                'mapped' => false
            ])
            ->add('pack', EntityType::class, [
                'class' => Pack::class,
                'choice_label' => function ($pack) {
                    return $pack->getFullName();
                },
                'label' => 'Choix du jeu de cartes',
                'placeholder' => $this->translator->trans('Select a pack')
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $pack = $this->em->getRepository(Pack::class)->find($data['pack']);

        $this->addElements($form, $pack);
    }

    protected function addElements(FormInterface $form, Pack $pack = null)
    {
        $form
            ->add('cli', TextType::class, [
                'mapped' => false
            ])
            ->add('pack', EntityType::class, [
            'class' => Pack::class,
            'choice_label' => function ($pack) {
                return $pack->getFullName();
            },
            'placeholder' => $this->translator->trans('Select a pack'),
            'label' => 'Choix du jeu de cartes'
        ]);

        $cards = [];
        if ($set = $this->em->getRepository(Set::class)->findOneBy(['pack' => $pack])) {
            $cards = $set->getCards();
        }
        foreach ($cards as $card) {
            foreach ($card->getTests() as $test) {
                $form->add($test->getTestResult(), TextType::class, [
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Kit::class
        ]);
    }


}