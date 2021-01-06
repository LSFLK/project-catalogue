<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

        $_project->languages  = $project->getProgrammingLanguage();
        $_project->topics = $project->getTopic();

        $_project->git_repos = [];
        $_project->mailing_lists = [];
        $_project->more_infos = [];

        foreach ($project->getGitRepo() as $gitRepo) {
            $_gitRepo = [
                'name' => $gitRepo->getName(),
                'url'  => $gitRepo->getUrl(),
                'licenseName' => $gitRepo->getLicenseName(),
                'starsCount'  => $gitRepo->getStarsCount(),
                'forksCount'  => $gitRepo->getForksCount(),
            ];
            
            array_push($_project->git_repos, $_gitRepo);
        }

        foreach ($project->getMailingList() as $mailingList) {
            $_mailingList = [
                'name' => $mailingList->getName(),
                'url'  => $mailingList->getUrl()
            ];

            array_push($_project->mailing_lists, $_mailingList);
        }

        foreach ($project->getMoreInfo() as $moreInfo) {
            $_moreInfo = [
                'name' => $moreInfo->getName(),
                'url'  => $moreInfo->getUrl()
            ];

            array_push($_project->more_infos, $_moreInfo);
        }
        
        return $this->render('view_project/index.html.twig', [
            'project' => $_project,
            'dir' => $this->getParameter('public_confirmed_dir')
        ]);
    }
}
