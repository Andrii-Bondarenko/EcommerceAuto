<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            throw new NotFoundHttpException('Sorry not existing!');
        }

        return $this->render('popups/fastCart.html.twig',['id'=>$request->request->get('id')]);

    }


    /**
     * @Route("/cart/sentFormFastBuy", name="sent-form-fast-buy")
     */
    public function sentFormFastBuy(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException('Sorry not existing!');
        }

        $data = $request->request->get('data');
        $response = [];
        $response['name'] = [];
        $response['phone'] = [];
        if(empty($data['name']) || empty($data['phone'])) {
            if(empty($data['name'])) {
                $response['name']['message'] = 'Поле Имя не может быть пустым полем';
                $response['status'] = false;
            }
            if(empty($data['phone'])) {
                $response['phone']['message'] = 'Поле Телефон не может быть пустым полем';
                $response['status'] = false;
            }

        } else {
            $productName = '';
            $productCode = '';
            if(!empty($data['id'])) {
                /** @var Product $product*/
                $product = $this->getDoctrine()->getRepository(Product::class)->find($data['id']);
                $productName = $product->getName();
                $productCode = $product->getInsideCode();
            }

            $message = (new \Swift_Message('Заказ'))
                ->setFrom('work.vereika@gmail.com')
                ->setTo('work.vereika@gmail.com')
                ->setBody( $this->renderView(
                    'emails/feedback.html.twig',
                    array(
                        'name' => $data['name'],
                        'phone' => $data['phone'],
                        'comment' => $data['comment'],
                        'product' => $productName,
                        'code' => $productCode,
                    )
                ),'text/html');

            $this->get('mailer')->send($message);
            $response['status'] = true;
        }
        return new Response(json_encode($response));

    }
}

