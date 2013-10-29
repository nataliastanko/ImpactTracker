<?php

namespace Impact\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProposedAnswer
 *
 * @ORM\Table(name="proposed_answers")
 * @ORM\Entity
 */
class ProposedAnswer
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
     * @ORM\ManyToOne(targetEntity="Impact\SurveyBundle\Entity\Question", inversedBy="proposed_answers", cascade={"persist"})
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question; // proposed answer parent

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return ProposedAnswer
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
}
