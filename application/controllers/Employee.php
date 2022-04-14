<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Employee_model','em');
        $this->load->model('Core_model','cm');
    }
	public function index()
	{	

		$this->load->view('emp_list');
	}
	public function getDatatableAjax(){

		$args1 = array(
                    'sTable' => "employees",
                    //'where' => array('id'=>$id),                      
                    'countOrResult' => 'count', 
                  ); 

        //$total_all_rows = $this->cm->getTableList($args1);
        $total_all_rows = $this->cm->getRowCount('employees');
        $columns = array(
            0 => 'emp_no',
            1 => 'first_name',
            2 => 'last_name',
            3 => 'birth_date',
            4 => 'city_name',
            5 => 'city_state',
        );
        $where = "1=1 ";

        if(isset($_POST['search']['value']))
        {
            if(!empty($_POST['search']['value'])){
            $where = " ";
            $search_value = $_POST['search']['value'];
            $where .= "  first_name like '%".$search_value."%'";
            $where .= " OR last_name like '%".$search_value."%'";
            $where .= " OR city_name like '%".$search_value."%'";
            $where .= " OR city_state like '%".$search_value."%'";
            $where .= " OR birth_date like '%".date('Y-m-d',strtotime($search_value))."%'";          
            $args['where'] = $where;
        	}
        }

        //--------------------------------------

        if(isset( $_POST['search_name'])){
         $name = $_POST['search_name'];     
            if(!empty($name))
            {
                $where .= " AND first_name = '".$name."'";
            }
        }

        if(isset($_POST['daterange']) && !empty($_POST['daterange']))
        {

            $datarange = explode("-", $_POST['daterange']);
            //pre($datarange);
            $from = date('Y-m-d', strtotime($datarange[0]));
            $to = date('Y-m-d', strtotime($datarange[1]));
            $where  .=" AND DATE(birth_date) BETWEEN '".$from."' AND  '".$to."' ";
        }

        if(isset( $_POST['search_city'])){
            $city2 = $_POST['search_city'];
            if(!empty($city2))
            {           
                $city_list = "" . implode ( "', '", $city2 ) . "";
                $where .= " AND c.city_name in  ('".$city_list."')";            
            }
        }   

        $args['where'] = $where;

        //--------------------------------------

        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "emp_no asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = "employees e";
        //$args['fields'] = "e.*,c.name as city_name";
        $args['joinlist'] = array(
                                 array(
                                    'table' => "indian_cities c",
                                    'condition' => "c.city_id = e.city_id", 
                                    'type' =>'left'
                                    ));

        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
            
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->emp_no;
            $sub_array[] = $row->first_name;
            $sub_array[] = $row->last_name;
            $sub_array[] = date('d-M-Y',strtotime($row->birth_date));
            $sub_array[] = $row->city_name;
            $sub_array[] = $row->city_state;
            
            //$sub_array[] = $row['city'];
            //$sub_array[] = '<a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Edit</a>  <a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
	public function test(){
		die;
		//483275
		for($i=10001;$i <= 483275;$i++ )
		{
		
		$id = rand(1,1507);
		$data = array('city_id' => $id);
	    $this->db->where('emp_no', $i);
	    $this->db->update('employees', $data);
	    //echo $i." - Query Updated<br>";

		}
		echo $i." Records Updated";		

	}

	function city_list()
	{
		$this->load->view('city_list');
	}
	public function getCityListAjax(){	
        
        $total_all_rows = $this->cm->getRowCount('indian_cities');
        $columns = array(
            0 => 'city_id',
            1 => 'city_name',
            2 => 'city_state'            
        );
        $where = "1=1 ";

        if(isset($_POST['search']['value']))
        {
            if(!empty($_POST['search']['value'])){
            $where = " ";
            $search_value = $_POST['search']['value'];
            $where .= "  city_id like '%".$search_value."%'";
            $where .= " OR city_name like '%".$search_value."%'";        
            $where .= " OR city_state like '%".$search_value."%'";                    
            $args['where'] = $where;
        	}
        }
       
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "city_id asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = "indian_cities";       

        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
            
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->city_id;          
            $sub_array[] = $row->city_name;
            $sub_array[] = $row->city_state;           
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
    function salary_list()
	{
		$this->load->view('salary_list');
	}
    public function getSalaryListAjax(){	
        //echo $_POST['columns'][2]['search']['value'];die;
        //print_r($_POST);die;
        $total_all_rows = $this->cm->getRowCount('indian_cities');
        $columns = array(
            0 => 'city_id',
            1 => 'city_name',
            2 => 'city_state'
            //3 => 'to_date'            
        );
        $where = "1=1 ";

        if(isset($_POST['search']['value']))
        {
            if(!empty($_POST['search']['value'])){
            $where = " ";
            $search_value = $_POST['search']['value'];
            $where .= "  city_name like '%".$search_value."%'";
            $where .= " OR city_state like '%".$search_value."%'";        
            //$where .= " OR last_name like '%".$search_value."%'";                    
            $args['where'] = $where;
        	}
        }
       
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "city_id asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = "indian_cities";       
        //$args['where'] = " id < 500";
        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
            
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->city_id;          
            $sub_array[] = $row->city_name;
            $sub_array[] = $row->city_state;
            /*$sub_array[] = date('d-M-Y',strtotime($row->from_date));
            $sub_array[] = date('d-M-Y',strtotime($row->to_date)); */       
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
    function world_city_list()
	{
		$this->load->view('world_city_list');
	}
    public function getWorldCityListAjax(){	

        //$state = $_POST['columns'][2]['search']['value'];
        $city = $_POST['columns'][1]['search']['value'];
        $state = $_POST['columns'][2]['search']['value'];
        $city_id = $_POST['columns'][0]['search']['value'];

        $city = $_POST['columns'][0]['search']['value'];
        $state = $_POST['columns'][1]['search']['value'];
        $country = $_POST['columns'][2]['search']['value'];

        //echo "city = ".$city."    state -".$state;die;
        //print_r($_POST);die;
        $total_all_rows = $this->cm->getRowCount('cities');
        $columns = array(
            0 => 'ct.name',
            1 => 's.name',
            2 => 'c.name',
            3 => 'c.native',
            4 => 'c.region'            
        );       

        if(!empty(($_POST['search']['value'])))
        {    
            if(!empty($_POST['search']['value']))
            {
                $where = " ";
                $search_value = $_POST['search']['value'];
                $where .= "  ct.name like '%".$search_value."%'";
                $where .= " OR s.name like '%".$search_value."%'"; 
                $where .= " OR c.name like '%".$search_value."%'";                    
                $args['where'] = $where;
        	}
        }
        elseif(isset($state) || isset($city) || isset($country))
        {
            $where = " 1=1  ";
            if(!empty($state))
            {
                $where .= " AND s.name  like '%".$state."%'";                
            }
            if(!empty($city))
            {
                $where .= " AND ct.name  like '%".$city."%'";                
            }
            if(!empty($country))
            {
                $where .= " AND c.name  like '%".$country."%'";                   
            }
            $args['where'] = $where;
        }
       
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "ct.id asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = " cities ct";  
        $args['fields'] = "c.name as country_name,s.name as state_name,ct.name as city_name,c.native,c.region";      
        $args['joinlist'] = array(
                                    array(
                                        'table' => "states s",
                                        'condition' => "s.id = ct.state_id", 
                                        'type' =>'left'
                                        ),
                                        array(
                                            'table' => "countries c",
                                            'condition' => "c.id = s.country_id ", 
                                            'type' =>'left'
                                            ),                                 
                                );
        //$args['where'] = " id < 500";
        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
        //echo "<pre>";print_r($list);die;    
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->city_name;          
            $sub_array[] = $row->state_name;
            $sub_array[] = $row->country_name;
            $sub_array[] = $row->native;
            $sub_array[] = $row->region;
            /*$sub_array[] = date('d-M-Y',strtotime($row->from_date));
            $sub_array[] = date('d-M-Y',strtotime($row->to_date)); */       
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
    function city_filter_list()
	{
		$this->load->view('city_filter_list');
	}
    public function getCityFilterListAjax(){	

        //$state = $_POST['columns'][2]['search']['value'];
        $city = $_POST['columns'][1]['search']['value'];
        $state = $_POST['columns'][2]['search']['value'];
        $city_id = $_POST['columns'][0]['search']['value'];

        //echo "city = ".$city."    state -".$state;die;
        //print_r($_POST);die;
        $total_all_rows = $this->cm->getRowCount('indian_cities');
        $columns = array(
            0 => 'city_id',
            1 => 'city_name',
            2 => 'city_state'
            //3 => 'to_date'            
        );
       

        if(!empty(($_POST['search']['value'])))
        {    
            if(!empty($_POST['search']['value'])){
            $where = " ";
            $search_value = $_POST['search']['value'];
            $where .= "  city_name like '%".$search_value."%'";
            $where .= " OR city_state like '%".$search_value."%'";        
            //$where .= " OR last_name like '%".$search_value."%'";                    
            $args['where'] = $where;
        	}
        }
        elseif(isset($state) || isset($city) || isset($city_id)){
            $where = " 1=1  ";
            if(!empty($state)){
                $where .= " AND city_state  like '%".$state."%'";                
            }
            if(!empty($city)){
                $where .= " AND city_name  like '%".$city."%'";                
            }
            if(!empty($city_id)){
                $where .= " AND city_id = ".$city_id."";                
            }
            $args['where'] = $where;
        }
       
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "city_id asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = "indian_cities";       
        //$args['where'] = " id < 500";
        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
            
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->city_id;          
            $sub_array[] = $row->city_name;
            $sub_array[] = $row->city_state;
            /*$sub_array[] = date('d-M-Y',strtotime($row->from_date));
            $sub_array[] = date('d-M-Y',strtotime($row->to_date)); */       
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
    function city_form()
	{
		$this->load->view('city_form');
	}
    public function getCityFormListAjax(){	

        //$state = $_POST['columns'][2]['search']['value'];
        $city = $_POST['columns'][1]['search']['value'];
        $state = $_POST['columns'][2]['search']['value'];
        $city_id = $_POST['columns'][0]['search']['value'];

        //echo "city = ".$city."    state -".$state;die;
        //print_r($_POST);die;
        $total_all_rows = $this->cm->getRowCount('indian_cities');
        $columns = array(
            0 => 'city_id',
            1 => 'city_name',
            2 => 'city_state'
            //3 => 'to_date'            
        );
       

        if(!empty(($_POST['search']['value'])))
        {    
            if(!empty($_POST['search']['value'])){
            $where = " ";
            $search_value = $_POST['search']['value'];
            $where .= "  city_name like '%".$search_value."%'";
            $where .= " OR city_state like '%".$search_value."%'";        
            //$where .= " OR last_name like '%".$search_value."%'";                    
            $args['where'] = $where;
        	}
        }
        elseif(isset($state) || isset($city) || isset($city_id)){
            $where = " 1=1  ";
            if(!empty($state)){
                $where .= " AND city_state  like '%".$state."%'";                
            }
            if(!empty($city)){
                $where .= " AND city_name  like '%".$city."%'";                
            }
            if(!empty($city_id)){
                $where .= " AND city_id = ".$city_id."";                
            }
            $args['where'] = $where;
        }
       
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column'];
            $order = $_POST['order'][0]['dir'];            
            $args['sorting'] = " ".$columns[$column_name]." ".$order." ";
        }
        else
        {           
            $args['sorting'] = "city_id asc";
        }

        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $args['limit'] = $length;
            $args['offset'] = $start;
        }

        $args['sTable'] = "indian_cities";       
        //$args['where'] = " id < 500";
        //$args['showQuery'] = true;
        $list = $this->cm->getTableList($args);
            
        $count_rows = count($list);
        $data = array();
        foreach($list as $row)
        {
            $sub_array = array();
            $sub_array[] = $row->city_id;          
            $sub_array[] = $row->city_name;
            $sub_array[] = $row->city_state;
            /*$sub_array[] = date('d-M-Y',strtotime($row->from_date));
            $sub_array[] = date('d-M-Y',strtotime($row->to_date)); */       
            $data[] = $sub_array;
        }

        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>   $total_all_rows,
            'data'=>$data,
        );
        echo  json_encode($output);		
	}
}
