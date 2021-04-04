<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use App\Repository\MarcadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    #[Route('/editar-favorito', name: 'app_editar_favorito')]
    public function editarFavorito(MarcadorRepository $marcadorRepository,Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $actualizado = true;
            $idMarcador = $request-> get('id');
            $entityManager = $this->getDoctrine()->getManager();
            $marcador = $marcadorRepository->findOneById($idMarcador);
            $marcador->setFavorito(!$marcador->getFavorito());
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $actualizado = false;
            }
            return $this->json([
                'actualizado' => $actualizado
            ]);
        }   
        throw $this->createNotFoundException();
    }

    #[Route('/favoritos', name: 'app_favoritos')]
    public function favoritos(MarcadorRepository $marcadorRepository){
        $marcadores = $marcadorRepository->findBy([
            'favorito' => true
        ]);
        return $this->render('index/index.html.twig', [
            'marcadores' => $marcadores,
        ]);

    }

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
