<head>
    <meta charset="UTF-8">
    <title>Backup Database</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Backup Database</h1>
        <div class="form-group">
            <form action="backup.php" method="get">
                <input type="hidden" name="nm_member" value="<?php echo $_SESSION['admin']['nm_member']; ?>">
                <button type="submit" class="btn btn-primary">Backup Database</button>
            </form>
        </div>
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
