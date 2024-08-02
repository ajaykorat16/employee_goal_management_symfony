<?php


namespace App\Form;

use App\Entity\Feedback;
use App\Entity\FeedbackCategory;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function __construct(
        private readonly Security $security,
    ){
    }

     public function buildForm(FormBuilderInterface $builder, array $options)
     {
        $builder
            ->add('category', EntityType::class, [
                'class' => FeedbackCategory::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'name', 
                'attr' => ['class' => 'form-control', 'style' => 'height:35px'],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => function(User $user) {
                    return $user->getName() . ' (' . $user->getDepartment() . ')';
                },
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('u');

                        $qb->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_EMPLOYEE%');
                    return $qb;
                },
                'attr' => ['class' => 'form-control', 'style' => 'height:35px'],
            ])
            ->add('description',TextareaType::class,[
                'row_attr' => ['class' => 'form-group mb-4'],
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
            'data_class' => Feedback::class,
        ]);

    }

}