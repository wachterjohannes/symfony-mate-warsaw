<?php

namespace App\Controller;

use App\Form\Signup;
use App\Form\SignupType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SignupController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('page.html.twig', ['title' => 'Acme', 'text' => 'A small shop for the demo.', 'signup' => true]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page.html.twig', ['title' => 'About Acme', 'text' => 'We sell things. Sign up to see them.']);
    }

    #[Route('/signup', name: 'signup')]
    public function signup(Request $request, LoggerInterface $logger): Response
    {
        $form = $this->createForm(SignupType::class, new Signup());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Account created', ['username' => $form->getData()->username]);

            return $this->redirectToRoute('welcome');
        }

        return $this->render('signup.html.twig', ['form' => $form]);
    }

    #[Route('/welcome', name: 'welcome')]
    public function welcome(): Response
    {
        return $this->render('page.html.twig', ['title' => 'Welcome!', 'text' => 'Your account is ready.']);
    }
}
