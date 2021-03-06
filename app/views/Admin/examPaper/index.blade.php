@extends('Admin.master_column')
@section('title')试卷管理@stop

@section('content')

  <div class="row">
      <div class="row text-right">
        {{ Form::open(array('role' => 'form', 'class' => 'form-inline', 'method' => 'get')) }}
          <div class="form-group" id="sort" style="padding-right:60px;">
              {{Form::select('sort1', array(), '', array('class' => 'sort1 form-control', 'data-value' => $query['sort1'] ))}}
              {{Form::select('sort2', array(), '', array('class' => 'sort2 form-control', 'data-value' => $query['sort2'] ))}}
              {{Form::select('sort3', array(), '', array('class' => 'sort3 form-control', 'data-value' => $query['sort3'] ))}}
              {{Form::select('sort4', array(), '', array('class' => 'sort4 form-control', 'data-value' => $query['sort4'] ))}}
              {{Form::select('sort5', array(), '', array('class' => 'sort5 form-control', 'data-value' => $query['sort5'] ))}}
          </div>
          <div class="clearfix"></div>

          <div class="form-group">
            {{ Form::label('inputName', '试卷名', array('class' => 'sr-only')) }}
            {{ Form::text('name', $query['name'], array('class' => 'form-control', 'id' => 'inputName', 'placeholder' => '试卷名')) }}
          </div>
          <div class="form-group">
            {{ Form::label('inputStatus', '状态', array('class' => 'sr-only')) }}
            {{ Form::select('status', $statusEnum, $query['status'], array('class' => 'form-control', 'id' => 'inputStatus')) }}
          </div>
          {{ Form::button('查找', array('class' => 'btn btn-info', 'type' =>'submit')) }}
        {{ Form::close() }}
      </div>

      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>试卷</th>
            <th>描述</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($lists as $list)
              <tr>
                <td>{{$list->id}}</td>
                <td><a href="/admin/examPaper/clist?id={{$list->id}}">{{$list->title}}</a></td>
                <td>{{$list->desc}}</td>
                <td>
                    @if ($list->status == 1)
                    <span class="label label-info">{{$statusEnum[$list->status]}}</span>
                    @elseif ($list['status'] == 0)
                    <span class="label label-warning">{{$statusEnum[$list->status]}}</span>
                    @else
                    <span class="label label-default">{{$statusEnum[$list->status]}}</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-xs">
                      <a class="btn btn-default btn-xs" href="{{url('/admin/examPaper/edit?id='. $list->id) }}"><i class="icon-edit"></i> 编辑</a>
                      <a class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" href="#"><span class="icon-caret-down"></span></a>
                      <ul class="dropdown-menu">
                          @if($list->status === 1)
                          <li class="disabled"><a href="#"><i class="icon-ok"></i> 上线</a></li>
                          <li><a href="#" class="btn_publish" data-toggle="modal" data-id="{{$list->id}}"  data-status="-1"><i class="icon-trash"></i> 下线</a></li>
                          @elseif($list->status === -1)
                          <li><a href="#" class="btn_publish" data-toggle="modal" data-id="{{$list->id}}"  data-status="1"><i class="icon-ok"></i> 上线</a></li>
                          <li class="disabled"><a href="#"><i class="icon-trash"></i> 下线</a></li>
                          @else
                          <li><a href="#" class="btn_publish" data-toggle="modal" data-id="{{$list->id}}" data-status="1"><i class="icon-ok"></i> 上线</a></li>
                          <li><a href="#" class="btn_publish" data-toggle="modal" data-id="{{$list->id}}" data-status="-1"><i class="icon-remove"></i> 下线</a></li>
                          @endif
                          <li class="divider"></li>
                          <li><a href="#" class="btn_delete" data-toggle="modal" data-id="{{$list->id}}" data-status="1"><i class="icon-trash"></i> 删除</a></li>
                      </ul>
                    </div>
                </td>
              </tr>
            @endforeach
        </tbody>
      </table>
  </div>
  <div class="row text-right">
      {{$lists->appends($query)->links()}}
  </div>

@stop

@section('js')
{{ HTML::script('/assets/jquery.cxselect.min.js') }}
<script type="text/javascript">

$(function(){

  //发布,下线
  $(".btn_publish").bind("click", function(){
      var id = $(this).data("id");
      var status = $(this).data("status");

      $.post("/admin/examPaper/editStatus", { "ajax": 1, "id": id, "status": status },
        function(data){
          if(data == 1)
          {
            location.reload();
            //alert("修改成功");
          }
          else
            alert("修改失败,请刷新页面重试");
      });

  });
  //删除
  $(".btn_delete").bind("click", function(){
      if (confirm("确认删除吗？"))
      {
          id = $(this).data('id');
          $.post("/admin/examPaper/del", {"ajax": 1, "id": id},

          function(data) {
              if(data == 1)
              {
                  location.reload();
                  alert("删除成功");
              }
              else
              {
                  alert("删除失败,请刷新页面重试");
              }
          },
          "json"
        )
        .fail(function(){
            console.log(data);
            alert("删除失败,请刷新页面重试");
        });
      }
  });

  // http://code.ciaoca.com/jquery/cxselect/
  $.cxSelect.defaults.url = '/admin/examSort.json';
  $('#sort').cxSelect({
      url:'/admin/examSort.json',
      firstTitle: '-请选择-分类-',
      selects: ['sort1', 'sort2', 'sort3', 'sort4', 'sort5'],
      nodata: 'none'
  });
});
</script>
@stop
