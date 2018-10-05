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
class FeedbackController extends Controller
{
    /**
     * @Route("/feedback/sentPhone", name="feedback-help")
     */
    public function getPopupCart(Request $request)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException('Sorry not existing!');
        }

        $data = $request->request->get('data');
        if(empty($data['phone'])) {
            $response['phone']['message'] = 'Поле Телефон не может быть пустым';
            $response['status'] = false;
        } else {
            $message = (new \Swift_Message('Не нашли запчасть'))
                ->setFrom('work.vereika@gmail.com')
                ->setTo('work.vereika@gmail.com')
                ->setBody( $this->renderView(
                    'emails/feedbackHelp.html.twig',
                    array(
                        'phone' => $data['phone'],
                    )
                ),'text/html');

            $this->get('mailer')->send($message);
            $response['status'] = true;
        }

        return new Response(json_encode($response));
    }

}

