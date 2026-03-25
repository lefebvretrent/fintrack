<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'api_categories_')]
class CategoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();

        $data = array_map(fn(Category $c) => [
            'id'    => $c->getId(),
            'name'  => $c->getName(),
            'color' => $c->getColor(),
        ], $categories);

        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        if (empty($body['name']) || empty($body['color'])) {
            return $this->json(['error' => 'name and color are required'], 400);
        }

        $category = new Category();
        $category->setName($body['name']);
        $category->setColor($body['color']);

        $this->em->persist($category);
        $this->em->flush();

        return $this->json([
            'id'    => $category->getId(),
            'name'  => $category->getName(),
            'color' => $category->getColor(),
        ], 201);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Category $category): JsonResponse
    {
        $this->em->remove($category);
        $this->em->flush();

        return $this->json(['message' => 'Category deleted'], 200);
    }
}
