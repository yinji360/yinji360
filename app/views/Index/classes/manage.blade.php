@extends('Index.master')
@section('title')班级管理 @stop
@extends('Index.column.columnHead')

@section('headjs')
<script src="/assets/layer/layer.min.js"></script>
@stop

@section('content')
<div class="container-column wrap">
  <div class="row">
    @include('Index.column.nav')
  <div class="wrap-right">
      <div class="cl tabtool">
      	<a style="color:#999999;display:none;" href="/classes?column_id={{$query['column_id']}}">
      		<span class="fsort-back"></span>&nbsp;&nbsp;返回
      	</a>
      	<span class="tab-bar"></span>
        <span class="tab-title-prev">
	        <a href="/classes?column_id={{$query['column_id']}}">我的班级</a>
	        <span>&nbsp;>&nbsp;</span>
	        <span style="color:#499528;">班级管理</span>
        </span>
      </div>	  
	  
      @if(!empty($classes))
      @foreach($classes as $k=>$v)
      <div class="cl fsort-line">
        <form action="/classes/manage/doEdit" method="post">
          <input type="hidden" name="id" value="{{$v->id}}" />
          <input type="hidden" name="column_id" value="{{$query['column_id']}}" />
          
          <div style="float:left;line-height:40px;">
          	<span class="fsort-num">{{$k+1}}</span>
            <input class="fsort-input" type="text" name="name" style="width:250px;" value="{{$v->name}}" maxlength="50" />
           	<span>（成员：@if($v) {{$v->students()->where('classmate.status', 1)->count()}} @else '0' @endif）</span>
          </div>

          <div style="float:right;line-height:40px;">
            <button type="button" class="manage-btn"
            	onclick="location.href='/classes/mates?class_id={{$v->id}}&column_id={{$query['column_id']}}'">成员管理</button>
            <button type="button" class="manage-btn"
				onclick="show({{$v->id}})">班级转让</button>
            <button type="button" class="manage-btn" style="margin-left:5px;"
            	onclick="delClasses({{$v->id}})">删&nbsp;除</button>
            <button type="submit" class="manage-btn" style="margin-left:5px;">保&nbsp;存</button>
          </div>
        </form>
      </div>
      @endforeach
      
      <div>
      	<a class="fsort-new" href="/classes/create?column_id={{$query['column_id']}}&tag=manage">+创建班级</a>
      </div>
      
	  <div style="text-align:center;margin-top:20px;">
	  	{{$classes->appends($query)->links()}}
	  </div>
	  @endif
  </div>
  <div class="clear"></div>
</div> <!-- /container -->
</div>

  <div class="panel-bg" style="left:415px;top:100px;display:none;" id="changeBox">
       <div class="panel-box">
            <div class="panel-tt">
                输入转让教师
                <a href="###" class="panel-close" onclick="closeBox()"></a>
            </div>
            <div class="panel-con">
                 <ul class="fiebox">
                     <li>
                         <span>教师姓名：</span>
                         <input type="text" name="name" class="texttype" id="teacher_name" value=""/>
                     </li>
                     <li>
                         <span>手机号码：</span>
                         <input type="text" name="name" class="texttype" id="teacher_phone" value=""/>
                         <p class="error" style="display:none;">输入手机号错误呀</p>
                     </li>
                     <li>
                         <div class="pd-type">
                             <button type="submit" class="btntype1" onclick="changeClass()">确 定</button>
							 <input type="hidden" id="class_id" value="0" />
                             <button type="submit" class="btntype2" onclick="closeBox()">取 消</button>
                         </div>
                     </li>
                 </ul>
            </div>
       </div>
  </div>
<input type="hidden" value="{{$query['column_id']}}" id="column_id" />
<script type="text/javascript">
function changeClass(){

	var class_id = $("#class_id").val();
	var column_id = $("#column_id").val();
	var teacher_name = $("#teacher_name").val();
	if(teacher_name.replace(/(^\s*)/g, "")==''){
		layer.alert('请填写老师姓名');
		return false;
	}
	var teacher_phone = $("#teacher_phone").val();
	var regtel = /^(1[3|4|5|8])\d{9}$/;
	if(!regtel.exec(teacher_phone)){	
		layer.alert('请填写正确的手机号');
		return false;
	}
	if(class_id==0) return false;
	
	$.ajax( {  
        type : "get",  
        url : "/classes/changeteacher", 
		data : "id="+class_id+"&column_id="+column_id+"&teacher_name="+teacher_name+"&teacher_phone="+teacher_phone,
        dataType:"json",  
        success : function(msg) {
			if(msg.status){
				$("#teacher_phone").attr('value','');
				$("#teacher_name").attr('value','');	
				closeBox();				
				layer.alert(msg.notice);
			}else{
				layer.alert(msg.notice);
			}
        }  
    });

	
}
function show(id){
	$("#class_id").attr('value',id);
	$("#changeBox").css('display','');
}
function closeBox(){
	$("#teacher_name").val('');
	$("#teacher_phone").val('');
	$("#changeBox").css('display','none');
}

function delClasses(id) {
	layer.confirm('您确定要删除吗？', function() {
    	location.href='/classes/manage/doDel?id=' + id + '&column_id={{$query['column_id']}}';
	});
}

</script>

@stop


