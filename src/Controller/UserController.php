<?php

namespace App\Controller;

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
        return $this->render('user/subscribe.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/profile', name: 'profile_user')]
    public function profile(): Response
    {
        $user = $this->getUser();
        // dd($user);
        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/update', name: 'profile_update')]
    public function update(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            if ($plainPassword !== $confirmPassword) {
                $form->get('confirmPassword')->addError(new FormError('Les mots de passe ne correspondent pas'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form
                ]);
            }
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName($form->get('firstName')->getData());
            $user->setLastName($form->get('lastName')->getData());
            $user->setAddress($form->get('address')->getData());
            $user->setPostal($form->get('postal')->getData());
            $user->setCity($form->get('city')->getData());
            $user->setTelephone($form->get('telephone')->getData());
            $user->setBirthDate($form->get('birthDate')->getData());


            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // return $security->login($user, UserAuthenticator::class, 'main');
        }
        // dd($user);
        return $this->render('user/update.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
