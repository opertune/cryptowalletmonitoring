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
                'required' => true,
                'label' => 'emailInput',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'emailInput'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'translation_domain' => 'register',
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'passwordRepeatNotMatch',
                'options' => [
                    'row_attr' => [
                        'class' => 'form-floating'
                    ],
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'passwordInput',
                    'attr' => [
                        'placeholder' => 'passwordInput',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
                ],
                'second_options' => [
                    'label' => 'passwordInputRepeat',
                    'attr' => [
                        'placeholder' => 'passwordInputRepeat',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
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
                ],
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'translation_domain' => 'register',
                'attr' => [
                    'class' => 'g-recaptcha w-100 btn btn-lg btn-warning',
                    'data-sitekey' => $_ENV['GOOGLE_RECAPTCHAv3_SITE_KEY'],
                    'data-callback' => 'onSubmit',
                    'data-action' => 'submit'
                ],
                'label' => 'submitButton',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
