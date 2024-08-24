<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Fotos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Galeria de Fotos</h2>

    <?php
    $sql = "SELECT photos.*, albums.title AS album_title, users.username FROM photos 
            JOIN albums ON photos.album_id = albums.id 
            JOIN users ON photos.user_id = users.id
            ORDER BY photos.uploaded_at DESC";
    $result = $conn->query($sql);

    while ($photo = $result->fetch_assoc()) {
        ?>
        <div class="photo">
            <img src="<?php echo $photo['file_path']; ?>" alt="Foto">
            <p>Álbum: <?php echo $photo['album_title']; ?></p>
            <p>Postado por: <?php echo $photo['username']; ?></p>
            <p>Data: <?php echo $photo['uploaded_at']; ?></p>
            <?php if (!empty($photo['description'])): ?>
                <p>Descrição: <?php echo $photo['description']; ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
    ?>
</body>
</html>
