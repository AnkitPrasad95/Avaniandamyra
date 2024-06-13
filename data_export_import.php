<?php 
require_once('app/autoload.php');
$allOldProducts = $query->allOldProducts();
// echo "<pre>";
// print_r($allOldProducts);
// echo "</pre>";
echo $product_ids = implode(',',$allOldProducts)

// foreach($allOldProducts as $key=> $product) {
    
//     //print_r($product);
    
//     $images = explode(',',$product->images);
//     //echo "<pre>";
//     $name = $product->name;
//     $categories = $product->categories;
//     $images = str_replace('http://127.0.0.1/avaniandamyra/wp-content/uploads/2022/08/', '', $images[0]);
//     //echo "</pre>";
    

//    //$save = $query->save_data($name, $categories, $images);
// }
?>