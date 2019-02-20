<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 06/02/19
 * Time: 14:54
 */

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createCategory($array)
    {
        $category = new Category();

        $category->setName($array['name']);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }
}