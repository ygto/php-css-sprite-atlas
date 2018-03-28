<?php namespace CssAtlas\SpriteAtlas;

use CssAtlas\Sprite\Sprite;

class SpriteAtlas extends Sprite
{
    protected $outputPath;
    /**
     * @var SpriteAtlas[] $spriteAtlases ;
     */
    protected $spriteAtlases = [];
    protected $sprites = [];
    protected $offsetY = 0;
    protected $css;
    protected $pattern = '
                .%s:after {
                    background-image: url(\'%s\');
                    display: inline-block;
                    width: %spx; 
                    height: %spx;
                    content:"";
                    background-position: left 0 top -%spx;
                    
                }
                ';

    public function __construct($deep = 0)
    {
        $this->deep = $deep;
    }

    private function getCss()
    {
        return $this->css;
    }

    /**
     * @param $outputPathSrc
     * @param SpriteAtlas $parentAtlas
     */
    public function generateImageAndCss($outputPathSrc, $parentAtlas = null)
    {
        $outputPath = $this->getOutputPath($outputPathSrc);

        $sprites = $this->getSprites();

        $width = 0;
        $height = 0;
        foreach ($sprites as $sprite) {
            $height += $sprite->getHeight();
            $width = max($width, $sprite->getWidth());
        }

        if ($sprites) {
            $this->setResource($width, $height);
            foreach ($sprites as $sprite) {
                $this->appendCss($sprite);
                $this->appendSprite($sprite);
            }
        }
        if ($parentAtlas) {
            $this->appendAtlasCss($parentAtlas);
        }
        if ($sprites) {
            imagepng($this->resource, $outputPath . '.png');
        }
        file_put_contents($outputPath . '.css', $this->css);
        foreach ($this->getSpriteAtlases() as $atlas) {
            $atlas->generateImageAndCss($outputPathSrc, $this);
        }
    }

    public function loadFromDir($path)
    {
        if (!$this->isDir($path)) {
            return;
        }
        $this->path = $path;
        $filePaths = scandir($path);
        foreach ($filePaths as $filePath) {
            if (in_array($filePath, ['.', '..'])) {
                continue;
            }
            $imagePath = $path . '/' . $filePath;
            if (!$this->isFile($imagePath)) {
                $atlas = new SpriteAtlas($this->deep + 1);
                $atlas->loadFromDir($imagePath);
                $this->addSpriteAtlas($atlas);
                continue;
            }

            $this->addSprite(new Sprite($imagePath));
        }
    }


    private function addSpriteAtlas($atlas)
    {
        $this->spriteAtlases[] = $atlas;
    }

    public function addSprite(Sprite $sprite)
    {
        $sprite->setDeep($this->deep);
        $this->sprites[] = $sprite;
    }

    private function isFile($filePath)
    {
        return !is_dir($filePath);
    }

    private function isDir($path)
    {
        return !$this->isFile($path);
    }

    /**
     * @return Sprite[]
     */
    private function getSprites()
    {
        return $this->sprites;
    }

    /**
     * @return SpriteAtlas[]
     */
    private function getSpriteAtlases()
    {
        return $this->spriteAtlases;

    }

    private function setResource($width, $height)
    {

        $this->resource = imagecreatetruecolor($width, $height);
        imagesavealpha($this->resource, true);
        $color = imagecolorallocatealpha($this->resource, 0, 0, 0, 127);
        imagefill($this->resource, 0, 0, $color);
        imagealphablending($this->resource, false);

        return $this->resource;
    }


    public function getFileName()
    {
        return trim(str_replace(['.', '/', '\\'], ['', '_', '_'], $this->path), '/_');
    }


    public function getOutputPath($outputPath)
    {
        $outputPath = rtrim($outputPath, '/');

        return $outputPath . '/' . $this->getFileName();
    }

    /**
     * @param $sprite Sprite
     */
    private function appendSprite($sprite)
    {
        imagecopyresampled($this->resource, $sprite->getResource(), 0, $this->offsetY, 0, 0, $sprite->getWidth(), $sprite->getHeight(), $sprite->getWidth(), $sprite->getHeight());
        imagealphablending($this->resource, true);
        $this->offsetY += $sprite->getHeight();
    }

    /**
     * @param $sprite Sprite
     * @param $imagePath
     */
    private function appendCss($sprite)
    {
        $this->css .= sprintf($this->pattern,
            $this->getRelativeSpriteName($sprite),
            $this->getFileName() . '.png',
            $sprite->getWidth(),
            $sprite->getHeight(),
            $this->offsetY
        );
    }

    private function getRelativeSpriteName($sprite)
    {
        return $this->getFileName() . '_' . $sprite->getFileName();
    }

    /**
     * @param $atlas SpriteAtlas
     * @param $string
     */
    private function appendAtlasCss($atlas)
    {
        $this->css .= $atlas->getCss();
    }


}