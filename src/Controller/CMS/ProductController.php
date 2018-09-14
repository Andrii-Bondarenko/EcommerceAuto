<?php

namespace App\Controller\CMS;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Model;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Utils\ExportImportCSV;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Base class, extends Easy Admin.
 * Here we can change base functionality of Easy Admin module,
 * add and override base functions.
 *
 * Path prefix (/manager) define in config/routes.yaml for all actions in controller
 *
 * Class BaseAdminController
 * @package App\Controller
 */
class ProductController extends EasyAdminController
{
    public function importAction()
    {
        $parameters = [];
        $this->entity['templates']['import'] = 'easy_admin/Product/import.html.twig';
        return $this->executeDynamicMethod('render<EntityName>Template', array('import', $this->entity['templates']['import'], $parameters));
    }


    /**
     * @Route("/cms/Product/import", name="importProduct")
     */
    public function import(ObjectManager $manager)
    {
        if(!ExportImportCSV::checkFileFormat($_FILES['file']['name'])) {
            return new Response('Не правельный формат файла',500);
        }

        $data = ExportImportCSV::importCSV($_FILES['file']['tmp_name']);
        $dataProducts = ExportImportCSV::formatData($data);

        foreach ($dataProducts as $item) {
            /** @var Product $product*/
            $product = $this->getDoctrine()
                ->getRepository(Product::class)->findOneBy(['insideCode'=>trim($item['inside_code'])]);

            $category = $this->getDoctrine()
                ->getRepository(Category::class)->findOneBy(['name'=>trim($item['category'])]);

            if(empty($category)) {
                return new Response('Категории "'.$item['category'].'" не сущевствует! '.$item['inside_code'],500);
            }
            $brand ='';
            if(!empty($item['brand'])) {
                $brand  = $this->getDoctrine()
                    ->getRepository(Brand::class)->findOneBy(['name'=>trim($item['brand'])]);
                if(empty($brand)) {
                    return new Response('Бренда "'.$item['brand'].'" не сущевствует!',500);
                }
            }

            $allModels = [];
            if(!empty($item['models'])) {
                $models = explode(',',$item['models']);
                foreach ($models  as $modelItem) {
                    $modelItem = trim($modelItem);
                    $modelItem =  trim($modelItem," \xC2\xA0");
                    $model = $this->getDoctrine()
                        ->getRepository(Model::class)->findOneBy(['name'=>$modelItem]);
                    if(empty($model)) {
                        var_dump(trim($modelItem));
                        return new Response('Модели "'.$modelItem.'" не сущевствует! '.$item['inside_code'],500);
                    }
                    $allModels[] = $model;
                }
            }

            if(empty($item['price'])) {
                return new Response('Не указана цена для "'.$item['inside_code'].'"',500);
            }

            $item['price'] = (float)str_replace(',', '.', $item['price']);
            if(!is_numeric($item['price'])) {
                return new Response('Не верно указана цена для "'.$item['inside_code'].'"',500);
            }

            $item['price_action'] = (float)str_replace(',', '.', $item['price_action']);
            if(!empty($item['price_action']) && !is_numeric($item['price_action'])) {
                return new Response('Не верно указана акционная цена для "'.$item['inside_code'].'"',500);
            }
            if(empty($item['name'])) {
                return new Response('Не указана имя для "'.$item['inside_code'].'"',500);
            }

            if(!empty($product)) {
                $product->setName($item['name']);
                $product->setCategory($category);
                if (!empty($brand)) {
                    $product->setBrand($brand);
                }
                $modelsProduct = $product->getModels();
                foreach ($modelsProduct as $modelProduct) {
                    $product->removeModel($modelProduct);
                }

                if (!empty($allModels)) {
                    foreach ($allModels as $itemMod) {
                        $product->addModel($itemMod);
                    }
                }
                $product->setPrice(ceil($item['price']));
                $product->setPriceAction(ceil($item['price_action']));
                if(empty($item['new']) || $item['new']==0){
                    $product->setNew(false);
                }else{
                    $product->setNew(true);
                }
                $product->setDescription($item['description']);
                if(empty($item['active']) || $item['active']==0){
                    $product->setActive(false);
                }else{
                    $product->setActive(true);
                }
                $product->setAlias(mb_strtolower(substr($this->translit($item['name']),0,35)).'-'.$item['inside_code']);

                $product->setDescription($item['garanty']);
                $product->setCounry($item['country']);
                $product->setManufacturer($item['manufacturer']);
                $product->setCode($item['code']);
                $product->setFuture($item['future']);
////////////////////////////////////////////////////////////////////
            } else {
                $product = new Product();
                $product->setInsideCode($item['inside_code']);
                $product->setName($item['name']);
                $product->setCategory($category);

                if (!empty($brand)) {
                    $product->setBrand($brand);
                }

                if (!empty($allModels)) {
                    foreach ($allModels as $itemMod) {
                        $product->addModel($itemMod);
                    }
                }

                $product->setPrice(ceil($item['price']));
                $product->setPriceAction(ceil($item['price_action']));
                if(empty($item['new']) || $item['new']==0){
                    $product->setNew(false);
                }else{
                    $product->setNew(true);
                }
                $product->setDescription($item['description']);
                if(empty($item['active']) || $item['active']==0){
                    $product->setActive(false);
                }else{
                    $product->setActive(true);
                }



                $product->setAlias(mb_strtolower(substr($this->translit($item['name']),0,35)).'-'.$item['inside_code']);
                $product->setDescription($item['garanty']);
                $product->setCounry($item['country']);
                $product->setManufacturer($item['manufacturer']);
                $product->setCode($item['code']);
                $product->setFuture($item['future']);
            }
            $manager->persist($product);
            $manager->flush();

        }
        $manager->flush();
        foreach ($dataProducts as $item) {
            /** @var Product $productObj*/
            $productObj = $this->getDoctrine()
                ->getRepository(Product::class)->findOneBy(['insideCode'=>$item['inside_code']]);

            $oldImages = $productObj->getImages();
            if(!empty($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    $manager->remove($oldImage);
                }
            }
            if(!empty($item['image'])) {
                $imagesNames = explode(',', $item['image']);
                foreach ($imagesNames as $name) {
                    if(!empty($name)) {
                        $format = substr(trim($name), -3, 3);
                        if ($format != 'jpg' && $format != 'png' && $format != 'JPG' && $format != 'PNG') {
                            return new Response('Не верный формат картиник для "' . $item['inside_code'] . '"', 500);
                        }
                        if (empty($productObj)) {
                            return new Response('Ошибка продукта "' . $item['inside_code'] . '"', 500);
                        }
                        $image = new ProductImage();
                        $image->setProduct($productObj);
                        $image->setImage(trim($name));
                        $image->setUpdatedAt(new \DateTime('now'));
                        $manager->persist($image);
                    }
                }
            }
        }
        $manager->flush();
        return new Response('Успех',200);
    }

    public function importProducts($dataProducts,ObjectManager $manager)
    {

    }

    public function translit($str) {
        $rus = array(
            'А', 'Б', 'В', 'Г',
            'Д', 'Е', 'Ё', 'Ж',
            'З', 'И', 'Й', 'К',
            'Л', 'М', 'Н', 'О',
            'П', 'Р', 'С', 'Т',
            'У', 'Ф', 'Х', 'Ц',
            'Ч', 'Ш', 'Щ', 'Ъ',
            'Ы', 'Ь', 'Э', 'Ю',
            'Я', 'а', 'б', 'в',
            'г', 'д', 'е', 'ё',
            'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н',
            'о', 'п', 'р', 'с',
            'т', 'у', 'ф', 'х',
            'ц', 'ч', 'ш', 'щ',
            'ъ', 'ы', 'ь', 'э',
            'ю', 'я', ' ',',',
            '(',')','.','&');
        $lat = array(
            'a', 'b', 'v', 'g',
            'd', 'e', 'e', 'gh',
            'z', 'i', 'y', 'k',
            'l', 'm', 'n', 'o',
            'p', 'r', 's', 't',
            'u', 'f', 'h', 'c',
            'ch', 'sh','sch','y',
            'y', 'y', 'e', 'yu',
            'ya', 'a', 'b', 'v',
            'g', 'd', 'e', 'e',
            'gh', 'z', 'i', 'y',
            'k', 'l', 'm', 'n',
            'o', 'p', 'r', 's',
            't', 'u', 'f', 'h',
            'c', 'ch', 'sh', 'sch',
            'y', 'y', 'y', 'e',
            'yu', 'ya', '-', '-',
            '-','-','-','-');
        return str_replace($rus, $lat, $str);
    }
}

