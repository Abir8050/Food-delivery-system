<?php
session_start();
?>

<form action="submit_feedback.php" method="post" novalidate>
    <label for="reviewId">Review ID:</label>
    <input type="text" id="reviewId" name="review_id">
    <?php if (isset($_SESSION['errors']['review_id'])) echo "<p>{$_SESSION['errors']['review_id']}</p>"; ?>
    <br>
    
    <label for="message">Feedback Message:</label>
    <textarea id="message" name="message"></textarea>
    <?php if (isset($_SESSION['errors']['message'])) echo "<p>{$_SESSION['errors']['message']}</p>"; ?>
    <br>
    
    <input type="submit" value="Submit Feedback">
</form>
