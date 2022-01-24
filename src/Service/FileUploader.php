<?php

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {


        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '.' . $file->guessExtension();

        try {


            $directory = $this->getTargetDirectory();


            $file->move(
                $directory,
                $fileName
            );
        } catch (FileException $e) {
            dump($e);
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {



        return $this->targetDirectory;
    }
}
