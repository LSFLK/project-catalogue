<?php

namespace App\Service;

use App\Entity\Organization;
use App\Service\FileHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;

class OrganizationHandler
{
    private $entityManager;
    private $fileHandler;
    private $validator;


    public function __construct(EntityManagerInterface $entityManager, FileHandler $fileHandler, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->fileHandler = $fileHandler;
        $this->validator = $validator;
    }


    public function createOrganizationObjectWithRequestData(Request $request): Organization
    {
        $data = $request->request;
        $files = $request->files;

        $organization = new Organization();
        $organization->setName($data->get('name'));
        $organization->setWebsite($data->get('website'));
        $organization->setDescription($data->get('description'));

        if ($organization_logo = $files ? $files->get('organization_logo') : null) {
            $organization->setOrganizationLogo($this->fileHandler->upload($organization_logo));
        }
        else if ($organization_logo_previously_uploaded = $data->get('organization_logo_previously_uploaded')) {
            $this->fileHandler->copyFileToTempDirectoryFromConfirmedDirectory($organization_logo_previously_uploaded);
            $organization->setOrganizationLogo($organization_logo_previously_uploaded);
        }

        return $organization;
    }


    public function writeNewOrganization(Organization $organization)
    {
        $this->_validate($organization);

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        $this->fileHandler->moveFileToConfirmedDirectory($organization->getOrganizationLogo());

        return $organization->getId();
    }


    private function _validate($object)
    {
        $errors = $this->validator->validate($object);
        if (count($errors)) { throw new \Exception((string) $errors); }
    }
}