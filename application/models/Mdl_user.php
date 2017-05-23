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

	// 计算用户总数
	public function get_total_user_count(){
		$sql="
			SELECT
				COUNT(*) as num
			FROM
				(SELECT
					u.user_id
				FROM
					(SELECT * FROM tbl_user WHERE `status` = 0) u 
						INNER JOIN
					tbl_rel_user_like_news rel ON rel.user_id=u.user_id
						INNER JOIN
					(SELECT * FROM tbl_news WHERE `status` = 0) news ON news.news_id=rel.news_id
				GROUP BY 
					u.user_id) a
		";
		return $this->db->query($sql)->row()->num;
	}

	public function get_martix_user_id_list(){
		$sql="
			SELECT
				u.user_id
			FROM
				(SELECT * FROM tbl_user WHERE `status` = 0) u 
					INNER JOIN
				tbl_rel_user_like_news rel ON rel.user_id=u.user_id
					INNER JOIN
				(SELECT * FROM tbl_news WHERE `status` = 0) news ON news.news_id=rel.news_id
			GROUP BY 
				u.user_id
			ORDER BY 
				u.user_id ASC
		";
		return $this->db->query($sql)->result();
	}

}

/* End of file Mdl_user.php */
/* Location: ./application/models/Mdl_user.php */