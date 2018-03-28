it concatenate the sprites and create sprite atlas per director

**example**


```php
$path = './sprites';

$atlas = new \CssAtlas\SpriteAtlas\SpriteAtlas();

$atlas->loadFromDir($path);

$atlas->generateImageAndCss('./dist');
```

**output**

<table>
    <tr>
        <td>
            <img alt="dist/sprites.png" src="dist/sprites.png">
        </td>
        <td>
            <img alt="dist/sprites_brands_music.png" src="dist/sprites_brands_music.png">
        </td>
        <td>
            <img alt="dist/sprites_brands_social.png" src="dist/sprites_brands_social.png">
        </td>
    </tr>
</table>

`
sprites_brands.css`

```css
 .sprites_box:after {
                    background-image: url('sprites.png');
                    display: inline-block;
                    width: 128px; 
                    height: 128px;
                    content:"";
                    background-position: left 0 top -0px;
                    
                }
                
                .sprites_circle_dot:after {
                    background-image: url('sprites.png');
                    display: inline-block;
                    width: 256px; 
                    height: 256px;
                    content:"";
                    background-position: left 0 top -128px;
                    
                }
```