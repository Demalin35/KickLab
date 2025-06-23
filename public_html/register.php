<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
include 'nitropack-config.php';
?>
<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/styles.css" rel="stylesheet">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow p-4" style="width: 500px;">
            <h4 class="text-center mb-4">Create a New Account</h4>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form id="registerForm" action="process_register.php" method="POST" novalidate>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required>
                    <div class="invalid-feedback">Please enter your first name.</div>
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required>
                    <div class="invalid-feedback">Please enter your last name.</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                    <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                    <div class="invalid-feedback">Passwords must match.</div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">I agree to the Terms and Conditions</label>
                    <div class="invalid-feedback">You must agree to continue.</div>
                </div>
                <div class="d-grid gap-2">
                    <button id="submitBtn" type="submit" class="btn btn-success" disabled>Register</button>
                    <a href="login.php" class="btn btn-outline-success">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            const validate = () => {
                const firstName = form.first_name.value.trim();
                const lastName = form.last_name.value.trim();
                const email = form.email.value.trim();
                const password = form.password.value;
                const confirmPassword = form.confirm_password.value;
                const terms = form.terms.checked;

                let isValid = true;

                // First Name
                form.first_name.classList.remove("is-invalid", "is-valid");
                if (!firstName) {
                    form.first_name.classList.add("is-invalid");
                    isValid = false;
                } else {
                    form.first_name.classList.add("is-valid");
                }

                // Last Name
                form.last_name.classList.remove("is-invalid", "is-valid");
                if (!lastName) {
                    form.last_name.classList.add("is-invalid");
                    isValid = false;
                } else {
                    form.last_name.classList.add("is-valid");
                }

                // Email
                form.email.classList.remove("is-invalid", "is-valid");
                const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                if (!emailValid) {
                    form.email.classList.add("is-invalid");
                    isValid = false;
                } else {
                    form.email.classList.add("is-valid");
                }

                // Password
                form.password.classList.remove("is-invalid", "is-valid");
                if (password.length < 8) {
                    form.password.classList.add("is-invalid");
                    isValid = false;
                } else {
                    form.password.classList.add("is-valid");
                }

                // Confirm Password
                form.confirm_password.classList.remove("is-invalid", "is-valid");
                if (confirmPassword !== password || confirmPassword === "") {
                    form.confirm_password.classList.add("is-invalid");
                    isValid = false;
                } else {
                    form.confirm_password.classList.add("is-valid");
                }

                // Terms
                form.terms.classList.remove("is-invalid");
                if (!terms) {
                    form.terms.classList.add("is-invalid");
                    isValid = false;
                }

                submitBtn.disabled = !isValid;
            };

            form.addEventListener('input', validate);
            form.addEventListener('change', validate);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>