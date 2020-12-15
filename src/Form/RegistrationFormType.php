<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\VarDumper\Cloner\Data;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-4', 'autofocus' => true],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ])]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control mb-4'],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a valid email address',
                    ])]
            ])
            ->add('password_digest', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'invalid_message' => 'Passwords do not match',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'csrf_protection' => false
        ]);
    }
}
