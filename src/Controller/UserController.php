<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class UserController extends AbstractController
{

    private $entitym;

    public function __construct(EntityManagerInterface $entitym)
    {
        $this->entitym = $entitym;
    }

    #[Route('/registration', name: 'app_user_registration')]
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        $user = new User();
        $registration_form = $this->createForm(UserType::class, $user);
        // $user->setRoles(['ROLE_USER']);
        // dump($registration_form->get('password')->getData());
        // die();
        // $registration_form->handleRequest($request);
        // if ($registration_form->isSubmitted()) {
        //     dump($registration_form->isValid());
        //     die();
        // }

        $registration_form->handleRequest($request);
        if ($registration_form -> isSubmitted() && $registration_form -> isValid()) {

            
            $plainTextPassword = $registration_form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);
            $user->setPassword($hashedPassword);

            // dump($plainTextPassword);
            // dump($hashedPassword);
            // die();

            $this->entitym->persist($user);
            $this->entitym->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController', 'form' => $registration_form->createView(),
        ]);
    }
}
