<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    private $productService;

    public function __construct(
        ProductRepository $productRepository,
        ProductService $productService
    )
    {
        $this->productRepository = $productRepository;
        $this->productService = $productService;
    }

    /**
     * @Route("/products", name="products")
     */
    public function index()
    {
        $list = $this->productRepository->findAll();
        return $this->render('product/index.html.twig', ['products' => $list]);
    }


    /**
     * @Route("/products/new", name="products.new")
     */
    public function newProduct(Request $request)
    {
        $product = new Product();

        // build empty form
        $form = $this->createForm(ProductForm::class, $product);

        return $this->render('product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{id}", name="products.edit")
     */
    public function editProduct(Request $request, $id)
    {
        $product = $this->productRepository->find($id);

        if (null === $product) {
            $this->createNotFoundException(sprintf('product with id %s not found', $id));
        }

        $form = $this->createForm(ProductForm::class, $product);

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    /**
     * @Route("/products/create", name="products.create", methods="POST")
     */
    public function createProduct(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        // product service createFromForm
        if ($form->isValid() === false) {
            // throw some exception
        }

        $this->productService->createFromForm($form);
        return $this->redirectToRoute('products');

    }

    /**
     * @Route("/products/update/{id}", name="products.update", methods="POST")
     */
    public function updateProduct($id, Request $request)
    {
        $product = $this->productRepository->find($id);

        if (null === $product) {
            $this->createNotFoundException(sprintf('product with id %s not found', $id));
        }

        // build form from class
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        // product service createFromForm
        if ($form->isValid() === false) {
            // throw some exception
        }

        $this->productService->updateFromForm($form, $product);
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function delete($id)
    {
        $product = $this->productRepository->find($id);


        if (null === $product) {
            $this->createNotFoundException(sprintf('product with id %s not found', $id));
        }

        $this->productService->removeProduct($product);

        return $this->redirectToRoute('products');
    }
}