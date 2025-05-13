<?php

// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\{UserInterface, PasswordAuthenticatedUserInterface};

/**
 * Représente un utilisateur du système.
 *
 * Cette entité implémente l'interface UserInterface pour l'intégration avec le système de sécurité Symfony.
 * Elle permet de gérer l'authentification et le stockage des informations liées à l'utilisateur.
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null L'identifiant unique de l'utilisateur
     */
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null L'email de l'utilisateur, doit être unique
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var array Les rôles attribués à l'utilisateur
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string|null Le mot de passe hashé de l'utilisateur
     */
    /** @var string|null The hashed password */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var string|null Le mot de passe en clair (non mappé)
     */
    private ?string $plainPassword = null;

    /**
     * @var string|null Le nom d'utilisateur
     */
    #[ORM\Column(length: 32)]
    private ?string $nomUtilisateur = null;

    /**
     * @var string|null Le prénom de l'utilisateur
     */
    #[ORM\Column(length: 50)]
    private ?string $prenomUtilisateur = null;

    /**
     * @var string|null Le type d'utilisateur (par exemple : 'Eleve')
     */
    #[ORM\Column(length: 20)]
    private ?string $typeUtilisateur = null;

    /**
     * @return int|null L'identifiant unique de l'utilisateur
     */
    public function getId(): ?int { return $this->id; }

    /**
     * @return string|null L'email de l'utilisateur
     */
    public function getEmail(): ?string { return $this->email; }

    /**
     * @param string $email L'email à attribuer à l'utilisateur
     * @return self
     */
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    /**
     * @return string L'identifiant unique de l'utilisateur utilisé pour l'authentification (email)
     */
    public function getUserIdentifier(): string { return (string)$this->email; }

    /**
     * @return array Les rôles de l'utilisateur
     */
    public function getRoles(): array {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Ajouter un rôle de base pour chaque utilisateur
        return array_unique($roles);
    }

    /**
     * @param array $roles Les rôles à attribuer à l'utilisateur
     * @return self
     */
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    /**
     * @return string|null Le mot de passe hashé
     */
    public function getPassword(): ?string { return $this->password; }

    /**
     * @param string $password Le mot de passe hashé à attribuer à l'utilisateur
     * @return self
     */
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    /**
     * @return string|null Le mot de passe en clair (utilisé temporairement avant le hashage)
     */
    public function getPlainPassword(): ?string { return $this->plainPassword; }

    /**
     * @param string|null $plainPassword Le mot de passe en clair à attribuer à l'utilisateur
     * @return self
     */
    public function setPlainPassword(?string $plainPassword): self { $this->plainPassword = $plainPassword; return $this; }

    /**
     * Efface les informations sensibles, comme le mot de passe en clair
     */
    public function eraseCredentials(): void { $this->plainPassword = null; }

    /**
     * @return string|null Le nom d'utilisateur
     */
    public function getNomUtilisateur(): ?string { return $this->nomUtilisateur; }

    /**
     * @param string $n Le nom d'utilisateur à attribuer
     * @return self
     */
    public function setNomUtilisateur(string $n): self { $this->nomUtilisateur = $n; return $this; }

    /**
     * @return string|null Le prénom de l'utilisateur
     */
    public function getPrenomUtilisateur(): ?string { return $this->prenomUtilisateur; }

    /**
     * @param string $p Le prénom à attribuer à l'utilisateur
     * @return self
     */
    public function setPrenomUtilisateur(string $p): self { $this->prenomUtilisateur = $p; return $this; }

    /**
     * @return string|null Le type d'utilisateur (ex. 'Eleve')
     */
    public function getTypeUtilisateur(): ?string { return $this->typeUtilisateur; }

    /**
     * @param string $t Le type d'utilisateur à attribuer (ex. 'Eleve')
     * @return self
     */
    public function setTypeUtilisateur(string $t): self { $this->typeUtilisateur = $t; return $this; }
}
