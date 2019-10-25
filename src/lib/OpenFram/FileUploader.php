<?php


namespace OpenFram;


use GuzzleHttp\Psr7\UploadedFile;
use function OpenFram\u;

class FileUploader
{
    protected $targetDirectory;
    protected $entityName;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     * @param $entityName
     */
    public function __construct($targetDirectory, $entityName)
    {
        $this->targetDirectory = $targetDirectory;
        $this->entityName = $entityName;
    }


    public function uploadFile(UploadedFile $file, int  $id)
    {

        $file->moveTo($this->targetDirectory . '/'.$this->entityName.'-' . u($id) . '.jpg');

    }

    public function getFile(int $entityId)
    {
        $imagePath = $this->targetDirectory . '/' . $this->entityName . '-' . u($entityId) . '.jpg';
        return file_exists($imagePath) ? '/images/' . $this->entityName . '/' . $this->entityName . '-' . u($entityId) . '.jpg' : '/images/' . $this->entityName . '/' . $this->entityName . '-default.jpg';
    }

    public function deleteFile($id)
    {
        $filePath = $this->targetDirectory . '/'.$this->entityName.'-' . u($id) . '.jpg';
        if (file_exists($filePath)) {
            unlink($filePath);
        }

    }


    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param mixed $targetDirectory
     */
    public function setTargetDirectory($targetDirectory): void
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName): void
    {
        $this->entityName = $entityName;
    }




}