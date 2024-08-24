<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para curtir fotos.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $photo_id = $_POST['photo_id'];

    // Verifica se o usuário já curtiu a foto
    $sql = "SELECT id FROM likes WHERE user_id = ? AND photo_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $photo_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // O usuário já curtiu a foto, remover curtida
        $sql = "DELETE FROM likes WHERE user_id = ? AND photo_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $photo_id);
        $stmt->execute();
        echo "Curtida removida.";
    } else {
        // Adiciona a curtida
        $sql = "INSERT INTO likes (user_id, photo_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $photo_id);
        $stmt->execute();
        echo "Foto curtida.";
    }

    $stmt->close();
    $conn->close();
}
?>
