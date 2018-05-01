<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 21.12.2017
 * Time: 13:55
 */

namespace AppBundle\Services;


use Eventviva\ImageResize;

class AppManager
{
    const PROKAT = "instrumenti";
    const POKYPKA = "stroymaterialy";

    const TYPE = [
        self::PROKAT => 0,
        self::POKYPKA => 1
    ];

    const TYPE_NAME = [
        0 => self::PROKAT,
        1 => self::POKYPKA
    ];

    public function __construct()
    {
    }

    public function getTypeByName($name)
    {
        switch ($name) {
            case "instrumenti": case null:
                return self::TYPE["instrumenti"];
            case "stroymaterialy":
                return self::TYPE["stroymaterialy"];
            default:
                return null;
        }
    }

    static public function saveImg($file)
    {

        $dir = is_dir('images/') ? 'images/' : __DIR__.'/../../../web/'.'images/';

        if (is_string($file)) {
            $file = $dir.$file;
        }

        $img = new ImageResize($file);

        switch ($img->source_type) {
            case 1: $type = '.gif'; break;
            case 2: $type = '.jpg'; break;
            case 3: $type = '.png'; break;
            default: $type = '.swf';
        }

        $imgName = 'img'.time().rand(1, 1000).$type;

        $img->quality_jpg = 90;
        $img->resizeToBestFit(450, 550);
        $img->save($dir.'big-'.$imgName);
        $img->resizeToBestFit(200, 150);
        $img->save($dir.'min-'.$imgName);

        return $imgName;
    }
}
