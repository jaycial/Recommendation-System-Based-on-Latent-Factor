<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_martix extends CI_Model {


	public function get_u_i_martix(){
		$sql="
			SELECT
				u.user_id,news.news_id,IF(rel.score IS NOT NULL,rel.score,'0') score
			FROM
				(SELECT * FROM tbl_user WHERE `status` = 0) u 
					INNER JOIN
				tbl_rel_user_like_news rel ON rel.user_id=u.user_id
					INNER JOIN
				(SELECT * FROM tbl_news WHERE `status` = 0) news ON news.news_id=rel.news_id
			ORDER BY 
				news.news_id ASC,u.user_id ASC
		";
		return $this->db->query($sql)->result();
	}


}

/* End of file Mdl_user.php */
/* Location: ./application/models/Mdl_user.php */