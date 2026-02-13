</main>
<footer>
    <div class="container">
        <?php if (isset($_SESSION['user_id'])):
            // Using the $header_user from header.php if available, or fetch here if needed
            // For safety, let's just use the session check
            ?>
            <p>&copy; <?php echo date("Y"); ?> FoodHome. All rights reserved.</p>
            <p>Restaurant Portal for Professional Owners</p>
        <?php else: ?>
            <p>&copy; <?php echo date("Y"); ?> FoodHome. All rights reserved.</p>
        <?php endif; ?>
    </div>
</footer>
</body>

</html>