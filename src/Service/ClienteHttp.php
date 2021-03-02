<?php
namespace App\Service;

use App\Repository\CategoriaRepository;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpClient\HttpClient;

Class ClienteHttp
{
    private $clienteHttp;
    public function __construct(CategoriaRepository $categoriaRepository)
    {
        $this -> clienteHttp = HttpClient::create();
    }
    public function obtenerCodigoUrl(string $url){
        $codigoEstado = null;
        try{
            $respuesta = $this ->clienteHttp->request('GET',$url);
            $codigoEstado = $respuesta->getStatusCode();
        }catch(Exception $e){
            $codigoEstado = null;
        }
        return $codigoEstado;

    }

}