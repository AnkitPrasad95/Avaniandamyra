<?php 
class query{

    function __construct() {
        include 'config2.php';
    }
    public function getCategory()
    {
        $statement = $this->db->prepare("select * from tbl_product_category where parent_category = 0 order by index_order asc");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function grandProductCount($id) {
        $statement = $this->db->prepare("SELECT id, name FROM tbl_product_category where parent_category = ?");
        $statement->execute(array($id));
        $childs = $statement->fetchAll(PDO::FETCH_OBJ);
        // echo "<pre>";
        // print_r($childs);
        // echo "</pre>";
        //echo emplode($childs);
        //print_r($childs);
        $data = array();
        foreach($childs as $child) {
            $cat = $id.','.$child->id;
            $query = "select id, name from tbl_product_list where categories LIKE '$cat%'";
            $statement = $this->db->prepare($query);
            $statement->execute(array());
            $product = $statement->fetchAll(PDO::FETCH_OBJ);
            //$total += $count;
            array_push($data, $product);
        }
        $result = array();
        foreach ($data as $array) {
            $result = array_merge($result, $array);
        }
    
        $uniqueArry = array();
     
        foreach($result as $val) { //Loop1 
            
            foreach($uniqueArry as $uniqueValue) { //Loop2 
    
                if($val == $uniqueValue) {
                    continue 2; // Referring Outer loop (Loop 1)
                }
            }
            $uniqueArry[] = $val;
        }
        //print_r($uniqueArry); die;
        //echo "<pre>";
       // print_r($uniqueArry);
        //echo "</pre>";
       
        //die();
        return count($uniqueArry);
       
    }

    public function productCount($id = '')
    {
        if($id != '') {
            $statement = $this->db->prepare("select * from tbl_product_list where categories LIKE ?");
            $statement->execute(array("%$id"));
        } else {
            $statement = $this->db->prepare("select * from tbl_product_list");
            $statement->execute();
        }
        return $statement->rowCount();
    }

    
    public function childCategories($id = '') {

        $statement = $this->db->prepare("SELECT * FROM tbl_product_category where parent_category = ?");
        $statement->execute(array($id));
        return $statement->fetchAll(PDO::FETCH_OBJ);

    }
    /* ---------------------------Manage Product according to Visitor login---------------------------------- */
    public function visitorProductCount($cat_url = '', $child_url = '', $filter= '') {
        //echo $filter;
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $catres = $statement->fetch(PDO::FETCH_OBJ); 
    
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$child_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $childres = $statement->fetch(PDO::FETCH_OBJ); 

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }
        

        if(!empty($childres) && !empty($catres)) {
            $categories = $catres->id.','.$childres->id;
            $query = "SELECT id FROM tbl_product_list where categories LIKE '$categories%'";
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
        } else if(empty($childres) && !empty($catres)) {
            $categories = $catres->id;
            // $statement = $this->db->prepare("SELECT id, name FROM tbl_product_category where parent_category = ?");
            // $statement->execute(array($categories));
            // $childs = $statement->fetchAll(PDO::FETCH_OBJ);
           
            // $data = array();
            // foreach($childs as $child) {
            //     $cat = $categories.','.$child->id;
            //     $query = "select id, name from tbl_product_list where categories LIKE '$cat%'";
            //     $statement = $this->db->prepare($query);
            //     $statement->execute(array());
            //     $product = $statement->fetchAll(PDO::FETCH_OBJ);
            //     //$total += $count;
            //     array_push($data, $product);
            // }
            // //print_r($data);
            // $result = array();
            // foreach ($data as $array) {
            //     $result = array_merge($result, $array);
            // }
        
            // $uniqueArry = array();
         
            // foreach($result as $val) { //Loop1 
                
            //     foreach($uniqueArry as $uniqueValue) { //Loop2 
        
            //         if($val == $uniqueValue) {
            //             continue 2; // Referring Outer loop (Loop 1)
            //         }
            //     }
            //     $uniqueArry[] = $val;

            // }
            //$count = count($uniqueArry);
            $query = "SELECT id FROM tbl_product_list where categories LIKE '$categories%'";
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
        } else if(!empty($childres) && empty($catres)) {
            if(!empty($filterRes)) {
                $categories = $childres->id.','.$filter_id;
            } else {
                $categories = $childres->id;
            }
            
            $query = "SELECT id FROM tbl_product_list where categories LIKE '$categories%'";
            $statement = $this->db->prepare($query);
            $statement->execute();
            $count = $statement->rowCount();
        }
        
        return $count;
    }

    public function getProductCatSubcat($cat_url = '', $child_url = '', $orderBy ='', $filter= '', $startFrom = '', $showRecordPerPage = ''){

        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $catres = $statement->fetch(PDO::FETCH_OBJ); 
    
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$child_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $childres = $statement->fetch(PDO::FETCH_OBJ); 

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }

        if(!empty($childres) && !empty($catres)) {
            $categories = $catres->id.','.$childres->id;
        } else if(empty($childres) && !empty($catres)) {
            $categories = $catres->id;
            
        } else if(!empty($childres) && empty($catres)) {
            if(!empty($filterRes)) {
                $categories = $childres->id.','.$filter_id;
            } else {
                $categories = $childres->id;
            }
           
        }
        $query = "SELECT * FROM tbl_product_list where categories LIKE '$categories%' order by id $orderBy limit $startFrom, $showRecordPerPage";
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getProductCat($cat_url = '',  $orderBy ='', $filter= '', $startFrom = '', $showRecordPerPage = '') {
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_OBJ); 
        

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }

        if(!empty($filterRes)) {
            $cat_id = $res->id.','.$filter_id;
        } else {
            $cat_id = $res->id;
        }
    
        $query = "SELECT * FROM tbl_product_list where categories LIKE '$cat_id%' order by id $orderBy limit ".$startFrom.','. $showRecordPerPage;
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /* ---------------------------Manage Product according to Customer login---------------------------------- */
    public function getCustomerProductCountByCatSubcat($cat_url = '', $child_url = '', $products = '', $filter= '') {
        if(!empty($cat_url) && !empty($child_url)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $catres = $statement->fetch(PDO::FETCH_OBJ); 
        
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$child_url'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $childres = $statement->fetch(PDO::FETCH_OBJ); 

            if(!empty($filter)) {
                $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
                $statement = $this->db->prepare($sql);
                $statement->execute();
                $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
                $filter_id = $filterRes->id;
    
            }
    

            if(!empty($childres) && !empty($catres)) {
                $categories = $catres->id.','.$childres->id;
            } else if(empty($childres) && !empty($catres)) {
                $categories = $catres->id;
            } else if(!empty($childres) && empty($catres)) {
                if(!empty($filterRes)) {
                    $categories = $childres->id.','.$filter_id;
                } else {
                    $categories = $childres->id;
                }
                
            }
            $query = "SELECT id FROM tbl_product_list where id IN($products) AND categories LIKE '$categories%'";
            $statement = $this->db->prepare($query);
            $statement->execute();

        } else {
            $sql = "SELECT id FROM `tbl_product_category` where slug  = '$cat_url'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $res = $statement->fetch(PDO::FETCH_OBJ); 
            $cat_id = $res->id;

            $statement = $this->db->prepare("SELECT id FROM tbl_product_list where id IN($products) AND categories LIKE '$cat_id%'");
            $statement->execute();
        }
        return $statement->rowCount();
    }

    public function getProductCatSubcatByCustomer($cat_url = '', $child_url = '', $orderBy ='', $products = '', $filter= '', $startFrom = '', $showRecordPerPage = ''){

        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $catres = $statement->fetch(PDO::FETCH_OBJ); 
    
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$child_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $childres = $statement->fetch(PDO::FETCH_OBJ); 

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }

        if(!empty($childres) && !empty($catres)) {
            $categories = $catres->id.','.$childres->id;
        } else if(empty($childres) && !empty($catres)) {
            $categories = $catres->id;
        } else if(!empty($childres) && empty($catres)) {
            if(!empty($filterRes)) {
                $categories = $childres->id.','.$filter_id;
            } else {
                $categories = $childres->id;
            }
           
        }
        $query = "SELECT * FROM tbl_product_list where id IN($products) AND categories LIKE '$categories%' order by id $orderBy limit $startFrom, $showRecordPerPage";
        $statement = $this->db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getCustomerProductCountByCat($cat_url = '', $products = '' , $filter= '') {

        $sql = "SELECT id FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_OBJ); 
        $cat_id = $res->id;

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }
        if(!empty($filterRes)) {
            $cat_id = $cat_id.','.$filter_id;
        } else {
            $cat_id = $cat_id;
        }

        $query = "SELECT id FROM tbl_product_list where id IN($products) AND categories LIKE '$cat_id%'";
        $statement = $this->db->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }

    public function getProductCatByCustomer($cat_url = '', $orderBy ='', $products = '', $filter= '', $startFrom = '', $showRecordPerPage = '') {
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $res = $statement->fetch(PDO::FETCH_OBJ); 
        $cat_id = $res->id;

        if(!empty($filter)) {
            $sql = "SELECT * FROM `tbl_product_category` where slug  = '$filter'";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $filterRes = $statement->fetch(PDO::FETCH_OBJ); 
            $filter_id = $filterRes->id;

        }
        if(!empty($filterRes)) {
            $cat_id = $cat_id.','.$filter_id;
        } else {
            $cat_id = $cat_id;
        }
    
        $query = "SELECT * FROM tbl_product_list where id IN($products) AND categories LIKE '$cat_id%' order by id $orderBy limit ".$startFrom.','. $showRecordPerPage;
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFilter($cat_url = '', $child_url = '') {
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$cat_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $catres = $statement->fetch(PDO::FETCH_OBJ); 
    
        $sql = "SELECT * FROM `tbl_product_category` where slug  = '$child_url'";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $childres = $statement->fetch(PDO::FETCH_OBJ); 

        if(!empty($childres) && !empty($catres)) {
            $categories = 0;
        } else if(empty($childres) && !empty($catres)) {
            $categories = $catres->id;
        } else if(!empty($childres) && empty($catres)) {
            $categories = $childres->id;
        } else {
            $categories = 0;
        }
        $sql = "SELECT id, name, slug, parent_category FROM `tbl_product_category` where parent_category  > 0 AND parent_category = $categories AND status = 1";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ); 
    }

   /* ---------------------------Manage Product according to Customer login end---------------------------------- */
    //end product listing code

    public function imageList($pro_id){
        $statement = $this->db->prepare("select * from tbl_gallery where p_category_id =? order by photo_id desc");
        $statement->execute(array($pro_id));
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
    

   

    public function contactEnquirySave($value) {
        //print_r($value); die;
        $statement = $this->db->prepare("insert into tbl_contact (theme, email, file_path, attachment, comment, created_at, user_ip) VALUES (?,?,?,?,?,?,?)");
        $statement->execute($value);
    }

    public function newsletterSave($value) {
        
        $requestData = array($value[0]);
        $statement = $this->db->prepare("select * from tbl_subscriber where subs_email = ?");
        $statement->execute($requestData);
        $total = $statement->rowCount();
        if($total > 0) {
            return "email_exist";
        } else {
            $statement = $this->db->prepare("insert into tbl_subscriber (subs_email, subs_date_time, subs_active, user_ip) VALUES (?,?,?,?)");
            $statement->execute($value);
            return "added_success";
        }
        
    }

    public function user_login($username, $password) {
        $username = strip_tags($username);
        $password = strip_tags($password);
        $statement = $this->db->prepare("select * from tbl_customers where (user_name =? OR email = ?) AND status=?");
        $statement->execute(array($username, $username, 1));
        $total = $statement->rowCount();  
        $row = $statement->fetch(PDO::FETCH_OBJ);    
        if($total==0) {
            return 'user_error';
        } else {       
            $row_password = $row->password;
        }
        if( $row_password != md5($password) ) {
            return 'password_error';
        } else {       
            return $row;
        }
    }

    public function getCustomerDetailsByid($id) {
        $statement = $this->db->prepare("select * from tbl_customers where id =? AND status=?");
        $statement->execute(array($id, 1));
        $total = $statement->rowCount();  
        $row = $statement->fetch(PDO::FETCH_OBJ);  
        if($total > 0) {
            return $row;
        } else {
            return 'user_not_exist';
        }
        
    }

    public function getCollectionByUser($ids = '') {
        if(!empty($ids)) {
            $statement = $this->db->prepare("select * from manage_collection where collection_id IN($ids)");
            $statement->execute();
            $collections = $statement->fetchAll(PDO::FETCH_OBJ);
            //print_r($collections); die;
            $product_ids = array();
            $category_ids = array();
            foreach($collections as $collection) {
                $category = $collection->categories;
                $product = $collection->products;
                array_push($product_ids, $product);
                array_push($category_ids, $category);
                
            }

            //for product data start
            $result = array();
            foreach ($product_ids as $array) {
                $data = explode(',', $array);
                $result = array_merge($result, $data);
            }
            $uniqueArry = array();
 
            foreach($result as $val) { //Loop1 
                
                foreach($uniqueArry as $uniqueValue) { //Loop2 

                    if($val == $uniqueValue) {
                        continue 2; // Referring Outer loop (Loop 1)
                    }
                }
                $proUniqueArry[] = $val;
            }
            $productData = implode(',', $proUniqueArry);
            $statement = $this->db->prepare("select id, name, categories from tbl_product_list where id IN($productData) order by id desc");
            $statement->execute();
            $products = $statement->fetchAll(PDO::FETCH_OBJ);
            //for product data end

            //for category data start
            $result = array();
            foreach ($category_ids as $array) {
                $data = explode(',', $array);
                $result = array_merge($result, $data);
            }
            $uniqueArry = array();
 
            foreach($result as $val) { //Loop1 
                
                foreach($uniqueArry as $uniqueValue) { //Loop2 

                    if($val == $uniqueValue) {
                        continue 2; // Referring Outer loop (Loop 1)
                    }
                }
                $catUniqueArry[] = $val;
            }
            $categoryData = implode(',', $catUniqueArry);
            $statement = $this->db->prepare("select id, name, slug, parent_category from tbl_product_category where id IN($categoryData) order by id asc");
            $statement->execute();
            $categories = $statement->fetchAll(PDO::FETCH_OBJ);
            //for category data end
            
        } else {
            $statement = $this->db->prepare("select * from manage_collection where collection_id = 1");
            $statement->execute();
            $collections = $statement->fetchAll(PDO::FETCH_OBJ);
            $product_ids = array();
            $category_ids = array();
            foreach($collections as $collection) {
                $category = $collection->categories;
                $product = $collection->products;
                array_push($product_ids, $product);
                array_push($category_ids, $category);
                
            }

            //for product data start
            $result = array();
            foreach ($product_ids as $array) {
                $data = explode(',', $array);
                $result = array_merge($result, $data);
            }
            $uniqueArry = array();
 
            foreach($result as $val) { //Loop1 
                
                foreach($uniqueArry as $uniqueValue) { //Loop2 

                    if($val == $uniqueValue) {
                        continue 2; // Referring Outer loop (Loop 1)
                    }
                }
                $proUniqueArry[] = $val;
            }
            $productData = implode(',', $proUniqueArry);
            $statement = $this->db->prepare("select id, name, categories from tbl_product_list where id IN($productData) order by id desc");
            $statement->execute();
            $products = $statement->fetchAll(PDO::FETCH_OBJ);
            //for product data end

            //for category data start
            $result = array();
            foreach ($category_ids as $array) {
                $data = explode(',', $array);
                $result = array_merge($result, $data);
            }
            $uniqueArry = array();
 
            foreach($result as $val) { //Loop1 
                
                foreach($uniqueArry as $uniqueValue) { //Loop2 

                    if($val == $uniqueValue) {
                        continue 2; // Referring Outer loop (Loop 1)
                    }
                }
                $catUniqueArry[] = $val;
            }
            $categoryData = implode(',', $catUniqueArry);
            $statement = $this->db->prepare("select id, name, slug, parent_category from tbl_product_category where id IN($categoryData) order by id asc");
            $statement->execute();
            $categories = $statement->fetchAll(PDO::FETCH_OBJ);
            //for category data end
        }
        // echo "<pre>";
        // print_r($data); 
        // die;
        $data = ['categories' => $categories, 'products' => $products, 'user_collentions' => $collections];
        return $data;
    }

    public function checkOrder($product_id, $customer_id, $comment, $quantity){
        $query = "select id, product_id, user_id from tbl_order_list where product_id =? AND user_id =? AND mail_status =?";
        $statement = $this->db->prepare($query);
        $statement->execute(array($product_id, $customer_id, 0));
        $total = $statement->rowCount();
        
        if($total > 0) {
            $data = array($comment, $quantity, $product_id);
            $statement = $this->db->prepare("update tbl_order_list set comment=?, quantity=? where product_id =?");
            $statement->execute($data);
            return 'product_already_exist';
        } else {
            $data = array($customer_id, $product_id, $comment, $quantity, date('Y-m-d h:i:s'));
            $statement = $this->db->prepare("insert into tbl_order_list (user_id, product_id, comment, quantity, created_at) VALUES (?,?,?,?,?)");
            $statement->execute($data);
            return "product_added";
        }
    }

    public function orderCount($user_id){
        $statement = $this->db->prepare("select id, product_id, user_id from tbl_order_list where user_id =? AND mail_status =?");
        $statement->execute(array($user_id, 0));
        return $statement->rowCount();
    }

    public function orderList($user_id){
        $statement = $this->db->prepare("select tbl_order_list.id as order_id, tbl_order_list.comment, tbl_order_list.quantity, tbl_order_list.mail_status, tbl_product_list.* from tbl_order_list 
            inner join tbl_product_list ON tbl_order_list.product_id = tbl_product_list.id
            where tbl_order_list.user_id =? AND tbl_order_list.mail_status =?");
        $statement->execute(array($user_id, 0));
        return $res = $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function orderRemove($product_id, $user_id){
        $statement = $this->db->prepare("delete from tbl_order_list where product_id=? AND user_id =? AND mail_status =? ");
        $res = $statement->execute(array($product_id, $user_id, 0));
        if($res) {
            return 'deleted';
        } else {
            return 'error';
        }

    }

    public function addComment($comment, $quantity, $product_id, $user_id) {
        $data = array($comment, $quantity, $product_id, $user_id, 0);
        $statement = $this->db->prepare("update tbl_order_list set comment=?, quantity=? where product_id = ? AND user_id = ? AND mail_status = ?");
        $statement->execute($data);
        return "comment_added";
    }

    public function getProductDetails($product_id){
        $statement = $this->db->prepare("select * from tbl_product_list where id =?");
        $statement->execute(array($product_id));
        return $product = $statement->fetch(PDO::FETCH_OBJ);
    }

    public function get_images($product_id){
        $statement = $this->db->prepare("select  * from tbl_gallery where p_category_id = ? order by photo_id asc");
        $statement->execute(array($product_id));
        return $images = $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateOrderStatus($user_id) {
        $data = array(1, $user_id);
        $statement = $this->db->prepare("update tbl_order_list set mail_status=? where user_id =?");
        $statement->execute($data);
    }

    //wish list 

    public function add_to_wishlist($product_id, $user_id){

        $data = array($product_id, $user_id);
        $statement = $this->db->prepare("select  * from tbl_wishlist where product_id = ? AND user_id =?");
        $statement->execute($data);
        $total =$statement->rowCount();
        if($total > 0){
            $res = $statement->fetch(PDO::FETCH_OBJ);
            $statement = $this->db->prepare("delete from tbl_wishlist where where product_id = ? AND user_id =?");
            $statement->execute($data);
            return "remove_from_wishlist";

        } else {
            $statement = $this->db->prepare("insert into tbl_wishlist (user_id, product_id, created_at) VALUES (?,?,?)");
            $statement->execute(array($user_id, $product_id, date('Y-m-d H:i:s')));
            return "added_in_wishlist";
        }

    }

    public function get_product_wishlist($product_id, $user_id){
        $data = array($product_id, $user_id);
        $statement = $this->db->prepare("select  * from tbl_wishlist where product_id = ? AND user_id =?");
        $statement->execute($data);
        return $total = $statement->rowCount();
    }

    public function gatColletionDetail($id) {
        $statement = $this->db->prepare("select * from tbl_collection where id =?");
        $statement->execute(array($id));
        return $collection = $statement->fetch(PDO::FETCH_OBJ);
    }

    public function getCommonCollections() {
        $statement = $this->db->prepare("select * from manage_collection where show_on_header =?");
        $statement->execute(array(1));
        return $collections = $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function allOldProducts(){
        $statement = $this->db->prepare("select id from tbl_product_list order by id asc");
        $statement->execute();
        return  $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function save_data($name, $categories, $image){
        $statement = $this->db->prepare("insert into tbl_product_list_new (name, categories, file_path, thumbnail_image, created_at) VALUES (?,?,?,?,?)");
        $statement->execute(array($name, $categories, 'assets/uploads/product-list/', $image, date('Y-m-d H:i:s')));
    }

     public function random_string($length = 8) {
        $alphabets = range('A','Z');
        $numbers = range('0','9');
        $additional_characters = array('_','=');
        $final_array = array_merge($alphabets,$numbers,$additional_characters);
        $password = '';
        while($length--) {
          $key = array_rand($final_array);
    
          $password .= $final_array[$key];
        }
      if (preg_match('/[A-Za-z0-9]/', $password))
        {
         return $password;
        }else{
        return  random_string();
        }
    
     }

    public function checkExistingUser($postData){
        
        $useremail = strip_tags($postData['Email']);
        $statement = $this->db->prepare("select * from tbl_customers where email = ? AND status=?");
        $statement->execute(array($useremail, 1));
        $total = $statement->rowCount();  
        $row = $statement->fetch(PDO::FETCH_OBJ);
        $password = $this->random_string();
        
        if($total > 0) {
            $data = array(md5($password), $password, $useremail);
            $statement = $this->db->prepare("update tbl_customers set password=?, updated_password = ? where email = ?");
            $statement->execute($data);
            $message = ['message' => 'already_exist', 'password' => $password];

           $requestData = array(date('Y-m-d H:i:s'), $useremail);
           $statement = $this->db->prepare("update tbl_user_request set modified_at = ? where email = ?");
           $statement->execute($requestData);
        } else {
           
           $username =  strtolower(str_replace(' ', '-', $postData['Person'])).date('dmyhis');
           $names = explode(' ', $postData['Person']);
          
           $f_name = $names[0];
           if(count($names) > 1) {
            if(!empty($names[2])) {
                $name2 = $names[2];
            } else {
                $name2 = '';
            }
            $l_name = $names[1].' '.$name2;
           } else if(!empty($names[1])) {
            $l_name = $names[1];
           }  else {
            $l_name = '';
           }
           $phone = $postData['Phone'];
           //print_r($postData); die;
           $data = array($username, $useremail, $f_name, $l_name, $phone, md5($password), 'visitor', 1, $password, date('Y-m-d H:i:s'));
           $query = "insert into tbl_customers (user_name, email, first_name, last_name, contact, password, role, status, updated_password, created_at) VALUES (?,?,?,?,?,?,?,?,?,?)";
           $statement = $this->db->prepare($query);
           $res = $statement->execute($data);
           //print_r($res); die;
           
           $message = ['message' => 'new_visitor', 'username' =>  $username, 'password' => $password];

           //insert data into request info
           $requestData = array($postData['organization_name'], $postData['Person'], $postData['Email'], $postData['Phone'], $postData['Address'], $postData['Buyer_type'], $postData['message'], date('Y-m-d H:i:s'));
           $statement = $this->db->prepare("insert into tbl_user_request (organization_name, person, email, phone, address, type_of_buyer, message, created_at) VALUES(?,?,?,?,?,?,?,?)");
           $statement->execute($requestData);
           
        }
       
        return $message;
    }

    public function getProducts($product_id) {
        $query = "select id, name, file_path, thumbnail_image from tbl_product_list where id = $product_id";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return  $statement->fetch(PDO::FETCH_OBJ);
    }

    public function checkCurrentOrderCount($user_id) {
        $query = "select id from tbl_order_list where user_id = $user_id AND mail_status = 0";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->rowCount();
    }
    
   public function saveOrder($uniqueOrder_id, $user_id, $comment_msg) {
    
    $statement = $this->db->prepare("insert into tbl_orders (order_id, user_id, message, created_at) VALUES (?,?,?,?)");
    $statement->execute(array($uniqueOrder_id, $user_id, $comment_msg, date('Y-m-d H:i:s')));
    $LAST_ID = $this->db->lastInsertId();
    $cartData = $this->orderList($user_id);
    foreach($cartData as $orderListRow){
        if( $orderListRow['quantity'] > 1) {
            $quantity =  $orderListRow['quantity']; 

        } else {
            $quantity = 1;
        }

        $this->saveOrderDetails($LAST_ID, $user_id, $orderListRow['id'], $quantity, $orderListRow['comment']);
    }

   }
   public function saveOrderDetails($saveOrder, $user_id, $product_id, $quantity, $comment) {
    $statement = $this->db->prepare("insert into tbl_order_details (order_id, user_id, product_id, quantity, message, created_at) VALUES (?,?,?,?,?,?)");
    $statement->execute(array($saveOrder, $user_id, $product_id, $quantity, $comment, date('Y-m-d H:i:s')));
   }

}
