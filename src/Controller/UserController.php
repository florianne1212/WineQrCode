<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Winery;

class UserController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/user/me', name: 'app_user')]
    public function index(): Response
    {
        if ($this->getUser()) {
            $isProducer = in_array('ROLE_IS_PRO', $this->getUser()->getRoles());

        $entityManager = $this->doctrine->getManager();
        $id = $this->getUser()->getId();
        $winery = $this->getUser()->getWinery();
        }
        return $this->render('user/index.html.twig', [
            'isProducer' => $isProducer,
            'winery' => $winery,
            'id' => $id,
        ]);
    }
}
