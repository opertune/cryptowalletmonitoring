<?php

namespace App\Form;

use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class addWallet extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'label' => 'Exchange',
                'choices' => [
                    'Binance' => 'binance',
                    'FTX' => 'ftx',
                    'Kucoin' => 'kucoin',
                    'Gate.io' => 'gate',
                    'Coinbase' => 'coinbase',
                ],
                'row_attr' => [
                    'class' => 'text-danger'
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
            ])
            ->add('apiKey', TextType::class, [
                'row_attr' => [
                    'class' => 'text-danger'
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
            ])
            ->add('secretKey', TextType::class, [
                'row_attr' => [
                    'class' => 'text-danger'
                ],
                'label_attr' => [
                    'class' => 'text-white'
                ],
            ])
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
