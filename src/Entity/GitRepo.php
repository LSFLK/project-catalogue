<?php

namespace App\Entity;

use App\Repository\GitRepoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GitRepoRepository::class)
 */
class GitRepo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="git_repo")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $license_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stars_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $forks_count;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getLicenseName(): ?string
    {
        return $this->license_name;
    }

    public function setLicenseName(?string $license_name): self
    {
        $this->license_name = $license_name;

        return $this;
    }

    public function getStarsCount(): ?int
    {
        return $this->stars_count;
    }

    public function setStarsCount(?int $stars_count): self
    {
        $this->stars_count = $stars_count;

        return $this;
    }

    public function getForksCount(): ?int
    {
        return $this->forks_count;
    }

    public function setForksCount(?int $forks_count): self
    {
        $this->forks_count = $forks_count;

        return $this;
    }
}
