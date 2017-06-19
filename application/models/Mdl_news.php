<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_news extends CI_Model {

	public function get_news_list($type,$limit,$per_page,$user_id = ''){
		if('' == $user_id){
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
		}else{
			$sql="
				SELECT 
					news.news_id,news.title,news.img_url,news.source_time,news.abstract,news.from
				FROM
					tbl_news news 
						LEFT JOIN
					(SELECT
						news_id
					FROM
						tbl_rel_user_like_news rel 
					WHERE
						rel.user_id={$user_id}
							AND
						rel.score=1
					GROUP BY 
						rel.news_id) new_tbl ON new_tbl.news_id=news.news_id
				WHERE
					news.`status`=0 
						AND
					new_tbl.news_id IS NULL 
						AND
					news.type='$type'
				ORDER BY 
					news.source_time DESC
				LIMIT 
					$limit,$per_page
			";
		}
		
		$res=$this->db->query($sql)->result();
		return $res;
	}

	public function get_news_list_recommend($limit,$per_page,$user_id){
		$sql="
			SELECT
				news.news_id,news.title,news.img_url,news.source_time,news.abstract,news.from
			FROM
				tbl_news news 
					INNER JOIN 
				tbl_result_martix r_m ON news.news_id=r_m.item_id
					LEFT JOIN
				(SELECT * FROM tbl_rel_user_like_news rel WHERE rel.user_id ={$user_id}) n ON r_m.item_id=n.news_id
			WHERE
				n.rel_id IS NULL
					AND
				r_m.user_id={$user_id}
					AND 
				r_m.score > 3
			ORDER BY 
				r_m.score DESC
			LIMIT 
				$limit,$per_page
		";		
		$res=$this->db->query($sql)->result();
		return $res;
	}

	public function get_news_count($type,$user_id = ''){
		if('' == $user_id){
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
		}else{
			$sql="
				SELECT 
					COUNT(*) as num
				FROM
					tbl_news news 
						LEFT JOIN
					(SELECT
						news_id
					FROM
						tbl_rel_user_like_news rel 
					WHERE
						rel.user_id={$user_id}
							AND
						rel.score=1
					GROUP BY 
						rel.news_id) new_tbl ON new_tbl.news_id=news.news_id
				WHERE
					news.`status`=0 
						AND
					new_tbl.news_id IS NULL 
						AND
					news.type='$type'
			";
			$res=$this->db->query($sql)->row();
			return $res->num;
		}
		
	}

	public function get_news_count_recommend($user_id){
		$sql="
			SELECT
				COUNT(*) as num
			FROM
				tbl_news new 
					INNER JOIN 
				tbl_result_martix r_m ON new.news_id=r_m.item_id
					LEFT JOIN
				(SELECT * FROM tbl_rel_user_like_news rel WHERE rel.user_id ={$user_id}) n ON r_m.item_id=n.news_id
			WHERE
				n.rel_id IS NULL
					AND
				r_m.user_id={$user_id}
					AND 
				r_m.score > 3
		";
		$res=$this->db->query($sql)->row();
		return $res->num;		
	}

	public function get_news_info($news_id,$user_id = ''){
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
		$res=($this->db->query($sql)->row())?($this->db->query($sql)->row()->like_status):'0';
		return $res;
	}

	public function get_user_score_status($user_id,$news_id){
		$sql="
			SELECT
				rel.score
			FROM
				tbl_rel_user_like_news rel
			WHERE
				rel.user_id={$user_id}
					AND
				rel.news_id={$news_id}
		";
		$res=($this->db->query($sql)->row())?($this->db->query($sql)->row()->score):'-1';
		return $res;
	}

	public function insert_user_like_status($value_arr){
		if(!empty($value_arr)){
			$this->db->insert('tbl_rel_user_like_news',$value_arr);
		}
		return $this->db->insert_id();
	}

	public function update_user_like_status($value_arr,$where_arr){
		if(!empty($value_arr) && !empty($where_arr)){
			$this->db->update('tbl_rel_user_like_news',$value_arr,$where_arr);
		}
		return 1;
	}

	// 计算新闻总数
	public function get_total_news_count(){
		$sql="
			SELECT
				COUNT(*) as num
			FROM
				(SELECT
					news.news_id
				FROM
					(SELECT * FROM tbl_user WHERE `status` = 0) u 
						INNER JOIN
					tbl_rel_user_like_news rel ON rel.user_id=u.user_id
						INNER JOIN
					(SELECT * FROM tbl_news WHERE `status` = 0) news ON news.news_id=rel.news_id
				GROUP BY 
					news.news_id) a
		";
		return $this->db->query($sql)->row()->num;
	}

	public function get_martix_news_id_list(){
		$sql="
			SELECT
				news.news_id
			FROM
				(SELECT * FROM tbl_user WHERE `status` = 0) u 
					INNER JOIN
				tbl_rel_user_like_news rel ON rel.user_id=u.user_id
					INNER JOIN
				(SELECT * FROM tbl_news WHERE `status` = 0) news ON news.news_id=rel.news_id
			GROUP BY 
				news.news_id
			ORDER BY 
				news.news_id ASC
		";
		return $this->db->query($sql)->result();
	}

}

/* End of file Mdl_news.php */
/* Location: ./application/models/Mdl_news.php */