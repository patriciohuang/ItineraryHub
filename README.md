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
* **Dependency Injection:** Instead of manually creating objects, I built a custom Container. Controllers declare their dependencies (e.g., ITripService) in their constructors, and the container automatically resolves and injects the correct implementation. This follows the Dependency Inversion Principle.
* **Automatic View Mapping:** Controllers automatically find their corresponding view file based on the method name (e.g., HomeController::index to views/home/index.php), removing the need for repetitive require statements.

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