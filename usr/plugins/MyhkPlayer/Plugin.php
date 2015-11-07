<?php
/**
 * 基于虾米音乐、网易云音乐歌曲ID全自动解析的Html5音乐播放器
 * 
 * @package 明月浩空播放器免费版
 * @author 明月浩空
 * @version 20150801
 * @link http://limh.me
 */
class MyhkPlayer_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('MyhkPlayer_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('MyhkPlayer_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
       /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
		$file = 'http://free.limh.me/my/typecho/url.txt';
		$content = file_get_contents($file);
		$start3 = strpos($content, "E=", 0);
		$end3   = strpos($content, "F", $start3);
		$banben = substr($content, $start3+2, $end3-$start3-2);
		$start4 = strpos($content, "F=", 0);
		$end4   = strpos($content, "G", $start4);
		$xiazai = substr($content, $start4+2, $end4-$start4-2);
		if($banben=='20150801'){
			$title= '当前版本：<span class="sel">20150801</span> 暂无更新！';
		}else{
			$title= '当前版本：<span class="sel">20150801</span></br></br>最新版本：<span class="sel"><a style="color:#f00" href="http://free.limh.me/update_'.$banben.'.html" title="点击查看'.$banben.'版本更新日志" target="_blank">'.$banben.'</a></span> 请<a title="点击下载播放器插件'.$banben.'版本" href="'.$xiazai.'" target="_blank">[点击这里]</a>下载';
		}
        $domain = new Typecho_Widget_Helper_Form_Element_Text('domain', NULL, '填写歌单后台显示的绑定域名', _t(''.$title.'</br></br>免费版后台：<a href="http://free.limh.me/admin" target="_blank">http://free.limh.me</b></a> 商业版购买联系<a href="http://wpa.qq.com/msgrd?v=3&uin=6354321&site=qq&menu=yes" target="_blank">QQ：6354321</a></br></br>绑定域名[填写歌单后台显示的绑定域名，不加http://和www]：'));
        $form->addInput($domain);
		$name = new Typecho_Widget_Helper_Form_Element_Text('name', NULL, '填写你的网站名称', _t('网站名称[填写你的网站名称，用于播放器界面显示]：'));
        $form->addInput($name);
		$key = new Typecho_Widget_Helper_Form_Element_Text('key', NULL, '填写歌单后台显示的激活码', _t('激活码[填写有效激活码，用于播放器检测授权]：'));
        $form->addInput($key);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 输出头部css
     * 
     * @access public
     * @return void
     */
    public static function header(){
        $cssUrl = Helper::options()->pluginUrl . '/MyhkPlayer/style/player.css?ver=20150801';
		echo '<script src="http://libs.baidu.com/jquery/1.8.0/jquery.min.js"></script>' . "\n";
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />' . "\n";
		echo '<link href="http://libs.baidu.com/fontawesome/4.2.0/css/font-awesome.css" rel="stylesheet" type="text/css" />'."\n";
    }
    /**
     * 输出底部
     * 
     * @access public
     * @return void
     */
    public static function footer(){
        $options = Typecho_Widget::widget('Widget_Options')->plugin('MyhkPlayer'); 
        $jsUrl1 = Helper::options()->pluginUrl . '/MyhkPlayer/js/mousewheel.js';
		$jsUrl2 = Helper::options()->pluginUrl . '/MyhkPlayer/js/scrollbar.js';
		$jsUrl3 = Helper::options()->pluginUrl . '/MyhkPlayer/js/player.js?ver=20150801';
        echo '<script type="text/javascript">name="'.$options->name.'";domain="'.$options->domain.'";key="'.$options->key.'";</script>
<div id="wenkmPlayer">
	<div class="player">
		<div class="infos">
			<div class="songstyle"><i class="fa fa-music"></i> <span class="song"></span></div>
            <div class="timestyle"><i class="fa fa-clock-o"></i> <span class="time">00:00 / 00:00</span></div>
			<div class="artiststyle"><i class="fa fa-user"></i> <span class="artist"></span><span class="moshi"><i class="loop fa fa-random current"></i> 随机播放</span></div>
            <div class="artiststyle"><i class="fa fa-folder"></i> <span class="artist1"></span><span class="geci"></span></div>
		</div>
		<div class="control">
			<i class="loop fa fa-retweet" title="顺序播放"></i>
			<i class="prev fa fa-backward" title="上一首"></i>
			<div class="status">
				<b>
					<i class="play fa fa-play" title="播放"></i>
					<i class="pause fa fa-pause" title="暂停"></i>
				</b>
			</div>
			<i class="next fa fa-forward" title="下一首"></i>
			<i class="random fa fa-random current" title="随机播放"></i>
		</div>
		<div class="musicbottom">
			<div class="volume">
				<i class="mute fa fa-volume-off"></i>
				<i class="volumeup fa fa-volume-up"></i>
				<div class="progress">
					<div class="volume-on ts5">
						<div class="drag" title="音量"></div>
					</div>
				</div>
			</div>
			<div class="switch-playlist">
				<i class="fa fa-bars" title="播放列表"></i>
			</div>
            <div class="switch-ksclrc">
				<i class="fa fa-toggle-on" title="关闭歌词"></i>
			</div>
			<div class="switch-default">
				<i class="fa fa-refresh" title="切换默认专辑"></i>
			</div>
		</div>
		<div class="cover"></div>
	</div>
	<div class="playlist">
		<div class="playlist-bd">
			<div class="album-list">
				<div class="musicheader"></div>
					<div class="list"></div>
			</div>
			<div class="song-list">
				<div class="musicheader"><i class="fa fa-angle-right"></i><span></span></div>
			<div class="list"><ul></ul></div>
			</div>
		</div>
	</div>
	<div class="switch-player">
		<i class="fa fa-angle-right" style="margin-top: 20px;"></i>
	</div>
</div>
<div id="wenkmTips"></div>
<div id="wenkmLrc"></div>
<div class="myhk_pjax_loading_frame"></div>
<div class="myhk_pjax_loading"></div>' . "\n";
        echo '<script type="text/javascript" src="'.$jsUrl1.'"></script>' . "\n";
		echo '<script type="text/javascript" src="'.$jsUrl2.'"></script>' . "\n";
		echo '<script type="text/javascript" src="'.$jsUrl3.'"></script>' . "\n";
    }

}