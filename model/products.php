<?php
require_once("./config/connect.php");
/**
* @author: Lucky Molefe
*/
class Shop {
	private $connect = null;
	private $productID;
	protected $queryProduct;
	protected $data = null;

	public function __construct() {
		global $connect;
		$this->conn = $connect;
	}

	public function getProducts() {
		$stmt = $this->conn->prepare("SELECT a.product_id, a.product_name, a.price, a.category_id, b.product_id, b.path
									FROM products a LEFT JOIN product_image b
									ON a.product_id = b.product_id
									ORDER BY a.product_id ASC");
		$stmt->execute();
		$count = $stmt->rowCount();
		if($count) {
			return $rows = $stmt->fetchAll();
		} 
		else {
			return false;
		}
	}

	public function doSearch($string) { //this function is used by users to search other registered users
		// $query = $string;
		// $searched_word = stripslashes(strip_tags($query));
		$string = "%".$string."%";
		$this->queryProduct = $string;
		return $this->productSearch();
	}

	private function productSearch() {
		$get = $this->conn->prepare("SELECT a.product_id, a.product_name, a.price, a.category_id, b.product_id, b.path 
									FROM products a LEFT JOIN product_image b
									ON a.product_id = b.product_id
									WHERE a.product_name LIKE ? ORDER BY a.product_name ASC");
		$get->bindParam(1, $this->queryProduct, PDO::PARAM_STR);
		$get->execute();
		$count = $get->rowCount();
		if($count > 0) {
			return $rows = $get->fetchAll();
		}
		else {
			return false;
		}
	}

	protected function hash_data($data) {
		$this->data = $data;
		return SHA1($this->data);
	}

} /* END class Shop() */

$initObj = new Shop(); //instantiate object

?>