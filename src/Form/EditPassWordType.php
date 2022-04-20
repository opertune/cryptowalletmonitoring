<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditPassWordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("old_password", PasswordType::class, [
                'translation_domain' => 'account',
                'constraints' => [
                    new NotBlank([
                        'message' => 'editPasswordOldNotBlank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'editPasswordOldLengthMin',
                        // max length allowed by Symfony for security reasons
                        'max' => 16,
                        'maxMessage' => 'editPasswordOldLengthMax'
                    ]),
                ],
                'required' => true,
                'mapped' => false,
                'label' => 'editPassword.old',
                'row_attr' => [
                    'class' => 'form-floating text-dark'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'editPassword.old'
                ],
            ])
            ->add("new_password", RepeatedType::class, [
                'translation_domain' => 'account',
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'editPasswordNewNotBlank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'editPasswordNewLengthMin',
                        // max length allowed by Symfony for security reasons
                        'max' => 16,
                        'maxMessage' => 'editPasswordNewLengthMax'
                    ])
                ],
                'mapped' => false,
                'required' => true,
                'invalid_message' => 'editPasswordMatch',
                'options' => [
                    'row_attr' => [
                        'class' => 'password-field form-floating text-dark'
                    ],
                ],
                'first_options' => [
                    'label' => 'editPassword.new',
                    'attr' => [
                        'placeholder' => 'editPassword.new',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
                ],
                'second_options' => [
                    'label' => 'editPassword.repeat',
                    'attr' => [
                        'placeholder' => 'editPassword.repeat',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
                ],
            ])
            ->add("save", SubmitType::class, [
                'translation_domain' => 'account',
                'attr' => [
                    'class' => 'btn w-100 btn btn-lg btn-warning'
                ],
                'label' => 'editPassword.save'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
