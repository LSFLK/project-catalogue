<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
     */
    private $objective;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $organization;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Url
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bug_tracking;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     */
    private $documentation;

    /**
     * @ORM\OneToMany(targetEntity=GitRepo::class, mappedBy="project", orphanRemoval=true)
     * @Assert\NotBlank
     */
    private $git_repo;

    /**
     * @ORM\ManyToOne(targetEntity=DomainExpertise::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $domain_expertise;

    /**
     * @ORM\ManyToOne(targetEntity=TechnicalExpertise::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $technical_expertise;

    /**
     * @ORM\OneToMany(targetEntity=MailingList::class, mappedBy="project")
     */
    private $mailing_list;

    /**
     * @ORM\OneToMany(targetEntity=MoreInfo::class, mappedBy="project")
     */
    private $more_info;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $project_data_file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $project_logo;

    /**
     * @ORM\ManyToMany(targetEntity=ProgrammingLanguage::class, inversedBy="projects")
     */
    private $programming_language;

    /**
     * @ORM\ManyToMany(targetEntity=Topic::class, inversedBy="projects")
     */
    private $topic;

    public function __construct()
    {
        $this->git_repo = new ArrayCollection();
        $this->mailing_list = new ArrayCollection();
        $this->more_info = new ArrayCollection();
        $this->programming_language = new ArrayCollection();
        $this->topic = new ArrayCollection();
    }

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

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(string $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getBugTracking(): ?string
    {
        return $this->bug_tracking;
    }

    public function setBugTracking(?string $bug_tracking): self
    {
        $this->bug_tracking = $bug_tracking;

        return $this;
    }

    public function getDocumentation(): ?string
    {
        return $this->documentation;
    }

    public function setDocumentation(?string $documentation): self
    {
        $this->documentation = $documentation;

        return $this;
    }

    /**
     * @return Collection|GitRepo[]
     */
    public function getGitRepo(): Collection
    {
        return $this->git_repo;
    }

    public function addGitRepo(GitRepo $gitRepo): self
    {
        if (!$this->git_repo->contains($gitRepo)) {
            $this->git_repo[] = $gitRepo;
            $gitRepo->setProject($this);
        }

        return $this;
    }

    public function removeGitRepo(GitRepo $gitRepo): self
    {
        if ($this->git_repo->removeElement($gitRepo)) {
            // set the owning side to null (unless already changed)
            if ($gitRepo->getProject() === $this) {
                $gitRepo->setProject(null);
            }
        }

        return $this;
    }

    public function getDomainExpertise(): ?DomainExpertise
    {
        return $this->domain_expertise;
    }

    public function setDomainExpertise(?DomainExpertise $domain_expertise): self
    {
        $this->domain_expertise = $domain_expertise;

        return $this;
    }

    public function getTechnicalExpertise(): ?TechnicalExpertise
    {
        return $this->technical_expertise;
    }

    public function setTechnicalExpertise(?TechnicalExpertise $technical_expertise): self
    {
        $this->technical_expertise = $technical_expertise;

        return $this;
    }

    /**
     * @return Collection|MailingList[]
     */
    public function getMailingList(): Collection
    {
        return $this->mailing_list;
    }

    public function addMailingList(MailingList $mailingList): self
    {
        if (!$this->mailing_list->contains($mailingList)) {
            $this->mailing_list[] = $mailingList;
            $mailingList->setProject($this);
        }

        return $this;
    }

    public function removeMailingList(MailingList $mailingList): self
    {
        if ($this->mailing_list->removeElement($mailingList)) {
            // set the owning side to null (unless already changed)
            if ($mailingList->getProject() === $this) {
                $mailingList->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MoreInfo[]
     */
    public function getMoreInfo(): Collection
    {
        return $this->more_info;
    }

    public function addMoreInfo(MoreInfo $moreInfo): self
    {
        if (!$this->more_info->contains($moreInfo)) {
            $this->more_info[] = $moreInfo;
            $moreInfo->setProject($this);
        }

        return $this;
    }

    public function removeMoreInfo(MoreInfo $moreInfo): self
    {
        if ($this->more_info->removeElement($moreInfo)) {
            // set the owning side to null (unless already changed)
            if ($moreInfo->getProject() === $this) {
                $moreInfo->setProject(null);
            }
        }

        return $this;
    }

    public function getProjectDataFile(): ?string
    {
        return $this->project_data_file;
    }

    public function setProjectDataFile(?string $project_data_file): self
    {
        $this->project_data_file = $project_data_file;

        return $this;
    }

    public function getProjectLogo(): ?string
    {
        return $this->project_logo;
    }

    public function setProjectLogo(?string $project_logo): self
    {
        $this->project_logo = $project_logo;

        return $this;
    }

    /**
     * @return Collection|ProgrammingLanguage[]
     */
    public function getProgrammingLanguage(): Collection
    {
        return $this->programming_language;
    }

    public function addProgrammingLanguage(ProgrammingLanguage $programmingLanguage): self
    {
        if (!$this->programming_language->contains($programmingLanguage)) {
            $this->programming_language[] = $programmingLanguage;
        }

        return $this;
    }

    public function removeProgrammingLanguage(ProgrammingLanguage $programmingLanguage): self
    {
        $this->programming_language->removeElement($programmingLanguage);

        return $this;
    }

    /**
     * @return Collection|Topic[]
     */
    public function getTopic(): Collection
    {
        return $this->topic;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topic->contains($topic)) {
            $this->topic[] = $topic;
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        $this->topic->removeElement($topic);

        return $this;
    }
}
