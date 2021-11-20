# IIS - Fituska with gamification
#### Used technologies
- [slim framework](https://www.slimframework.com/) used for routing
- [doctrine](https://www.doctrine-project.org/index.html) used for ORM

## Table of Content
* [Requirements](#requirements)
* [Installation](#installation)
    * [Using docker](#using-docker)
    * [Using composer](#using-composer)
    * [Using XAMP MAMP](#using-xamp-mamp)
* [Development](#development)

## Requirements
- `composer`
- `docker` with `docker-compose` (necessary if using [docker](#using-docker) for running the app)

## Installation
We can start the application 3 different ways using:
1. [docker](#using-docker)
2. [composer](#using-composer) 
3. [XAMP/MAMP](#using-xamp-mamp)
___
##### Using docker
All following commands should be run in root directory:
- run `docker-compose up` - this sets up php server, mysql & phpmyadmin (you might wanna run `docker-compose up -d` for _detached_ mode aka. running in background)
- run `./install-dependencies.sh`
- run `./update-schema.sh`
___
##### Using composer
For using this method you have to setup database on your own and change database settings in `fituska-api/settings.php` file. You'll also need to make sure you are using correct version of PHP (tested with 7.3)
- move to `fituska-api/` folder and run `composer install` and stay in this folder
- run `composer start` - this starts php server
- run `php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql` - this creates tables by `fituska-api/src/Domain/` folder into database
___
##### Using XAMP MAMP
Almost same as using composer, but you'll set your "apache directory" to `fituska-api/src/public/` and then run the server
- then run `php vendor/bin/doctrine orm:schema-tool:update --force --dump-sql` in `fituska-api/` - this creates tables by `fituska-api/src/Domain/` folder into database

___
## Development
In `fituska-api/src/` folder:

##### public
- `.htaccess` - you shouldn't change this
- `index.php` - this file contains some middlewares (you probably won't change those) and endpoint definitions 

##### Domain
Contains files for ORM to database. They're all should be inside `App\Domain` namespace and have getter/setter methods

##### Controller
Contains controllers, for various endpoints, that has functions for CRUD operations

##### Services
Contains helper functions (mostly) for various controllers.

___
## Endpoints
All endpoints should be forwarded to `localhost:8000/{endpoint}` where `{endpoint}` is endpoint specified in next chapters

##### login/signup
- `/signup` - `[POST]` creates new user account

```json
{
    "name": "string",
    "password": "string",
    "email": "string",
    "role": "int" // this should correspond to default member role ID in database
}
```

- `/login` - `[POST]` log in user and return JWT in response (the JWT is used for communicating with protected endpoints)

```json
{
    "password": "string",
    "email": "string",
}
```

### Public endpoints
<details>
<summary>users</summary>

- `/users/{id}/get` get user by id
- `/users/email/{email}/get` - get users by email
- `/users/name/{name}/get` - get users by name

</details>

<details>
<summary>courses</summary>

- `/courses/get` - get all courses
- `/courses/get/approved` - get all approved courses (this should be used most probably by users to browse)
- `/courses/{code}/get` - get course by unique course code

</details>

<details>
<summary>threads</summary>

- `/courses/{coude}/threads/get` - get threads for course with specified course code
- `/threads/title/{title}/get` - get thread by title
- `/threads/id/{id}/get` - get thread and all it's messages (not yet implemented) by thread id

</details>


### Protected endpoints
<details>
<summary>roles</summary>

This endpoint should be only accessed by user with role that has name `admin`
- `/roles` - get all available roles
- `/roles/add/{name}` - `[POST]` add new role with specified name
- `/roles/{id}/{name}` - `[PUT]` update existing role with id to new name
- `/roles/{id}` - `[DELETE]` delete role with ID

</details>

<details>
<summary>users</summary>

- `/users` - gets all users (probably won't be necessary?)
- `/users/{userID}/role/{roleID}` - `[PUT]` update role of user with specified id (only user with admin role is able to change those)

</details>

<details>
<summary>courses</summary>

- `/courses/get/not-approved` - gets all yet not approved courses (only if user role equals to 'moderator' or 'admin')
- `/courses/add` - `[POST]` add new course

```json
{
    "code": "string",
    "name": "string"
}
```

- `/courses/{code}/approve` - `[PUT]` approves course with specified course code (only if user's role is 'moderator' or 'admin')

</details>

<details>
<summary>thread categories</summary>

All of those endpoints are for lecturer of course only
- `/courses/{code}/get/categories` - get all thread categories for specified course with course code
- `/categories/add` - `[POST]` add new thread category for course

```json
{
    "name": "string",
    "course_code": "string"
}
```

- `/categories/{id}/update` - `[PUT]` update thread category with specified id

```json
{
    "name": "string"
}
```

- `/categories/{id}/delete` - `[DELETE]` delete existing category with specified id

</details>

<details>
<summary>threads</summary>

- `/threads/add` - `[POST]` add new thread (only for enrolled students (not yet done) or lecturer of course)

```json
{
    "course_code": "string",
    "title": "string",
    "category": "int",
    "message": "string" // will have message attachments in the future
}
```

- `/threads/{id}/close` - `[PUT]` close existing thread, can be only done by lecturer of course (waiting for gamification features)

- `/threads/{id}/delete` - `[DELETE]` delete thread with specified id (only for author of thread or lecturer of course)

</details>

<details>
<summary>thread messages</summary>

Because users should get points for correct answers they shouldn't be able to change/delete their messages
- `/threads/{id}/messages/add` - `[POST]` add new message to thread

```json
{
    "message": "string"
}
```

</details>