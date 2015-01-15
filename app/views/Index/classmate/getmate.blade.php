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
	        <span style="color:#499528;">成员管理</span>
        </span>
      </div>

      @if(!empty($student))
      @foreach($student as $k=>$v)
      <div class="cl fsort-line">
        <form action="/classes/manage/doEdit" method="post">
          <input type="hidden" name="id" value="{{$v->id}}" />
          <input type="hidden" name="column_id" value="{{$query['column_id']}}" />
          
          <div style="float:left;line-height:40px;">
          	<input type='checkbox' id='check[]'>
			<span class="fsort-num">{{$v['id']}}{{$v['name']}}</span>
			
           
          </div>

          <div style="float:right;line-height:40px;">
          
            <button type="button" class="manage-btn" style="margin-left:5px;"
            	onclick="delClasses({{$v->id}})">删&nbsp;除</button>
          </div>
        </form>
      </div>
      @endforeach
      
     
	  @endif
  </div>
  <div class="clear"></div>
</div> <!-- /container -->
</div>

@stop


