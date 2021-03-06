@extends('Index.master')
@section('title')我的消息 @stop

@extends('Index.column.columnHead')

@section('content')
<div class="container-column wrap">
  <div class="row">
    @include('Index.column.nav')
  <div class="wrap-right">
      <div class="tabtool">
          <a href="/message?column_id={{$query['column_id']}}" style="color:#499626;"><返回</a>
          查看消息
          <div class="clear"></div>
      </div>
      <div class="clear"></div>

      <div class="classes-list">
        <div>
            @if(!empty($messages))
            <div style="margin: 10px 0px; font-weight: 700; border-bottom: 1px dashed #999;">共有{{$messages->count()}}条对话记录:</div>
              {{ Form::open(array('url' => '/message?column_id='.$query['column_id'], 'method' => 'post')) }}
            <div style="border-bottom: 1px dashed #cdcdcd">
              <div style="float: left; margin: 0px 0px 8px;">
                <img src="{{Attachments::getAvatar(Session::get('uid'))}}" width="48" height="48" style="border:1px solid #f2f2f2;padding:2px;"/>
              </div>
              <div style="padding: 0px 0px 0px 60px; margin: 8px 0px;">
                <div style="margin">
                  {{ Form::textarea('content', '', array('class' => '', 'id' => 'inputContent', 'style' => 'width:100%', 'rows' => 4)) }}
                </div>
                <div>
                  {{ Form::hidden('receiver_id', ($message->sender_id == Session::get('uid') ? $message->receiver_id : $message->sender_id)) }}
                  {{ Form::hidden('dialog', 1) }}
                  {{ Form::submit('发送', array('class' => '')) }}
                  {{ HTML::ul($errors->all()) }}
                </div>
              </div>
            </div>
              @foreach($messages as $k => $v)
              <div style="border-bottom: 1px dashed #cdcdcd" id="message_{{$v->id}}">
                <div style="float: left; margin: 0px 0px 8px;">
                  <img src="{{Attachments::getAvatar($v->sender_id)}}" width="48" height="48" style="border:1px solid #f2f2f2;padding:2px;"/>
                </div>
                <div style="padding: 0px 0px 0px 60px; margin: 8px 0px;" class="msg-box" data-id="{{$v->id}}">
                  <div style="color: rgb(73, 149, 40); font-weight: 700;">
                    @if ($v->sender_id == Session::get('uid'))
                    您说:
                    @else
                    {{$v->sender->name}}说:
                    @endif
                  </div>
                  <div>
                        {{$v->content}}
                  </div>
                  <div style="color:#999" class="msg-date">
                        {{$v->created_at}}
                        <span style="display:none;float:right;" class="msg-delete"><a href="javascript:;" onClick="delete_message('{{$v->id}}');">删除</a></span>
                  </div>
                </div>
              </div>
          <div class="clear"></div>
              @endforeach

            @endif
        </div>


          <div class="clear"></div>
      </div>
  </div>
  <div class="clear"></div>
</div> <!-- /container -->
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/layer/layer.min.js"></script>
<script type="text/javascript">
$(function(){
  $(".msg-box").hover(function(){
    $(this).children(".msg-date").children(".msg-delete").css('display','block');
  }, function(){
    $(this).children(".msg-date").children(".msg-delete").css('display','none');
  });

  delete_message = function(id){
    layer.confirm('您确定要删除吗？', function(){
      $.ajax({
      url:'/message/'+id+"?column_id={{$query['column_id']}}",
        // async:false,
        type:'delete',
      })
      .fail(function(){
        layer.msg('删除失败,请刷新重试', 2, 1);
      })
      .success(function(){
        $('#message_'+id).remove();
        layer.msg('删除成功', 1, 1);
      });
    });
  };

});
</script>
@stop


