<?php

namespace App\Form;

use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddWalletType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'translation_domain' => 'wallet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'nameNotBlank'
                    ]),
                ],
                'choices' => [
                    'Binance' => 'Binance', // Left (key) = Label, Right = value
                    'Gate.io' => 'Gate.io',
                    'Kucoin' => 'Kucoin',
                    'FTX' => 'FTX',
                    'Coinbase' => 'Coinbase',
                ],
                'required' => true,
                'mapped' => false,
                'multiple' => false,
                'label' => 'addWalletForm.exchangeName',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'placeholder' => 'addWalletForm.exchangeName',
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                ],
            ])
            ->add('apiKey', TextType::class, [
                'translation_domain' => 'wallet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'apiKeyNotBlank'
                    ]),
                ],
                'required' => true,
                'mapped' => false,
                'label' => 'addWalletForm.apiKey',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'addWalletForm.apiKey',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('secretKey', TextType::class, [
                'translation_domain' => 'wallet',
                'constraints' => [
                    new NotBlank([
                        'message' => 'secretKeyNotBlank'
                    ]),
                ],
                'required' => true,
                'mapped' => false,
                'label' => 'addWalletForm.secretKey',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'addWalletForm.secretKey',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('passPhrase', TextType::class, [
                'translation_domain' => 'wallet',
                'required' => false,
                'mapped' => false,
                'label' => 'addWalletForm.passPhrase',
                'row_attr' => [
                    'id' => 'passPhraseID',
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'form-control customInput mb-2',
                    'placeholder' => 'addWalletForm.passPhrase',
                    'autocomplete' => 'off'
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                // Get all form field data
                $data = $event->getData();
                $form = $event->getForm();

                if ($data['name'] == 'Kucoin') {
                    $form->add('passPhrase', TextType::class, [
                        'translation_domain' => 'wallet',
                        'constraints' => [
                            new NotBlank([
                                'message' => 'passPhraseKeyNotBlank'
                            ]),
                        ],
                        'required' => true,
                        'mapped' => false,
                        'label' => 'addWalletForm.passPhrase',
                        'row_attr' => [
                            'id' => 'passPhraseID',
                            'class' => 'form-floating'
                        ],
                        'attr' => [
                            'class' => 'form-control customInput mb-2',
                            'placeholder' => 'addWalletForm.passPhrase',
                            'autocomplete' => 'off'
                        ],
                    ]);
                }
            })
            ->add('save', SubmitType::class, [
                'translation_domain' => 'wallet',
                'row_attr' => [
                    'class' => 'form-floating'
                ],
                'attr' => [
                    'class' => 'w-100 btn btn-lg btn-warning'
                ],
                'label' => 'addWalletForm.submitButton'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
