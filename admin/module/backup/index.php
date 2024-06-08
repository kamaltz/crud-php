<?php
// Konfigurasi database
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'grosir';

$message = '';
$connectionMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['backup'])) {
        // Buat file backup dengan timestamp
        $backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = __DIR__ . '/' . $backupFile;

        // Perintah mysqldump
        $command = "mysqldump --user={$dbuser} --password={$dbpass} --host={$dbhost} {$dbname} > {$backupPath}";
        system($command, $result);

        if ($result === 0) {
            // Mengunduh file backup
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($backupPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backupPath));
            readfile($backupPath);
            unlink($backupPath); // Hapus file setelah diunduh
            exit;
        } else {
            $message = "Terjadi kesalahan saat melakukan backup.";
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
    <title>Backup Database</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Backup Database</h1>
        <form action="backup.php" method="post">
            <div class="form-group">
                <button type="submit" name="backup" class="btn btn-primary">Backup Database</button>
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
