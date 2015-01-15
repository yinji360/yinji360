<?php

class MyController extends BaseController
{

	public function __construct()
    {
        $query = Input::only('column_id');

        if ((!isset($query['column_id']) || !is_numeric($query['column_id'])) && Request::path() != 'column/static') {
            echo ("<script>window.location.href='/column/static';</script>");
        }
    }

	/* 登录处理 */
	public function index()
	{

		echo 111444444;exit;
	
	}

}
