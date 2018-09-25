<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Model;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
        $data['title'] = 'Купить автозапчасти недорого производителя ВАЗ, ГАЗ | +38 (063) 413-73-79';
        $data['description'] = 'Интернет магазин автозапчастей Part-Store: недорогие запчасти от производителей ВАЗ, ГАЗ, ЗАЗ.';
        $data['keywords'] = 'Интернет магазин автозапчастей, автозапчасти для ВАЗ, автозапчасти для ГАЗ, автозапчасти дешево, автозапчасти Украина';
        //meta//
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

        $data['title'] = 'Автозапчасти '.$brand->getName().', купить автозапчасти для '.$brand->getName();
        $data['description'] = 'Автозапчасти '.$brand->getName().' от магазина Part-Store! Запчасти для всех моделей '.$brand->getName().' и других брендов, высокое качество и доступные цены! Спешите заказать запчасть! ☎(063)4137379. ';
        $data['keywords'] = 'автозапчасти для '.$brand->getName().', интернет-магазин '.$brand->getName().', купить автозапчасти '.$brand->getName();

        $data['catalog']['name'] = 'Выберите модель '.$brand->getName();
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
     * @Route("/brand/{alias}/{model}/{page}", name="catalog-model", requirements={"page": "\d+"}, defaults={"page": 1})
     */
    public function catalogModel($alias, $model,$page)
    {

        $brand = $this->getDoctrine()
            ->getRepository(Brand::class)->findOneBy(['alias'=>$alias]);
        if (empty($brand)) { throw new Exception('Product don\'t exist',404 );}
        $model = $this->getDoctrine()
            ->getRepository(Model::class)->findOneBy(['alias'=>$model]);
        if (empty($model) || $model->getBrand()!==$brand) { throw new Exception('Product don\'t exist',404 );}
        if (!empty($page) && $page>1) {
            $data['noindex'] = '';
        }
        $data['title'] = 'Запчасти для '.$model->getName().', купить автозапчасти для '.$model->getName();
        $data['description'] = 'Автозапчасти '.$model->getName().' от магазина Part-Store! Запчасти для '.$model->getName().' и других моделей '.$brand->getName().', низкие цены и быстрая доставка! Спешите заказать запчасть! ☎(063)4137379. ';
        $data['keywords'] = 'автозапчасти для '.$model->getName().', интернет-магазин '.$model->getName().', купить автозапчасти '.$model->getName();

        $data['model'] = $model;
        $data['catalog']['name'] = 'Крупноузловой каталог '.$model->getName();
        $data['catalog_addition']['name'] = 'Все запчасти для ('.$model->getName().')';

        $data['catalog']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1, 'showBottom'=>0]);

        $data['pager'] = $this->getPagerCatalogModel($brand, $model,$page);

        $brandItem['name'] = $brand->getName();
        $brandItem['link'] = $this->generateUrl('catalog-brand', array('alias' => $brand->getAlias()));

        $data['breadcrumbs'][] = $brandItem;

        $currentItem['name'] = $model->getName();
        $data['breadcrumbs'][] = $currentItem;
        $data['catalog_type'] = 'catalog-product';

        return $this->render('pages/catalog/indexWithProduct.html.twig', ['data'=>$data]);
    }

    /**
     * @Route("/brand/{alias}/{model}/{category}/{page}", name="catalog-product",  requirements={"page": "\d+"}, defaults={"page": 1})
     */
    public function catalogProduct($alias, $model, $category,$page,SessionInterface $session)
    {

        $brand = $this->getDoctrine()
            ->getRepository(Brand::class)->findOneBy(['alias'=>$alias]);
        if (empty($brand)) { throw new Exception('Product don\'t exist',404 );}

        $model = $this->getDoctrine()
            ->getRepository(Model::class)->findOneBy(['alias'=>$model]);
        if (empty($model) || $model->getBrand()!==$brand) { throw new Exception('Product don\'t exist',404 );}

        $session->set('model',$model);
        $category = $this->getDoctrine()
            ->getRepository(Category::class)->findOneBy(['alias'=>$category]);
        if (empty($category)) { throw new Exception('Product don\'t exist',404 );}
        if (!empty($page) && $page>1) {
            $data['noindex'] = '';
        }
        $data['title'] = 'Запчасти для '.$model->getName().' '.$category->getName().', купить автозапчасти для '.$model->getName() .' '.$category->getName();
        $data['description'] = 'Запчасти '.$model->getName().' '.$category->getName().' от магазина Part-Store! Запчасти '.$category->getName().' для '.$model->getName().' и других моделей '.$brand->getName().', низкие цены и быстрая доставка! Спешите заказать запчасть! ☎(063)4137379. ';
        $data['keywords'] = 'автозапчасти для '.$model->getName().' '.$category->getName().', интернет-магазин '.$model->getName().' '.$category->getName().', купить автозапчасти '.$model->getName().' '.$category->getName();

        $data['catalog']['name'] = 'Каталог '.$model->getName(). ' ('.$category->getName().')';
        $data['catalog_addition']['name'] = 'Запчасти для ТО ('.$brand->getName().' '.$model->getName().')';
        $data['catalog_addition']['items'] = $this->getDoctrine()
            ->getRepository(Category::class)->findBy(['active'=>1,'showBottom'=>1]);


        $data['pager'] = $this->getPagerCatalogProduct($brand, $model, $category,$page);
        $data['breadcrumbs'] = $this->getBreadcrumbsCatalogProduct($brand,$model,$category);

        return $this->render('pages/catalog/items.html.twig', ['data'=>$data]);
    }

    private function getPagerCatalogProduct($brand,$model,$category,$page) {
        /** @var $qb QueryBuilder*/
        $qb = $this->getDoctrine()->getManager()->getRepository(Product::class)
            ->createQueryBuilder("product");

        $qb->andWhere('product.category = :category')->setParameter('category',$category->getId());
        $qb->andWhere('product.brand = :brand')->setParameter('brand',$brand->getId());
        $qb->leftJoin('product.models', 'm');
        $qb->andWhere('m.id=:model')->setParameter('model',$model->getId());
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }

    private function getPagerCatalogModel($brand,$model,$page) {
        /** @var $qb QueryBuilder*/
        $qb = $this->getDoctrine()->getManager()->getRepository(Product::class)
            ->createQueryBuilder("product");

        $qb->andWhere('product.brand = :brand')->setParameter('brand',$brand->getId());
        $qb->leftJoin('product.models', 'm');
        $qb->andWhere('m.id=:model')->setParameter('model',$model->getId());
        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }

    private function getBreadcrumbsCatalogProduct($brand,$model,$category)
    {
        $breadcrumbs = [];
        $brandItem['name'] = $brand->getName();
        $brandItem['link'] = $this->generateUrl('catalog-brand', array('alias' => $brand->getAlias()));

        $modelItem['name'] = $model->getName();
        $modelItem['link'] = $this->generateUrl('catalog-model',
            array(
                'alias' => $brand->getAlias(),
                'model'=>$model->getAlias())
        );
        $breadcrumbs[] = $brandItem;
        $breadcrumbs[] = $modelItem;

        $currentItem['name'] = $category->getName();
        $breadcrumbs[] = $currentItem;
        return $breadcrumbs;
    }



    /**
     * @Route("/search/{page}", name="search",  requirements={"page": "\d+"}, defaults={"page": 1})
     */
    public function search($page, Request $request)
    {
        $search = trim($request->query->get('search'));
        $data['catalog']['name'] = 'Поиск "'.$search.'"';
        $data['pager'] = $this->getPagerSearch($page,$search);

        $data['noindex'] = '';
        $data['title'] = 'Поиск '.'"'.$search.'"';
        $data['description'] = 'Запчасти для авто от магазина Part-Store! Низкие цены и быстрая доставка! Спешите заказать запчасть! ☎(063)4137379. ';
        $data['keywords'] = 'автозапчасти, интернет-магазин запчастей';


        $currentItem['name'] = "Поиск";
        $data['breadcrumbs'][] = $currentItem;
        $data['search'] = $search;
        return $this->render('pages/catalog/items.html.twig', ['data'=>$data]);
    }

    private function getPagerSearch($page, $search) {
        /** @var $qb QueryBuilder*/
        $qb = $this->getDoctrine()->getManager()->getRepository(Product::class)
            ->createQueryBuilder("product");

        $qb->having( 'product.name LIKE :query')
            ->setParameter('query', '%' . $search . '%');

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage(12);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }

}

