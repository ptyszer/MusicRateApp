<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="homepage")
     */
    public function indexAction()
    {
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findLatest();
//        dump($reviews);die();
        return $this->render('main/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'reviews' => $reviews
        ]);
    }

    /**
     * @Route("/", methods={"POST"}, name="search")
     */
    public function searchAction(Request $request)
    {
        $phrase = $request->get('search');
        $artists = $this->getDoctrine()->getRepository(Artist::class)->findByName($phrase);
        $albums = $this->getDoctrine()->getRepository(Album::class)->findByName($phrase);
        $genres = $this->getDoctrine()->getRepository(Genre::class)->findByName($phrase);
        return $this->render('main/search_result.html.twig', array(
            'artists' => $artists,
            'albums' => $albums,
            'genres' => $genres,
        ));
    }

    /**
     * @Route("/my_page", name="my_page")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function myPageAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $artists = $em->getRepository('AppBundle:Artist')->findBy(['addedBy' => $user]);
        $albums = $em->getRepository('AppBundle:Album')->findBy(['addedBy' => $user]);
        $genres = $em->getRepository('AppBundle:Genre')->findBy(['addedBy' => $user]);
        return $this->render('main/user_page.html.twig', array(
            'artists' => $artists,
            'albums' => $albums,
            'genres' => $genres,
        ));
    }
}
