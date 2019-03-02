<?php

namespace Stage\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Joueur
 *
 * @Vich\Uploadable
 * @ORM\Table(name="joueur")
 * @ORM\Entity(repositoryClass="Stage\AdminBundle\Repository\JoueurRepository")
 */
class Joueur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datenaiss", type="date")
     */
    private $datenaiss;

    /**
     * @ORM\ManyToOne(targetEntity="Stage\AdminBundle\Entity\Pays")
     */
    public $nomPays;

    /**
     * @ORM\ManyToOne(targetEntity="Stage\AdminBundle\Entity\Federation")
     */
    public $nomFederation;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;


    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Joueur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     *
     * @return Joueur
     */
    public function setDatenaiss($datenaiss)
    {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime
     */
    public function getDatenaiss()
    {
        return $this->datenaiss;
    }

    /**
     * @return mixed
     */
    public function getNomPays()
    {
        return $this->nomPays;
    }

    /**
     * @param mixed $nomPays
     */
    public function setNomPays($nomPays)
    {
        $this->nomPays = $nomPays;
    }

    public function getAge()
    {
        $interval= $this->datenaiss->diff(new \DateTime());
        return $interval->y;
    }

    /**
     * @return mixed
     */
    public function getNomFederation()
    {
        return $this->nomFederation;
    }

    /**
     * @param mixed $nomFederation
     */
    public function setNomFederation($nomFederation)
    {
        $this->nomFederation = $nomFederation;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;


    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}

