<?php

namespace App\Controller\Api;

use App\Service\UserService;
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
        UserService $userService
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
        if (!$this->userService->checkExpiration(getallheaders()['X-Auth-Token'])){
                $this->userService->userRefreshToken(getallheaders()['X-Auth-Token']);

            $category = $this->categoryService->createCategory($data);

            return $this->json($category,201);
        }
        return $this->json("Token Expired", 401);
    }
}
