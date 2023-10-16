# Route list

This list contains all existing API routes and their description.
All routes in this list are prefixed with '/api'

## Table of contents:
1. [Auth routes](#auth-routes)
2. [User routes]()
3. [Project routes](#project-routes)
4. [Board routes](#board-routes)
5. [Task routes]()
---

## Auth routes

> ### Login route
>
> **URI:** '/login'  
> **Method:** POST  
> **Description:** Logs in user if specified information is found in the database.
> 
> **Requires:**
> - E-mail;
> - Password;

> ### Register route
>
> **URI:** '/register'  
> **Method:** POST  
> **Description:** Creates a new user if specified information is validated and isn't already found in the database.  
>
> **Requires:**
> - E-mail (unique);
> - Name;
> - Password (8 characters);
> - Password confirmation;

> ### Logout route
>
> **URI:** '/logout'  
> **Method:** POST  
> **Description:** Logs out user by deleting the generated authorization bearer token.

---

## Project routes

> ### Index route
>
> **URI:** '/projects'  
> **Method:** GET  
> **Description:** Gets all public projects available in the database.
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": [
>     {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   ]
> }
> ```

> ### Store route
>
> **URI:** '/projects'  
> **Method:** POST  
> **Description:** Creates a new project and assigns to user if information is validated.
>
> **Requires:**
> - Title (required);
> - Description;
> - Is public? (optional, default: true);
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": [
>     {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   ]
> }
> ```

> ### Show route
>
> **URI:** '/projects/{project}'  
> **Method:** GET  
> **Description:** Gets information of the specified project.
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": [
>     {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   ]
> }
> ```

> ### Update route
>
> **URI:** '/projects/{project}'  
> **Method:** POST  
> **Description:** Updates the information of the specified project.
>
> **Requires:**
> - Title (required);
> - Description;
> - Is public?;
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": [
>     {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   ]
> }
> ```

> ### Destroy route
>
> **URI:** '/project/{project}'  
> **Method:** POST  
> **Description:** Deletes the specified project.
>
> **Returns:**
> - Status: 204

---

## Board routes

> ### Index route
>
> **URI:** '/projects/{project}/boards'  
> **Method:** GET  
> **Description:** Gets all boards available in the project.
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": {
>     "board": {
>       "id": 1,
>       "title": "Board title",
>       "description": "Board description"
>     },
>     "project": {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   }
> }
> ```

> ### Store route
>
> **URI:** '/projects/{project}/boards'  
> **Method:** POST  
> **Description:** Creates a new board and assigns to project if information is validated.
>
> **Requires:**
> - Title (required);
> - Description;
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": {
>     "board": {
>       "id": 1,
>       "title": "Board title",
>       "description": "Board description"
>     },
>     "project": {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   }
> }
> ```

> ### Show route
>
> **URI:** '/projects/{project}/boards/{board}'  
> **Method:** GET  
> **Description:** Gets information of the specified board.
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": {
>     "board": {
>       "id": 1,
>       "title": "Board title",
>       "description": "Board description"
>     },
>     "project": {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   }
> }
> ```

> ### Update route
>
> **URI:** '/projects/{project}/boards/{board}'  
> **Method:** POST  
> **Description:** Updates the information of the specified board.
>
> **Requires:**
> - Title (required);
> - Description;
>
> **Returns:**
> - Status: 200
> - JSON structure:
> ```json
> {
>   "data": {
>     "board": {
>       "id": 1,
>       "title": "Board title",
>       "description": "Board description"
>     },
>     "project": {
>       "id": 1,
>       "title": "Project title",
>       "description": "Project description"
>     }
>   }
> }
> ```

> ### Destroy route
>
> **URI:** '/projects/{project}/boards/{board}'  
> **Method:** POST  
> **Description:** Deletes the specified board.
>
> **Returns:**
> - Status: 204

---

## Task routes

---
