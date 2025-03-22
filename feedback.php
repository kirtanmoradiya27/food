<?php
include('partials-front/header.php');
include('db.php');

// Ensure User is Logged In
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('You need to log in to submit feedback.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch User ID Using Email
$user_email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT id FROM tbl_users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    echo "<script>alert('User not found! Please log in again.'); window.location.href='logout.php';</script>";
    exit();
}

// Handle Feedback Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_id = intval($_POST['food_id']);
    $feedback_text = htmlspecialchars(trim($_POST['feedback_text']));
    $date = $_POST['date'];

    if (empty($food_id) || empty($feedback_text) || empty($date)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Insert Feedback
    $stmt = $conn->prepare("INSERT INTO tbl_feedback (user_id, food_id, feedback_text, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $food_id, $feedback_text, $date);
    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }
    $stmt->close();
}

// Fetch available food items for dropdown
$food_query = $conn->query("SELECT id, title FROM tbl_food");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit Feedback</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        main { width: 50%; margin: 20px auto; background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        h2 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; }
        label { margin-top: 10px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; }
        textarea { height: 100px; resize: vertical; }
        button { margin-top: 15px; background-color: #28a745; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <main>
        <h2>Submit Feedback</h2>
        <form method="POST" action="">
            <label for="food_id">Food Item:</label>
            <select name="food_id" required>
                <option value="">Select a food item</option>
                <?php while ($food = $food_query->fetch_assoc()): ?>
                    <option value="<?= $food['id']; ?>"><?= htmlspecialchars($food['title']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="feedback_text">Feedback:</label>
            <textarea name="feedback_text" required></textarea>

            <label for="date">Date:</label>
            <input type="datetime-local" name="date" value="<?= date('Y-m-d\TH:i') ?>" required>

            <button type="submit">Submit Feedback</button>
        </form>
    </main>
</body>
</html>

<?php include('partials-front/footer.php'); ?>
