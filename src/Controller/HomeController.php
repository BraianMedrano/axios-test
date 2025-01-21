<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private $entitym;

    public function __construct(EntityManagerInterface $entitym)
    {
        $this->entitym = $entitym;
    }


    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        // A good practice to handle the form is to use the handleRequest method
        $form->handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()) {
            $this->entitym->persist($product);
            $this->entitym->flush();

            return $this->redirectToRoute('app_home');
        }
        


        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
