<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Service\ProductValidator;
use App\Service\UserTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/products")
 */
class ProductController extends AbstractController
{
    private $productRepository;
    private $productService;
    private $categoriaRepository;
    private $productValidator;
    private $userService;

    public function __construct(
        ProductRepository $productRepository,
        ProductService $productService,
        CategoryRepository $categoriaRepository,
        ProductValidator $productValidator,
        UserTokenService $userService
    )
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
        $this->categoriaRepository = $categoriaRepository;
        $this->productValidator = $productValidator;
        $this->userService = $userService;
    }

    /**
     * @Route("/", name="api.products", methods="GET")
     */
    public function getProducts()
    {
        $list = $this->productRepository->findAll();

        return $this->json($list,200);
    }

    /**
     * @Route("/{id}", name="api.products.find", methods="GET")
     */
    public function getProductById($id)
    {
        $product = $this->productRepository->find($id);

        return $this->json($product,200);
    }

    /**
     * @Route("/", name="api.products.create", methods="POST")
     */
    public function createProduct(Request $request)
    {
        $data = \json_decode($request->getContent(), true);
        $category = $this->categoriaRepository->find($data['category']);


        if($category === null)
        {
            throw $this->createNotFoundException("Category Id not found");
        }
        $violations = $this->productValidator->validateProduct($data);
        if (count($violations)) {
            return $this->badRequestResponse($violations);
        }

        $product = $this->productService->createFromArray($data, $category);

        return $this->json($product,201);
    }

    /**
     * @param array $message
     *
     * @return JsonResponse
     */
    private function badRequestResponse(array $message): JsonResponse
    {
        return new JsonResponse(
            $message,
            Response::HTTP_BAD_REQUEST,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    /**
     * @Route("/{id}", name="api.products.update", methods="PUT")
     */
    public function updateProduct($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        $data = json_decode($request->getContent(), true);

        $category = $this->categoriaRepository->find($data['category']);

        if($category === null)
        {
            throw $this->createNotFoundException("Category Id not found");
        }
        $this->productService->updateFromArray($data, $product, $category);

        return $this->json($product,200);
    }

    /**
     * @Route("/{id}", name="api.products.delete", methods="DELETE")
     */

    public function delete($id)
    {
        $product = $this->productRepository->find($id);


        if (null === $product) {
            $this->createNotFoundException(sprintf('product with id %s not found', $id));
        }

        $this->productService->removeProduct($product);

        return $this->json([],200);
    }
}