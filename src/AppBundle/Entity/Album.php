<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Album
 *
 * @ORM\Table(name="album")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlbumRepository")
 */
class Album
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="tracklist", type="array", nullable=true)
     */
    private $tracklist;

    /**
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="albums")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;

    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="albums")
     * @ORM\JoinTable(name="album_genre")
     */
    private $genres;

    /**
     * Album constructor.
     */
    public function __construct()
    {
        $this->genres = new ArrayCollection();
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
     * @return Album
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
     * @return Album
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
     * Set tracklist
     *
     * @param array $tracklist
     *
     * @return Album
     */
    public function setTracklist($tracklist)
    {
        $this->tracklist = $tracklist;

        return $this;
    }

    /**
     * Get tracklist
     *
     * @return array
     */
    public function getTracklist()
    {
        return $this->tracklist;
    }

    /**
     * Set artist
     *
     * @param \AppBundle\Entity\Artist $artist
     *
     * @return Album
     */
    public function setArtist(\AppBundle\Entity\Artist $artist = null)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return \AppBundle\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
