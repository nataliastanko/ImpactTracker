<?php

namespace Impact\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity
 */
class Question
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=25)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Impact\SurveyBundle\Entity\Survey", inversedBy="questions", cascade={"persist"})
     * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
     */
    protected $survey; // question parent

    /**
     * @ORM\OneToMany(targetEntity="Impact\SurveyBundle\Entity\ProposedAnswer", mappedBy="question", cascade={"persist"})
     */
    protected $proposed_answers;

    /**
     * @ORM\OneToMany(targetEntity="Impact\SurveyBundle\Entity\UserAnswer", mappedBy="question", cascade={"persist"})
     */
    protected $user_answers;

    public function __construct(){
        $this->proposed_answers = new ArrayCollection();
        $this->user_answers = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setSurvey($survey)
    {
        $this->survey = $survey;
    
        return $this;
    }

    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Question
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Question
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
