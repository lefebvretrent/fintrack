# FinTrack — Personal Finance Tracker

A full-stack personal finance tracking application built with **Vue.js 3** and **PHP 8 / Symfony**, demonstrating the core technologies used at Square One Insurance.

## Tech Stack

**Backend**
- PHP 8.5 / Symfony 7
- Doctrine ORM with MySQL 8
- RESTful JSON API

**Frontend**
- Vue.js 3 (Composition API)
- Pinia for state management
- Vue Router for client-side routing
- Chart.js / vue-chartjs for data visualisation
- Axios for HTTP requests

**Infrastructure**
- Docker / Docker Compose for local development
- MySQL 8 running in a Docker container

## Features

- Track income and expenses across custom categories
- Set monthly budgets per category
- Dashboard with real-time spending vs budget breakdown
- Doughnut and bar charts showing spending patterns
- Full CRUD for transactions, budgets and categories
- RESTful API with proper HTTP status codes and input validation

## Project Structure
```
fintrack/
├── backend/                  # Symfony PHP API
│   ├── src/
│   │   ├── Controller/Api/   # REST controllers
│   │   ├── Entity/           # Doctrine ORM entities
│   │   └── Repository/       # Database query layer
│   └── migrations/           # Database migrations
└── frontend/                 # Vue.js 3 app
    └── src/
        ├── components/       # Reusable Vue components
        ├── views/            # Page-level components
        ├── stores/           # Pinia state management
        └── services/         # API service layer
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/categories` | List all categories |
| POST | `/api/categories` | Create a category |
| DELETE | `/api/categories/{id}` | Delete a category |
| GET | `/api/transactions` | List all transactions |
| POST | `/api/transactions` | Create a transaction |
| DELETE | `/api/transactions/{id}` | Delete a transaction |
| GET | `/api/budgets?month=YYYY-MM` | List budgets for a month |
| POST | `/api/budgets` | Create or update a budget |
| GET | `/api/summary?month=YYYY-MM` | Spending vs budget summary |

## Getting Started

### Prerequisites
- PHP 8.5+
- Composer
- Node.js 20+
- Docker Desktop

### Installation

1. Clone the repository:
```bash
git clone https://github.com/lefebvretrent/fintrack.git
cd fintrack
```

2. Copy the environment file:
```bash
cp .env.example .env
```

3. Start MySQL via Docker:
```bash
docker compose up mysql -d
```

4. Install and run the backend:
```bash
cd backend
composer install
php bin/console doctrine:migrations:migrate
php -S 0.0.0.0:8000 -t public
```

5. Install and run the frontend (new terminal):
```bash
cd frontend
npm install
npm run dev
```

6. Open **http://localhost:5173** in your browser

## Architecture Decisions

**Why Symfony?** Symfony's component-based architecture, Doctrine ORM, and maker bundle make it well suited to enterprise applications where structure and maintainability matter.

**Why Pinia?** Pinia is the officially recommended state management library for Vue 3. Its Composition API-based stores mirror the same patterns used in Vue components, keeping the codebase consistent.

**Why a dedicated API service layer?** Centralising all HTTP calls in `src/services/api.js` means the rest of the app never talks to Axios directly — swapping the HTTP client or changing the base URL is a one-line change.
