<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(CategoryRepository $categoryRepository ): Response
    {
        return $this->render('app/index.html.twig', [
            //'controller_name' => 'AppController',
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/category/{id}', name: 'app_category')]
    public function category($id, CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request): Response
    {
        $category = $categoryRepository->find($id);

        if(!$category){
            throw $this->createNotFoundException('Category not found');
        }

        $sort = $request->query->get('sort');

        if ($sort == 'price_asc') {
            $products = $productRepository->findBy(['category' => $category], ['price' => 'ASC']);
        } else if ($sort == 'price_desc') {
            $products = $productRepository->findBy(['category' => $category], ['price' => 'DESC']);
        } else {
            $products = $category->getProducts();
        }

        return $this->render('app/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'sort' => $sort,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product')]
    public function product($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        #dd($district);

        if(!$product){
            throw $this->createNotFoundException('Product not founddd');
        }


        return $this->render('app/product.html.twig', [
            #'controller_name' => 'McdoController',
            'product' => $product,
        ]);
    }



}
