<?php

require_once 'db.php';
require_once 'class/product.class.php';

$product = new Product($conn);

if (isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->getById();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $product->id = $_POST['id'];
    $product->reference = $_POST['reference'];
    $product->title = $_POST['title'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];

    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    $image_ext = explode('.', $image_name);
    $image_actual_ext = strtolower(end($image_ext));

    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($image_actual_ext, $allowed)) {
        if ($image_error === 0) {
            if ($image_size < 1000000) {
                $image_name_new = uniqid('', true) . "." . $image_actual_ext;
                $image_destination = 'uploads/' . $image_name_new;
                move_uploaded_file($image_tmp_name, $image_destination);

                $product->image = $image_name_new;
            } else {
                echo "Le fichier est trop volumineux!";
            }
        } else {
            echo "Il y a eu une erreur lors du téléchargement de votre fichier!";
        }
    } else {
        echo "Vous ne pouvez pas télécharger des fichiers de ce type!";
    }

    $product->update();

    header('Location: show_product.php?id=' . $product->id);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes des produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="container mt-4">
            <h1><?php echo $product->title; ?></h1>

            <div class="row mb-4">
                <div class="col-md-4">
                    <img src="./uploads/<?php echo $product->image ?>" class="img-fluid" alt="<?php echo $product->title; ?>">
                </div>
                <div class="col-md-8">
                    <p>Référence: <?php echo $product->reference; ?></p>
                    <p><?php echo $product->description; ?></p>
                    <p class="fw-bold"><?php echo $product->price; ?> €</p>
                </div>
            </div>

            <h2>Modifier le produit</h2>
            <form action="./show_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                <div class="mb-3">
                    <label for="reference" class="form-label">Référence</label>
                    <input type="text" class="form-control" id="reference" name="reference" value="<?php echo $product->reference; ?>">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $product->title; ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo $product->description; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Prix (€)</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product->price; ?>">
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <?php if ($product->image != '') { ?>
                            <p>image actual</p>
                            <img style="width: 100px;" src="./uploads/<?php echo $product->image; ?>" class="img-fluid" alt="<?php echo $product->title; ?>">
                        <?php } ?>
                    </div>
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Laisser vide pour conserver l'image actuelle</small>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </main>
</body>