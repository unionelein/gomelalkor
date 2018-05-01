<?php

namespace AppBundle\Entity;

use AppBundle\Services\AppManager;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     * @Assert\Length(
     *      max = 45,
     *      maxMessage = "Название должно быть не больше 45 символов"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(
     *      max = 100000,
     *      maxMessage = "Максимальная цена - 100000"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "Короткое описание должно быть не больше 150 символов"
     * )
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imgBig;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imgMin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $type;

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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->imgBig ? new File('images/'.$this->imgBig) : null;
    }

    /**
     * @param mixed $file
     */
    public function setImg($file)
    {
        if (!$file) {
            return;
        }

        $imgName = AppManager::saveImg($file);

        $this->imgBig = 'big-'.$imgName;
        $this->imgMin = 'min-'.$imgName;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getTypeName()
    {
        switch ($this->type) {
            case AppManager::TYPE[AppManager::POKYPKA]: return 'Стройматериалы';
            case AppManager::TYPE[AppManager::PROKAT]:  return 'Инструменты';
            default: return 'Не определен';
        }
    }

    /**
     * @return mixed
     */
    public function getImgBig()
    {
        return $this->imgBig;
    }

    /**
     * @param mixed $imgBig
     */
    public function setImgBig($imgBig)
    {
        $this->imgBig = $imgBig;
    }

    /**
     * @return mixed
     */
    public function getImgMin()
    {
        return $this->imgMin;
    }

    /**
     * @param mixed $imgMin
     */
    public function setImgMin($imgMin)
    {
        $this->imgMin = $imgMin;
    }

    /**
     * @return mixed
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param mixed $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }
}
