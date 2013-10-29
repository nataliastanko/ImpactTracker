<?php

namespace Impact\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Survey
 *
 * @ORM\Table(name="surveys")
 * @ORM\Entity
 */
class Survey
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="email_content", type="text")
     */
    private $email_content;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Impact\SurveyBundle\Entity\Question", mappedBy="survey", cascade={"persist"})
     */
    protected $questions;

    public function __construct(){
        $this->questions = new ArrayCollection();
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

    /**
     * Set title
     *
     * @param string $title
     * @return Survey
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set email_content
     *
     * @param string $emailContent
     * @return Survey
     */
    public function setEmailContent($emailContent)
    {
        $this->email_content = $emailContent;
    
        return $this;
    }

    /**
     * Get email_content
     *
     * @return string 
     */
    public function getEmailContent()
    {
        return $this->email_content;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Survey
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
