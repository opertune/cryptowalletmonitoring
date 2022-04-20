<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'translation_domain' => 'resetPassword',
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'resetPasswordNotBlank'
                    ]),
                    new Email([
                        'message' => 'resetPasswordEmail'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'resetPasswordLengthMin',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                        'maxMessage' => 'resetPasswordLengthMax',
                    ])
                ],
                'required' => true,
                'label' => 'form.email',
                'row_attr' => [
                    'class' => 'form-floating text-dark'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'form.email'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
