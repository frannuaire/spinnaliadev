<?php

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Users
 *
 * @ORM\Table(name="users", name="fos_user")
 * @ORM\Entity
 */
class Users implements UserInterface, \Serializable{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=true)
     */
    private $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nickname", type="string", length=45, nullable=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    private $mail;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    private $created;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="modified", type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="validite", type="datetime", nullable=true)
     */
    private $validite;

    /**
     * @ORM\OneToMany(targetEntity="Websites")
     * 
     */
    private $websites;
    
     /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $fullName;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $username;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;
 
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];
    
     public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function getId() {
        return $this->id;
    }

 public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }
 
    public function getFullName(): string
    {
        return $this->fullName;
    }
 
    public function getUsername(): string
    {
        return $this->username;
    }
 
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
 
    public function getEmail(): string
    {
        return $this->email;
    }
 
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
 
    public function getPassword(): string
    {
        return $this->password;
    }
 
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
 
    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
 
        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
 
        return array_unique($roles);
    }
 
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
 
    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
 
        return null;
    }
 
    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }
 
    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }
 
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }
    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getCreated(): \DateTime {
        return $this->created;
    }

    public function getModified(): \DateTime {
        return $this->modified;
    }

    public function getValidite(): \DateTime {
        return $this->validite;
    }

    public function getWebsites() {
        return $this->websites;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function setCreated(\DateTime $created) {
        $this->created = $created;
    }

    public function setModified(\DateTime $modified) {
        $this->modified = $modified;
    }

    public function setValidite(\DateTime $validite) {
        $this->validite = $validite;
    }

    public function setWebsites($websites) {
        $this->websites = $websites;
    }


}
