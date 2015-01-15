@extends('Index.master')
@section('title')我的班级@stop
@extends('Index.column.columnHead')

@section('content')
<div class="container-column wrap">
  <div class="row">
  @include('Index.column.nav')

<div class="wrap-right">
      <div class="tabtool">
        <span class="tab-bar"></span>
		<span class="tab-title-prev"><a href="/classes?column_id={{$query['column_id']}}">我的班级</a><span>&nbsp;>&nbsp;</span>
        <span class="tab-title-prev"><a href="/classes/manage?column_id={{$query['column_id']}}">管理班级</a><span>&nbsp;>&nbsp;</span>
		<span class="tab-title">成员管理</span>

        <span class="tab-btn">
          <a href="/classes/create?column_id={{$query['column_id']}}" class="tabtool-btn">创建班级</a>
          <a href="/classes/manage?column_id={{$query['column_id']}}" class="tabtool-btn">管理班级</a>
        </span>
      </div>
      <div class="clear"></div>
	  
     <!-- cygg s -->
	 <div class="Conbox">
	      <div class="fav-list">
		       <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-2">
					
					  <tr>
						<td width="5%" class="td1">&nbsp;</td>
						<td width="10%" class="td1">序号</td>
						<td width="58%" class="td1 t-a">姓名</td>
						<td width="27%" class="td1">操作</td>
					  </tr>
					@if(!empty($student))
					@foreach($student as $k=>$v)
					  <tr  id="classmate_{{$v['mate_id']}}">
						<td><input type="checkbox" value="5" name="fid" data-id="{{$v['mate_id']}}"></td>
						<td class="td2">{{$k+1}}</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="{{Attachments::getAvatar($v['id'])}}" alt="" ></a>
							 <span>{{$v['name']}}</span>
						</td>
						<td><a href="###" onclick="delete_classmate({{$v['mate_id']}});" class="tyadel">删除</a></td>
					  </tr>
					@endforeach
					@endif
			      </table>

				  <div style="text-align:center;">
					  {{$classmate->appends($query)->links()}}
			
				  </div>

					<div class="cl">
						<div style="float:left;font-size:13px;">
							<input name="all" id="check-all" type="checkbox" style="margin-left:10px;vertical-align:middle;" onclick="checkAll()">
							<label for="check-all" style="vertical-align:middle;">全选</label>
							
							<input id="invert-check" type="checkbox" name="invert" style="margin-left:10px;vertical-align:middle;" onclick="invertCheck()">
							<label for="invert-check" style="vertical-align:middle;">反选</label>
							
							<span style="margin-left:10px;vertical-align:middle;cursor:pointer;" onclick="deleteCheck()">批量删除</span>
						</div>
				
							<div style="float:left;margin-left:20px;">
					<select name="msort" id="class_id" class="vm" style="font-size:12px;">
										<option value="0" title="选择班级">
							选择班级
						</option>
						@if(!empty($classes))
						@foreach($classes as $k=>$v)
						@if($v['id']!=$query['class_id']) 
										<option value="{{$v['id']}}" title="{{$v['name']}}">
							{{$v['name']}}
						</option>
						@endif
						@endforeach
						@endif
									</select>
					<a class="btn01" href="javascript:void(0);" onclick="movecheck()">班级转移</a>
				  </div>
			   </div>
		  </div>
		  
		  
	 </div>
	 <!-- cygg s -->
      
  </div> <!-- /container -->
<div class="clear"></div>
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/layer/layer.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
  delete_classmate = function(id){
    layer.confirm('您确定要删除吗？', function(){
      $.ajax({
        url:'/classmate/'+id,
        // async:false,
        type:'delete',
      })
      .fail(function(){
        layer.msg('删除失败', 2, 1);
      })
      .success(function(){
        layer.closeAll();
        $('#classmate_'+id).remove();
      });
    });
  };
});

$("#check-all").click(function(){
				if(this.checked){
					$("#invert-check").attr('checked',false);
					$("input[name='fid']").each(function(){this.checked=true;});
				}//else{
					//$("input[name='fid']").each(function(){this.checked=false;});
				//}
			});

$("#invert-check").click(function(){
				if(this.checked){
					$("#check-all").attr('checked',false);
					$("input[name='fid']").each(function(){this.checked=false;});
				}
			});

function deleteCheck(){
	
		var ids = '';
		$("input[name='fid']").each(function(){
			if(this.checked){
				ids += ','+$(this).attr('data-id');
			}
		});
		if(ids!=''){
			layer.confirm('您确定要删除吗？', function(){
			$.ajax( {  
				type : "post",  
				url : "/classmate/postDelete", 
				data : "ids="+ids,
				dataType:"json",  
				success : function(msg) {
					if(msg.status){
						$("input[name='fid']").each(function(){
							if(this.checked){
								$('#classmate_'+$(this).attr('data-id')).remove();
							}
						});
						layer.closeAll();
					}else{
						layer.msg('删除失败', 2, 1);
					}
				}  
			});
			})
		}else{
				layer.alert("请选择要删除的成员");
		}
    
}

function movecheck(){
	var class_id = $("#class_id").val();
	
	if(class_id==0){
		layer.alert('请选择要转入的班级');
		return false;
	}
		var ids = '';
		$("input[name='fid']").each(function(){
			if(this.checked){
				ids += ','+$(this).attr('data-id');
			}
		});
		if(ids!=''){
			$.ajax( {  
				type : "post",  
				url : "/classmate/postUpdate", 
				data : "ids="+ids+"&class_id="+class_id,
				dataType:"json",  
				success : function(msg) {
					if(msg.status){
						$("input[name='fid']").each(function(){
							if(this.checked){
								$('#classmate_'+$(this).attr('data-id')).remove();;
							}
						});
						layer.closeAll();
					}else{
						layer.msg('转移失败', 2, 1);
					}
				}  
			});
		}else{
				layer.alert("请选择要转移的成员");
		}

		
		
}
</script>
@stop



