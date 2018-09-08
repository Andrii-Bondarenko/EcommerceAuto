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
            new \Twig_SimpleFunction('getCatalogUrl', array($this, 'getCatalogUrl'))

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

}