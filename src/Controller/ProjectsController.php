<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/projects", name="projects")
     */
    public function index(): Response
    {
        $domain_options = array(
            'Agriculture & Farming',
            'Arts & Heritage Crafts',
            'Banking, Finance and Insurance',
            'Charity & Philanthropy',
            'Disaster Relief',
            'Economy',
            'Education',
            'Elections & Governance',
            'Emergency Response',
            'Environment, Forestry & Wildlife Conservation',
            'Fisheries & Aquatic Resources',
            'Foreign Affairs'
        );

        $technical_options = array(
            'Cloud',
            'Data Analytics & Visualization',
            'Database',
            'Developer Tools',
            'Enterprise',
            'Entertainment',
            'Games',
            'Graphics, Video, Audio',
            'Internationalization or Localization',
            'Internet of Things (IoT)',
            'Location & Maps',
            'Machine Learning, Neural Networks & AI'
        );

        $programming_languages = array(
            'Ballerina',
            'C',
            'C#',
            'C++',
            'CSS',
            'Dart',
            'Go',
            'Haskell',
            'HTML',
            'Java',
            'Javascript',
            'Lua',
            'Objective-C',
            'Perl',
            'PHP',
            'Python',
            'R',
            'Ruby',
            'Rust',
            'Shell',
            'SQL',
            'Swift',
            'Typescript'
        );

        $project_names = array(
            'Project One',
            'Project Two',
            'Project Three',
            'Project Four',
            'Project Five',
            'Project Six',
            'Project Seven',
            'Project Eight',
            'Project Nine',
            'Project Ten',
            'Project Eleven',
            'Project Tweleve'
        );

        return $this->render('projects/index.html.twig', [
            'domain_options' => $domain_options,
            'technical_options' => $technical_options,
            'programming_languages' => $programming_languages,
            'project_names' => $project_names,
        ]);
    }
}
