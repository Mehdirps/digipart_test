<?php
class Product {
    private $conn;
    private $table_name = "products";
    private $info_table = "product_infos";
    
    public $id;
    public $reference;
    public $title;
    public $image;
    public $description;
    public $price;
    
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function create(){
        // Commencer une transaction
        $this->conn->beginTransaction();
        
        try {
            // Insertion dans la table products
            $query = "INSERT INTO " . $this->table_name . " 
                    SET 
                        reference=:reference, 
                        title=:title, 
                        image=:image";
        
            $stmt = $this->conn->prepare($query);
        
            $this->reference = htmlspecialchars(strip_tags($this->reference));
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->image = htmlspecialchars(strip_tags($this->image));
        
            $stmt->bindParam(":reference", $this->reference);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":image", $this->image);
        
            $stmt->execute();
            
            // Obtenir l'ID du produit nouvellement inséré
            $this->id = $this->conn->lastInsertId();
            
            // Insertion dans la table product_infos
            $query = "INSERT INTO " . $this->info_table . " 
                    SET 
                        product_id=:product_id,
                        description=:description, 
                        price=:price";
            
            $stmt = $this->conn->prepare($query);
            
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->price = htmlspecialchars(strip_tags($this->price));
            
            $stmt->bindParam(":product_id", $this->id);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":price", $this->price);
            
            $stmt->execute();
            
            // Valider la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function getAll(){
        $query = "SELECT
            p.id, p.reference, p.title, p.image
            FROM
            " . $this->table_name . " p
            ORDER BY
            p.id DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function getById(){
        $query = "SELECT
                p.id, p.reference, p.title, p.image,
                pi.description, pi.price
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    " . $this->info_table . " pi ON p.id = pi.product_id
            WHERE
                p.id = ?
            LIMIT 0,1";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if($row) {
            $this->reference = $row['reference'];
            $this->title = $row['title'];
            $this->image = $row['image'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            return true;
        }
        
        return false;
    }
    
    public function update(){
        // Commencer une transaction
        $this->conn->beginTransaction();
        
        try {
            // Réupérer l'image actuelle avant la mise à jour
            $current_image_query = "SELECT image FROM " . $this->table_name . " WHERE id = ?";
            $current_image_stmt = $this->conn->prepare($current_image_query);
            $current_image_stmt->bindParam(1, $this->id);
            $current_image_stmt->execute();
            $current_image = $current_image_stmt->fetch(PDO::FETCH_ASSOC)['image'];
            
            // Mise à jour de la table products
            $query = "UPDATE
                    " . $this->table_name . "
                SET
                    reference = :reference,
                    title = :title,
                    image = :image
                WHERE
                    id = :id";
        
            $stmt = $this->conn->prepare($query);
        
            $this->reference = htmlspecialchars(strip_tags($this->reference));
            $this->title = htmlspecialchars(strip_tags($this->title));
            
            // Vérifier si une nouvelle image a été téléchargée
            if(empty($this->image)) {
                $this->image = $current_image;
            } else {
                // Supprimer l'ancienne image si elle existe
                if($current_image && file_exists('uploads/' . $current_image)) {
                    unlink('uploads/' . $current_image);
                }
            }
            
            $this->image = htmlspecialchars(strip_tags($this->image));
            $this->id = htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(':reference', $this->reference);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':image', $this->image);
            $stmt->bindParam(':id', $this->id);
        
            $stmt->execute();
            
            // Mise à jour de la table product_infos
            $query = "UPDATE
                    " . $this->info_table . "
                SET
                    description = :description,
                    price = :price
                WHERE
                    product_id = :product_id";
            
            $stmt = $this->conn->prepare($query);
            
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->price = htmlspecialchars(strip_tags($this->price));
            
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':product_id', $this->id);
            
            $stmt->execute();
            
            // Valider la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function delete(){
        // Commencer une transaction
        $this->conn->beginTransaction();
        
        try {
            // Réupérer l'image avant la suppression
            $query = "SELECT image FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si l'image existe, la supprimer
            if($row && $row['image'] && file_exists('uploads/' . $row['image'])) {
                unlink('uploads/' . $row['image']);
            }
            
            // Supprimer d'abord de product_infos (pour respecter les contraintes de clé étrangère)
            $query = "DELETE FROM " . $this->info_table . " WHERE product_id = ?";
        
            $stmt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            
            // Puis supprimer de products
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            
            // Valider la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
