<?php

namespace Impact\SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

/**
* @Route("/answer")
*/
class AnswerController extends Controller
{
    
    /**
     *
     * @Route("/survey/{id}/{program}/{survey}", name="answer_survey")
     * 
     * Parameters:
     * user id, program id, survey id
     * additional key parameter?
     */
    public function answerAction($id, $program, $survey)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ImpactUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        // @Todo, check if only once activated user!

        $passwd = $entity->generatePassword();
        $entity->setPassword($passwd);

        // @Todo salt! 

		$encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($passwd, $entity->getSalt());                      
        $entity->setPassword($password);

        $entity->setIsActive(1);

        $em->persist($entity);
        $em->flush();

        //passwd

        return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
    }

}
