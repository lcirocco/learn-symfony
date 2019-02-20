<?php

namespace App\Service;


use App\Entity\ProductAttribute;
use App\Repository\AttributesRepository;

class ProductAttributeService
{
    private $attributeRepository;

    public function __construct(AttributesRepository $attributesRepository)
    {
        $this->attributeRepository = $attributesRepository;
    }

    public function create(array $data)
    {
        $attribute = $this->attributeRepository->find($data['attributeId']);

        //TODO exception

        $productAtributte = new ProductAttribute();

        $productAtributte->setAttribute($attribute)
            ->setValue($data['value']);

        return $productAtributte;
    }

}