<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * File storage component
 *
 * @author mukol
 */
class Storage extends Component implements StorageInterface
{
    
    private $fileName;
    
    /**
     * Save given UploadedFile instance to desk
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->preparePath($file);
        
        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }
    
    /**
     * Prepare path to save uploaded file
     * @param Uploaded $file
     * @return stringFile $file
     */
    protected function preparePath(UploadedFile $file)
    {
        $this->fileName = $this->getFilename($file);
        //     0c/a9/gfwe7gskge7g668ewgtt7eeweg5789kjh23oi44t.jpg
        
        $path = $this->getStoragePath() . $this->fileName;
        //     /var/www/project/frontend/web/uploads/0c/a9/gfwe7gskge7g668ewgtt7eeweg5789kjh23oi44t.jpg
        
        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
        
    }
    
    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilename(UploadedFile $file)
    {
        // $file->tempname    -   /tmp/qio93kf
        
        $hash = sha1_file($file->tempName);   //  0ca9gfwe7gskge7g668ewgtt7eeweg5789kjh23oi44t
        
        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0);  //  0c/a9/gfwe7gskge7g668ewgtt7eeweg5789kjh23oi44t
        return $name . '.' . $file->extension;  //  0c/a9/gfwe7gskge7g668ewgtt7eeweg5789kjh23oi44t.jpg
    }
    
    /**
     * @return string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }
    
    /**
     * 
     * @param string $filename
     * @return string
     */
    public function getFile(string $filename) {
        return Yii::$app->params['storageUri'].$filename;
    }
    
    /**
     * @param string $filename
     * @return boolean
     */
    public function deleteFile(string $filename)
    {
        $file = $this->getStoragePath().$filename;
        
        if (file_exists($file)) {
            // Если файл сущестаует, удаляем
            return unlink($file);
        }
        
        // Файла не - хорошо. И удалять не нужно
        return true;
    }
}
