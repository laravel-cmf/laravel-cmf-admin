<?php

namespace LaravelCMF\Admin\Resources\Fields;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Resources\Fields\Values\ImageObject;

class Image extends ResourceField
{
    /**
     * @var Filesystem
     */
    protected $disk;

    /** @var  UploadedFile */
    protected $fileUpload;

    protected $form_template = 'admin.fields.form.image';

    /**
     * Return the rendered value of this resource field to display in list views.
     * @return mixed
     */
    public function displayList()
    {
        $fileData = $this->getValue();
        if (!$fileData) {
            return '';
        }

        return '<img src="' . cmf_file_url($fileData->src) . '" alt="" width="100" />';
    }

    protected function getFormDisplayData()
    {
        $data = parent::getFormDisplayData();

        $data['value'] = json_decode($data['value'], true);

        return $data;
    }

    public function preUpdate()
    {
        //move files
        if ($this->fileUpload && $this->processedData) {
            $fileData = json_decode($this->processedData, true);
//            $this->fileUpload->move($fileData['path'], $fileData['file_name']);
            $disk = $this->getDisk();
            $disk->put($fileData['path'] . DIRECTORY_SEPARATOR . $fileData['file_name'],
                file_get_contents($this->fileUpload));

            //delete old one
            $this->delete();
        }
    }

    public function processRequest(Request $request)
    {
        //get the file
        //move the file someplace
        //boom
        $fileData = null;
        $fileKey  = $this->getRequestKey() . '.file';
        $keepKey  = $this->getRequestKey() . '.keep';
        $file     = $request->file($fileKey, null);
        if ($file) {
            //possibly validate image sizes etc.?
            //check stuff on $data
            $fileData = [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getClientSize(),
                'mime_type' => $file->getClientMimeType(),
            ];

            $filePath       = '/uploads/images/';
            $fileName       = $file->getClientOriginalName();
            $uniqueFileName = $this->getUniqueFilePath($filePath, $fileName);

            $fileData['path']      = $filePath;
            $fileData['file_name'] = $uniqueFileName;
            $fileData['src']       = $filePath . $uniqueFileName;
            $this->fileUpload      = $file;
            $this->setProcessedData(json_encode($fileData));
        } else {
            if (!$request->input($keepKey)) {
                $this->fileUpload = null;
                $this->setProcessedData(null);
            }
        }
    }

    private function getUniqueFilePath($filePath, $fileName, $version = null)
    {
        $uniqueFileName = (!empty($version) ? $version . '_' : '') . $fileName;
        $file           = $filePath . $uniqueFileName;
        $disk           = $this->getDisk();
        if ($disk->exists($file)) {
            $uniqueFileName = $this->getUniqueFilePath($filePath, $fileName, $version + 1);
        }

        return $uniqueFileName;
    }

    public function getValue()
    {
        $imageArray = $this->fieldValue ? json_decode($this->fieldValue, true) : null;

        return !empty($imageArray) && is_array($imageArray) ? new ImageObject($imageArray) : null;

    }

    /**
     * @return Filesystem
     */
    public function getDisk()
    {
        if (!$this->disk) {
            $this->disk = Storage::disk(CMF::configGet('disk', 'public'));
        }
        return $this->disk;
    }

    public function delete()
    {
        $disk = $this->getDisk();
        if ($this->getValue() && $disk->exists($this->getValue()->src)) {
            $disk->delete($this->getValue()->src);
        }
    }
}