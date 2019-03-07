<?php

namespace App\Controller\Api;

use App\Entity\UserToken;
use App\Service\UserTokenService;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api/categories")
 */
class CategoryController extends AbstractController
{
    private $categoryService;
    private $userService;

    public function __construct(
        CategoryService $categoryService,
        UserTokenService $userService
    )
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
    }

    /**
     * @Route("/", name="api.category.create", methods="POST")
     */
    public function createProduct(Request $request)
    {
        $data = \json_decode($request->getContent(), true);

            $category = $this->categoryService->createCategory($data);

            return $this->json($category,201);
    }
}
