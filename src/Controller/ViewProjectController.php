<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
use App\Service\GitHubAPI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ViewProjectController extends AbstractController
{
    /**
     * @Route("/projects/id={id}", name="view_project")
     */
    public function index($id): Response
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        $_project = new \stdClass();

        $_project->name        = $project->getName();
        $_project->objective   = $project->getObjective();
        $_project->description = $project->getDescription();

        $_project->organization = $project->getOrganization();
        $_project->website      = $project->getWebsite();

        $_project->domain_expertise    = $project->getDomainExpertise()->getName();
        $_project->technical_expertise = $project->getTechnicalExpertise()->getName();
        
        $_project->bug_tracking  = $project->getBugTracking();
        $_project->documentation = $project->getDocumentation();

        $_project->project_data_file = $project->getProjectDataFile();
        $_project->project_logo      = $project->getProjectLogo();

        $git_repos = [];
        $mailing_lists = [];
        $more_infos = [];

        $languages = [];
        $topics = [];

        foreach ($project->getGitRepo() as $gitRepo) {
            $_gitRepo = new GitHubAPI($gitRepo);
            array_push($git_repos, $_gitRepo->getGitRepoRequiredData());

            $languages = array_merge($languages, $_gitRepo->getLanguages());
            $topics = array_merge($topics, $_gitRepo->getTopics());
        }

        foreach ($project->getMailingList() as $mailingList) {
            $_mailingList = [
                'name' => $mailingList->getName(),
                'url'  => $mailingList->getUrl()
            ];

            array_push($mailing_lists, $_mailingList);
        }

        foreach ($project->getMoreInfo() as $moreInfo) {
            $_moreInfo = [
                'name' => $moreInfo->getName(),
                'url'  => $moreInfo->getUrl()
            ];

            array_push($more_infos, $_moreInfo);
        }

        $_project->git_repos = $git_repos;
        $_project->mailing_lists  = $mailing_lists;
        $_project->more_infos = $more_infos;
        
        $_project->languages  = array_unique($languages);
        $_project->topics = array_unique($topics);
        
        return $this->render('view_project/index.html.twig', [
            'project' => $_project,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }
}
