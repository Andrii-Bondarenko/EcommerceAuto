<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(options={"collate":"utf8_general_ci", "charset":"utf8", "engine":"MyISAM"})
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Поле Name не может быть пустым")
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Title must be at least {{ limit }} characters long",
     *      maxMessage = "Title cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     *
     *
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Alias must be at least {{ limit }} characters long",
     *      maxMessage = "Alias cannot be longer than {{ limit }} characters"
     * )
     *
     * @Assert\Regex(
     *     pattern="/^[0-9a-z-]+$/",
     *     match=true,
     *     message="Алиас может иметь только маленькие латинские буквы или дефис"
     * )
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $alias;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceAction;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active=true;

    /**
     * @Assert\NotBlank(message="Brand cannot be null")
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id", nullable=false)
     */
    private $brand;

    /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="Model")
     * @JoinTable(name="products_models",
     *      joinColumns={@JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="model_id", referencedColumnName="id")}
     *      )
     */
    private $models;

    /**
     * @Assert\NotBlank(message="Category cannot be null")
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $garanty;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $counry;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=false)
     */
    private $insideCode;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $future;

    /**
     * @ORM\Column(type="boolean")
     */
    private $new=false;

    /**
     * @ORM\OneToMany(targetEntity="ProductImage", mappedBy="product", cascade={"all"})
     */
    private $images;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;
    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }


    public function __construct() {
        $this->models = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice( $price)
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceAction()
    {
        return $this->priceAction;
    }

    public function setPriceAction($priceAction)
    {
        $this->priceAction = $priceAction;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive( $active)
    {
        $this->active = $active;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModels()
    {
        return $this->models;
    }

    public function addModel(Model $model)
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
        }
        return $this;
    }

    public function removeModel(Model $model)
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model) ;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getGaranty()
    {
        return $this->garanty;
    }

    /**
     * @param mixed $garanty
     */
    public function setGaranty($garanty)
    {
        $this->garanty = $garanty;
    }

    /**
     * @return mixed
     */
    public function getCounry()
    {
        return $this->counry;
    }

    /**
     * @param mixed $counry
     */
    public function setCounry($counry)
    {
        $this->counry = $counry;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @param mixed $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return mixed
     */
    public function getInsideCode()
    {
        return $this->insideCode;
    }

    /**
     * @param mixed $insideCode
     */
    public function setInsideCode($insideCode)
    {
        $this->insideCode = $insideCode;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getNew()
    {
        return $this->new;
    }

    /**
     * @param mixed $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getFuture()
    {
        return $this->future;
    }

    /**
     * @param mixed $future
     */
    public function setFuture($future)
    {
        $this->future = $future;
    }

    function __toString()
    {
       return $this->name;
    }


}
