<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB connection
include 'db.php';

// Initialize messages
$error = '';
$success = '';

// Handle login
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                $_SESSION['success'] = "Welcome back, " . htmlspecialchars($user['name']) . "!";
                header("Location: home.php");
                exit;
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "No user found with this email!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Delmi Food Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1,900,700,500,400&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Satoshi', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center p-4">

    <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8 md:p-10">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Welcome Back</h2>

        <!-- Success message -->
        <?php if (!empty($_SESSION['success'])) : ?>
            <div class="bg-green-50 text-green-700 border border-green-200 p-3 rounded mb-5 text-center text-sm font-medium">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Error message -->
        <?php if (!empty($error)) : ?>
            <div class="bg-red-50 text-red-700 border border-red-200 p-3 rounded mb-5 text-center text-sm font-medium">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="" class="space-y-5">
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                    required
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Enter your password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                name="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition duration-300 shadow-md"
            >
                Sign In
            </button>
        </form>

        <!-- Footer Text -->
        <p class="mt-6 text-center text-gray-600 text-sm">
            Don't have an account?
            <a href="register.php" class="text-blue-600 font-medium hover:underline">Register here</a>
        </p>
    </div>

</body>
</html>
