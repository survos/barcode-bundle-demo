<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Survos\BarcodeBundle\Service\BarcodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/{generatorCode}/demo', name: 'app_demo')]
    #[Template('/app/demo.html.twig')]
    public function demo(string $generatorCode, BarcodeService $barcodeService)
    {
        $generatorClass = $barcodeService->getGeneratorClass($generatorCode);
        return [
            'imageFormat' => $barcodeService->getImageFormat($generatorClass),
            'generatorCode' => $generatorCode,
            'generatorClass' => $generatorClass,
            'types' => $barcodeService->getGeneratorTypes()
        ];
    }

}
