<?php

namespace Impact\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Impact\UserBundle\Entity\User;
use Impact\UserBundle\Form\UserType;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use CCC\LinkedinImporterBundle\Form as LiForm;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ImpactUserBundle:User')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all User entities.
     *
     * @Route("/linkedin", name="user_linkedin")
     * @Method("GET")
     * @Template()
     */
    public function linkedinAction()
    {
        $importer = $this->get('ccc_linkedin_importer.importer');
        $importer->setRedirect($this
            ->generateUrl('impact_ccc_linkedin_importer_receivePrivate', array('submit'=>true), true));
        // $importer->setRedirect($this
        //     ->generateUrl('ccc_linkedin_importer_receivePrivate', array('submit'=>true), true));

        //nothing on this form except for a submit button to start the process
        $form = $this->createForm(new LiForm\RequestPrivate());
        $request = $this->getRequest();
        
        if($request->isMethod('POST')) {

                $form->handleRequest($request);
                
                if($form->isValid()) {
                        //user hit the start button, so request permission from the user to allow your app on their linkedin account
                        //this will redirect to linkedin's site 
                        return $importer->requestPermission();
                }
                
        }

        // $access_token = $importer->setCode($code_retrived_from_permission_request)->requestAccessToken();
        // $profile_data = $importer->requestUserData('private', $access_token);

        exit;
        return $this->render('CCCLinkedinImporterBundle:Default:requestPrivate.html.twig', array('form' => $form->createView()));

        return array();
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="user_create")
     * @Method("POST")
     * @Template("ImpactUserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);





        // //load up the importer class
        // $importer   = $this->get('linkedin.importer');
        
        // //set a redirect for linkedin to bump the user back to after they approve your app
        // $importer->setRedirect($this->generateUrl('ccc_linkedin_importer_receivePrivate', array('submit'=>true), true));
        
        //nothing on this form except for a submit button to start the process
        $form       = $this->createForm(new LiForm\RequestPrivate());
        $request    = $this->getRequest();
        
        // if($request->isMethod('POST')) {

        //     $form->handleRequest($request);
            
        //     if($form->isValid()) {
        //         //user hit the start button, so request permission from the user to allow your app on their linkedin account
        //         //this will redirect to linkedin's site 
        //         return $importer->requestPermission();
        //     }
            
        // }


        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="user_update")
     * @Method("PUT")
     * @Template("ImpactUserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ImpactUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
