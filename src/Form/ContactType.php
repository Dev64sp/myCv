<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'class' => 'input-message-contact',
                    'placeholder' => 'Nom et prénom',
                    'value'=> ''
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input-message-contact',
                    'placeholder' => 'Email',
                    'value'=> ''
                ]
            ])
            ->add('subject', null, [
                'label' => false,
                'attr' => [
                    'class' => 'input-message-contact',
                    'placeholder' => 'Object',
                    'value'=> ''
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'row_attr' => [
                    'placeholder' => 'Message',
                    'wrap' => true
                ], 
                'attr' => [
                    'class' => 'textarea-message-contact',
                    'value'=> '',
                ]
            ])
            ->add('captcha', ReCaptchaType::class)
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'button-message-contact',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
