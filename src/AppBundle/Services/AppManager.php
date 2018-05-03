<?php

namespace AppBundle\Services;


use Eventviva\ImageResize;

class AppManager
{
    const PROKAT  = 0;
    const POKUPKA = 1;
    
    const TYPE_URL = [
        self::PROKAT  => 'instrumenty',
        self::POKUPKA => 'strojmaterialy'
    ];

    public function __construct()
    {
    }

    /**
     * @param string $typeUrl
     * @return false|int
     */
    static public function getTypeByUrl(string $typeUrl)
    {
        return array_search($typeUrl, self::TYPE_URL, true);
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
