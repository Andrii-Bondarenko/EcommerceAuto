<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductImage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductImageEmbeddedForm extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductImage::class,
        ]);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.id = :id')->setParameter('id', $_GET['id'])
                        ;
                },
                'attr' => array('style' => 'display:none;'),
                'label'=>'Image'
            ])
            ->add('imageFile', VichImageType::class, [])
        ;
    }
    public function onPostSetData(FormEvent $event)
    {
    }
}