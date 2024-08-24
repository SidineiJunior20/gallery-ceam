<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <h2>Galeria de Fotos</h2>

    <?php
    $sql = "SELECT users.username, photos.id AS photo_id, photos.file_path 
            FROM photos 
            JOIN users ON photos.user_id = users.id 
            ORDER BY photos.uploaded_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $photo_id = $row['photo_id'];

            // Obter número de curtidas
            $like_sql = "SELECT COUNT(*) AS like_count FROM likes WHERE photo_id = $photo_id";
            $like_result = $conn->query($like_sql);
            $like_data = $like_result->fetch_assoc();
            $like_count = $like_data['like_count'];

            // Obter comentários
            $comment_sql = "SELECT comments.comment, users.username 
                            FROM comments 
                            JOIN users ON comments.user_id = users.id 
                            WHERE comments.photo_id = $photo_id 
                            ORDER BY comments.created_at ASC";
            $comment_result = $conn->query($comment_sql);
            ?>

            <div>
                <p><strong><?php echo $row['username']; ?></strong></p>
                <img src="<?php echo $row['file_path']; ?>" alt="Photo" style="width:300px;height:auto;">
                <p><?php echo $like_count; ?> Curtidas</p>

                <!-- Formulário de Curtida -->
                <form action="like.php" method="post">
                    <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
                    <button type="submit">Curtir</button>
                </form>

                <!-- Comentários -->
                <div>
                    <h4>Comentários:</h4>
                    <?php while ($comment_row = $comment_result->fetch_assoc()) { ?>
                        <p><strong><?php echo $comment_row['username']; ?>:</strong> <?php echo $comment_row['comment']; ?></p>
                    <?php } ?>
                </div>

                <!-- Formulário de Comentário -->
                <form action="comment.php" method="post">
                    <input type="hidden" name="photo_id" value="<?php echo $photo_id; ?>">
                    <textarea name="comment" rows="2" placeholder="Adicione um comentário..." required></textarea><br>
                    <button type="submit">Comentar</button>
                </form>
            </div>

            <?php
        }
    } else {
        echo "Nenhuma foto disponível.";
    }

    $conn->close();
    ?>

    <?php include 'templates/footer.php'; ?>
</body>
</html>
