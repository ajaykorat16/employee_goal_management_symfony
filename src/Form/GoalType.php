<?php


namespace App\Form;

use App\Entity\Goals;
use App\Entity\GoalsCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalsType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder
            ->add('category', EntityType::class, [
                'class' => GoalsCategory::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name', 
                'attr' => ['class' => 'form-control', 'style' => 'height:35px'],
            ])
            ->add('description',TextareaType::class,[
                'row_attr' => ['class' => 'form-group mb-4'],
            ])
            ->add('reporter', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'attr' => ['style' => 'display:none;'],
                'mapped' => false, 
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success save',
                    ],
                    'label' => 'Save',
            ])
            
        ;
     }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Goals::class,
        ]);

    }

}