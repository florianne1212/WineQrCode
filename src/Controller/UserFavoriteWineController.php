<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Wine;
use App\Entity\UserFavoriteWine;
use Doctrine\Persistence\ManagerRegistry;


class UserFavoriteWineController extends AbstractController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    #[Route('/user/favorite/wine', name: 'app_user_favorite_wine')]
    public function index(): Response
    {
        $favorite = $this->getUser()->getWinesFavoritedByUser();

        return $this->render('user_favorite_wine/index.html.twig', [
            'controller_name' => 'UserFavoriteWineController',
            'favorite' => $favorite,
        ]);
    }

    public function removeWineFromFavorite( $userId, $wineId): Response
    {
        $entityManager = $this->doctrine->getManager();
        $favoriteWine = $entityManager->getRepository(UserFavoriteWine::class)->findOneBy(['user' => $userId, 'wine' => $wineId]);

        if ($favoriteWine) {
            $entityManager->remove($favoriteWine);
            $entityManager->flush();    
            return new Response('Favorite wine relation deleted successfully');
        } else {
            return new Response('Favorite wine relation not found', Response::HTTP_NOT_FOUND);
        }
    }
}