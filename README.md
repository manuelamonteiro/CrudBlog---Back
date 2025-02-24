# CRUD Blog API (Laravel + JWT + RBAC)

This project is a back-end API for a simple blog system with posts and comments. It uses **Laravel**, **JWT** for authentication, and **role-based access control** (RBAC) to ensure that only the resource owner can edit or delete their posts/comments.

## Features

- **User Registration & Login**: Implemented with JWT for stateless authentication.  
- **RBAC**: Only the owner of a post/comment can edit or delete it.  
- **CRUD Operations**:  
  - **Posts**: Create, Read, Update, Delete.  
  - **Comments**: Create, Read, Update, Delete.  
- **JWT Authentication**: Routes requiring modifications are protected by an auth guard.  
- **RESTful API**: Returns JSON responses, including errors.

## Prerequisites

- **PHP** >= 8.0  
- **Composer** >= 2.0  
- **MySQL** or another database supported by Laravel  
- **Laravel** (installed via Composer)

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/manuelamonteiro/CrudBlog---Back.git
   ```
2. **Navigate to the project folder**:
   ```bash
   cd crud-blog
   ```
3. **Install dependencies**:
   ```bash
   composer install
   ```
4. **Create a `.env` file** by copying from `.env.example`:
   ```bash
   cp .env.example .env
   ```
5. **Update your `.env`** with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=crud_blog
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password
   ```
6. **Generate an app key**:
   ```bash
   php artisan key:generate
   ```
7. **Run migrations** to create the tables:
   ```bash
   php artisan migrate
   ```
8. **Install JWT** (if not already set up):
   ```bash
   php artisan jwt:secret
   ```
9. **Serve the application**:
   ```bash
   php artisan serve
   ```
   By default, the app will be available at **http://127.0.0.1:8000**.

## API Endpoints

Below is a brief overview of the main endpoints. All **protected routes** require a valid **JWT** token in the `Authorization` header as `Bearer <token>`.

### Auth

- **POST** `/api/register`  
  Registers a new user.  
  **Body (JSON)**:
  ```json
  {
    "name": "Example User",
    "email": "user@example.com",
    "password": "secret"
  }
  ```
- **POST** `/api/login`  
  Authenticates the user and returns a JWT token.  
  **Body (JSON)**:
  ```json
  {
    "email": "user@example.com",
    "password": "secret"
  }
  ```

### Posts

- **GET** `/api/posts`  
  Returns a list of all posts (public endpoint).  
- **GET** `/api/posts/{id}`  
  Returns a single post with its comments (public endpoint).  
- **POST** `/api/posts`  
  Creates a new post (protected).  
  **Body (JSON)**:
  ```json
  {
    "title": "My Post Title",
    "content": "Post content here..."
  }
  ```
- **PUT** `/api/posts/{id}`  
  Updates a post (protected; only the post owner can update).  
- **DELETE** `/api/posts/{id}`  
  Deletes a post (protected; only the post owner can delete).

### Comments

- **POST** `/api/posts/{post_id}/comments`  
  Creates a new comment on a specific post (protected).  
  **Body (JSON)**:
  ```json
  {
    "content": "Comment content..."
  }
  ```
- **PUT** `/api/posts/{post_id}/comments/{id}`  
  Updates a comment (protected; only the comment owner can update).  
- **DELETE** `/api/posts/{post_id}/comments/{id}`  
  Deletes a comment (protected; only the comment owner can delete).

## Authorization & RBAC

- **Policies**:  
  Each model (Post, Comment) has a Policy that ensures only the resource owner can modify or delete the resource.  
- **Middleware**:  
  JWT authentication is applied via the `jwt.auth` middleware in the routes, protecting all non-public endpoints.

## Contributing

1. **Fork** the repository.  
2. **Create** a new feature branch: `git checkout -b feature/my-feature`.  
3. **Commit** changes: `git commit -m "feat: implement my new feature"`.  
4. **Push** to the branch: `git push origin feature/my-feature`.  
5. **Create** a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).