<?php
session_start();

$errorMessage;

if (isset($_POST['upload'])) {
    if (isset($_SESSION['upload_count'])) {
        $errorMessage = 'Вы уже загружали фото ранее';
    } else {
        try {
            if (($_FILES['photo']['type'] === 'image/jpeg' || $_FILES['photo']['type'] === 'image/png') && $_FILES['photo']['size'] < 2048000) {
                if (!file_exists('storage')) {
                    mkdir('images');
                }
                $newFileAddress = './images/' . $_FILES['photo']['name'];
                move_uploaded_file($_FILES['photo']['tmp_name'], $newFileAddress);
                $_SESSION['upload_count'] = 1;
                header('Location: ' . $newFileAddress);
                exit;
            } else {
                $errorMessage = 'Допускается загрузка только JPG/PNG файлов размером не более 2Mb.';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма для загрузки фото</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-3">
        <?php
            if (isset($errorMessage)) {
                echo "<div class='alert alert-danger' role='alert'>{$errorMessage}</div>";
            }
        ?>
        <div class="card p-4">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload" value="1">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Выберите изображение для загрузки:</label>
                    <input class="form-control" type="file" name="photo" id="formFile" required>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
</body>
</html>
