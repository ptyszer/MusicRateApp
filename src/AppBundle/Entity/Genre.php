<?php

namespace AppBundle\Entity;

use AppBundle\Utils\ItemInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenreRepository")
 * @UniqueEntity("name")
 */
class Genre implements ItemInterface
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $description;

    /**
     * @var datetime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var datetime
     *
     * @ORM\Column(name="last_edit", type="datetime", nullable=true)
     */
    private $lastEdit;

    /**
     * @ORM\ManyToMany(targetEntity="Album", mappedBy="genres")
     */
    private $albums;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addedGenres")
     * @ORM\JoinColumn(name="added_by", referencedColumnName="id", onDelete="SET NULL")
     */
    private $addedBy;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="editedGenres")
     * @ORM\JoinColumn(name="edited_by", referencedColumnName="id", onDelete="SET NULL")
     */
    private $editedBy;

    /**
     * Genre constructor.
     */
    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->creationDate = new \DateTime();
        $this->public = false;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Genre
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Genre
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param bool $public
     */
    public function setPublic($public = true)
    {
        $this->public = $public;
    }

    /**
     * @return mixed
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return mixed
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * @param mixed $editedBy
     */
    public function setEditedBy($editedBy)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * @return DateTime
     */
    public function getLastEdit()
    {
        return $this->lastEdit;
    }

    /**
     * @param \DateTime $lastEdit
     */
    public function setLastEdit($lastEdit)
    {
        $this->lastEdit = $lastEdit;
    }

    /**
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlbums()
    {
        return $this->albums;
    }
}

