<?php


namespace App\Controller;


use App\Service\TranslatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TranslatorController
 * @package App\Controller
 * @Route("/language")
 */
class TranslatorController extends AbstractController
{
    private $translatorService;

    public function __construct(TranslatorService $translatorService)
    {
        $this->translatorService = $translatorService;
    }

    /**
     * @Route("/{language}", name="language")
     * @param Request $request
     * @param $language
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLanguage(Request $request, $language)
    {
        $this->translatorService->editLanguage($language);
        return $this->redirect($request->headers->get('referer'));
    }
}