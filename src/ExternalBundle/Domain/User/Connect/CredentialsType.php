<?php

namespace ExternalBundle\Domain\User\Connect;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CredentialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'attr' => ['placeholder' => 'form.email.placeholder'],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'form.password',
                'attr' => ['placeholder' => 'form.password.placeholder'],
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Credentials::class,
            'method' => 'POST',
            'translation_domain' => 'UserConnect',
        ]);
    }
}
