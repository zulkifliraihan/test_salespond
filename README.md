# CRM Application – Salespond Technical Test

This project is a **technical test submission** for **Salespond**, implementing a CRM system with a Laravel backend and Vue frontend using modern development patterns and Docker containerization.

---

## 📦 Prerequisites

Ensure the following tools are installed:

- [Docker](https://www.docker.com/) & [Docker Compose](https://docs.docker.com/compose/)
- *(Optional)* [Node.js](https://nodejs.org/) & Yarn/NPM – for running the frontend manually

---

## 🚀 Running the Application via Docker

1. **Clone this repository**

2. **Setup environment variables**  
   Copy the `.env.example` files to `.env` in both the `backend` and `frontend_new` directories.

3. **Run the following command from the project root**  
   ```sh
   docker-compose up --build
   ```

4. **Access the services:**
   - Backend API: [http://localhost:8000](http://localhost:8000)
   - Frontend (served via Nginx): [http://localhost:8080](http://localhost:8080)
   - MySQL: Port `3308`, user: `root`, password: `root`
   - Redis: Port `6378`

---

## 📁 Project Structure

```
.
├── backend
├── frontend_new
├── nginx
└── docker-compose.yml
```

---

## 🔧 Backend Details (Laravel 12)

The backend is built using **Laravel 12** and follows the **Repository Pattern** for clean separation of concerns.

### 🛠 Technologies
- PHP 8.3
- Laravel 12
- MySQL (Database)
- Redis (for data caching)

### 📘 Models & Relationships

1. **Role**  
   - Fields: `name (string)`  
   - Assumption: Acts as a parent model (1 Role has many Contacts)

2. **Contact**  
   - Fields: `role_id (int)`, `name (string)`, `phone (string)`, `company (string)`, `is_favorite (boolean)`  
   - Relationship: Belongs to `Role`

3. **CallLog**  
   - Fields: `contact_id (int)`, `duration (int - seconds)`, `status (enum)`  
   - Relationship: Belongs to `Contact`  
   - Uses a custom enum file: `StatusCallLogEnum.php`

### 🧩 Architectural Pattern
```
API Routes → Controller → Services → Repository → Model
```

### ✅ Backend Assumptions
- Utilizes the repository pattern for maintainable logic
- Implements Redis caching (10-minute TTL for key-specific data)
- Adds a **favorite filter** for listing contacts

---

## 🌐 Frontend Details (Vue 2 + TypeScript)

The frontend is developed using **Vue 2** with **Vite**, **Tailwind UI**, and **TypeScript**, optimized for modularity and state management.

### 🛠 Technologies
- Node.js 18
- Vue 2
- Vuex
- Axios
- Lodash
- Tailwind UI
- Prettier

### 🧠 Frontend Architecture
```
Vue Router → Views (Pages) → Vuex (State Management)
```

- Includes a **global pagination component**
- Implements a **favorite filter toggle** for contacts list

---

## 📬 API Documentation

API collection is available in Postman:

- [View on Postman Workspace](https://universal-comet-297339.postman.co/workspace/My-Workspace~60c34773-ddb7-43bc-8d6e-4da259803133/collection/15607869-30db7d86-f3bb-44d1-a3d7-ea39bc4ead72?action=share&creator=15607869)

---

## 🧑‍💻 Author

Technical Test by **Zulkifli Raihan**
