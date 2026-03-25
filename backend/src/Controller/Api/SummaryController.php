<?php

namespace App\Controller\Api;

use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/summary', name: 'api_summary_')]
class SummaryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private TransactionRepository $transactionRepository,
        private BudgetRepository $budgetRepository
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $month = $request->query->get('month', date('Y-m'));

        // get date range for the month
        $startDate = new \DateTime($month . '-01');
        $endDate   = (clone $startDate)->modify('last day of this month');

        $categories = $this->categoryRepository->findAll();

        $summary = [];
        $totalIncome   = 0;
        $totalExpenses = 0;

        foreach ($categories as $category) {
            // get all transactions for this category in this month
            $transactions = $this->transactionRepository->findByDateRangeAndCategory(
                $startDate,
                $endDate,
                $category
            );

            $spent  = 0;
            $income = 0;

            foreach ($transactions as $transaction) {
                if ($transaction->getType() === 'expense') {
                    $spent += (float) $transaction->getAmount();
                } else {
                    $income += (float) $transaction->getAmount();
                }
            }

            // get budget for this category/month
            $budget = $this->budgetRepository->findOneBy([
                'category' => $category,
                'month'    => $month,
            ]);

            $budgetLimit = $budget ? (float) $budget->getAmountLimit() : 0;
            $remaining   = $budgetLimit - $spent;

            $totalExpenses += $spent;
            $totalIncome   += $income;

            $summary[] = [
                'category'     => [
                    'id'    => $category->getId(),
                    'name'  => $category->getName(),
                    'color' => $category->getColor(),
                ],
                'spent'        => round($spent, 2),
                'income'       => round($income, 2),
                'budget_limit' => round($budgetLimit, 2),
                'remaining'    => round($remaining, 2),
                'over_budget'  => $spent > $budgetLimit && $budgetLimit > 0,
            ];
        }

        return $this->json([
            'month'          => $month,
            'total_income'   => round($totalIncome, 2),
            'total_expenses' => round($totalExpenses, 2),
            'net'            => round($totalIncome - $totalExpenses, 2),
            'categories'     => $summary,
        ]);
    }
}
