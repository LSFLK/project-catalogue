<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileHandler
{
    private $filesystem;
    private $temp_dir;
    private $confirmed_dir;
    private $slugger;

    public function __construct($temp_dir, $confirmed_dir, SluggerInterface $slugger)
    {
        $this->filesystem = new Filesystem();
        $this->temp_dir = $temp_dir;
        $this->confirmed_dir = $confirmed_dir;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->temp_dir, $fileName);
        }
        catch (FileException $e) {
            return null;
        }

        return $fileName;
    }

    public function moveFileToConfirmedDirectory(string $fileName)
    {
        $pathToFileInTempDirectory = $this->temp_dir.$fileName;
        $pathToFileInConfirmedDirectory = $this->confirmed_dir.$fileName;

        $this->filesystem->rename($pathToFileInTempDirectory, $pathToFileInConfirmedDirectory);
    }

    public function removeFileFromConfirmedDirectory(string $fileName)
    {
        $pathToFile = $this->confirmed_dir.$fileName;

        $this->filesystem->remove($pathToFile);
    }

    public function copyFileToTempDirectoryFromConfirmedDirectory(string $fileName) {
        $pathToFileInConfirmedDirectory =  $this->confirmed_dir.$fileName;
        $pathToFileInTempDirectory = $this->temp_dir.$fileName;

        $this->filesystem->copy($pathToFileInConfirmedDirectory, $pathToFileInTempDirectory, true);
    }
}
