<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_news extends CI_Model {

	public function get_news_list($type,$limit,$per_page){
		$sql="
			SELECT
				news.news_id,news.title,news.img_url,news.source_time,news.abstract,news.from
			FROM
				tbl_news news
			WHERE
				news.`status`=0
					AND
				news.type='$type'
			ORDER BY 
				news.source_time DESC
			LIMIT 
				$limit,$per_page
		";
		$res=$this->db->query($sql)->result();
		return $res;
	}

	public function get_news_count($type){
		$sql="
			SELECT
				count(*) AS num
			FROM
				tbl_news news
			WHERE
				news.`status`=0
					AND
				news.type='$type'
		";
		$res=$this->db->query($sql)->row();
		return $res->num;
	}

	public function get_news_info($news_id){
		$sql="
			SELECT
				news_id,title,img_url,content
			FROM
				tbl_news news
			WHERE
				news.`status`=0
					AND
				news.news_id={$news_id}
		";
		$res=$this->db->query($sql)->row();
		return $res;
	}

	public function get_user_like_status($user_id,$news_id){
		$sql="
			SELECT
				rel.like_status
			FROM
				tbl_rel_user_like_news rel
			WHERE
				rel.user_id={$user_id}
					AND
				rel.news_id={$news_id}
		";
		return $res=$this->db->query($sql)->row()->like_status;
	}

}

/* End of file Mdl_news.php */
/* Location: ./application/models/Mdl_news.php */