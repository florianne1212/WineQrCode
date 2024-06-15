<?php

namespace App\Controller;

use App\Entity\Wine;
use App\Entity\Winery;
use App\Form\WineType;
use App\Entity\UserFavoriteWine;
use App\Repository\WineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\UploaderHelper;



#[Route('/wine')]
class WineController extends AbstractController
{
    #[Route('/', name: 'app_wine_index', methods: ['GET'])]
    public function index(WineRepository $wineRepository): Response
    {
        $wines = $wineRepository->findAll();
        $user = $this->getUser();
        foreach ($wines as $wine) {
            $winesWithOwnerInfo[] = [
                'id' => $wine->getId(),
                'name' => $wine->getName(),
                'ImageFileName' => $wine->getImageFileName(),
                'region' => $wine->getRegion(),
                'isowner' => $wine->getOwner() === $user
            ];
        }
        return $this->render('wine/index.html.twig', [
            'wines' => $wines ? $winesWithOwnerInfo : [],
        ]);
    }

    #[Route('/me', name: 'app_wine_owner', methods: ['GET'])]
    public function my_wines(EntityManagerInterface $entityManager): Response
    {
        $owner_wines = $this->getUser()->getWines();
        return $this->render('wine/owner.html.twig', [
            'wines' => $owner_wines,
        ]);
    }

    #[Route('/new', name: 'app_wine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $wine = new Wine();
        $form = $this->createForm(WineType::class, $wine);
        $form->handleRequest($request);
        $wine->setOwner($this->getUser());
        $wine->setWinery($this->getUser()->getWinery());

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadWineImage($uploadedFile);
                $wine->setImageFilename($newFilename);
            }
            $entityManager->persist($wine);
            $entityManager->flush();

            return $this->redirectToRoute('app_wine_show', ['id' => $wine->getId()]);

        }

        return $this->render('wine/new.html.twig', [
            'wine' => $wine,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_wine_show', methods: ['GET'])]
    public function show(Wine $wine, UrlGeneratorInterface $urlGenerator): Response
    {
        $qrCodeUrl = 'http://127.0.0.1:3000/qr/code';
        $wineUrl = $urlGenerator->generate('app_wine_show', ['id' => $wine->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = $this->forward('App\Controller\QrCodeController::createQrCode', [
            'url' => $wineUrl,
        ]);
        $qrCode = $response->getContent();
        if($this->getUser())
            $isFavorite = $this->getUser()->isWineFavoritedByUser($wine);
        $winery = $wine->getWinery();
        $isowner = $wine->getOwner() === $this->getUser();

        return $this->render('wine/show.html.twig', [
            'wine' => $wine,
            'qrCode' => $qrCode,
            'isFavorite' => $this->getUser() ? $isFavorite : false,
            'winery' => $winery,
            'isowner' => $isowner,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wine $wine, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(WineType::class, $wine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadWineImage($uploadedFile);
                $wine->setImageFilename($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_wine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wine/edit.html.twig', [
            'wine' => $wine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_wine_delete', methods: ['POST'])]
    public function delete(Request $request, Wine $wine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wine->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($wine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wine_owner', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/favorite/add', name: 'app_add_wine_favorite', methods: ['POST'])]
    public function add_favorite(Request $request, Wine $wine, EntityManagerInterface $entityManager): Response
    {

        $response = $this->forward('App\Controller\UserFavoriteWineController::addWineToFavorite', [
            'wine' => $wine,
        ]);

        return $this->redirectToRoute('app_wine_show', ['id' => $wine->getId(), 'result' => '$response']);
    }

    #[Route('/{id}/favorite', name: 'app_remove_wine_favorite', methods: ['POST', 'GET'])]
    public function remove_favorite(Request $request, Wine $wine, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        $wineId = $wine->getId();

        $response = $this->forward('App\Controller\UserFavoriteWineController::removeWineFromFavorite', [
            'userId' => $userId,
            'wineId' => $wineId,
        ]);

        return $this->redirectToRoute('app_wine_show', ['id' => $wine->getId()]);
    }
}