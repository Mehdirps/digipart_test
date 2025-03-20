<?php

require_once 'db.php';
require_once 'class/product.class.php';

$product = new Product($conn);

$productsList = $product->getAll();

if(isset($_GET['id']) && isset($_GET['delete'])) {
    $product->id = $_GET['id'];
    $product->delete();
    header('Location: products_list.php');
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
        <h1 class="text-center">Listes des produits</h1>
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Référence</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $productsList->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <tr>
                            <?php if($row['image'] !== '') { ?>
                                <td><img src="./uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" style="max-width: 100px;"></td>
                            <?php } else { ?>
                                <td><p>Pas d'image</p>
                            <?php } ?>
                            <td><?php echo $row['reference']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td>
                                <a href="show_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Voir</a>
                                <a href="products_list.php?id=<?php echo $row['id']; ?>&delete=true" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>