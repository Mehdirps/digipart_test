<?php
require_once 'db.php';
require_once 'class/product.class.php';

$product = new Product($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product->reference = $_POST['reference'];
    $product->title = $_POST['title'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->priceTaxIncl = $_POST['priceTaxIncl'];
    $product->priceTaxExcl = $_POST['priceTaxExcl'];
    $product->idLang = $_POST['idLang'];
    $product->quantity = $_POST['quantity'];

    if (!empty($_FILES['image']['name'])) {
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
                    exit;
                }
            } else {
                echo "Il y a eu une erreur lors du téléchargement de votre fichier!";
                exit;
            }
        } else {
            echo "Vous ne pouvez pas télécharger des fichiers de ce type!";
            exit;
        }
    } else {
        $product->image = null;
    }

    $product->create();
    header('Location: products_list.php');
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="container">
            <h1 class="text-center">Ajouter un produit</h1>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="reference" class="form-label">Référence</label>
                    <input type="text" class="form-control" id="reference" name="reference">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Prix</label>
                    <input type="text" class="form-control" id="price" name="price">
                </div>
                <div class="mb-3">
                    <label for="priceTaxIncl" class="form-label">Prix TTC</label>
                    <input type="text" class="form-control" id="priceTaxIncl" name="priceTaxIncl">
                </div>
                <div class="mb-3">
                    <label for="priceTaxExcl" class="form-label">Prix HT</label>
                    <input type="text" class="form-control" id="priceTaxExcl" name="priceTaxExcl">
                </div>
                <div class="mb-3">
                    <label for="idLang" class="form-label">Langue</label>
                    <select class="form-select" id="idLang" name="idLang">
                        <option selected disabled>-- Choisir une langue --</option>
                        <option value="FRA">Français</option>
                        <option value="ANG">Anglais</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantité</label>
                    <input type="number" class="form-control" id="quantity" name="quantity">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </main>
    <footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </footer>
</body>

</html>