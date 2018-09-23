<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        $currentItem['name'] = "О компании";
        $data['breadcrumbs'][] = $currentItem;
        return $this->render('pages/content/about.html.twig', ['data'=>$data]);
    }
}

