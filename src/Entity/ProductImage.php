<?php

namespace App\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
/**
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class ProductImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="images")
     * @ORM\JoinColumn(name="product")
     */
    private $product;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $updatedAt;
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new DateTime('now');
        }
    }
    public function getParentPropertyName()
    {
        return 'product';
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new DateTime('now'));
    }
    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }
    public function getImageFile()
    {
        return $this->imageFile;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    public function __toString()
    {
        return (string) $this->getId();
    }
}
