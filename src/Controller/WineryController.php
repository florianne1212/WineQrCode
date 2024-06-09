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
use Gedmo\Sluggable\Util\Urlizer;
use App\Service\UploaderHelper;


#[Route('/winery')]
class WineryController extends AbstractController
{
    #[Route('/{id}', name: 'app_winery_show')]
    public function show(Winery $winery, UrlGeneratorInterface $urlGenerator): Response
    {
        if($this->getUser())
        {
            $isowner = $this->getUser()->getWinery() === $winery;
        }
            $id = $winery->getId();
     
        return $this->render('winery/show.html.twig', [
            'winery' => $winery,
            'id' => $id,
            'isowner' => $this->getUser() ? $isowner : false,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_winery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Winery $winery, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(WineryType::class, $winery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadWineryImage($uploadedFile);
                $winery->setImageFilename($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_winery_show', ['id' => $winery->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('winery/edit.html.twig', [
            'winery' => $winery,
            'form' => $form,
        ]);
    }
}
