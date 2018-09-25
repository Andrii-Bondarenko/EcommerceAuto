<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/{alias}", name="product")
     */
    public function product($alias,SessionInterface $session)
    {

        $product = $this->getDoctrine()
            ->getRepository(Product::class)->findOneBy(['alias'=>$alias]);
        if (empty($product)) { throw new Exception('Product don\'t exist',404 );}


        $data['title'] = 'Купить '.$product->getName();
        $data['description'] = 'Купить '.$product->getName().' недорого - '.$product->getPrice().'грн. Запчасти для '.$product->getBrand().' в интернет-магазине с доставкой.';
        $data['keywords'] = $product->getName().', купить '.$product->getName();
        if($product->getCode()) {
            $data['title'].= ' ('.$product->getCode().')';
            $data['keywords'].= ', '.$product->getCode();
            $data['description'].= ' Код '.$product->getCode().'';
        }

        $data['product'] = $product;
        $data['breadcrumbs'] = $this->getBreadcrumbs($product,$session);

        return $this->render('pages/product/index.html.twig', ['data'=>$data]);
    }

    private function getBreadcrumbs($product,$session) {
        /**@var $product Product*/
        $breadcrumbs = [];
        $brand = $product->getBrand();
        $models = $product->getModels();
        if(!empty($brand) && !empty($models)) {
            $brandItem['name'] = $brand->getName();
            $brandItem['link'] = $this->generateUrl('catalog-brand', array('alias' => $brand->getAlias()));
            $breadcrumbs[] = $brandItem;

            if(!empty($session->get('model'))) {
                $model = $session->get('model');
                $modelItem['name'] = $model->getName();
                $modelItem['link'] = $this->generateUrl('catalog-model',
                    array(
                        'alias' => $brand->getAlias(),
                        'model'=>$model->getAlias())
                );;
                $breadcrumbs[] = $modelItem;
            } else {
                foreach ($models as $model) {
                    $modelItem['name'] = $model->getName();
                    $modelItem['link'] = $this->generateUrl('catalog-model',
                        array(
                            'alias' => $brand->getAlias(),
                            'model'=>$model->getAlias())
                    );;
                    $breadcrumbs[] = $modelItem;
                    break;
                }
            }
        }

        $category = $product->getCategory();
        if(!empty($category)) {
            $categoryItem['name'] = $category->getName();
            $categoryItem['link'] = $this->generateUrl('catalog-product',
                array(
                    'alias' => $brand->getAlias(),
                    'model'=>$model->getAlias(),
                    'category'=>$category->getAlias()
                )
            );
            $breadcrumbs[] = $categoryItem;
        }
        $currentItem['name'] = $product->getName();
        $breadcrumbs[] = $currentItem;
        return  $breadcrumbs;
    }
}

