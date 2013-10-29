<?php

namespace Impact\ProgramBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Program
 *
 * @ORM\Table(name="programs")
 * @ORM\Entity
 */
class Program
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Impact\UserBundle\Entity\User", inversedBy="program_involved", cascade={"persist"})
     * @ORM\JoinTable(name="programs_users",
     *      joinColumns={@ORM\JoinColumn(name="program_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $participants;

    /**
     * Constructs a new instance of Program
     */
    public function __construct() {
        $this->participants = new ArrayCollection();
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

    public function getParticipants(){
        return $this->participants;
    }
    
    public function setParticipants(ArrayCollection $participants) {
        foreach($participants as $p){
            $this->addParticipant($p);
        }
    }
    
    public function addParticipant(\Impact\UserBundle\Entity\User $u){
        $u->addProgramInvolved($this); // synchronously updating inverse side
        $this->participants[] = $u;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Program
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
     * Set description
     *
     * @param string $description
     * @return Program
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
