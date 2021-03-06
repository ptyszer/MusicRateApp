<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Artist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Album controller.
 *
 * @Route("album")
 */
class AlbumController extends Controller
{
    /**
     * Lists all album entities.
     *
     * @Route("/", name="album_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em->getRepository('AppBundle:Album')->findBy(['public' => 1]);

        return $this->render('album/index.html.twig', array(
            'albums' => $albums,
        ));
    }

    /**
     * Creates a new album entity.
     *
     * @Route("/new/{artistId}", name="album_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request, $artistId)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $artist = $this->getDoctrine()->getRepository(Artist::class)->findOneBy(['id'=>$artistId]);
        $album = new Album();
        $form = $this->createForm('AppBundle\Form\AlbumType', $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded image file
            /** @var UploadedFile $file */
            $file = $album->getImage();

            // upload the file to the directory where images are stored
            if($file){
                $result = $this->get('speicher210_cloudinary.uploader')->upload($file);
                $album->setImage($result['public_id']);
            }

            $album->setArtist($artist);
            $album->setAddedBy($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute('album_show', array('id' => $album->getId()));
        }

        return $this->render('album/new.html.twig', array(
            'album' => $album,
            'artist' => $artist,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a album entity.
     *
     * @Route("/{id}", name="album_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Album $album)
    {
        try {
            $this->denyAccessUnlessGranted('view', $album);
            return $this->render('album/show.html.twig', array('album' => $album));
        } catch (AccessDeniedException $e){
            return new Response($e->getMessage()) ;
        }
    }

    /**
     * Displays a form to edit an existing album entity.
     *
     * @Route("/{id}/edit", name="album_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction(Request $request, Album $album)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $deleteForm = $this->createDeleteForm($album);
        $editForm = $this->createForm('AppBundle\Form\AlbumType', $album);
        $editForm->handleRequest($request);
//        $isApproved = $request->get('approved') ? 1 : 0;

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // $file stores the uploaded image file
            /** @var UploadedFile $file */
            $file = $album->getImage();

            // upload the file to the directory where images are stored
            if($file){
                $result = $this->get('speicher210_cloudinary.uploader')->upload($file);
                $album->setImage($result['public_id']);
            }

            $date = new \DateTime();
            $album->setEditedBy($user);
            $album->setLastEdit($date);
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute('album_show', array('id' => $album->getId()));
        }

        try {
            $this->denyAccessUnlessGranted('edit', $album);
            return $this->render('album/edit.html.twig', array(
                'album' => $album,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        } catch (AccessDeniedException $e){
            return new Response($e->getMessage()) ;
        }
    }

    /**
     * Deletes a album entity.
     *
     * @Route("/{id}", name="album_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Album $album)
    {
        $form = $this->createDeleteForm($album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($album);
            $em->flush();
        }

        return $this->redirectToRoute('album_index');
    }

    /**
     * Set album entity as public.
     *
     * @Route("/{id}/set_public", name="album_set_public")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function setPublicAction(Album $album)
    {
        $album->setPublic();
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('album_show', array('id' => $album->getId()));
    }

    /**
     * Creates a form to delete a album entity.
     *
     * @param Album $album The album entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Album $album)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('album_delete', array('id' => $album->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
