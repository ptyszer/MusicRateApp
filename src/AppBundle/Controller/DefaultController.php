<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Genre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
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
        return $this->render('search_result.html.twig', array(
            'artists' => $artists,
            'albums' => $albums,
            'genres' => $genres,
        ));
    }
}
