<?php
include('config/constants.php');

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check_email = "SELECT * FROM tbl_users WHERE email='$email'";
        $res = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($res) > 0) {
            echo "<script>alert('Email already registered!');</script>";
        } else {
            $sql = "INSERT INTO tbl_users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<script>
                        alert('Registration Successful! Please login.');
                        window.location.href = 'login.php';
                      </script>";
                exit();
            } else {
                echo "<script>alert('Registration failed. Try again!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Delmi Food Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1,900,700,500,300,400&display=swap" rel="stylesheet">

    <!-- Custom Tailwind config for colors if needed -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Satoshi', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6', // Blue-500
                        secondary: '#2563eb', // Blue-600
                        lightGray: '#f3f4f6', // Gray-100
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-lightGray flex items-center justify-center min-h-screen font-sans">

    <!-- Registration Container -->
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-2 text-center">Create Account</h2>
        <p class="text-gray-500 mb-8 text-center text-sm">Join Delmi Food Order and start your journey!</p>

        <!-- Registration Form -->
        <form action="" method="POST" class="space-y-5">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition duration-200">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition duration-200">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition duration-200">
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="••••••••"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none transition duration-200">
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit"
                class="w-full bg-primary hover:bg-secondary text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                Register
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 border-t border-gray-200"></div>

        <!-- Already have an account -->
        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="login.php" class="text-primary font-medium hover:underline">Login here</a>
        </p>
    </div>


    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Satoshi', 'sans-serif'],
                },
                colors: {
                    primary: '#3b82f6',
                    secondary: '#2563eb',
                    lightGray: '#93c5fd',  // Updated color
                },
            }
        }
    }
</script>

</body>
</html>
