<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO photos (user_id, file_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $target_file);

        if ($stmt->execute()) {
            echo "Imagem carregada com sucesso!";
        } else {
            echo "Erro ao salvar a imagem no banco de dados.";
        }

        $stmt->close();
    } else {
        echo "Erro ao fazer upload da imagem.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <h2>Upload de Imagem</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Selecione uma imagem:</label>
        <input type="file" id="file" name="file" required><br><br>

        <button type="submit">Upload</button>
    </form>
</body>
</html>
