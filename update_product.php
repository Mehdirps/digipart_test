<?php
require_once 'db.php';
require_once 'class/product.class.php';

$product = new Product($conn);

if(isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->getById();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product->id = $_POST['id'];
    $product->reference = $_POST['reference'];
    $product->title = $_POST['title'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    
    // Check if a new image was uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        
        $image_ext = explode('.', $image_name);
        $image_actual_ext = strtolower(end($image_ext));
        
        $allowed = ['jpg', 'jpeg', 'png'];
        
        if(in_array($image_actual_ext, $allowed)) {
            if($image_size < 1000000) {
                $image_name_new = uniqid('', true) . "." . $image_actual_ext;
                $image_destination = 'uploads/' . $image_name_new;
                move_uploaded_file($image_tmp_name, $image_destination);
                
                // Set the new image name in the product object
                $product->image = $image_name_new;
            } else {
                echo "Le fichier est trop volumineux!";
            }
        } else {
            echo "Vous ne pouvez pas télécharger des fichiers de ce type!";
        }
    } else {
        // No new image uploaded, keep existing image
        $product->image = "";  // Empty means keep existing
    }
    
    if($product->update()) {
        header('Location: products_list.php');
    } else {
        echo "Une erreur est survenue lors de la mise à jour du produit.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include './includes/header.php'; ?>
    <main>
        <div class="container">
            <h1 class="text-center">Modifier un produit</h1>
            <form action="update_product.php" method="post" enctype="multipart/form-data">
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
                    <textarea class="form-control" id="description" name="description"><?php echo $product->description; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Prix</label>
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $product->price; ?>">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <?php if($product->image): ?>
                        <div class="mb-2">
                            <img src="uploads/<?php echo $product->image; ?>" alt="<?php echo $product->title; ?>" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image">
                    <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </main>
    <footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </footer>
</body>
</html>
