<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (['Pencil', 'Pen', 'Paper', 'Magic Marker'] as $name) {
             $product = (new Product())
                 ->setName($name)
                 ;
             $manager->persist($product);
        }

        $manager->flush();
    }
}
