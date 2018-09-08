<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class CatalogController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $data['catalog']['name'] = 'Каталог автозапчастей';
        $data['catalog_addition']['name'] = 'Запчасти для ТО';

        $data['catalog_addition']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1,'showBottom'=>1]);

        $data['catalog']['items'] = $this->getDoctrine()
            ->getRepository(Brand::class)->findBy(['active'=>1]);
        $data['catalog_type'] = 'catalog-brand';

        return $this->render('pages/index/index.html.twig', ['data'=>$data]);
    }


    /**
     * @Route("/brand/{alias}", name="catalog-brand")
     */
    public function catalogBrand($alias)
    {

        $brand = $this->getDoctrine()
            ->getRepository(Brand::class)->findOneBy(['alias'=>$alias]);

        if (empty($brand)) { throw new Exception('Brand don\'t exist',404 );}

        $data['catalog']['name'] = 'Выберете модель '.$brand->getName();
        $data['catalog_addition']['name'] = 'Запчасти для ТО ('.$brand->getName().')';

        $data['catalog_addition']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1,'showBottom'=>1]);

        $data['catalog']['items'] = $this->getDoctrine()
            ->getRepository(Model::class)->findBy(['active'=>1, 'brand'=>$brand]);

        $currentItem['name'] = $brand->getName();
        $data['breadcrumbs'][] = $currentItem;
        $data['catalog_type'] = 'catalog-model';

        return $this->render('pages/catalog/index.html.twig', ['data'=>$data]);
    }


    /**
     * @Route("/brand/{alias}/{model}", name="catalog-model")
     */
    public function catalogModel($alias, $model)
    {

        $brand = $this->getDoctrine()
            ->getRepository(Brand::class)->findOneBy(['alias'=>$alias]);
        if (empty($brand)) { throw new Exception('Product don\'t exist',404 );}
        $model = $this->getDoctrine()
            ->getRepository(Model::class)->findOneBy(['alias'=>$model]);
        if (empty($model) || $model->getBrand()!==$brand) { throw new Exception('Product don\'t exist',404 );}


        $data['catalog']['name'] = 'Крупноузловой каталог  '.$brand->getName().' '.$model->getName();
        $data['catalog_addition']['name'] = 'Запчасти для ТО ('.$brand->getName().' '.$model->getName().')';

        $data['catalog_addition']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1,'showBottom'=>1]);

        $data['catalog']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1, 'showBottom'=>0]);

        $brandItem['name'] = $brand->getName();
        $brandItem['link'] = $this->generateUrl('catalog-brand', array('alias' => $brand->getAlias()));

        $data['breadcrumbs'][] = $brandItem;

        $currentItem['name'] = $model->getName();
        $data['breadcrumbs'][] = $currentItem;
        $data['catalog_type'] = 'sas';

        return $this->render('pages/catalog/index.html.twig', ['data'=>$data]);
    }

}

