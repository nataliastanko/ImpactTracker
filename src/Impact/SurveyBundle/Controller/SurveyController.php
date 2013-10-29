<?php

namespace Impact\SurveyBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Impact\SurveyBundle\Entity\Survey;
use Impact\SurveyBundle\Form\SurveyType;
use SendGrid;

/**
 * Survey controller.
 *
 * @Route("/survey")
 */
class SurveyController extends Controller
{

    /**
     * Lists all Survey entities.
     *
     * @Route("/", name="survey")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ImpactSurveyBundle:Survey')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Survey entity.
     *
     * @Route("/", name="survey_create")
     * @Method("POST")
     * @Template("ImpactSurveyBundle:Survey:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Survey();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('survey_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Survey entity.
    *
    * @param Survey $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Survey $entity)
    {
        $form = $this->createForm(new SurveyType(), $entity, array(
            'action' => $this->generateUrl('survey_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Survey entity.
     *
     * @Route("/new", name="survey_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Survey();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Survey entity.
     *
     * @Route("/{id}", name="survey_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Sends the survey
     *
     * @Route("/send/program/{program_id}", name="survey_send")
     * @Method("POST")
     * @Template()
     */
    public function sendAction($program_id, Request $request)
    {

        // create sendgrid 
        $id = $request->get('suvery_id');

        $em = $this->getDoctrine()->getManager();
        $survey = $em->getRepository('ImpactSurveyBundle:Survey')->find($id);

        if (!$survey) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $program = $em->getRepository('ImpactProgramBundle:Program')->find($program_id);


        // iterate throug users and send
        $sendgrid = new SendGrid($this->container->parameters['sendgrid_username'], $this->container->parameters['sendgrid_password']);

        
        $templateFile = "ImpactSurveyBundle:Email:survey.html.twig";


        foreach ($program->getParticipants() as $user) {
            $mail = new SendGrid\Mail(); 
            
            $params = array(
                'user' => $user, 
                'program' => $program, 
                'survey' => $survey
            );

            $body = $this->renderView($templateFile, $params);

            // send test email
            $mail->addTo($user->getEmail());
            $mail->setFrom('impacttracker.com');
            $mail->setSubject('Survey for '. $program->getTitle());
            $mail->setHtml($body);

            $sendgrid->web->send($mail);
        }

        

        return array(
            'entity'      => $survey,
        );
    }


    /**
     * Displays a form to edit an existing Survey entity.
     *
     * @Route("/{id}/edit", name="survey_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
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
    * Creates a form to edit a Survey entity.
    *
    * @param Survey $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Survey $entity)
    {
        $form = $this->createForm(new SurveyType(), $entity, array(
            'action' => $this->generateUrl('survey_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Survey entity.
     *
     * @Route("/{id}", name="survey_update")
     * @Method("PUT")
     * @Template("ImpactSurveyBundle:Survey:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactSurveyBundle:Survey')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Survey entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('survey_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Survey entity.
     *
     * @Route("/{id}", name="survey_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ImpactSurveyBundle:Survey')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Survey entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('survey'));
    }

    /**
     * Creates a form to delete a Survey entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('survey_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
