<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Review;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Review controller.
 *
 * @Route("review")
 */
class ReviewController extends Controller
{
    /**
     * Creates a new review entity.
     *
     * @Route("/new/{album}/{user}", name="review_add")
     * @Method({"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function addAction(Album $album, User $user, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $review = $this->getDoctrine()->getRepository(Review::class)->findOneBy([
                'user' => $user->getID(),
                'album' => $album->getID()
            ]);
        }

        if(!isset($review)){
            $review = new Review();
        }
        $reviewForm = $this->createForm('AppBundle\Form\ReviewType', $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                return $this->redirectToRoute('fos_user_security_login');
            }
            $review->setAlbum($album);
            $review->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('album_show', array('id' => $album->getId()));
        }
    }
}
