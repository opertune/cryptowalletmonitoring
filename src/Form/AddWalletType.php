<?php

namespace App\Form;

use App\Entity\Wallet;
use App\Subscriber\AddWalletSubscriber\AddWalletSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class addWallet extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'label' => 'Exchange',
                'placeholder' => 'Chose your exchange',
                'choices' => [
                    'Binance' => 'Binance', // Left (key) = Label, Right = value
                    'Gate.io' => 'Gate.io',
                    'Kucoin' => 'Kucoin',
                    'FTX' => 'FTX',
                    'Coinbase' => 'Coinbase',
                ],
                'row_attr' => [
                    'class' => 'text-danger'
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
                'required' => true,
                'mapped' => false
            ])
            ->add('apiKey', TextType::class, [
                'row_attr' => [
                    'class' => 'text-danger'
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your api key.'
                    ]),
                ],
                'required' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'disabled']
            ])
            ->add('secretKey', TextType::class, [
                'row_attr' => [
                    'class' => 'text-danger',
                    'id' => 'add_wallet_secretKey'
                ],
                'label_attr' => [
                    'class' => 'text-white',
                ],
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'disabled']
            ])
            ->add('passPhrase', TextType::class, [
                'row_attr' => [
                    'class' => 'text-danger',
                    'id' => 'add_wallet_passPhrase'
                ],
                'label_attr' => [
                    'class' => 'text-white',
                ],
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'disabled']
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                // Get all form field data
                $data = $event->getData();
                $form = $event->getForm();

                // If selected exchange is not ftx or coinbase we need secret key
                if ($data['name'] != 'Coinbase') {
                    // Override secretKey row with constraint
                    $form->add('secretKey', TextType::class, [
                        'row_attr' => [
                            'class' => 'text-danger',
                            'id' => 'add_wallet_secretKey'
                        ],
                        'label_attr' => [
                            'class' => 'text-white',
                        ],
                        'required' => true,
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please enter your secret key.'
                            ]),
                        ],
                        'attr' => ['autocomplete' => 'disabled']
                    ]);
                }

                if ($data['name'] == 'Kucoin') {
                    $form->add('passPhrase', TextType::class, [
                        'row_attr' => [
                            'class' => 'text-danger',
                            'id' => 'add_wallet_passPhrase'
                        ],
                        'label_attr' => [
                            'class' => 'text-white',
                        ],
                        'required' => true,
                        'mapped' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please enter your secret key.'
                            ]),
                        ],
                        'attr' => ['autocomplete' => 'disabled']
                    ]);
                }
            })
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Add wallet'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
