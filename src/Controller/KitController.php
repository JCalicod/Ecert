<?php


namespace App\Controller;


use App\Entity\Kit;
use App\Entity\Pack;
use App\Entity\Set;
use App\Form\Type\KitType;
use App\Service\KitService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class KitController
 * @package App\Controller
 * @Route({
 *     "en": "/kit",
 *     "fr": "/kit"
 * })
 * @IsGranted("ROLE_ADMIN")
 */
class KitController extends AbstractController
{
    private $em;
    private $translator;
    private $kitService;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator, KitService $kitService)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->kitService = $kitService;
    }

    /**
     * @Route("/", name="create_kit")
     * @return Response
     * @throws \Exception
     */
    public function home(Request $request)
    {
        $created = false;
        $form = $this->createForm(KitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->all();
            $this->kitService->createKit($data);
            if ($this->kitService->getError()) {
                $this->addFlash('danger', $this->kitService->getError());
            }
            else {
                $created = true;
            }
        }
        return $this->render('security/create_kit.html.twig', [
            'form' => $form->createView(),
            'created' => $created
        ]);
    }

    /**
     * @Route("/pack-models", name="get_pack_models")
     * @param Request $request
     * @return JsonResponse
     */
    public function getPackModels(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            if ($pack = $this->em->getRepository(Pack::class)->find($request->request->get('pack_id'))) {
                if ($set = $this->em->getRepository(Set::class)->findOneBy(['pack' => $pack])) {
                    $cards = $set->getCards();

                    $tests = [];

                    foreach ($cards as $card) {
                        foreach ($card->getTests() as $test) {
                            $tests[] = [
                                'id' => $test->getId(),
                                'measure' => $test->getMeasure(),
                                'test_result' => $test->getTestResult(),
                                'unit' => $test->getUnit(),
                                'expected_result' => $test->getExpectedResult(),
                                'field_name' => $test->getFieldName()
                            ];
                        }
                    }
                    return new JsonResponse($tests);
                }
                else {
                    return new JsonResponse($this->translator->trans('This pack doesn\'t have a configured set.'), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            else {
                return new JsonResponse($this->translator->trans('This pack doesn\'t exist.'), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else {
            return new JsonResponse($this->translator->trans('This call is unauthorized.'), JsonResponse::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @Route({
     *     "en": "/list",
     *     "fr": "/liste"
     * }, name="kit_list")
     */
    public function kitList(Request $request, DataTableFactory $dataTableFactory)
    {
        $kits = $this->em->getRepository(Kit::class)->findAll();

        $table = $dataTableFactory->create()
            ->add('serial_number', TextColumn::class, ['label' => $this->translator->trans('Serial Number')])
            ->add('random_key', TextColumn::class, ['label' => $this->translator->trans('Random Key')])
            ->add('cli', TextColumn::class, ['label' => $this->translator->trans('CLI')])
            ->add('state', TextColumn::class, ['label' => $this->translator->trans('State')])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Kit::class
            ])
        ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('security/kit_list.html.twig', [
            'datatable' => $table
        ]);
    }
}