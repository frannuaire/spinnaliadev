<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity
 */
class Report
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="value", type="string", length=45, nullable=true)
     */
    private $value;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Websites", inversedBy="rapport")
     * @ORM\JoinTable(name="report_done",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rapport_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="websites_id", referencedColumnName="id"),
     *     @ORM\JoinColumn(name="websites_users_id", referencedColumnName="users_id")
     *   }
     * )
     */
    private $websites;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->websites = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getId() {
        return $this->id;
    }

    public function getValue() {
        return $this->value;
    }

    public function getWebsites(): \Doctrine\Common\Collections\Collection {
        return $this->websites;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function setWebsites(\Doctrine\Common\Collections\Collection $websites) {
        $this->websites = $websites;
    }


}
