<?php
/**
 * Created by PhpStorm.
 * User: Claudio
 * Date: 16/10/2017
 * Time: 21:50
 */

namespace App\DataFixtures\ORM;

use App\Entity\ProfileImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProfileImageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $image = new ProfileImage();
            $image->setName('image '.$i);
            $image->setEnabled(true);
            $image->setSelectable(true);
            $image->setPathSVG('//st.qubit.tv/assets/public/qubit/qubit-ar/avatar/0'. $i .'.svg');
            $image->setPathSmall('//st.qubit.tv/assets/public/qubit/qubit-ar/avatar/0'. $i .'.png');
            $image->setPathMedium('//st.qubit.tv/assets/public/qubit/qubit-ar/avatar/0'. $i .'.png');
            $image->setPathLarge('//st.qubit.tv/assets/public/qubit/qubit-ar/avatar/0'. $i .'x2.png');

            $manager->persist($image);
        }

        $manager->flush();
    }
}