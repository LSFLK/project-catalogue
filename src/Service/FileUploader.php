<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileUploader
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

    public function moveToConfirmedDirectory(string $fileName)
    {
        $temp = $this->temp_dir.$fileName;
        $confirmed = $this->confirmed_dir.$fileName;

        $this->filesystem->rename($temp, $confirmed);
    }
}
