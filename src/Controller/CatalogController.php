<?php

namespace App\Controller;

use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends Controller
{
    private $data;
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $data['catalog_name'] = 'Каталог автозапчастей';
        $data['catalog'] = $this->getDoctrine()->getRepository(Brand::class)->findBy(['active'=>1]);
        return $this->render('pages/index/index.html.twig', ['data'=>$data]);
    }

}

