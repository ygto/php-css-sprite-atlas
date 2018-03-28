<?php namespace CssAtlas\Sprite;

class Sprite
{

    protected $path;
    protected $deep;
    protected $width;
    protected $height;
    protected $resource;


    /**
     * Sprite constructor.
     * @param $path
     */
    public function __construct($path = null)
    {
        if ($path) {

            $this->path = $path;
            switch (mime_content_type($this->path)) {
                case 'image/jpeg':
                    $this->resource = imagecreatefromjpeg($this->path);
                    break;
                case 'image/png':
                    $this->resource = imagecreatefrompng($this->path);
                    break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getDeep()
    {
        return $this->deep;
    }

    /**
     * @param mixed $deep
     */
    public function setDeep($deep)
    {
        $this->deep = $deep;
    }

    public function getResource()
    {

        return $this->resource;
    }

    public function getHeight()
    {
        if (!$this->height) {
            $this->height = imagesy($this->getResource());
        }

        return $this->height;
    }

    public function getWidth()
    {
        if (!$this->width) {
            $this->width = imagesx($this->getResource());
        }

        return $this->width;
    }

    public function getFileName()
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

}