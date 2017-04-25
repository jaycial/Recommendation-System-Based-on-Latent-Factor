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

}

/* End of file Mdl_news.php */
/* Location: ./application/models/Mdl_news.php */