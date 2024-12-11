<?php

namespace App\Controller;

use App\Form\PaymentType;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function subscribe(Request $request): Response
    {
        return $this->render('user/subscribe.html.twig', [
            // 'formMensuel' => $formMensuel->createView(),
            // 'formAnnuel' => $formAnnuel->createView(),
        ]);
    }
    #[Route('/user/payment/{id}', name: 'payment_user', methods: ['GET'])]
    public function payment(Request $request, EntityManagerInterface $entityManager, $id): Response
    {

        $user = $this->getUser();
        $prix = 0;
        if ($id === '1') {
            $type = "mensuel";
            $prix = "23,99";
        } else if ($id === '2') {
            $type = "annuel";
            $prix = "259,09";
        }

        $form = $this->createForm(PaymentType::class);
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
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $token = $randomString;
        // dd($type, $prix, $user);
        return $this->render('user/payment.html.twig', [
            'type' => $type,
            'prix' => $prix,
            'form' => $form,
            'token' => "_tokenCSRF{$token}"
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
