<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Artist controller.
 *
 * @Route("artist")
 */
class ArtistController extends Controller
{
    /**
     * Lists all artist entities.
     *
     * @Route("/", name="artist_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->findBy(['public' => 1]);

        return $this->render('artist/index.html.twig', array(
            'artists' => $artists,
        ));
    }

    /**
     * Creates a new artist entity.
     *
     * @Route("/new", name="artist_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $artist = new Artist();
        $form = $this->createForm('AppBundle\Form\ArtistType', $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $file stores the uploaded image file
            /** @var UploadedFile $file */
            $file = $artist->getImage();

            // upload the file to the directory where images are stored
            if($file){
                $result = $this->get('speicher210_cloudinary.uploader')->upload($file);
                $artist->setImage($result['public_id']);
            }

            $artist->setAddedBy($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            return $this->redirectToRoute('artist_show', array('id' => $artist->getId()));
        }

        return $this->render('artist/new.html.twig', array(
            'artist' => $artist,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a artist entity.
     *
     * @Route("/{id}", name="artist_show")
     * @Method("GET")
     */
    public function showAction(Artist $artist)
    {
        try {
            $this->denyAccessUnlessGranted('view', $artist);
            return $this->render('artist/show.html.twig', array(
                'artist' => $artist
            ));
        } catch (AccessDeniedException $e){
            return new Response($e->getMessage()) ;
        }
    }

    //todo - fix image update
    /**
     * Displays a form to edit an existing artist entity.
     *
     * @Route("/{id}/edit", name="artist_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Artist $artist)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $deleteForm = $this->createDeleteForm($artist);
        $editForm = $this->createForm('AppBundle\Form\ArtistType', $artist);
        $editForm->handleRequest($request);
//        $isApproved = $request->get('approved') ? 1 : 0;

//        if($artist->getImage()){
//
//        }
//
//        $image = $artist->getImage();
//        $imagePath = cloudinary_url($image);
//
//        dump($editForm);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            // $file stores the uploaded image file
            /** @var UploadedFile $file */
            $file = $artist->getImage();

            // upload the file to the directory where images are stored
            if($file){
                $result = $this->get('speicher210_cloudinary.uploader')->upload($file);
                $artist->setImage($result['public_id']);
            }

            $date = new \DateTime();
            $artist->setEditedBy($user);
            $artist->setLastEdit($date);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artist_show', array('id' => $artist->getId()));
        }

        try {
            $this->denyAccessUnlessGranted('edit', $artist);
            return $this->render('artist/edit.html.twig', array(
                'artist' => $artist,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        } catch (AccessDeniedException $e){
            return new Response($e->getMessage()) ;
        }
    }

    /**
     * Deletes a artist entity.
     *
     * @Route("/{id}", name="artist_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Artist $artist)
    {
        $form = $this->createDeleteForm($artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($artist);
            $em->flush();
        }

        return $this->redirectToRoute('artist_index');
    }

    /**
     * Set artist entity as public.
     *
     * @Route("/{id}/set_public", name="artist_set_public")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function setPublicAction(Artist $artist)
    {
        $artist->setPublic();
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('artist_show', array('id' => $artist->getId()));
    }

    /**
     * Creates a form to delete a artist entity.
     *
     * @param Artist $artist The artist entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Artist $artist)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('artist_delete', array('id' => $artist->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
