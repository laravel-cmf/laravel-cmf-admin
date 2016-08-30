<?php

namespace LaravelCMF\Admin\Resources\Fields\Values;

class ImageObject
{
    public $original_name;  // what it was uploaded as
    public $extension;  // eg "jpg"
    public $size;  // eg 662906
    public $mime_type;  // eg "image/jpeg"
    public $path;  // eg "/uploads/images/"
    public $file_name;  // final unique filename saved as
    public $src; //path + filename
    public $src_url; //processed URL/CDN of image

    /**
     * ImageObject constructor.
     * @param array $imageData
     */
    public function __construct(array $imageData)
    {
        foreach($imageData as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        if(!empty($this->src)) {
            $this->src_url = cmf_file_url($this->src);
        }
    }
}