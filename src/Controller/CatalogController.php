<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends Controller
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('pages/index/index.html.twig');
    }

    /**
     * @Route("/2", name="get_person_details")
     */
    public function index2()
    {
        return new Response('2',200);
    }






}

