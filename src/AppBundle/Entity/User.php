<?php
namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="user")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="addedBy")
     */
    private $addedAlbums;

    /**
     * @ORM\OneToMany(targetEntity="Album", mappedBy="editedBy")
     */
    private $editedAlbums;

    /**
     * @ORM\OneToMany(targetEntity="Artist", mappedBy="addedBy")
     */
    private $addedArtists;

    /**
     * @ORM\OneToMany(targetEntity="Artist", mappedBy="editedBy")
     */
    private $editedArtists;

    /**
     * @ORM\OneToMany(targetEntity="Genre", mappedBy="addedBy")
     */
    private $addedGenres;

    /**
     * @ORM\OneToMany(targetEntity="Genre", mappedBy="editedBy")
     */
    private $editedGenres;

    public function __construct()
    {
        parent::__construct();

        $this->reviews = new ArrayCollection();
        $this->addedAlbums = new ArrayCollection();
        $this->addedArtists = new ArrayCollection();
        $this->addedGenres = new ArrayCollection();
        $this->editedAlbums = new ArrayCollection();
        $this->editedArtists = new ArrayCollection();
        $this->editedGenres = new ArrayCollection();
    }

    /**
     * Add review
     *
     * @param \AppBundle\Entity\Review $review
     *
     * @return User
     */
    public function addReview(\AppBundle\Entity\Review $review)
    {
        $this->reviews[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \AppBundle\Entity\Review $review
     */
    public function removeReview(\AppBundle\Entity\Review $review)
    {
        $this->reviews->removeElement($review);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @return mixed
     */
    public function getAddedAlbums()
    {
        return $this->addedAlbums;
    }

    /**
     * @return mixed
     */
    public function getEditedAlbums()
    {
        return $this->editedAlbums;
    }

}
