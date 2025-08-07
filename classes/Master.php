<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `category_list` set {$data} ";
		}else{
			$sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Category Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Category has successfully added.";
				else
					$resp['msg'] = "Category details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `category_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_policy(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `policy_list` set {$data} ";
		}else{
			$sql = "UPDATE `policy_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `policy_list` where `code` = '{$code}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = ' Policy Code exists on the selected category.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$pid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $pid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Policy has successfully added.";
				else
					$resp['msg'] = " Policy details has been updated successfully.";
				if(!empty($_FILES['doc']['tmp_name'])){
					$file = $_FILES['doc']['tmp_name'];
					$fname = "uploads/policies/{$pid}.pdf";
					$type = mime_content_type($file);
					if($type == 'application/pdf'){
						if(is_file(base_app.$fname))
						unlink(base_app.$fname);
						$move = move_uploaded_file($file,base_app.$fname);
						if($move){
							$this->conn->query("UPDATE `policy_list` set doc_path = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$pid}' ");
						}else{	
							$resp['msg'].="But Unable to upload document due to unknown reason.";
						}
					}else{
						$resp['msg'].="But Unable to upload document due to invalid file type.";
					}
				}
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_policy(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `policy_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Policy has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_client(){
		if(empty($_POST['id'])){
			$pref = date('Ym-');
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `client_list` where `code` = '{$pref}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",abs($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $pref.$code;
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `client_list` set {$data} ";
		}else{
			$sql = "UPDATE `client_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$cid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $cid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = " Client has successfully added.";
			else
				$resp['msg'] = " Client details has been updated successfully.";
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/clients/'.$cid.'.png';
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$resp['msg'].=" But Image failed to upload due to invalid file type.";
				}else{
					$new_height = 200; 
					$new_width = 200; 
			
					list($width, $height) = getimagesize($upload);
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$resp['msg'].=" But Image failed to upload due to unkown reason.";
					}
				}
				if(isset($uploaded_img)){
					$this->conn->query("UPDATE client_list set `image_path` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$cid}' ");
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_client(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `client_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Client has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function get_expiration(){
		extract($_POST);
		$resp['value'] = date("Y-m-d",strtotime($registration_date. " + {$duration} years"));
		$resp['status'] = 'success';
		return json_encode($resp);
	}
	function save_insurance(){
		if(empty($_POST['id'])){
			$pref = date('Ym-');
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `insurance_list` where `code` = '{$pref}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",abs($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $pref.$code;
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `insurance_list` set {$data} ";
		}else{
			$sql = "UPDATE `insurance_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$iid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $iid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = " Insurance has successfully added.";
			else
				$resp['msg'] = " Insurance details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_insurance(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `insurance_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Insurance has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_payment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$this->conn->real_escape_string($v)}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `payment_history` set {$data} ";
		}else{
			$sql = "UPDATE `payment_history` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = " Payment has successfully added.";
			else
				$resp['msg'] = " Payment details has been updated successfully.";
			$total = $this->conn->query("SELECT total_amount FROM `insurance_list` where id = '{$insurance_id}'")->fetch_array()[0];
			$total = $total > 0 ? $total : 0;
			$total_paid = $this->conn->query("SELECT SUM(amount) from payment_history where insurance_id = '{$insurance_id}'")->fetch_array()[0];
			$total_paid = $total_paid > 0 ? $total_paid : 0;
			$pstatus = $total_paid > 0 ? ($total_paid == $total) ? 2 : 1 : 0;
			$balance = $total - $total_paid;
			$this->conn->query("UPDATE `insurance_list` set paid_amount = '{$total_paid}', payment_status = '{$pstatus}', `balance` ='{$balance}' where id = '{$insurance_id}'");
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_payment(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `payment_history` where id = '{$id}'");
		if($get->num_rows > 0){
			$res = $get->fetch_array();
		}
		$del = $this->conn->query("DELETE FROM `payment_history` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Payment has been deleted successfully.");
			if(isset($res['insurance_id'])){
				$total = $this->conn->query("SELECT total_amount FROM `insurance_list` where id = '{$res['insurance_id']}'")->fetch_array()[0];
				$total = $total > 0 ? $total : 0;
				$total_paid = $this->conn->query("SELECT SUM(amount) from payment_history where insurance_id = '{$res['insurance_id']}'")->fetch_array()[0];
				$total_paid = $total_paid > 0 ? $total_paid : 0;
				$pstatus = $total_paid > 0 ? ($total_paid == $total) ? 2 : 1 : 0;
				$balance = $total - $total_paid;
				$this->conn->query("UPDATE `insurance_list` set paid_amount = '{$total_paid}', payment_status = '{$pstatus}', `balance` ='{$balance}' where id = '{$res['insurance_id']}'");
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_insurance_status(){
		extract($_POST);
		
		$update = $this->conn->query("UPDATE `insurance_list` set status = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," insurance's Status has been updated successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_policy':
		echo $Master->save_policy();
	break;
	case 'delete_policy':
		echo $Master->delete_policy();
	break;
	case 'save_client':
		echo $Master->save_client();
	break;
	case 'delete_client':
		echo $Master->delete_client();
	break;
	case 'get_expiration':
		echo $Master->get_expiration();
	break;
	case 'save_insurance':
		echo $Master->save_insurance();
	break;
	case 'delete_insurance':
		echo $Master->delete_insurance();
	break;
	case 'save_payment':
		echo $Master->save_payment();
	break;
	case 'delete_payment':
		echo $Master->delete_payment();
	break;
	case 'update_insurance_status':
		echo $Master->update_insurance_status();
	break;
	default:
		// echo $sysset->index();
		break;
}