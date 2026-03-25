<?php

namespace App\Controller\Api;

use App\Entity\Budget;
use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/budgets', name: 'api_budgets_')]
class BudgetController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private BudgetRepository $budgetRepository,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $month = $request->query->get('month', date('Y-m'));

        $budgets = $this->budgetRepository->findBy(['month' => $month]);

        $data = array_map(fn(Budget $b) => [
            'id'           => $b->getId(),
            'month'        => $b->getMonth(),
            'amount_limit' => $b->getAmountLimit(),
            'category'     => [
                'id'    => $b->getCategory()->getId(),
                'name'  => $b->getCategory()->getName(),
                'color' => $b->getCategory()->getColor(),
            ],
        ], $budgets);

        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        if (empty($body['amount_limit']) || empty($body['month']) || empty($body['category_id'])) {
            return $this->json(['error' => 'amount_limit, month and category_id are required'], 400);
        }

        $category = $this->categoryRepository->find($body['category_id']);

        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }

        // update if budget already exists for this category/month
        $existing = $this->budgetRepository->findOneBy([
            'category' => $category,
            'month'    => $body['month'],
        ]);

        $budget = $existing ?? new Budget();
        $budget->setAmountLimit($body['amount_limit']);
        $budget->setMonth($body['month']);
        $budget->setCategory($category);

        $this->em->persist($budget);
        $this->em->flush();

        return $this->json([
            'id'           => $budget->getId(),
            'month'        => $budget->getMonth(),
            'amount_limit' => $budget->getAmountLimit(),
            'category'     => [
                'id'    => $category->getId(),
                'name'  => $category->getName(),
                'color' => $category->getColor(),
            ],
        ], 201);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Budget $budget): JsonResponse
    {
        $this->em->remove($budget);
        $this->em->flush();

        return $this->json(['message' => 'Budget deleted'], 200);
    }
}
