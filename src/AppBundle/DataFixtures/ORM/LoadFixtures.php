<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function genus()
    {
        $genera = [
            'Хэй э',
            'Диджей dk d d',
            'Хей',
            'Битмейкер',
            'Хэй',
            'Уличный',
            'Денсер',
            'Шух',
            'Шух',
            'Шейкер',
            'Шейкер',
            'Хух',
            'Патимейкер',
        ];
        $key = array_rand($genera);
        return $genera[$key];
    }
}