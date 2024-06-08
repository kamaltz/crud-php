<?php
// Konfigurasi database
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nama_database';

$message = '';
$connectionMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['restore_file']) && isset($_POST['restore'])) {
        $filePath = $_FILES['restore_file']['tmp_name'];

        if (is_uploaded_file($filePath)) {
            // Perintah mysql untuk restore
            $command = "mysql --user={$dbuser} --password={$dbpass} --host={$dbhost} {$dbname} < {$filePath}";
            system($command, $result);

            if ($result === 0) {
                $message = "Database berhasil direstore.";
            } else {
                $message = "Terjadi kesalahan saat merestore database.";
            }
        } else {
            $message = "Terjadi kesalahan upload file.";
        }
    } elseif (isset($_POST['check_connection'])) {
        // Cek koneksi ke database
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($conn->connect_error) {
            $connectionMessage = "Koneksi gagal: " . $conn->connect_error;
        } else {
            $connectionMessage = "Koneksi berhasil.";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Restore Database</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Restore Database</h1>
        <form action="restore.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="restore_file">Pilih file .sql untuk restore:</label>
                <input type="file" name="restore_file" id="restore_file" class="form-control" accept=".sql" required>
            </div>
            <div class="form-group">
                <button type="submit" name="restore" class="btn btn-primary">Restore</button>
                <button type="submit" name="check_connection" class="btn btn-info">Cek Koneksi Database</button>
            </div>
        </form>
        <?php if ($message): ?>
            <div class="alert alert-danger">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <?php if ($connectionMessage): ?>
            <div class="alert alert-info">
                <?php echo $connectionMessage; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
