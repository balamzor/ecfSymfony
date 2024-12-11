<?php

namespace App\Controller;

use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/subscribe', name: 'subscribe_user')]
    public function subscribe(): Response
    {
        $stripe = new \Stripe\StripeClient('sk_test_BQokikJOvBiI2HlWgH4olfQ2');
        $customer = $stripe->customers->create([
            'description' => 'example customer',
            'email' => 'email@example.com',
            'payment_method' => 'pm_card_visa',
        ]);
        // echo $customer;
        return $this->render('user/subscribe.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/profile', name: 'profile_user')]
    public function profile(): Response
    {
        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/update', name: 'profile_update')]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form->get('firstName')->getData());
            $user->setLastName($form->get('lastName')->getData());
            $user->setAddress($form->get('address')->getData());
            $user->setPostal($form->get('postal')->getData());
            $user->setCity($form->get('city')->getData());
            $user->setTelephone($form->get('telephone')->getData());
            $user->setBirthDate($form->get('birthDate')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('profile_user');
        }
        // dd($user);
        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
