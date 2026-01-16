# Itinerary Hub

A web application to centralize and organize travel plans (flights, hotels, activities), especially for groups. It solves the problem of fragmented planning.
The core feature is a collaborative, chronological “Trip” timeline. Users can create, update, and delete their trips, and invite friends for shared planning.

##  Setup & Run

1.  **Start the Application**
    Run the following command in the project root:
    ```bash
    docker compose up
    ```
    The application will be running at **http://localhost**.

## Test Credentials
You can register a new account, or use these pre-made test accounts (if you imported the database):
* **User 1 (Owner):** `patricio@test.com` / `password123`
* **User 2 (Collaborator):** `teacher@test.com` / `password123`

## Technical Implementation

### Architecture (MVC)
The project follows a strict MVC pattern without using a framework:
* **Controllers:** Handle request logic.
* **Services:** Handle business logic and permissions.
* **Views:** PHP templates.

### Coding Patterns
* **Explicit Dependency Instantiation:** I manually instantiate services in the controller constructors (e.g., `$this->tripService = new TripService();`) instead of using a Dependency Injection Container. I did this to keep the data flow explicit and easier to debug.
* **Centralized Routing:** All routes are defined in `public/index.php` using FastRoute.

### AJAX & API
To update pages without refreshing (Rubric requirement), I implemented a Javascript fetch handler:
* **File:** `app/src/Views/trip/share-modal.php`
* **Logic:** The JavaScript calls the `/api/trip/generate-invite` endpoint (TripController), receives a JSON response, and updates the invite link input field dynamically.

## Compliance

### GDPR (Privacy)
* **Data Minimization:** Registration only requires a username, email, and password. Extra personal data is optional.
* **Right to Erasure:** The "Delete Trip" function uses a database `ON DELETE CASCADE` constraint. Deleting a trip permanently removes all associated items and memberships from the database.
* **Consent:** Users must click "Accept" on an invite page (`join-confirmation.php`) before they are added to a trip.

### WCAG (Accessibility)
* **Forms:** All inputs use `label for="..."` linked to `id="..."` (e.g., `login.php`, `trip-add.php`).
* **Icon Buttons:** Buttons with no text (like the Delete trash icon) include `aria-label` attributes to define their function for screen readers.
* **Status Updates:** The loading spinner in the Share Modal uses `role="status"` to announce changes to assistive technology.