<?php

namespace App\Controller\Api;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transactions', name: 'api_transactions_')]
class TransactionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $transactions = $this->transactionRepository->findBy(
            [],
            ['date' => 'DESC']
        );

        $data = array_map(fn(Transaction $t) => [
            'id'          => $t->getId(),
            'amount'      => $t->getAmount(),
            'description' => $t->getDescription(),
            'date'        => $t->getDate()->format('Y-m-d'),
            'type'        => $t->getType(),
            'category'    => [
                'id'    => $t->getCategory()->getId(),
                'name'  => $t->getCategory()->getName(),
                'color' => $t->getCategory()->getColor(),
            ],
        ], $transactions);

        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        if (empty($body['amount']) || empty($body['description']) || empty($body['date']) || empty($body['type']) || empty($body['category_id'])) {
            return $this->json(['error' => 'amount, description, date, type and category_id are required'], 400);
        }

        $category = $this->categoryRepository->find($body['category_id']);

        if (!$category) {
            return $this->json(['error' => 'Category not found'], 404);
        }

        $transaction = new Transaction();
        $transaction->setAmount($body['amount']);
        $transaction->setDescription($body['description']);
        $transaction->setDate(new \DateTime($body['date']));
        $transaction->setType($body['type']);
        $transaction->setCategory($category);

        $this->em->persist($transaction);
        $this->em->flush();

        return $this->json([
            'id'          => $transaction->getId(),
            'amount'      => $transaction->getAmount(),
            'description' => $transaction->getDescription(),
            'date'        => $transaction->getDate()->format('Y-m-d'),
            'type'        => $transaction->getType(),
            'category'    => [
                'id'    => $category->getId(),
                'name'  => $category->getName(),
                'color' => $category->getColor(),
            ],
        ], 201);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Transaction $transaction): JsonResponse
    {
        $this->em->remove($transaction);
        $this->em->flush();

        return $this->json(['message' => 'Transaction deleted'], 200);
    }
}
