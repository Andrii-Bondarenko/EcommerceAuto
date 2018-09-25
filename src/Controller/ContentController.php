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

    /**
     * @Route("/payment", name="payment")
     */
    public function payment()
    {
        $data['title'] = 'Оплата заказа в интернет-магазин Part-Store.';
        $data['description'] = 'Мы предлагаем большой выбор вариантов оплаты Вашего заказа. Звоните и заказывайте запчасти для авто. Отличные цены, доставка по Украине. ';
        $data['keywords'] = 'оплата, оплата запчастей';

        $currentItem['name'] = "Оплата";
        $data['breadcrumbs'][] = $currentItem;
        return $this->render('pages/content/payment.html.twig', ['data'=>$data]);
    }

    /**
     * @Route("/guarantee", name="guarantee")
     */
    public function guarantee()
    {
        $data['title'] = 'Гарантии - интернет-магазин Part-Store.';
        $data['description'] = 'Мы предлагаем своевременное гарантийное обслуживание для наших клиентов. Заказывайте запчасти в интернет-магазине Part-Store. Лучшие цены в Украине, быстрая доставка по Украине.  ';
        $data['keywords'] = 'гарантии';

        $currentItem['name'] = "Гарантии";
        $data['breadcrumbs'][] = $currentItem;
        return $this->render('pages/content/guarantee.html.twig', ['data'=>$data]);
    }
}

