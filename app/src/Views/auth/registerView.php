<?php require __DIR__ . '/../../Views/partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2>Create Account</h2>
                    <?php require __DIR__ . '/../partials/messages.php'; ?>
                    <form action="/register" method="POST">
                        <div class="mb-3">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="d-flex gap-2">
                            <div class="mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="firstname" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="lastname" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                    <p class="mt-3 text-center">
                        <a href="/login">Already have an account? Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../Views/partials/footer.php'; ?>