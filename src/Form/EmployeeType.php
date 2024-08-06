<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('roles', HiddenType::class, [
                'data' => 'ROLE_EMPLOYEE'
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success save',
                    
                ],
                'label' => 'Save'
            ])
        ;
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $data['roles'] = 'ROLE_EMPLOYEE';
            $event->setData($data);
        });
        
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
        ]);
    }
}