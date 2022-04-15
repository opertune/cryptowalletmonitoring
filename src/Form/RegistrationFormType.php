<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Email field can\'t be empty'
                    ]),
                    new Email([
                        'message' => 'The email "{{ value }}" is not a valid email.'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your email should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                        'maxMessage' => 'Your email should be at least {{ limit }} characters',
                    ])
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
                'row_attr' => [
                    'class' => 'text-danger'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => [
                    'attr' => [
                        'class' => 'password-field'
                    ],
                    'row_attr' => [
                        'class' => 'text-danger'
                    ],
                    'label_attr' => [
                        'class' => 'text-white'
                    ],
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 16,
                        'maxMessage' => 'Your password should be at least {{ limit }} characters'
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'g-recaptcha w-100 btn btn-lg btn-warning mb-2',
                    'data-sitekey' => $_ENV['GOOGLE_RECAPTCHAv3_SITE_KEY'],
                    'data-callback' => 'onSubmit',
                    'data-action' => 'submit'
                ],
                'label' => 'Register'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
