<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index(){
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model(array('mdl_user','mdl_news'));
		// 用户信息
		$data['user_info']='';
		if(isset($this->session->uid)){
			$data['user_info']=$this->mdl_user->get_user_info($this->session->uid);
		}
		// 分页
		$data['per_page']=$per_page=10;
		$data['type']=$type=intval($this->input->get('type'))>0?intval($this->input->get('type')):'1';
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
		$data['total']=$total=$this->mdl_news->get_news_count($type);
		$data['total_page']=($total%$per_page==0)?($total/$per_page):(($total/$per_page)+1);
		$limit=($page-1)*$per_page;
		
		$data['news_info']=$this->mdl_news->get_news_list($type,$limit,$per_page);

		$this->load->view('home',$data);
	}

	public function detail(){
		$news_id=intval($this->input->get('id'));
		if($news_id>0){
			$this->load->model('mdl_news');
			$this->load->helper('url');

			// 用户信息
			$data['user_info']='';
			if(isset($this->session->uid)){
				$data['user_info']=$this->mdl_user->get_user_info($this->session->uid);
			}
			$data['news_info']=$this->mdl_news->get_news_info($news_id);

			$this->load->view('detail', $data);
		}else{
			// 非法ID
			redirect('home/index');
		}
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
		$this->form_validation->set_rules('username', '用户名' , 'trim|required|min_length[4]|max_length[10]');
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