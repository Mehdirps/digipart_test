<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../db.php';
require_once '../class/product.class.php';

$product = new Product($conn);

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if(isset($_GET['id']) && !empty($_GET['id'])) {
        
        $product->id = intval($_GET['id']);
        
        if($product->getById()) {
            $product_data = [
                "id" => $product->id,
                "reference" => $product->reference,
                "title" => $product->title,
                "description" => $product->description,
                "price" => $product->price,
                "image" => $product->image,
                "image_url" => "../uploads/" . $product->image,
                "priceTaxIncl" => $product->priceTaxIncl,
                "priceTaxExcl" => $product->priceTaxExcl,
                "idLang" => $product->idLang,
                "quantity" => $product->quantity
            ];
            
            http_response_code(200);
            
            echo json_encode($product_data);
        } else {
            http_response_code(404);
            
            echo json_encode(["message" => "Aucun produit trouvé avec cet ID."]);
        }
    } else {
        http_response_code(400);
        
        echo json_encode(["message" => "Requête invalide. L'ID du produit est requis."]);
    }
} else {
    http_response_code(405);
    
    echo json_encode(["message" => "Méthode non autorisée. Seules les requêtes GET sont acceptées."]);
}
?>
