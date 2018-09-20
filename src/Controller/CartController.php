<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Base class, extends Easy Admin.
 * Here we can change base functionality of Easy Admin module,
 * add and override base functions.
 *
 * Path prefix (/admin) define in config/routes.yaml for all actions in controller
 *
 * Class AdminController
 * @package App\Controller
 */
class CartController extends Controller
{
    /**
     * @Route("/cart/getPopupCart", name="popup-cart")
     */
    public function getPopupCart(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new Exception('Product don\'t exist',404 );
        }
        return $this->render('popups/fastCart.html.twig');

    }


    /**
     * @Route("/cart/sentFormFastBuy", name="sent-form-fast-buy")
     */
    public function sentFormFastBuy(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new Exception('Product don\'t exist',404 );
        }

        return new Request(json_encode($request->request->get('data')));
    }
}

