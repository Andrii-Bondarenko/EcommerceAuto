<?php

namespace App\Controller\CMS;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
class BrandController extends EasyAdminController
{
    public function modelAction()
    {
        return $this->redirectToRoute('easyadmin', [
            'entity' => 'Model',
            'action' => 'list',
            'brand' => $this->request->query->get('id'),
        ]);
    }
}

