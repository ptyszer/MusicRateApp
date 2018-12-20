<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->findBy(['public' => 0]);
        $albums = $em->getRepository('AppBundle:Album')->findBy(['public' => 0]);
        $genres = $em->getRepository('AppBundle:Genre')->findBy(['public' => 0]);
        return $this->render('admin/admin.html.twig', array(
            'artists' => $artists,
            'albums' => $albums,
            'genres' => $genres,
        ));
    }

}
