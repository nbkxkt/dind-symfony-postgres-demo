<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(Connection $connection): Response
    {
        $entries = $connection->fetchAllAssociative(
            'SELECT id, label, value, created_at FROM demo_entry ORDER BY id ASC'
        );

        return $this->render('home/index.html.twig', ['entries' => $entries]);
    }

    #[Route('/healthz', name: 'app_health', methods: ['GET'])]
    public function health(Connection $connection): JsonResponse
    {
        $connection->executeQuery('SELECT 1')->fetchOne();
        $count = (int) $connection->fetchOne('SELECT COUNT(*) FROM demo_entry');

        return $this->json(['status' => 'ok', 'database' => 'postgresql', 'sample_rows' => $count]);
    }
}
