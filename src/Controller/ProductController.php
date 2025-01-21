<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{

    private $entityM;

    public function __construct(EntityManagerInterface $entityM)
    {
        $this->entityM = $entityM;
    }

    #[Route('/product', name: 'app_product_list')]
    public function list(): Response
    {
        $products = $this->entityM->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }


    #[Route('/product/{id}', name: 'app_product')]
    public function show(Product $product, $id): Response
    {
        // $product = $this->entityM->getRepository(Product::class)->find($id);
        // $product = $this->entityM->getRepository(Product::class)->findBy(['name' => 'COCA COLA 2.25L COMUN']);
        // $product = $this->entityM->getRepository(Product::class)->findOneBy(['name' => 'COCA COLA 2.25L COMUN']);
        $customProduct1 = $this->entityM->getRepository(Product::class)->findProductByIdCustomQuery($id);
        $customProduct2 = $this->entityM->getRepository(Product::class)->findProductById($id);

        return $this->render('product/products.html.twig', [
            'controller_name' => ['name' => 'ProductController'],
            'product' => $product, 
            'productCustom1' => $customProduct1,  
            'productCustom2' => $customProduct2
        ]);
    }

    #[Route('/insert/product', name: 'app_product_insert')]
    public function insert(Request $request): Response
    {

        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);
        
        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {
            
            $product->setCreationDate(new \DateTime());

            $this->entityM->persist($product);
            $this->entityM->flush();

            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/insert.html.twig', [
            'form' => $productForm->createView(),
        ]);
    }

    #[Route('/remove/product/{id}', name: 'app_product_remove')]
    public function remove($id): Response
    {
        $product = $this->entityM->getRepository(Product::class)->find($id);

        if (!$product) {
            // throw $this->createNotFoundException('No product found for id ' . $id);
            return new Response('No product found for id ' . $id);
        }

        $this->entityM->remove($product);
        $this->entityM->flush();

        return $this->redirectToRoute('app_product_list');
    }



}
