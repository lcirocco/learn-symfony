<?php
/**
 * Created by PhpStorm.
 * User: teo
 * Date: 30/01/19
 * Time: 11:00
 */

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductForm;

class ProductService
{
    private $entityManager;
    private $productAttributeService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductAttributeService $productAtributeService
    )
    {
        $this->entityManager = $entityManager;
        $this->productAttributeService = $productAtributeService;
    }

    public function createFromForm($form)
    {
        $product = $form->getData();

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function updateFromForm($form, $product)
    {
        $product->setName($form->getData()->getName())
            ->setPrice($form->getData()->getPrice());

        $this->entityManager->flush();

        return $product;
    }

    public function removeProduct($product)
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function updateFromArray($array, $product, $category)
    {
        $product->setName($array['name'])
            ->setPrice($array['price'])
            ->setCategory($category)
            ->setDescription($array['description']);

        $this->entityManager->flush();

        return $product;
    }

    public function createFromArray($array, $category)
    {
        $product = new Product();

        $product->setName($array['name'])
            ->setPrice($array['price'])
            ->setCategory($category)
            ->setDescription($array['description']);

        foreach ($array['attributes'] as $attribute)
        {
            $productAttribute = $this->productAttributeService->create($attribute);
            $product->addProductAttribute($productAttribute);
            $productAttribute->setProduct($product);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}