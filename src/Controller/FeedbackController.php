<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use App\Repository\GoalsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'feedback')]
class FeedbackController extends AbstractController
{
    public function __construct(
        private readonly FeedbackRepository $feedbackRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly GoalsRepository $goalsRepository
    ){
    }

    #[Route('/feedback-list', name: '_list', methods: ['GET'])]
    public function index(): Response
    {
	    $currentUser = $this->getUser();

	    if (!$currentUser) {
		    return $this->redirectToRoute("app_login");
	    }
    
        $feedbacks = $this->feedbackRepository->findBy(['user' => $currentUser->getId()]);
    
        return $this->render('feedback/index.html.twig', [
            'feedbacks' => $feedbacks,
            'username' => $currentUser->getName(),
            'totalFeedback' => $this->feedbackRepository->getTotalCountsFeedbacks()
        ]);
    }

    #[Route('/feedback-create', name: '_create')]
    public function create(Request $request): Response
    {
        $feedback = new Feedback();

        $feedbackForm = $this->createForm(FeedbackType::class, $feedback);
        $feedbackForm->handleRequest($request);

        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
            $reporter = $this->getUser();
            
            $reporterName = $reporter; 
            $feedback->setReporter($reporterName);   

            $this->entityManager->persist($feedback); 
            $this->entityManager->flush();

            if ($this->security->isGranted('ROLE_ADMIN')) {

                $this->addFlash('success', sprintf('Feedback has been created successfully.')); 
                return $this->redirectToRoute('admin_list'); 

            }elseif($this->security->isGranted('ROLE_EMPLOYEE')) {

                return $this->redirectToRoute('feedback_list');  
                $this->addFlash('success', sprintf('Feedback has been created successfully.'));
            }
        }

        return $this->render('feedback/create.html.twig', [
            'feedback_form' => $feedbackForm->createView(),
        ]);
    }
}
