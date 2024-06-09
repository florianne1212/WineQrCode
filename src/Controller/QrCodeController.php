<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;

class QrCodeController extends AbstractController
{
    public function createQrCode(BuilderInterface $qrBuilder, string $url): Response
    {
        $qrCode = $qrBuilder
            ->size(400)
            ->margin(20)
            ->data($url)
            ->build();
        $base64 = $qrCode->getDataUri();

        return new Response($base64);
    }
}
