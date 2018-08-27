<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Websites
 *
 * @ORM\Table(name="websites", uniqueConstraints={@ORM\UniqueConstraint(name="url_UNIQUE", columns={"url"})}, indexes={@ORM\Index(name="fk_websites_users_idx", columns={"users_id"}), @ORM\Index(name="fk_websites_Category1_idx", columns={"Category_id"})})
 * @ORM\Entity
 */
class Websites
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Users
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Users", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="users_id", referencedColumnName="id")
     * })
     */
    private $users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Report", mappedBy="websites")
     */
    private $rapport;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rapport = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getId() {
        return $this->id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCategory(): \Category {
        return $this->category;
    }

    public function getUsers(): \Users {
        return $this->users;
    }

    public function getRapport(): \Doctrine\Common\Collections\Collection {
        return $this->rapport;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCategory(\Category $category) {
        $this->category = $category;
    }

    public function setUsers(\Users $users) {
        $this->users = $users;
    }

    public function setRapport(\Doctrine\Common\Collections\Collection $rapport) {
        $this->rapport = $rapport;
    }


}
