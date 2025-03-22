<?php include('partials-front/header.php'); ?>
<?php
include 'db.php';

// Initialize message variable
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars(trim($_POST['comment']));

    // Basic validation
    if (!empty($name) && !empty($rating) && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO tbl_reviews (reviewer_name, rating, review, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sis", $name, $rating, $comment);

        if ($stmt->execute()) {
            $message = '<div class="bg-green-500 text-white p-4 mb-4 rounded shadow-md">Thank you for your feedback!</div>';
        } else {
            $message = '<div class="bg-red-500 text-white p-4 mb-4 rounded shadow-md">Error submitting your review. Please try again.</div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="bg-red-500 text-white p-4 mb-4 rounded shadow-md">All fields are required.</div>';
    }
}

// Fetch customer feedback (latest first)
$sql = "SELECT * FROM tbl_reviews ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Customer Reviews</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1,900,700,500,300,400&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.svg">
        <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-800">


    <section class="py-16 px-4 mx-auto max-w-7xl">

        <!-- Heading -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3">What Our Customers Say</h2>
            <p class="text-lg text-gray-600">Real feedback from real customers</p>
        </div>

        <!-- Feedback Message -->
        <?php if (!empty($message)): ?>
            <div class="max-w-2xl mx-auto mb-8">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Leave a Review Button -->
        <div class="text-center mb-8">
            <button onclick="toggleForm()"
                class="inline-block bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 transition duration-300 font-semibold">
                Leave a Review
            </button>
        </div>

        <!-- Review Submission Form (Hidden by Default) -->
        <div id="reviewForm" class="hidden mb-12 max-w-2xl mx-auto">
            <h3 class="text-2xl font-semibold mb-4 text-center">Leave a Review</h3>
            <form action="" method="POST" class="bg-white p-8 rounded-lg shadow-md space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-gray-700 mb-1 font-medium">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3"
                        placeholder="Your name">
                </div>

                <!-- Rating Field -->
                <div>
                    <label for="rating" class="block text-gray-700 mb-1 font-medium">Rating <span class="text-red-500">*</span></label>
                    <select name="rating" id="rating" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3">
                        <option value="">Select a rating</option>
                        <option value="1">⭐☆☆☆☆ (1/5)</option>
                        <option value="2">⭐⭐☆☆☆ (2/5)</option>
                        <option value="3">⭐⭐⭐☆☆ (3/5)</option>
                        <option value="4">⭐⭐⭐⭐☆ (4/5)</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                    </select>
                </div>

                <!-- Comment Field -->
                <div>
                    <label for="comment" class="block text-gray-700 mb-1 font-medium">Comment <span class="text-red-500">*</span></label>
                    <textarea name="comment" id="comment" rows="4" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3"
                        placeholder="Write your review..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition duration-300 font-semibold">
                    Submit Review
                </button>
            </form>
        </div>

        <!-- Customer Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                        $name = htmlspecialchars($row["reviewer_name"]);
                        $rating = intval($row["rating"]);
                        $comment = htmlspecialchars($row["review"]);
                        $date = date("F j, Y", strtotime($row["created_at"]));
                    ?>
                    <div
                        class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 p-6 flex flex-col">
                        <div class="flex items-center mb-4">
                            <img src="https://i.pravatar.cc/150?u=<?php echo urlencode($name); ?>"
                                alt="<?php echo $name; ?>"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500">
                            <div class="ml-4">
                                <h3 class="font-bold text-lg text-gray-900"><?php echo $name; ?></h3>
                                <div class="flex items-center mt-1 space-x-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $rating): ?>
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.285 3.95a1 1 0 00.95.69h4.15c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.285 3.95c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.285-3.95a1 1 0 00-.364-1.118L2.426 9.377c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.95-.69l1.285-3.95z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="w-5 h-5 text-gray-300" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.285 3.95a1 1 0 00.95.69h4.15c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.285 3.95c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.285-3.95a1 1 0 00-.364-1.118L2.426 9.377c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.95-.69l1.285-3.95z" />
                                            </svg>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 italic flex-grow">"<?php echo $comment; ?>"</p>
                        <span class="text-sm text-gray-500">Posted on <?php echo $date; ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-600 text-center col-span-3">No reviews found.</p>
            <?php endif; ?>

            <?php $conn->close(); ?>
        </div>

    </section>

    <!-- Toggle Review Form Script -->
    <script>
        function toggleForm() {
            const form = document.getElementById('reviewForm');
            form.classList.toggle('hidden');
        }
    </script>

<?php include('partials-front/footer.php'); ?>
</body>
