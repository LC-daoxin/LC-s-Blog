<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
class TeStat_Action extends Typecho_Widget implements Widget_Interface_Do
{
	private $db;
	private $options;
	private $prefix;

	public function action()
	{
		$this->db = Typecho_Db::get();
		$this->prefix = $this->db->getPrefix();
		$this->options = Typecho_Widget::widget('Widget_Options');
		
		$cid = $this->request->cid;
		if(!$cid)
			$this->response->throwJson(array('status'=>0,'msg'=>'请选择喜欢的文章!'));
		$likes = Typecho_Cookie::get('__post_likes');
		if(empty($likes)){
			$likes = array();
		}else{
			$likes = explode(',', $likes);
		}
		
		if(!in_array($cid,$likes)){
			$row = $this->db->fetchRow($this->db->select('likesNum')->from('table.contents')->where('cid = ?', $cid)->limit(1));
			$this->db->query($this->db->update('table.contents')->rows(array('likesNum' => (int)$row['likesNum']+1))->where('cid = ?', $cid));
			array_push($likes, $cid);
			$likes = implode(',', $likes);
			Typecho_Cookie::set('__post_likes', $likes); //记录查看cookie
			$this->response->throwJson(array('status'=>1,'msg'=>'成功点赞!'));
		}
		$this->response->throwJson(array('status'=>0,'msg'=>'你已经点赞过了!'));
	}
}
