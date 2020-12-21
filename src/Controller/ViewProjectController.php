<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\DomainExpertise;
use App\Entity\TechnicalExpertise;
use App\Entity\ProgrammingLanguage;
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

        $git_repo_names = [];
        $git_repo_urls = [];
        $mailing_list_names = [];
        $mailing_list_urls = [];
        $more_info_names = [];
        $more_info_urls = [];

        foreach ($project->getGitRepo() as $gitRepo) {
            $name = $gitRepo->getName();
            $url  = $gitRepo->getUrl();

            array_push($git_repo_names, $name);
            array_push($git_repo_urls, $url);
        }

        foreach ($project->getMailingList() as $mailingList) {
            $name = $mailingList->getName();
            $url  = $mailingList->getUrl();

            array_push($mailing_list_names, $name);
            array_push($mailing_list_urls, $url);
        }

        foreach ($project->getMoreInfo() as $moreInfo) {
            $name = $moreInfo->getName();
            $url  = $moreInfo->getUrl();

            array_push($more_info_names, $name);
            array_push($more_info_urls, $url);
        }

        $_project->git_repo_names = $git_repo_names;
        $_project->git_repo_urls  = $git_repo_urls;
        $_project->mailing_list_names = $mailing_list_names;
        $_project->mailing_list_urls  = $mailing_list_urls;
        $_project->more_info_names = $more_info_names;
        $_project->more_info_urls  = $more_info_urls;

        $_project->languages = array('java', 'ballerina');
        $_project->tags = array('programming-language', 'language', 'compiler');
        
        return $this->render('view_project/index.html.twig', [
            'project' => $_project
        ]);
    }
}
