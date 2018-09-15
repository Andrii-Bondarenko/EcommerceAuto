<?php

namespace App\Twig;


use App\Entity\Exam;
use App\Entity\RoleCheckpoint;
use App\Entity\ScheduleCourse;
use App\Entity\ScheduleStudent;
use App\Entity\StudentExam;
use App\Entity\UniversityPerson;
use App\Entity\User;
use Psr\Container\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppExtension extends \Twig_Extension
{
    public $accessor;
    public $container;
    public $router;

    public function __construct(ContainerInterface $container,UrlGeneratorInterface $router)
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->container = $container;
        $this->router = $router;
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getCatalogUrl', array($this, 'getCatalogUrl')),
            new \Twig_SimpleFunction('getCatalogProductUrl', array($this, 'getCatalogProductUrl')),
            new \Twig_SimpleFunction('getImageForProductCatalog', array($this, 'getImageForProductCatalog')),
            new \Twig_SimpleFunction('getCropString', array($this, 'getCropString'))
        );
    }



    public function getCatalogUrl($item, $type)
    {
        switch ($type) {
            case 'catalog-brand':
                return $this->router->generate(
                    'catalog-brand',
                    array('alias' => $item->getAlias())
                );
            case 'catalog-model':
                return $this->router->generate(
                    'catalog-model',
                    array('alias' => $item->getBrand()->getAlias(),'model'=> $item->getAlias())
                );
        }
    }

    public function getCatalogProductUrl($item, $model)
    {

        return $this->router->generate(
            'catalog-product',
            array(
                'alias' => $model->getBrand()->getAlias(),
                'model'=> $model->getAlias(),
                'category'=>$item->getAlias()

        ));

    }

    public function getImageForProductCatalog($images)
    {
        $current = null;
        if(!empty($images)) {
            foreach ($images as $image) {
                $current = $image->getImage();
                break;
            }
        }
        if(empty($current)) {
            $current = 'no_image.jpg';
        }

        return '/img/products/'.$current;
    }

    public function getCropString($string, $length)
    {

        if(mb_strlen($string,'utf-8')>$length) {
            return mb_substr($string, 0, $length,'utf-8').'...';
        }else {
            return $string;
        }

    }

}