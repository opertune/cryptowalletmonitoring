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
                'translation_domain' => 'register',
                'constraints' => [
                    new NotBlank([
                        'message' => 'emailNotBlank'
                    ]),
                    new Email([
                        'message' => 'emailFormat'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'emailMinLength',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                        'maxMessage' => 'emailMaxLength',
                    ])
                ],
                'label' => 'emailInput',
                'label_attr' => [
                    'class' => 'text-white'
                ],
                'row_attr' => [
                    'class' => 'text-danger'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'translation_domain' => 'register',
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'passwordRepeatNotMatch',
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
                    'label' => 'passwordInput',
                ],
                'second_options' => [
                    'label' => 'passwordInputRepeat',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'passwordNotBlank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'passwordMinLength',
                        // max length allowed by Symfony for security reasons
                        'max' => 16,
                        'maxMessage' => 'passwordMaxLength'
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'translation_domain' => 'register',
                'attr' => [
                    'class' => 'g-recaptcha w-100 btn btn-lg btn-warning mb-2',
                    'data-sitekey' => $_ENV['GOOGLE_RECAPTCHAv3_SITE_KEY'],
                    'data-callback' => 'onSubmit',
                    'data-action' => 'submit'
                ],
                'label' => 'submitButton'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
