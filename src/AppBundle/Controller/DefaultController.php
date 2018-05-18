<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
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
        $string = $request->get('search');
        $results = $this->getDoctrine()->getRepository(Album::class)->search($string);
        return $this->render('search_result.html.twig', array('albums' => $results));
    }
}
