<?php

namespace App\Controller;

use App\Entity\Winery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\WineryType;


#[Route('/winery')]
class WineryController extends AbstractController
{
    #[Route('/', name: 'app_winery')]
    public function index(): Response
    {
        return $this->render('winery/index.html.twig', [
            'controller_name' => 'WineryController',
        ]);
    }

    #[Route('/{_id}', name: 'app_winery_show')]
    public function show(Winery $winery, UrlGeneratorInterface $urlGenerator): Response
    {
        $winery = $this->getUser()->getWinery();
        $id = $winery->getId();
        return $this->render('winery/show.html.twig', [
            'winery' => $winery,
            'id' => $id,
        ]);
    }

    // #[Route('/{_id}/edit', name: 'app_winery_edit')]
    // public function edit(Request $request, Winery $winery, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(WineryType::class, $winery);
    //     $form->handleRequest($request);


    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         // return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('winery/edit.html.twig', [
    //         'winery' => $winery,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_winery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Winery $winery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WineryType::class, $winery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_winery_show', ['_id' => $winery->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('winery/edit.html.twig', [
            'winery' => $winery,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_wine_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Wine $wine, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(WineType::class, $wine);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_wine_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('wine/edit.html.twig', [
    //         'wine' => $wine,
    //         'form' => $form,
    //     ]);
    // }
}
