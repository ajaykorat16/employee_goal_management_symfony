<?php

namespace App\Controller;

use App\Entity\Goals;
use App\Entity\User;
use App\Form\GoalsType;
use App\Repository\GoalsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'goal')]
class GoalsController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly GoalsRepository $goalsRepository
    ){
    }

    #[Route('/goal-list', name: '_list')]
    public function list(): Response
    {
        $user = $this->getUser();

        return $this->render('admin/employee/show.html.twig', [
            'user' => $user,
            'goals' => $this->goalsRepository->getGoals(
                $user,
                GoalsRepository::PAGE_SIZE,
                GoalsRepository::OFFSET
            ),
            'totalGoals' => $this->goalsRepository->getTotalCountsGoals($user),
        ]);
    }

    #[Route('/update-date/{id}', name: '_upadteDate',  options: ['expose' => true])]
    public function edit(Request $request, Goals $goal): JsonResponse
    {
        $completedDate = $request->request->get('completedDate');

        if ($completedDate) {
            $goal->setCompletedDate(new \DateTime($completedDate));
            $this->entityManager->flush();
            return new JsonResponse(['status' => 'success', 'message' => 'Goal updated successfully.']);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'No data received'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/create-goal/{id}', name: '_create', options: ['expose' => true])]
    public function create(Request $request, User $user): JsonResponse
    {
        $goal = new Goals();
        $form = $this->createForm(GoalsType::class, $goal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $current_user = $this->getUser();

            $goal->setReporter($current_user);
            $goal->setUser($user);

            $this->entityManager->persist($goal);
            $this->entityManager->flush();

            $this->addFlash('success', 'Goal created successfully!');

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'status' => 'success',
                    'content' => $this->renderView('admin/modal/_goals_form.html.twig', [
                        'goals_form' => $form->createView(),
                        'action' => $this->generateUrl('goal_create', ['id' => $user->getId()]),
                    ])
                ]);
            }

            return $this->json([
                'status' => 'success',
                'content' => $this->renderView('admin/modal/_goals_form.html.twig', [
                    'goals_form' => $form->createView(),
                    'action' => $this->generateUrl('goal_create', ['id' => $user->getId()]),
                ])
            ]);
        }

        // Return JsonResponse for form validation errors
        return $this->json([
            'status' => 'error',
            'content' => $this->renderView('admin/modal/_goals_form.html.twig', [
                'goals_form' => $form->createView(),
                'action' => $this->generateUrl('goal_create', ['id' => $user->getId()]),
            ])
        ]);
    }

    #[Route('/edit-goal/{id}', name: '_edit', options: ['expose' => true])]
    public function editGoal(Request $request, Goals $goal): JsonResponse
    {
        $form = $this->createForm(GoalsType::class, $goal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reporter = $this->getUser();
            $goal->setReporter($reporter);
            
            $this->entityManager->flush();

            $this->addFlash('success', 'Goal Updated Successfully!');

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'status' => 'success',
                    'content' => $this->renderView('admin/modal/_goals_form.html.twig', [
                        'goals_form' => $form->createView(),
                        'action' => $this->generateUrl('goal_edit', ['id' => $goal->getId()]),
                    ])
                ]);
            }
        }

        return $this->json([
            'status' => 'error',
            'content' => $this->renderView('admin/modal/_goals_form.html.twig', [
                'goals_form' => $form->createView(),
                'action' => $this->generateUrl('goal_edit', ['id' => $goal->getId()]),
            ])
        ]);
    }
}