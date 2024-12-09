<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prénom'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom'],
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Date de naissance'],
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime('-18 years'),
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse'],
                'label' => 'Adresse',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('postal', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Code postal'],
                'label' => 'Code postal',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^0[0-9]*$/',
                        'message' => 'Le nombre doit commencer par un zéro',
                    ]),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Téléphone'],
                'label' => 'Téléphone',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ville'],
                'label' => 'Ville',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => ['class' => 'form-check-input'],
                'label' => 'J\'accepte les conditions d\'utilisation',
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control', 'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Assert\Callback([
                        'callback' => function ($object, ExecutionContextInterface $context) {
                            $form = $context->getRoot();

                            $plainPassword = $form->get('plainPassword')->getData();
                            $confirmPassword = $form->get('confirmPassword')->getData();

                            if ($plainPassword !== $confirmPassword) {
                                $context
                                    ->buildViolation('The passwords do not match')
                                    ->atPath('confirmPassword')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
                'label' => 'form.password',

                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'form.confirm_password',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control', 'placeholder' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
