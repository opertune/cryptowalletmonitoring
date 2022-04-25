<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'translation_domain' => 'contact',
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
            ->add('message', TextareaType::class, [
                'translation_domain' => 'contact',
                'constraints' => [
                    new NotBlank([
                        'message' => 'contactMessageNotBlank'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'contactMessageMinLenght',
                    ])
                ],
                'required' => true,
                'label' => 'messageInput',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'messageInput',
                ],
            ])
            ->add('send', SubmitType::class, [
                'translation_domain' => 'contact',
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
            // Configure your form options here
        ]);
    }
}
