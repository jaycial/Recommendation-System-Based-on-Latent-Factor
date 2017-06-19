<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model(array('mdl_user','mdl_news'));
		$recommend=false;
		// 分页
		$data['per_page']=$per_page=10;
		$data['type']=$type=intval($this->input->get('type'))>0?intval($this->input->get('type')):'1';
		if(7 == $type){
			if(isset($this->session->uid)){
				$recommend=true;
			}else{
				$type=1;
			}
		}
		switch ($type) {
			case '1':
				$type='war';
				break;
			case '2':
				$type='travel';
				break;
			case '3':
				$type='tech';
				break;
			case '4':
				$type='edu';
				break;
			case '5':
				$type='ent';
				break;
			case '6':
				$type='money';
				break;
			
			default:
				$type='war';
				break;
		}
		$data['page']=$page=intval($this->input->get('page'))>0?intval($this->input->get('page')):'1';
		// 用户信息
		$data['user_info']='';
		if($recommend){
			$data['user_info']=$this->mdl_user->get_user_info($this->session->uid);
			$data['total']=$total=$this->mdl_news->get_news_count_recommend($this->session->uid);
			$data['total_page']=($total%$per_page==0)?($total/$per_page):(($total/$per_page)+1);
			$limit=($page-1)*$per_page;
			$data['news_info']=$this->mdl_news->get_news_list_recommend($limit,$per_page,$this->session->uid);
		}else{
			if(isset($this->session->uid)){
				$data['user_info']=$this->mdl_user->get_user_info($this->session->uid);
				$data['total']=$total=$this->mdl_news->get_news_count($type,$this->session->uid);
				$data['total_page']=($total%$per_page==0)?($total/$per_page):(($total/$per_page)+1);
				$limit=($page-1)*$per_page;
				$data['news_info']=$this->mdl_news->get_news_list($type,$limit,$per_page,$this->session->uid);
			}else{
				$data['total']=$total=$this->mdl_news->get_news_count($type);
				$data['total_page']=($total%$per_page==0)?($total/$per_page):(($total/$per_page)+1);
				$limit=($page-1)*$per_page;
				$data['news_info']=$this->mdl_news->get_news_list($type,$limit,$per_page);
			}
		}
		
		$this->load->view('home',$data);
	}

	public function detail(){
		$news_id=intval($this->input->get('id'));
		if($news_id>0){
			$this->load->model(array('mdl_news','mdl_user'));
			$this->load->helper('url');
			$this->load->library('session');

			// 用户信息
			$data['user_info']='';
			$data['like_status']='0';
			if(isset($this->session->uid)){
				$data['user_info']=$this->mdl_user->get_user_info($this->session->uid);
				$data['like_status']=$this->mdl_news->get_user_like_status($this->session->uid,$news_id);
			}
			$data['news_info']=$this->mdl_news->get_news_info($news_id);

			// 判断用户是否已登陆，若已登陆，则计分
			if(isset($this->session->uid)){
				$data['user_score']=$user_score=$this->mdl_news->get_user_score_status($this->session->uid,$news_id);
				if($user_score == -1){	//访问详情的评分优先性最低，只能进行insert
					$value_arr=array(
						'user_id' => $this->session->uid,
						'news_id' => $news_id,
						'create_time' => time(),
						'update_time' => time(),
						'score'	=>	'3',
					);
					$this->db->insert('tbl_rel_user_like_news',$value_arr);
				}
			}

			$this->load->view('detail', $data);
		}else{
			// 非法ID
			redirect('home/index');
		}
	}

	public function ajax_add_like(){
		$this->load->model('mdl_news');
		$this->load->helper('url');
		$news_id=intval($this->input->post('news_id'));
		$user_id=intval($this->input->post('user_id'));

		// 判断当前状态，决定执行inset还是update
		$like_status=$this->mdl_news->get_user_score_status($user_id,$news_id);
		if('-1'==$like_status){
			// 执行insert
			$value_arr=array(
					'user_id' => $user_id,
					'news_id' => $news_id,
					'like_status' => '1',
					'create_time' => time(),
					'update_time' => time(),
					'score'	=>	'5',
				);
			$affected_id=$this->mdl_news->insert_user_like_status($value_arr);
		}else{
			// 执行更新操作
			$value_arr=array(
					'like_status' => '1',
					'update_time' => time(),
					'score'	=>	'5',
				);
			$where_arr=array(
					'user_id' => $user_id,
					'news_id' => $news_id,
				);
			$affected_id=$this->mdl_news->update_user_like_status($value_arr,$where_arr);
		}
		if(isset($affected_id)){
			// 操作成功
			$str="
				<p style=\"margin-top: 90px\"><img id=\"like_pic\" src=\"".base_url('front/images/like.png')."\"><span id=\"like_span\" style=\"color:rgb(225,87,72);\">感兴趣</span><img id=\"dislike_pic\" src=\"".base_url('front/images/per_dislike.png')."\" style=\"margin-left: 20px\" onclick=\"dislike('".$news_id."','".$user_id."','".site_url()."')\" onmouseover=\"dislike_mouse_on('dislike_pic','".base_url()."')\" onmouseout=\"dislike_mouse_out('dislike_pic','".base_url()."')\"><span id=\"dislike_span\">无聊</span></p>
			";
			echo $str;
		}else{
			var_dump($value_arr);
		}
	}

	public function ajax_add_dislike(){
		$this->load->model('mdl_news');
		$this->load->helper('url');
		$news_id=intval($this->input->post('news_id'));
		$user_id=intval($this->input->post('user_id'));

		// 判断当前状态，决定执行inset还是update
		$like_status=$this->mdl_news->get_user_score_status($user_id,$news_id);
		if('-1'==$like_status){
			// 执行insert
			$value_arr=array(
					'user_id' => $user_id,
					'news_id' => $news_id,
					'like_status' => '-1',
					'create_time' => time(),
					'update_time' => time(),
					'score'	=>	'2',
				);
			$affected_id=$this->mdl_news->insert_user_like_status($value_arr);
		}else{
			// 执行更新操作
			$value_arr=array(
					'like_status' => '-1',
					'update_time' => time(),
					'score'	=>	'2',
				);
			$where_arr=array(
					'user_id' => $user_id,
					'news_id' => $news_id,
				);
			$affected_id=$this->mdl_news->update_user_like_status($value_arr,$where_arr);
		}
		if(isset($affected_id)){
			// 操作成功
			$str="
				<p style=\"margin-top: 90px\"><img id=\"like_pic\" src=\"".base_url('front/images/per_like.png')."\" onclick=\"like('".$news_id."','".$user_id."','".site_url()."')\" onmouseover=\"like_mouse_on('like_pic','".base_url()."')\" onmouseout=\"like_mouse_out('like_pic','".base_url()."')\"><span id=\"like_span\">感兴趣</span><img id=\"dislike_pic\" src=\"".base_url('front/images/dislike.png')."\" style=\"margin-left: 20px\"><span id=\"dislike_span\" style=\"color:rgb(225,87,72);\">无聊</span></p>
			";
			echo $str;
		}else{
			echo "数据库发生错误，请稍后重试";
		}
	}

	public function ajax_never_show(){
		$this->load->library('session');
		$this->load->model('mdl_news');
		$news_id=$this->input->post('news_id');
		$user_id=$this->input->post('user_id');
		if($this->session->uid != $user_id){
			return;
		}else{
			// 判断当前状态，决定执行inset还是update
			$do_update=$this->mdl_news->get_user_score_status($user_id,$news_id);
			if(-1 != $do_update){
				// 执行更新操作
				$value_arr=array(
						'update_time' => time(),
						'score'	=>	'1',
					);
				$where_arr=array(
						'user_id' => $user_id,
						'news_id' => $news_id,
					);
				echo  $this->db->update('tbl_rel_user_like_news',$value_arr,$where_arr);
			}else{
				// 执行insert
				$value_arr=array(
						'user_id' => $user_id,
						'news_id' => $news_id,
						'create_time' => time(),
						'update_time' => time(),
						'score'	=>	'1',
					);
				echo  $this->db->insert('tbl_rel_user_like_news',$value_arr);
			}
		}
	}


	public function ajax_open_page_score(){
		$this->load->library('session');
		$this->load->model('mdl_news');
		$news_id=$this->input->post('news_id');
		$user_id=$this->input->post('user_id');
		if($this->session->uid != $user_id){
			return;
		}else{
			$score=$this->mdl_news->get_user_score_status($user_id,$news_id);
			if(3 == $score){
				// 执行更新操作
				$value_arr=array(
						'update_time' => time(),
						'score'	=>	'4',
					);
				$where_arr=array(
						'user_id' => $user_id,
						'news_id' => $news_id,
					);
				echo  $this->db->update('tbl_rel_user_like_news',$value_arr,$where_arr);
			}
		}
	}


	public function ajax_check_email_unique(){
		$this->load->model('mdl_user');
		$email=$this->input->post('email');
		$is_unique=$this->mdl_user->check_email_unique($email);
		echo $is_unique;
	}

	public function ajax_check_username_unique(){
		$this->load->model('mdl_user');
		$username=$this->input->post('username');
		$is_unique=$this->mdl_user->check_username_unique($username);
		echo $is_unique;
	}

	public function login(){
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation','session'));
		if(isset($this->session->uid)){
        	redirect('home/index');
        }

        $email=$this->input->post('email');
        $password=$this->input->post('password');
        // 设定表单过滤规则
        $this->form_validation->set_rules('email', '注册邮箱' , 'trim|required|valid_email');
		$this->form_validation->set_rules('password', '密码' , 'trim|required');

		if ($this->form_validation->run() == true){
			$this->load->model('mdl_user');
			if($uid=$this->mdl_user->check_login($email,md5($password))){
				// 登录成功
				$session_arr=array('uid' => $uid,);
				$this->session->set_userdata($session_arr);
				redirect('home/index');
			}else{
				// 登录失败
				echo "登录失败";exit;
        		redirect('home/login');
			}
		}
		$this->load->view('login');
	}

	public function register(){
		// 加载一堆辅助函数和库
		$this->load->database();
		$this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation','session'));
        if(isset($this->session->uid)){
        	redirect('home/index');
        }
		// 获取参数
		$email=$this->input->post('email');
		$username=$this->input->post('username');
		$password=$this->input->post('password');
		$cpassword=$this->input->post('cpassword');
		// 设定表单过滤规则
		$this->form_validation->set_rules('email', '注册邮箱' , 'trim|required|valid_email');
		$this->form_validation->set_rules('username', '用户名' , 'trim|required|min_length[2]|max_length[10]');
		$this->form_validation->set_rules('password', '密码' , 'trim|required|min_length[4]|max_length[10]');
		$this->form_validation->set_rules('cpassword', '确认密码' , 'trim|required|matches[password]');
		if ($this->form_validation->run() == true){
			$reg_arr=array(
					'email' => $email,
					'username' => $username,
					'password' => md5($password),
					'create_time' => time(),
					'last_login' => time(),
					'status' => '0',
				);
			if($this->db->insert('tbl_user',$reg_arr)){
				$uid=$this->db->insert_id();
				$session_arr=array('uid' => $uid,);
				$this->session->set_userdata($session_arr);
				redirect('home/index');
			}else{
				echo "数据库插入错误！";
			}

		}

		$this->load->view('register');
	}

	public function logout(){
		$this->load->library('session');
		$this->load->helper('url');
		if(!isset($this->session->uid)){
        	redirect('home/index');
        }else{
        	$array_items = array('uid');
        	$this->session->unset_userdata($array_items);
        	redirect('home/index');
        }
	}

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */