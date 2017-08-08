<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo "sss";
class Admin extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('goods_model');
	}
	public function main()
	{
      $this->load->view('admin/main.html');
	}
	public function top(){
		$this->load->view('admin/top.html');
	}
	public function left(){
		$this->load->view('admin/left.html');
	}
	public function footer(){
		$this->load->view('admin/footer.html');
	}
		public function index(){
		$this->load->view('admin/index.html');
	}
	public function add_wenzhang(){
		$this->load->helper('form');
		$this->load->view('admin/add_wenzhang.html');
	}
    public function check_info(){
    	if(isset($_FILES['pic']) && $_FILES['pic']['error']==0){
          $file_name = $_FILES['pic']['type'];
          $name=$_FILES['pic']['name'];
          $newname =FCPATH.'upload/';
        if(!is_dir($newname)){
			    mkdir($newname, 0777);
			}
          $filename= $_FILES['pic']['tmp_name'];
        if( strtolower($file_name)=='image/jpeg' || strtolower($file_name)=='image/png' || strtolower($file_name)=='image/jpg') {
           if( move_uploaded_file($filename,$newname.$name) ){  
                   $data['goods_pic']=$newname.$name;
                }
              } 
    	}
		$this->load->library('form_validation');
		// $this->form_validation->set_rules('title','文章标题','required|min_length[5]');
		// $this->form_validation->set_rules('aa','类型','required|integer');
		// $this->form_validation->set_rules('info','摘要','max_length[10]');
		//封装成了一个数组  在application/config/form_validation.php中  自己创建该php 命名一定要是指定的
		$status=$this->form_validation->run('article');
		if($status){
          $this->load->model('goods_model');
          $data=array(
             'user_name'=>$_POST['title']
          	);
          $this->goods_model->add($data);
          go('admin/add_wenzhang',1,'添加成功');

		}else{
			$this->load->helper('form');
		    $this->load->view('admin/add_wenzhang.html');
		}
	}
	public function show_video(){
    $arr=_gc('zxh');
    print_r($arr);
		error_reporting(0);
		$offent=isset($_GET['page']) ? $_GET['page'] :0;
		if($offent==0 || $offent == ''){
          $offent=1;
		}
        $offent=($offent-1)*20;
		$keywords=isset($_GET['keywords']) ? $_GET['keywords'] : '';
		$limit = 20;
    	$adminlist = $this->goods_model->getListByWhere('*', array('proName !=' => ' '), 'id desc',$offent, $limit,$keywords);
    	$totAdmin = $this->goods_model->getTotalByWhere(array('proName !=' => ' '));
        $page=$this->dvPage($_GET['page'], $totAdmin, $limit, "/ci/index.php/admin/show_video?keywords={$keywords}&page=","/ci/index.php/admin/show_video?keywords={$keywords}&page=",'');
        $this->assign('page',$page['code']);
        $this->assign('list',$adminlist);
    	//$data['pages'] = $this->genPageLinkNav($totAdmin,  $base_url, 3, $limit );
		$this->display('admin/show_vieo.html');
	}
    public function edit(){
    	$id=$this->uri->segment(3);
    	if($id){
          $info=$this->goods_model->get_video_one($id);
          print_r($info);
    	}
    }
   public function del(){
    $id=$this->uri->segment(3);
    if($id){
      $status=$this->goods_model->delvideo($id);
      if($status){
        go('admin/show_video',1,'删除成功');
      }
    }     
   }
	 public function dvPage($curPage, $total, $pageSize, $url,$url1, $html){
        $arr = array();
        $arr['total'] = $total;
        $arr['curPage'] = $curPage;
        $arr['maxPage'] = ceil($total/$pageSize);
        $arr['upPage'] = $curPage - 1>0?$curPage-1:1;
        $arr['nextPage'] = $curPage+1>$arr['maxPage']?$arr['maxPage']:$curPage+1;
        $arr['startPage'] = $curPage-1>0?$curPage-1:1;
        $arr['endPage'] = $arr['startPage']+2>$arr['maxPage']?$arr['maxPage']:$arr['startPage']+2;
        $arr['code'] = '<a href="'.$url1.'">首页</a><a href="'.$url.$arr['nextPage'].$html.'">下页</a>';
        for ($i=$arr['startPage']; $i<=$arr['endPage']; $i++)
        {
          if($i!=$curPage)$arr['code'] .= '<a href="'.$url.$i.$html.'">'.$i.'</a>';
          else $arr['code'] .= '<a href="'.$url.$i.$html.'" >'.$i.'</a>';
        }
        $arr['code'] .= '<a href="'.$url.$arr['upPage'].$html.'" >上页</a><a href="'.$url.''.$arr['maxPage'].'">尾页</a>';
        return $arr;
}
// 	 public function genPageLinkNav($total_rows = 0, $base_url = '', $uri_segment = 3, $page_num = 10, $num_links = 5)
// {
// 	$this->load->library('pagination');
// 	$pg['per_page'] = $page_num;
// 	$pg['base_url'] = $base_url ;
// 	$pg['total_rows'] = $total_rows;

// 	$pg['uri_segment'] = $uri_segment;
// 	$pg['num_links'] = $num_links;
// 	$pg['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
// 	$pg['full_tag_close'] = '</ul></div>';
// 	$pg['first_link'] = 'first';
// 	$pg['first_tag_open'] = '<li>';
// 	$pg['first_tag_close'] = '</li>';
// 	$pg['last_link'] = 'last';
// 	$pg['last_tag_open'] =  '<li>';
// 	$pg['last_tag_close'] = '</li>';
// 	$pg['next_link'] = ' ';
// 	$pg['next_tag_open'] =  '<li>';
// 	$pg['next_tag_close'] = '</li>';
// 	$pg['prev_link'] = ' ';
// 	$pg['prev_tag_open'] =  '<li>';
// 	$pg['prev_tag_close'] = '</li>';
// 	$pg['cur_tag_open'] = '<li class="active"><a href="#">';
// 	$pg['cur_tag_close'] = '</a></li>';
// 	$pg['num_tag_open'] =  '<li>';
// 	$pg['num_tag_close'] = '</li>';
// 	$this->pagination->initialize($pg);
// 	return $this->pagination->create_links();
// }
}
