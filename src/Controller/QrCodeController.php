<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Builder\BuilderInterface;

class QrCodeController extends AbstractController
{
    public function createQrCode(BuilderInterface $qrBuilder, string $data): Response
    {
        // $data = $request->query->get('data');
        $qrCode = $qrBuilder
            ->size(400)
            ->margin(20)
            ->data($data)
            ->build();
        $base64 = $qrCode->getDataUri();

        return new Response($base64);
    }
}
