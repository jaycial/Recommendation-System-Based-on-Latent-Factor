<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	public function index(){
		return;
	}

	/*
	*从接口中获取新闻存放于数据库中
	*/
	public function get_news_data_from_api(){
		$this->load->database();
		$type_arr=array('war','travel','tech','edu','ent','money');
		$url = 'http://wangyi.butterfly.mopaasapp.com/news/api?page=1&limit=100&type=';
		foreach ($type_arr as $value) {
			$news_data_info=json_decode(file_get_contents($url.$value));
			if(isset($news_data_info->list)){
				foreach ($news_data_info->list as $data) {
					$detail=json_decode(file_get_contents('http://wangyi.butterfly.mopaasapp.com/detail/api?simpleId='.$data->id));
					$match=preg_match('/<p>.+?<\/p>/',$detail->content,$abstract);
					if($match){
						$value_arr=array('title' => $data->title,
										 'news_url' => $data->docurl,
										 'img_url' => $data->imgurl,
										 'type' => $data->channelname,
										 'source_time' => $data->time,
										 'create_time' => time(),
										 'status' => '0',
										 'source_id' => $data->id,
										 'content' => $detail->content,
										 'from' => $detail->from,
										 'abstract' => $abstract[0]);
						$this->db->insert('tbl_news',$value_arr);
					}
				}
			}
		}
		echo "OK";	
	}

}

/* End of file Common.php */
/* Location: ./application/controllers/Common.php */