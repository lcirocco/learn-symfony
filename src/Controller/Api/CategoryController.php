<?php

namespace App\Controller\Api;

use App\Entity\UserToken;
use App\Repository\CategoryRepository;
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
    private $categoryRepository;

    public function __construct(
        CategoryService $categoryService,
        UserTokenService $userService,
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->categoryRepository = $categoryRepository;
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

    /**
     * @Route("/", name="api.category.list", methods="GET")
     */
    public function getCategories()
    {
        $list = $this->categoryRepository->findAll();

        return $this->json($list,200);
    }
}
