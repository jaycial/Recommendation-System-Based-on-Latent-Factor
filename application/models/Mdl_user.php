<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_user extends CI_Model {


	public function check_login($email,$password){
		$sql="
			SELECT
				u.user_id,u.`password`
			FROM 
				tbl_user u
			WHERE
				u.`status`=0
					AND
				u.email='$email'

		";
		$res=$this->db->query($sql)->row();
		if($password==$res->password){
			$this->db->update('tbl_user',array('last_login' => time()),array('user_id' => $res->user_id));
			return $res->user_id;
		}else{
			return false;
		}
	}

	public function get_user_info($uid){
		if($uid){
			$sql="
				SELECT
					u.user_id,u.username
				FROM
					tbl_user u
				WHERE
					u.`status`=0
						AND
					u.user_id={$uid}
			";
			return $this->db->query($sql)->row();
		}
	}	

	public function check_email_unique($email){
		$sql="
			SELECT
				COUNT(*) AS num
			FROM
				tbl_user u
			WHERE
				u.`status`=0
					AND
				u.email='$email'
		";
		$res=$this->db->query($sql)->row();
		return ($res->num > 0)?false:true;
	}

	public function check_username_unique($username){
		$sql="
			SELECT
				COUNT(*) AS num
			FROM
				tbl_user u
			WHERE
				u.`status`=0
					AND
				u.username='$username'
		";
		$res=$this->db->query($sql)->row();
		return ($res->num > 0)?false:true;
	}

}

/* End of file Mdl_user.php */
/* Location: ./application/models/Mdl_user.php */