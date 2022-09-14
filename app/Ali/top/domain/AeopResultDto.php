<?php

/**
 * result
 * @author auto create
 */
class AeopResultDto
{
	
	/** 
	 * 错误代码
	 **/
	public $error_code;
	
	/** 
	 * 出错时的错误信息。
	 **/
	public $error_message;
	
	/** 
	 * 操作结果。true/false分别表示成功和失败。
	 **/
	public $success;
	
	/** 
	 * 绑定成功的产品分组列表。
	 **/
	public $target_list;
	
	/** 
	 * 20150714015815415-0700
	 **/
	public $time_stamp;	
}
?>