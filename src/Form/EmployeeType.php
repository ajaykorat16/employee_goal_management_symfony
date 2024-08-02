<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
       $builder
            ->add('name', TextType::class,[
                'required' => true,
            ])
            ->add('email', EmailType::class,[
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => [
                        'label' => 'Password',
                        'attr' => ['class' => 'form-control'],
                    ],
                    'second_options' => [
                        'label' => 'Confirm Password',
                        'attr' => ['class' => 'form-control'],
                    ],
                    'mapped' => false
            ])
            ->add('department', TextType::class,[
                    'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'EMPLOYEE' => 'ROLE_EMPLOYEE',
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
                'choice_attr' => function($choiceValue) use ($options) {
                    return $choiceValue === $options['selected_role'] ? ['selected' => 'selected'] : [];
                },
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success save',
                    
                ],
                'label' => 'Save'
            ])
        ;

        $builder->get('roles')->addModelTransformer(new CallbackTransformer(
            function ($rolesAsArray) : string {
                if (is_array($rolesAsArray)) {
                    return implode(', ', $rolesAsArray);
                }
                return ''; 
            },
            function ($rolesAsString) : array {
                if (!empty($rolesAsString)) {
                    return explode(', ', $rolesAsString);
                }
                return []; 
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'selected_role' => null,
            'password_value' => null,
        ]);
    }
}