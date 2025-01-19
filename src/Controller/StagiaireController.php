<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use App\Entity\Stage;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stagiaire')]
final class StagiaireController extends AbstractController
{
    #[Route(name: 'app_stagiaire_index', methods: ['GET'])]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stagiaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stagiaire/new.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stagiaire_show', methods: ['GET'])]
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stagiaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stagiaire_delete', methods: ['POST'])]
    public function delete(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stagiaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/register-internship/{stageId}', name: 'app_stagiaire_register_stage', methods: ['POST'])]
    public function registerForStage(int $id, int $stageId, EntityManagerInterface $entityManager): Response
    {
        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($id);
        $stage = $entityManager->getRepository(Stage::class)->find($stageId);

        if (!$stagiaire || !$stage) {
            return $this->redirectToRoute('app_stagiaire_index');
        }

        if ($stage->getEndDate() < new \DateTime()) {
            $stagiaire->addStage($stage);
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            $this->addFlash('success', 'Stage registration successful!');
        } else {
            $this->addFlash('error', 'Cannot register for this stage. It has not ended yet.');
        }

        return $this->redirectToRoute('app_stagiaire_show', ['id' => $id]);
    }

    
    #[Route('/{id}/account', name: 'app_stagiaire_account', methods: ['GET'])]
    public function viewAccount(int $id, EntityManagerInterface $entityManager, StageRepository $stageRepository): Response
    {
        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($id);

        if (!$stagiaire) {
            throw $this->createNotFoundException('Student not found');
        }

        return $this->render('stagiaire/account.html.twig', [
            'stagiaire' => $stagiaire,
            'stages' => $stageRepository //FETCHES INTERNSHIPS FROM IMPORT
        ]);
    }
}
