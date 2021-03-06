<?php

class TopicController extends BaseController {

	public $flag = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D');

	public function index()
	{
		$id = Input::get('id');
		$column = Input::get('column');
		$exam = Input::get('exam');
		$vetting = Input::get('vetting');  // 如果是后台审核显示
		$uniqid = Input::get('uniqid');
		$from = Input::get('from'); // 返回页面url
		$fromColumn = Input::get('fromColumn');

		// 模拟真实考试环境
		$isReal = is_numeric(Input::get('real')) ? Input::get('real') : 0;

		// 题目数据保存
		$qinfo = array();
		$qlist = array();

		// 有唯一码的情况
		if(!empty($uniqid))
		{
			$qinfo = Session::get('qinfo');
			if($qinfo['uniqid'] == $uniqid)
			{
				$column = $qinfo['column_id'];
				$exam = !empty($qinfo['exam_id']) ? $qinfo['exam_id'] : 0;
				$qlist = $qinfo['list'];
			}
			else
				return $this->indexPrompt("", "没有题组信息，请返回重试", $url = "/", false);
		}
		// 如果是试卷
		elseif(is_numeric($exam))
		{
			$columnInfo = Column::find($column);
			$ep = new ExamPaper();

			// 获取大题列表
			$clist = $ep->getClist($exam);

			if( empty($clist) )
				return $this->indexPrompt("", "没有这个试卷信息", $url = "/", false);

			$exam_question = array(); // 记录试卷大题id和题目对应

			foreach($clist as $key => $v) 
			{
				$questions = array();
				// 如果是模拟试卷，则取指定的随机题数
				if($columnInfo->type == 6)
				{
					if($v->rnum > 0)
						$questions = $ep->getRandQuestions($v->id, $v->rnum);
				}
				else
					$questions = $ep->getQuestions($v->id);

				if(!empty($questions))
				{
					foreach ($questions as $key => $q) {
						$qlist[] = $q->question_id;
						$exam_question[$q->question_id] = $q->exam_id;
					}
				}
			}

			//print_r(DB::getQueryLog());

			// 题目数据保存
			$qinfo['exam_id'] = $exam;  // 试卷id号
			$qinfo['column_id'] = $column; // 科目分类id号
			$qinfo['uniqid'] = uniqid();  
			$qinfo['list'] = $qlist;  // 题目列表
			$qinfo['answers'] = array();  // 记录用户每题的答案
			$qinfo['trues'] = array();  // 记录答题对错
			$qinfo['exam_question'] = $exam_question; // 记录试卷大题id和题目对应

			Session::put('qinfo', $qinfo);
			Session::save();
		}
		// 有科目id,生成题目列表
		elseif( is_numeric($column) )
		{
			$columnInfo = Column::find($column)->toArray();
			if(!$columnInfo)
				return $this->indexPrompt("", "没有这个科目信息", $url = "/", false);

			$cqr = new ColumnQuestionRelation();

			// 如果是分类是题目类型，则随机取40道题
			if($columnInfo['type'] == 1)
				$list = $cqr->getRandList($column, 40);
			else
				$list = $cqr->getList($column);

			foreach ($list as $v) {
				$qlist[] = $v['question_id'];
			}

			if( !$qlist )
				return $this->indexPrompt("", "科目下没有题目信息", $url = "/", false);

			// 题目数据保存
			$qinfo['column_id'] = $column;
			$qinfo['uniqid'] = uniqid();
			$qinfo['list'] = $qlist;  // 题目列表
			$qinfo['answers'] = array();  // 记录用户每题的答案
			$qinfo['trues'] = array();  // 记录答题对错

			Session::put('qinfo', $qinfo);
			Session::save();
		}

		
		// 后台审核显示没有列表
		if(!empty($vetting))
		{
			$qlist = array();
		}

		// 题目id不对则设为第一题, 
		if(!empty($qlist) && (!is_numeric($id) || !in_array($id, $qlist)) )
		{
			$id =  $qlist[0];
		}


		$topic = new Topic();
		$info = $topic->get($id);

		if(!$info)
		{
			return $this->indexPrompt("", "没有这道题目信息", $url = "/", false);
		}

		// 不是管理员
		if($info['q']['status'] != 1 && Session::get('utype') != -1)
		{
			return $this->indexPrompt("", "题目没有通过审核", $url = "/", false);
		}

		$info['flag'] = $this->flag;

		if(!empty($info['a']))
		{
			// 随机答案顺序
			shuffle($info['a']);
		}

		$info['qlist'] = $qlist;
		$info['column'] = $column;
		$info['exam'] = $exam;

		// 设置返回地址
		if( !empty($from) )
			$info['backurl'] = $from;

		// 获取标题
		$info['headTitle'] = '';
		if( !empty($exam) )
		{
			$epinfo = ExamPaper::find($exam);
			$info['headTitle'] = $epinfo->title;

			// 生成返回链接
			$cn = new Column();
			$path = $cn->getPath($column);
			$cnum = count($path);
			if($cnum > 1)
				$info['backurl'] = "/column?id={$path[$cnum -2]['id']}&column_id={$path[$cnum -1]['id']}";

			// 分类头显示使用
            $info['columnHead'] = $path[$cnum -1];
		}
		elseif( !empty($column) )
		{
			$cninfo = Column::find($column);
			$info['headTitle'] = $cninfo->name;

			// 生成返回链接
			$cn = new Column();
			$path = $cn->getPath($column);
			$cnum = count($path);
			if($cnum > 2)
				$info['backurl'] = "/column?id={$path[$cnum -2]['id']}&column_id={$path[$cnum -1]['id']}";
			else if($cnum > 1)
				$info['backurl'] = "/column?id={$path[$cnum -1]['id']}&column_id={$path[0]['id']}";

			// 分类头显示使用
			$info['columnHead'] = $path[$cnum -1];
		}
		elseif( !empty($fromColumn) )
		{
			$cninfo = Column::find($fromColumn);
			$info['headTitle'] = $cninfo->name;

			// 生成返回链接
			$cn = new Column();
			$path = $cn->getPath($fromColumn);
			$cnum = count($path);
			if($cnum > 1)
				$info['backurl'] = "/column?id={$path[$cnum -2]['id']}&column_id={$path[$cnum -1]['id']}";

			// 分类头显示使用
			$info['columnHead'] = $path[$cnum -1];
		}

		// 来自错题记录
		if(Input::get('from') == 'fail')
		{
			$info['headTitle'] = '错题记录';
			$info['backurl'] = '/failTopic?column_id=' . Input::get('column_id');
			$info['from'] = 'fail';

			$cn = new Column();
			$path = $cn->getPath(Input::get('column_id'));
			$cnum = count($path);
			$info['columnHead'] = $path[$cnum -1];

			$info['column'] = Input::get('column_id');
		}
		// 来自收藏夹
		else if(Input::get('from') == 'favorite')
		{
			$info['headTitle'] = '我的收藏';
			$info['backurl'] = '/favorite?column_id=' . Input::get('column_id');
			$info['from'] = 'favorite';

			$cn = new Column();
			$path = $cn->getPath(Input::get('column_id'));
			$cnum = count($path);
			$info['columnHead'] = $path[$cnum -1];
		}

		// 答题对错
		if(!empty($qinfo['trues']))
			$info['trues'] = $qinfo['trues'];

		if(!empty($qinfo['uniqid']))
			$info['uniqid'] = $qinfo['uniqid'];

		// 获取当前题目的编号，显示使用
		$info['index'] = array_search($id, $qlist) +1;


		/* 
			如果是试卷题目，则要判断 答题时间，播放次数，播放间隔。
			如果有总答题时间则，则该时间后自动跳转到下一题
			如果没有设置总答题时间，但是设置了播放次数和播放间隔则播放完成后跳转到下一题
		*/
		if( !empty($info['exam']) )
		{
			// 获取试卷大题设置
			$epid = $qinfo['exam_question'][$id];  // 获取题目对应的大题id号
			$dtinfo = ExamPaper::find($epid);
			$info['total_time'] = $dtinfo['total_time'];
			$info['loops'] = $dtinfo['loops'];
			$info['time_spacing'] = $dtinfo['time_spacing'];
		}

		/* 生成音频播放列表 */
		$playList = array();
		// 读题音不用重复播放
		if( !empty($info['q']['sound_url']) )
		{
			$playList[] = array('url' => $info['q']['sound_url']);
		}

		// 这个判断是为了防止没有声音的播放 第几遍提示音
		if( !empty($info['q']['hint_url']) || !empty($info['a'][1]['sound_url']) )
		{
			$loop = empty( $info['loops'] ) ? 1 : $info['loops'];
			
			// 不是真实测试统一只放一遍
			if( !$isReal && $loop > 1 )
				$loop = 1;

			$info['is_loop'] = $loop;

			for($i = 0; $i < $loop; $i++)
			{
				// 不是模拟模式，设置了循环次数，则添加第几遍提示音
				if( $isReal && !empty( $info['loops'] ) && $info['loops'] > 1)
				{
					if($i == 0)
						$playList[] = array('url' => '/assets/sound/d1.mp3');
					elseif($i == 1)
						$playList[] = array('url' => '/assets/sound/d2.mp3');
					else
						$playList[] = array('url' => '/assets/sound/d3.mp3');
				}

				if($i > 0)
					$playList[] = array('loop_num' => $i +1);

				if( !empty($info['q']['hint_url']) )
				{
					$playList[] = array('url' => $info['q']['hint_url']);

					/* 2014.12.29亲需求模唱只播放一遍

					// 如果是模唱则默认放二遍提示音
					if($info['q']['type'] == 6)
						$playList[] = array('url' => $info['q']['hint_url']);
					
					*/
				}


				/* 如果是视唱或模唱不播放答案音，答案音实际为参考音 */
				if( $info['q']['type'] != 6 && $info['q']['type'] != 7)
				{
					if( !empty($info['a']) )
					{
						foreach ($info['a'] as $a) 
						{
							if( !empty($a['sound_url']) )
								$playList[] = array('url' => $a['sound_url']);
						}
					}
				}

				if( !empty($info['time_spacing']) )
					$playList[] = array('time_spacing' => $info['time_spacing']);
			}
		}
		
		$info['playList'] = $playList;
		$info['isReal'] = $isReal;
		
		// 收藏分类列表
		$fs = new FavoriteSort();
		$info['fsList'] = $fs->getList(array('uid' => Session::get('uid')));

		return $this->indexView('topic', $info);
	}


	/* 上传录音文件 */
	public function postRecorder()
	{
		$inputs = Input::all();
		$qid = $inputs['id'];
		$uid = Session::get('uid');

		$att = new Attachments();
		#Log::info( var_export($_FILES, true) );
		
		$saved = $att->addRecorder($_FILES["upload_file"]["tmp_name"]['filename'], $uid, $qid);

		if($_POST['format'] == 'json') {
		  header('Content-type: application/json');
		  print "{\"saved\": $saved}";
		} else {
		  print $saved ? "Saved" : 'Not saved';
		}

	}

	/* 记录答题情况 */
	public function post()
	{
		$inputs = Input::all();
		$qid = $inputs['id'];
		$uid = Session::get('uid');

		$realUrl = '';
		if($inputs['real'])
			$realUrl = '&real=' . $inputs['real'];

		// 保存wav录音文件
		if( !empty($inputs['wavBase64']) )
		{
			$fileBase6 = str_replace('data:audio/wav;base64,', '',  $_POST['wavBase64']);

			$decoded = base64_decode($fileBase6);
			//for ($i=0; $i < ceil(mb_strlen($fileBase6)/256); $i++)
			//   $decoded = $decoded . base64_decode(mb_substr($fileBase6,$i*256,256));

			$tmpname = tempnam("/tmp", 'wav');
			file_put_contents($tmpname, $decoded);

			$att = new Attachments();
			$saved = $att->addRecorder($tmpname, $uid, $qid);
		}

		if(!empty($inputs['uniqid']))
		{
			$qinfo = Session::get('qinfo');
			if(empty($qinfo))
				return $this->indexPrompt("", "不能重复提交答题结果", $url = "/column/static", false);

			$uniqid = $qinfo['uniqid'];

			$qinfo['trues'][$qid] = $inputs['isTrue'];
			$qinfo['answers'][$qid] = $inputs['answers'];

			Session::set('qinfo', $qinfo);
			Session::save();

			// 记录错题信息
			if($inputs['isTrue'] == -1)
			{
				$ft = new FailTopic();
				$ftd = array();
				$ftd['uid'] = Session::get('uid');
				$ftd['question_id'] = $qid;
				$ftd['column_id'] = $qinfo['column_id'];
				$ft->add(  $ftd );
			}

		    $qk = $qinfo['list'];
		    $tol = count($qk);
		    for($i = 0; $i < $tol; $i++)
		    {
		    	// 上一题
				if($inputs['act'] == 'prev')
				{
					// 如果已经是第一题
	        		if($qid == $qk[0])
	        		{
	        			header("Location: /topic?uniqid={$uniqid}&id={$qid}" . $realUrl);
		           		exit;
	        		}
	        		elseif($qid == $qk[$i])
	        		{
	        			$qid = $qk[$i -1];

	        			header("Location: /topic?uniqid={$uniqid}&id={$qid}". $realUrl);
		           		exit;
	        		}
	        	}
	        	else
	        	{
	        		// 如果已经是最后一题
	        		if($qid == $qk[$tol -1])
	        		{
						// 保存答题信息
						
						$qinfo['uid'] = Session::get('uid');

						// 如果是试卷
						if( isset($qinfo['exam_id']) )
						{
							$erl = new ExamResultLog();
							$erl->add($qinfo);

							Session::forget('qinfo');
							Session::save();

							header("Location: /topic/result?uniqid={$uniqid}&exam=" . $qinfo['exam_id']);
							exit;
						}
						else
						{
							// 如果是题目
							$topic = new Topic();
							$topic->addResultLog($qinfo);

							Session::forget('qinfo');
							Session::save();

							header("Location: /topic/result?uniqid={$uniqid}");
							exit;
						}

	        			//return $this->indexPrompt("操作成功", "答题完成", $url = "/");
	        			// header("Location: /topic/result?uniqid={$uniqid}");
		          		// exit;
	        		}
	        		elseif($qid == $qk[$i])
	        		{
	        			$qid = $qk[$i +1];
	        			header("Location: /topic?uniqid={$uniqid}&id={$qid}". $realUrl);
		           		exit;
	        		}
	        	}
		    }
		}
		else
		{
			return $this->indexPrompt("操作成功", "答题完成", $url = "/", false);
		}

		
	}


	/* 答题完成 */
	public function result()
	{
		$uniqid = Input::get('uniqid');
		
		$data = array();

		if( !empty(Input::get('exam')) )
		{
			$erl = new ExamResultLog();
			$list = $erl->getList($uniqid);
		}
		else
		{
			$topic = new Topic();
			$list = $topic->getResult($uniqid);
		}

		$data['rightNum'] = 0;
		$data['errorNum'] = 0;
		foreach ($list as $key => $value) 
		{
			 if($value['is_true'] == 1)
			 	$data['rightNum']++;
			 else
			 	$data['errorNum']++;
		}
		
		$data['list'] = $list;
		$data['scores'] =  (100 / count($list)) * $data['rightNum'];
		$data['scores'] = round($data['scores'], 1); // 四舍五入小数点后一位

		// 生成返回链接
		$cn = new Column();
		$path = $cn->getPath($list[0]['column_id']);
		$cnum = count($path);
		
		if($cnum > 2)
			$data['backurl'] = "/column?id={$path[$cnum -2]['id']}&column_id={$path[$cnum -1]['id']}";
		else if($cnum > 1)
			$data['backurl'] = "/column?id={$path[0]['id']}&column_id={$path[$cnum -1]['id']}";

		// 分类头显示使用
		$data['columnHead'] = $path[$cnum -1];

		// 设置父顶级科目id
		$data['column'] = $path[$cnum -1]['id'];

		return $this->indexView('topic_result',  $data);
	}


}
