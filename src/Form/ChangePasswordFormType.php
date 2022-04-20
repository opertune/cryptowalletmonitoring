<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'translation_domain' => 'resetPassword',
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'editPasswordOldNotBlank',
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
                'invalid_message' => 'resetPasswordFormMatch',
                'options' => [
                    'row_attr' => [
                        'class' => 'password-field form-floating text-dark'
                    ],
                ],
                'first_options' => [
                    'label' => 'resetForm.newPassword',
                    'attr' => [
                        'placeholder' => 'resetForm.newPassword',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
                ],
                'second_options' => [
                    'label' => 'resetForm.newPasswordRepeat',
                    'attr' => [
                        'placeholder' => 'resetForm.newPasswordRepeat',
                        'class' => 'password-field form-control customInput mb-2'
                    ],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
