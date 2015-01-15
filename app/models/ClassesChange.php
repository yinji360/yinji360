<?php

class ClassesChange extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'class_change_log';


    public function addData($data = array())
    {
       return  $this->insert($data);
    }
	
    public function getChangeInfo($ouid=0,$class_id=0)
    {
		return $this->where('original_uid',$ouid)->where('class_id',$class_id)->where('status',0)->first();

    }
	
	public function getChangeById($id){
	
		return $this->where('id',$id)->where('status',0)->first();
	
	}
	
	public function updateChange($id=0,$data=array()){
	
		if(!$id) return false;
		return $this->where('id', $id)->update($data);	
		
	}
	
	public function getlist($uid){
		
		$sql = "select c.*,t.name,t.tel,cla.name class_name from class_change_log c left join users t on c.original_uid=t.id left join class cla on c.class_id=cla.id where c.receive_uid={$uid} and c.status=0";
		return DB::select($sql);

	}

}
