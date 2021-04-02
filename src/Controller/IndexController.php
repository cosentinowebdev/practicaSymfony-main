<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    #[Route('/{categoria}', name: 'app_index', defaults: ['categoria'=> ''])]
    public function index(string $categoria, CategoriaRepository $categoriaRepository,MarcadorRepository $marcadorRepository): Response
    {
        dump($categoria);
        if (!empty($categoria))
        {
            if(!$categoriaRepository->findByNombre($categoria))
            {
                throw $this->createNotFoundException("La categoria '$categoria' no existe.");
            }
            $marcadores = $marcadorRepository->buscarPorNombreCategoria($categoria);

        } else {
            $marcadores = $marcadorRepository->findAll();

        }

        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);
    }
}
