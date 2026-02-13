<?php
/*
include 'db_connect.php'; // Ensure this file establishes the database connection.
include 'retrieve_reviews.php';

// Make sure the user is logged in, otherwise redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$restaurant_owner_id = $_SESSION['user_id'];

// Retrieve feedback for the restaurant owner.
$feedbackQuery = "SELECT * FROM feedback WHERE restaurant_owner_id = ?";
$feedbackStmt = mysqli_prepare($conn, $feedbackQuery);

if ($feedbackStmt) {
    mysqli_stmt_bind_param($feedbackStmt, 'i', $restaurant_owner_id);
    
    if (mysqli_stmt_execute($feedbackStmt)) {
        $feedbackResult = mysqli_stmt_get_result($feedbackStmt);
        $feedback = mysqli_fetch_assoc($feedbackResult); // Assuming one feedback per owner.
    } else {
        // Handle query execution error.
        $feedback = null;
    }
    
    mysqli_stmt_close($feedbackStmt);
} else {
    // Handle statement preparation error.
    $feedback = null;
}

mysqli_close($conn); // Close the connection since we are done with database operations.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
</head>
<body>
    <h1>Customer Reviews</h1>
    <?php if ($reviews): ?>
        <ul>
            <?php foreach ($reviews as $review): ?>
                <li>
                    <strong><?php echo htmlspecialchars($review['customer_name']); ?></strong>
                    <p>Rating: <?php echo htmlspecialchars($review['rating']); ?>/10</p>
                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    <p>Date: <?php echo htmlspecialchars($review['review_date']); ?></p>
                    <h2>Submit Feedback</h2>
    <form action="submit_feedback.php" method="post" novalidate>
        <label for="message">Feedback Message:</label>
        <textarea id="message" name="message"><?php echo $feedback ? htmlspecialchars($feedback['message']) : ''; ?></textarea>
        <?php if (isset($_SESSION['errors']['message'])): ?>
            <p><?php echo htmlspecialchars($_SESSION['errors']['message']); ?></p>
        <?php endif; ?>
        <br>
        <input type="submit" value="Submit Feedback">
    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>

   

    <p><a href='welcome.php'><b>Go to Main</b></a></p>
</body>
</html>
*/// The rest of your main PHP file

// Start the session if it has not been started
if(session_status() === PHP_SESSION_NONE) session_start();

include 'retrieve_reviews.php'; // This will set the $reviews variable.

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
</head>
<body>
    <h1>Customer Reviews</h1>
    <?php if (!empty($reviews)): ?>
        <ul>
            <?php foreach ($reviews as $review): ?>
                <li>
                    <strong><?php echo htmlspecialchars($review['customer_name']); ?></strong>
                    <p>Review ID: <?php echo htmlspecialchars($review['id']); ?></p>
                    <p>Rating: <?php echo htmlspecialchars($review['rating']); ?>/10</p>
                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    <p>Date: <?php echo htmlspecialchars($review['review_date']); ?></p>
                    <!-- Check if there is feedback for the review -->
                    <?php if (!empty($review['feedback_message'])): ?>
                        <p>Feedback: <?php echo htmlspecialchars($review['feedback_message']); ?></p>
                    <?php else: ?>
                        <!-- Feedback form -->
                        <h2>Submit Feedback</h2>
                        <form action="submit_feedback.php" method="post" novalidate>
                            <input type="hidden" name="review_id" value="<?php echo htmlspecialchars($review['id']); ?>">
                            <label for="message_<?php echo htmlspecialchars($review['id']); ?>">Feedback Message:</label>
                            <textarea id="message_<?php echo htmlspecialchars($review['id']); ?>" name="message"></textarea>
                            <br>
                            <input type="submit" value="Submit Feedback">
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>

    <p><a href='welcome.php'><b>Go to Main</b></a></p>
</body>
</html>
