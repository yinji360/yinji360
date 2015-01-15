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
        <span class="tab-title">我的班级</span> <i class="fc-499528">&gt;</i>
		<span class="tab-title">管理班级</span> <i class="fc-499528">&gt;</i>
		<span class="tab-title">成员管理</span>

        <span class="tab-btn">
          <a href="/classes/create?column_id=4" class="tabtool-btn">创建班级</a>
          <a href="/classes/manage?column_id=4" class="tabtool-btn">管理班级</a>
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
					  @if ($students->count() > 0)
					  @foreach ($students as $list)
					  <tr>
						<td><input type="checkbox" value="1" name="fid"></td>
						<td class="td2">1</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
					  @endforeach
					  @endif
					  <tr>
						<td><input type="checkbox" value="2" name="fid"></td>
						<td class="td2">2</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
					 <tr>
						<td><input type="checkbox" value="3" name="fid"></td>
						<td class="td2">3</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
					  <tr>
						<td><input type="checkbox" value="4" name="fid"></td>
						<td class="td2">4</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
					  <tr>
						<td><input type="checkbox" value="5" name="fid"></td>
						<td class="td2">5</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
					  <tr>
						<td><input type="checkbox" value="6" name="fid"></td>
						<td class="td2">6</td>
						<td class="namebox">
						     <a title="" href="#" class="pic"><img src="../images/pt01.jpg" alt="" ></a>
							 <span>杨幂</span>
						</td>
						<td><a href="/favorite/del?column_id=3&amp;qid=20" class="tyadel">删除</a></td>
					  </tr>
			      </table>
				  <div style="text-align:center;">
					  <ul class="pagination">
						<li class="disabled"><span>«</span></li><li class="active"><span>1</span></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=2">2</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=3">3</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=4">4</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=5">5</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=6">6</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=7">7</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=8">8</a></li><li class="disabled"><span>...</span></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=18">18</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=19">19</a></li><li><a href="http://cs.yinji360.com/failTopic?column_id=3&amp;page=2" rel="next">»</a></li>	</ul>
			
				  </div>
					<div class="cl">
						<div style="float:left;font-size:13px;">
							<input id="check-all" type="checkbox" style="margin-left:10px;vertical-align:middle;" onclick="checkAll(event)">
							<label for="check-all" style="vertical-align:middle;">全选</label>
							
							<input id="invert-check" type="checkbox" style="margin-left:10px;vertical-align:middle;" onclick="invertCheck()">
							<label for="invert-check" style="vertical-align:middle;">反选</label>
							
							<span style="margin-left:10px;vertical-align:middle;cursor:pointer;" onclick="deleteCheck()">批量删除</span>
						</div>
				
							<div style="float:left;margin-left:20px;">
					<select name="msort" class="vm" style="font-size:12px;">
										<option value="18" title="123">
							选择班级
						</option>
										<option value="19" title="222">
							222
						</option>
										<option value="20" title="333">
							333
						</option>
										<option value="21" title="4444">
							4444
						</option>
										<option value="22" title="555">
							555
						</option>
									</select>
					<a class="btn01" href="javascript:void(0);" onclick="moveCheck()">班级转移</a>
				  </div>
			   </div>
		  </div>
		  
		  
	 </div>
	 <!-- cygg s -->
      
  </div>> <!-- /container -->
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
</script>
@stop



