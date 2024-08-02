<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmployeeType;
use App\Repository\GoalsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin', name: 'admin')]
class IndexController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly GoalsRepository $goalsRepository
    ){
    }

    #[Route('', name: '_list', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/employee/index.html.twig',[
            'users' => $this->userRepository->getEmployees(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(EmployeeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password =  $form->get('password')->getData();
            $hashPassword = $passwordHasher->hashPassword($user,$password);
            $user->setPassword($hashPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'New employee has been created successfully');

            return $this->redirectToRoute('admin_list');
        }

        return $this->render('admin/employee/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): Response
    {
        $selectedRole = $user->getRoles()[0];

        $form = $this->createForm(EmployeeType::class, $user, 
                ['selected_role' => $selectedRole]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password =  $form->get('password')->getData();

            if ($password) {
                $encodedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            } else {
                $user->setPassword($user->getPassword());
            }    

            $this->entityManager->flush();

            $this->addFlash('success', sprintf('Employee %d has been updated successfully.', $user->getId()));

            return $this->redirectToRoute('admin_list');
        }

        return $this->render('admin/employee/create.html.twig', [
            'form' => $form->createView(),
            'users' => $user,
        ]);
    }

    #[Route('/show/{id}', name: '_show')]
    public function show(User $user): Response
    {
        return $this->render('admin/employee/show.html.twig', [
            'user' => $user,
            'goals' => $this->goalsRepository->findBy(['user' => $user->getId()]),
            'totalGoals' => $this->goalsRepository->getTotalCountsGoals(),
        ]);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete(User $user)
    {   
        $feedbacks = $user->getFeedback();

        $goals = $user->getGoal();

        foreach ($goals as $goal) {
            $this->entityManager->remove($goal);
        }

        foreach ($feedbacks as $feedback) {
            $this->entityManager->remove($feedback);
        }
        
        // Now remove the user
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    
        $this->addFlash('success', 'You have successfully deleted this employee');
        return $this->redirectToRoute('admin_list');
    }
}