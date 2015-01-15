<?php

class StuController extends BaseController {

    public $statusEnum = array('' => '所有状态', '0' => '发布', '1' => '撤销发布');
    public $genderEnum = array('f' => '女', 'm' => '男');
    public $pageSize = 10;

    public function __construct()
    {	
		//echo "dd";die;
        $query = Input::only('column_id');

        if ((!isset($query['column_id']) || !is_numeric($query['column_id'])) && Request::path() != 'column/static') {
            echo ("<script>window.location.href='/column/static';</script>");
        }
    }
	public function mates(){
		echo "dd";die;
	}
	
	
    
}
