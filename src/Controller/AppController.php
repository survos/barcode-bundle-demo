<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Survos\BarcodeBundle\Service\BarcodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    public function __construct(private BarcodeService $barcodeService)
    {
    }

    #[Route('/', name: 'app_app')]
    public function index(Request $request): Response
    {
        $string = $request->get('q', 'abcdefg');

        $extensionCheck  = array_reduce(['gd', 'imagick'], function(array $carry, $ext) {
            $carry[$ext] = extension_loaded($ext);
            return $carry;
        }, []);

        return $this->render('app/index.html.twig', [
            'string' => $string,
            'extensions' => $extensionCheck,
            'generators' => $this->barcodeService->getGenerators()
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
