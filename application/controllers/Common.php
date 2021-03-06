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
		$url = 'http://wangyi.butterfly.mopaasapp.com/news/api?page=1&limit=50&type=';
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

	/*
		将数据库中的U-I数据存入本地供矩阵拆分使用
	*/
	public function load_u_i_to_local(){
		$this->load->database();
		$this->load->model('mdl_user');
		$this->load->model('mdl_news');
		$this->load->model('mdl_martix');
		// 获取User数量和Item数量
		$user_count=$this->mdl_user->get_total_user_count();
		$item_count=$this->mdl_news->get_total_news_count();
		// 获取user列表和itme列表
		$user_id_list=$this->mdl_user->get_martix_user_id_list();
		$item_id_list=$this->mdl_news->get_martix_news_id_list();
		// 构造U-I id列表
		$user_id_str='';
		foreach ($user_id_list as $value) {
			$user_id_str .= ($value->user_id . ',');
		}

		$item_id_str = '';

		foreach ($item_id_list as $value) {
			$item_id_str .= ($value->news_id . ',');
		}
		// 构造U-I数据矩阵
		$u_i_martix=$this->mdl_martix->get_u_i_martix();
		$item_id=-1;
		$martix_str='';
		$count=-1;
		foreach ($u_i_martix as $value) {	//循环数据库中的每个值
			if($item_id != $value->news_id){	//对新item的遍历
				// 判断上个item是否遍历全了user
				if(-1 != $count && $count < $user_count){	//没有遍历全，需补0
					while ($count++ < $user_count) {
						$martix_str .= '0,';	//拼接字符串用于存储本地
						$count++;		//记录有多少用户对此项目进行了评分
					}
					// 0补全后，开始对新的item遍历
					$count = 0;		//遍历用户数置0
					$martix_str .= ($value->score . ',');	//拼接字符串用于存储本地
					$count++;		//记录有多少用户对此项目进行了评分
				}else{	//遍历全了，正常遍历此次的item
					$count = 0;		//遍历用户数置0
					$martix_str .= ($value->score . ',');	//拼接字符串用于存储本地
					$count++;		//记录有多少用户对此项目进行了评分
				}
			}else{	//仍旧是对上一个item的遍历
				$martix_str .= ($value->score . ',');	//拼接字符串用于存储本地
				$count++;		//记录有多少用户对此项目进行了评分
			}
			$item_id = $value->news_id;

		}
		// foreach结束，判断最后一个项目是否被全部遍历
		while ($count++ < $user_count) {
			$martix_str .= '0,';	//拼接字符串用于存储本地
			$count++;		//记录有多少用户对此项目进行了评分
		}

		$res_str = $item_count . "," . $user_count . "\n" . $martix_str;
		// 将数据存于本地
		file_put_contents('martix_data/data.txt', $res_str);
		file_put_contents('martix_data/user_id.txt', $user_id_str);
		file_put_contents('martix_data/item_id.txt', $item_id_str);
		echo "ok";
	}

	/*
	将算法处理后的矩阵数据转存回数据库
	*/ 
	public function load_u_i_to_db(){
		$this->load->database();
		$result_martix=trim(file_get_contents('martix_data/data_result_list.txt'),',');
		$user_id_str=trim(file_get_contents('martix_data/user_id.txt'),',');
		$item_id_str=trim(file_get_contents('martix_data/item_id.txt'),',');

		$user_id_arr=explode(',',$user_id_str);
		$item_id_arr=explode(',',$item_id_str);
		$score_arr=explode(',',$result_martix);
		// 清空现有表
		$this->db->empty_table('tbl_result_martix');
		$i=0;
		foreach ($item_id_arr as $value) {
			foreach ($user_id_arr as $data) {
				$value_arr=array(
						'user_id'		=>	$data,
						'item_id'		=>	$value,
						'score'			=>	$score_arr[$i++],
						'update_time'	=>	time(),
					);
				$this->db->insert('tbl_result_martix',$value_arr);
			}
		}
		echo "OK";
		
	}
}

/* End of file Common.php */
/* Location: ./application/controllers/Common.php */