<?php

class StudentsController extends BaseController {

    public $statusEnum = array('' => '所有状态', '0' => '发布', '1' => '撤销发布');
    public $genderEnum = array('f' => '女', 'm' => '男');
    public $pageSize = 10;

    public function __construct()
    {	
        $query = Input::only('column_id');

        if ((!isset($query['column_id']) || !is_numeric($query['column_id'])) && Request::path() != 'column/static') {
            echo ("<script>window.location.href='/column/static';</script>");
        }
    }

	/*
	 * 成员管理
	 * 参数  班级号、栏目号
	 * 返回班级下所有成员
	 */
	 public function getmate(){
		$query = Input::all();				
		$classmate = array();
		$classmate = Classmate::whereClass_id($query['class_id'])->where('classmate.status', 1)->orderBy('user_id','asc')->paginate($this->pageSize);
		//$classes = Classes::whereTeacherid(12)->orderBy('id','desc')->paginate($this->pageSize);
        $classmate_num = count($classmate);
        $columns = Column::find($query['column_id'])->child()->whereStatus(1)->orderBy('ordern', 'ASC')->get();
        $columnHead = Column::whereId($query['column_id'])->first();
		foreach ($classmate as $key => $value) {
            $student = Student::whereId($value->user_id)->get();	
        }
        return $this->indexView('classmate.getmate',
                compact('columns', 'columnHead', 'query', 'classmate', 'classmate_num','student'));
	 }
    
}
