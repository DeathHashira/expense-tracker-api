
# Expense Tracker API - PHP
A simple implemented API for expense tracking with raw PHP and MVC structure. \
Also Bearer Authentication has been implemented with JWT. you can learn more about it [here](https://github.com/googleapis/php-jwt).
## Installation instruction
1. Clone the repo or download the zip file and go to the root directory of project.
2. Install all required packages (first make sure you have php and composer installed).
```bash
composer install
```
3. Create your `.env` file based on `.env.example` and add your database informations and JWT secret in it. you can create one with `openssl`.
```bash
openssl rand -hex 32
```
4. Add migrations to your database.
```bash
./vendor/bin/doctrine-migrations migrate
```
5. Run it in your localhost and now you can use API. You can choose whichever port you want (default 8000).
```bash
php -S localhost:<port> -t public/
```
## API Documentary
This API is for tracking expense of each user, usage for financial applications. \
This API contains to models. A User represents each invidual, and a expense represents each expense one invidual can have. \
\
Base URL: localhost:port (The port you have set) \
And the protocol is HTTP.\
Also as mentioned above, this API uses `Bearer Authentication` method.

### 1. POST localhost:8000/signup
Sign new users to database.\
**Request Body:**
| Field | Type | Required | Description |
|---|---|---|---|
|username|string|yes|-|
|email|string|yes|Must be unique|
|password|string|yes|-|

```bash
curl -X localhost:8000/signup \
-d '{
    "username": "test",
    "email": "test@gmail.com",
    "password": "12345678"
}'
```

<details>
<summary>200 Response Example</summary>
No content
</details>
<details>
<summary>401 Response Example</summary>
No content
</details>

### 2. POST localhost:8000/login
Authenticate each user and log in them into their account.\
**Request Body**
| Field | Type | Required | Description |
|---|---|---|---|
|email|string|yes|-|
|password|string|yes|-|

```bash
curl -X localhost:8000/login \
-d '{
    "email": "test@gmail.com",
    "password": "12345678"
}'
```

<details>
<summary>200 Response Example</summary>
{
    "access_token": "USER_JWT_TOKEN"
}
</details>
<details>
<summary>401 Response Example</summary>
No content
</details>

### 3. POST localhost:8000/expenses
Shows all registered expenses for authenticated user.\
**Request Header**
|Header|Value|Required|
|---|---|---|
|Authorization| Bearer JWT_TOKEN|yes|

```bash
curl -X localhost:8000/expenses \
-H "Authorization: Bearer JWT_TOKEN"
```

<details>
<summary>200 Response Example</summary>
{
    "id": 1,
    "title": "dinner",
    "amount": 10.2,
    "category": "food"
}
</details>
<details>
<summary>401 Response Example</summary>
When the token is expired (or invalid) or authorization header is empty.
</details>
<details>
<summary>400 Response Example</summary>
No content
</details>

### 4. POST localhost:8000/expenses/add
Add new expense for authenticated user in database.\
**Request Header**
|Header|Value|Required|
|---|---|---|
|Authorization| Bearer JWT_TOKEN|yes|

**Request Body**
| Field | Type | Required | Description |
|---|---|---|---|
|title|string|yes|-|
|amount|decimal|yes|-|
|category|string|yes|-|

```bash
curl -X localhost:8000/expenses/add \
-H "Authorization: Bearer JWT_TOKEN" \
-d '{
    "title": "dinner",
    "amount": 10.2,
    "category": "food"
}'
```

<details>
<summary>200 Response Example</summary>
Back to /expenses route
</details>
<details>
<summary>401 Response Example</summary>
When the token is expired (or invalid) or authorization header is empty.
</details>
<details>
<summary>400 Response Example</summary>
No content
</details>

### 5. POST localhost:8000/expenses/delete
Delete one specific expense for authenticated user.\
**Request Header**
|Header|Value|Required|
|---|---|---|
|Authorization| Bearer JWT_TOKEN|yes|

**Request Body**
| Field | Type | Required | Description |
|---|---|---|---|
|id|int|yes|expense id|

```bash
curl -X localhost:8000/expenses/delete \
-H "Authorization: Bearer JWT_TOKEN" \
-d '{
    "id": 2
}'
```

<details>
<summary>200 Response Example</summary>
Back to /expenses route
</details>
<details>
<summary>401 Response Example</summary>
When the token is expired (or invalid) or authorization header is empty.
</details>
<details>
<summary>400 Response Example</summary>
No content
</details>

### 6. POST localhost:8000/update
Update specific expense's data for authenticated user.\
**Request Header**
|Header|Value|Required|
|---|---|---|
|Authorization| Bearer JWT_TOKEN|yes|

**Request Body**
| Field | Type | Required | Description |
|---|---|---|---|
|id|int|yes|expense id|
|title|string|no|-|
|amount|decimal|no|-|
|category|string|no|-|

```bash
curl -X localhost:8000/expenses/update \
-H "Authorization: Bearer JWT_TOKEN" \
-d '{
    "id": 3,
    "title": lunch,
    "amount": 5
}'
```

<details>
<summary>200 Response Example</summary>
Back to /expenses route
</details>
<details>
<summary>401 Response Example</summary>
When the token is expired (or invalid) or authorization header is empty.
</details>
<details>
<summary>400 Response Example</summary>
No content
</details>

## Usage
This project is only customized for practical usage and training projects.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
