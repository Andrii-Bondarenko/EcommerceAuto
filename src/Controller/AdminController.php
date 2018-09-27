<?php

namespace App\Controller;



use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Model;
use App\Entity\Product;
use Doctrine\DBAL\DBALException;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Content;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
class AdminController extends EasyAdminController
{

    /**
     * Génère le sitemap du site.
     *
     * @Route("/sitemap.{_format}", name="front_sitemap", Requirements={"_format" = "xml"})
     */
    public function sitemapAction(Request $request)
    {
        // We define an array of urls
        $urls = [];
        // We store the hostname of our website
        $hostname = $request->getHost();
        $urls[] = ['loc' => $this->get('router')->generate('about'), 'changefreq' => 'monthly', 'priority' => '0.8'];
        $urls[] = ['loc' => $this->get('router')->generate('payment'), 'changefreq' => 'monthly', 'priority' => '0.8'];
        $urls[] = ['loc' => $this->get('router')->generate('guarantee'), 'changefreq' => 'monthly', 'priority' => '0.8'];
        $brandRepository = $this->getDoctrine()->getRepository(Brand::class);
        $brands = $brandRepository->findAll();

        /**@var Brand $brand*/
        foreach ($brands as $brand) {
            $urls[] = ['loc' => $this->get('router')->generate('catalog-brand', ['alias' => $brand->getAlias()]), 'changefreq' => 'weekly', 'priority' => '0.8'];
        }

        $modelRepository = $this->getDoctrine()->getRepository(Model::class);
        $models = $modelRepository->findAll();
        /**@var Model $model*/
        foreach ($models as $model) {
            $urls[] = ['loc' => $this->get('router')->generate('catalog-model', ['alias' => $model->getBrand()->getAlias(),'model' => $model->getAlias()]), 'changefreq' => 'weekly', 'priority' => '0.7'];
        }

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->findBy(['active'=>1,'showBottom'=>0]);

        /**@var Category $category*/

        foreach ($categories as $category) {
            foreach ($models as $model) {
                $urls[] =
                 [
                    'loc' => $this->get('router')->generate(
                        'catalog-product',
                        [
                            'alias' => $model->getBrand()->getAlias(),
                            'model' => $model->getAlias(),
                            'category' => $category->getAlias()
                        ]
                    ),
                    'changefreq' => 'weekly',
                    'priority' => '0.7'
                ];
            }
        }

        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $products = $productRepository->findAll();

        foreach ($products as $product) {
            $urls[] = ['loc' => $this->get('router')->generate('product', ['alias' => $product->getAlias()]), 'changefreq' => 'weekly', 'priority' => '0.6'];
        }

        $response = new Response($this->renderView('website/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]));
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }


    /**
     * Génère le sitemap du site.
     *
     * @Route("/public/{page}", name="public", defaults={"page": 1},)
     */
    public function publicAction(Request $request)
    {
        throw new NotFoundHttpException('Sorry not existing!');
    }
}

