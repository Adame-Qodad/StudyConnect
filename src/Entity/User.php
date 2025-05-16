<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    private ?string $plainPassword = null;

    #[ORM\Column(length: 32)]
    private ?string $nomUtilisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomUtilisateur = null;

    #[ORM\Column(length: 20)]
    private ?string $typeUtilisateur = null;

    // === 🟡 Nouveaux champs pour gestion activation & mot de passe oublié ===

    /**
     * @var string|null Token d'activation pour la création de compte
     */
    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $activationToken = null;

    /**
     * @var \DateTimeInterface|null Date d’expiration du token d’activation
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $activationTokenExpiresAt = null;

    /**
     * @var string|null Token de réinitialisation du mot de passe
     */
    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $passwordResetToken = null;

    /**
     * @var \DateTimeInterface|null Date d’expiration du token de réinitialisation
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $passwordResetTokenExpiresAt = null;

    // === GETTERS & SETTERS ===

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }

    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getUserIdentifier(): string { return (string)$this->email; }

    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getPassword(): ?string { return $this->password; }

    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function getPlainPassword(): ?string { return $this->plainPassword; }

    public function setPlainPassword(?string $plainPassword): self { $this->plainPassword = $plainPassword; return $this; }

    public function eraseCredentials(): void { $this->plainPassword = null; }

    public function getNomUtilisateur(): ?string { return $this->nomUtilisateur; }

    public function setNomUtilisateur(string $n): self { $this->nomUtilisateur = $n; return $this; }

    public function getPrenomUtilisateur(): ?string { return $this->prenomUtilisateur; }

    public function setPrenomUtilisateur(string $p): self { $this->prenomUtilisateur = $p; return $this; }

    public function getTypeUtilisateur(): ?string { return $this->typeUtilisateur; }

    public function setTypeUtilisateur(string $t): self { $this->typeUtilisateur = $t; return $this; }

    // === GETTERS & SETTERS pour les nouveaux champs ===

    public function getActivationToken(): ?string { return $this->activationToken; }

    public function setActivationToken(?string $token): self { $this->activationToken = $token; return $this; }

    public function getActivationTokenExpiresAt(): ?\DateTimeInterface { return $this->activationTokenExpiresAt; }

    public function setActivationTokenExpiresAt(?\DateTimeInterface $date): self { $this->activationTokenExpiresAt = $date; return $this; }

    public function getPasswordResetToken(): ?string { return $this->passwordResetToken; }

    public function setPasswordResetToken(?string $token): self { $this->passwordResetToken = $token; return $this; }

    public function getPasswordResetTokenExpiresAt(): ?\DateTimeInterface { return $this->passwordResetTokenExpiresAt; }

    public function setPasswordResetTokenExpiresAt(?\DateTimeInterface $date): self { $this->passwordResetTokenExpiresAt = $date; return $this; }
}
