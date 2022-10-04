<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(
        #[Autowire('%kernel.project_dir%/public')]
        string $imageDir,
    ): Response
    {

        $imagePath = $imageDir . '/2-qr-codes.jpg';
        assert(file_exists($imagePath), $imagePath . ' not found');
        $zbar = new \TarfinLabs\ZbarPhp\Zbar($imagePath);
        $code = $zbar->scan();
        dd($code);


        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

}
