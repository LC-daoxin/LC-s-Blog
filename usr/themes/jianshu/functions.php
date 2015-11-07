<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form) {
    $logoText = new Typecho_Widget_Helper_Form_Element_Text('logoText', NULL, NULL, _t('网站文字LOGO'), _t('网站文字LOGO，单个文字;为空时取网站标题第一个文字'));
    $form->addInput($logoText);
    
    $Show = new Typecho_Widget_Helper_Form_Element_Checkbox('Show', 
        array('Showavatar' => _t('首页头像'),
          'Showfavatar' => _t('文章页头像'),
          'Showtags' => _t('文章页底部标签'),
          'Showauthor' => _t('文章页发布信息'),
          'Showtool' => _t('新版文章页工具集'),
          'Showolddonate' => _t('旧版支付宝捐款'),
          'Showolddonateimg' => _t('旧版支付宝捐款的二维码'),
          'Showfooter' => _t('全站底部分类及RSS订阅')),
        array('Showavatar', 'Showfavatar',  'Showtags',  'Showfooter' , 'Showtool' , 'Showauthor'), _t('自定义显示'));
    $form->addInput($Show->multiMode());
    
    $PageShow = new Typecho_Widget_Helper_Form_Element_Checkbox('PageShow', 
        array('Showpfavatar' => _t('文章页头像'),
          'Showptags' => _t('文章页底部标签'),
          'Showptool' => _t('新版文章页工具集'),
          'Showpolddonate' => _t('旧版支付宝捐款'),
          'Showpolddonateimg' => _t('旧版支付宝捐款的二维码')),
        array('Showtool'), _t('独立页面显示'));
    $form->addInput($PageShow->multiMode());    

    $comment = new Typecho_Widget_Helper_Form_Element_Radio('comment',
        array(
          'comments' => _t('默认评论框'),
          'comments_duoshuo' => _t('多说评论框')),
          'comments',
          	_t('评论框选择'),
            _t("选择后首页、文章页评论数自动同步更改")
          	);
    $form->addInput($comment);

    $duosuokey = new Typecho_Widget_Helper_Form_Element_Text('duosuokey', NULL, 'DUOSHUO_KEY', _t('多说评论框Key'), _t('请前往<a href="http://duoshuo.com/">http://duoshuo.com/</a>获取Key(既多说评论框域名)<br/>获取步骤为：</p>登录多说->后台管理->工具->获取代码->short_name:你的站点key'));
    $form->addInput($duosuokey);

    $duosuojs = new Typecho_Widget_Helper_Form_Element_Text('duosuojs', NULL, '//static.duoshuo.com/embed.js', _t('多说评论框JS'), _t('多说评论框embed.js路径，可用于embed.js本地化使用'));
    $form->addInput($duosuojs);

    $css = new Typecho_Widget_Helper_Form_Element_Radio('css',
        array(
          'default' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#e78170; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>默认风格'),
          'red' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#DA4453; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>红色风格'),
          'green' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#8CC152; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>绿色风格'),
          'blue' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#4A89DC; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>蓝色风格'),
          'purple' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#967ADC; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>紫色风格'),
          'black' => _t('<span style="display: inline-block; width: 24px; height: 15px; background-color:#434A54; -webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; -moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset; box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.3) inset;"></span>黑色风格')),
          'default',
          	_t('配色风格'));
    $form->addInput($css->multiMode());

    $Navigator = new Typecho_Widget_Helper_Form_Element_Radio('Navigator',
        array(
          'PageNav' => _t('默认风格'),
          'PageAjax' => _t('Ajax风格')),
          'PageNav',
          _t('底部分页'));
    $form->addInput($Navigator);

    $Ajax = new Typecho_Widget_Helper_Form_Element_Radio('Ajax',
        array(
          'UseAjax' => _t('开启'),
          'NoAjax'=>_t('关闭')),
          'NoAjax',
          _t('Ajax全站防刷新'),
          _t("测试功能，尚未完善…"));
    $form->addInput($Ajax);
    
    $Indent = new Typecho_Widget_Helper_Form_Element_Radio('Indent',
        array('IndentTrue'=>_t('开启'),'IndentFalse'=>_t('关闭')),
        'IndentFalse',
        _t("段落缩进"),
        _t("文章每个段落前自动缩进2个空格")
        );
    $form->addInput($Indent); 
    
    $lightbox = new Typecho_Widget_Helper_Form_Element_Radio('lightbox',
        array(
          'lightboxtrue' => _t('开启'),
          'lightboxfalse'=>_t('关闭')),
          'lightboxtrue',
          _t('文章图片'),
          _t("开启后如原版仅显示图片名称描述，点击才显示图片"));
    $form->addInput($lightbox);
    
    $highlight = new Typecho_Widget_Helper_Form_Element_Radio('highlight',
        array(
          'true' => _t('开启'),
          'false'=>_t('关闭')),
          'true',
          _t('代码高亮'),
          _t("开启后如原版高亮显示代码片段"));
    $form->addInput($highlight);    
        
    $post_author = new Typecho_Widget_Helper_Form_Element_Radio('post_author',
        array(
          'authorimg' => _t('图片地址'),
          'authormail' => _t('邮箱地址')),
          'authormail',
          _t('文章作者头像'),
          _t("图片地址使用博主头像</br>邮箱地址使用作者邮箱设置的gravatar头像")
          );
    $form->addInput($post_author);           
    
	// 头像地址
    $avatarUrl= new Typecho_Widget_Helper_Form_Element_Text('avatarUrl', NULL, NULL, _t('博主头像'), _t('填写图片URL，留空则输出默认。图片为主题img/目录下avatar.png'));
    $form->addInput($avatarUrl);
    $avatarDomain = new Typecho_Widget_Helper_Form_Element_Text('avatarDomain', NULL, 'http://cn.gravatar.com', _t('头像地址'),_t('替换Typecho使用的Gravatar头像服务器（ www.gravatar.com ）'));
    $form->addInput($avatarDomain);
    $favatar = new Typecho_Widget_Helper_Form_Element_Text('favatar', NULL, NULL, _t('文章页底部头像'), _t('填写图片URL，留空则输出默认。默认为首页头像'));
    $form->addInput($favatar);
    $tips = new Typecho_Widget_Helper_Form_Element_Text('tips', NULL, NULL, _t('底部头像描述'), _t('底部头像文字签名描述'));
    $form->addInput($tips);
    
    $alipayAccount = new Typecho_Widget_Helper_Form_Element_Text('alipayAccount', NULL, NULL, _t('打赏支付宝帐号'), _t('在这里填入支付宝帐号'));
    $form->addInput($alipayAccount);
    $alipayAmount = new Typecho_Widget_Helper_Form_Element_Text('alipayAmount', NULL, NULL, _t('默认打赏金额'), _t('在这里填入打赏金额'));
    $form->addInput($alipayAmount);
    $alipayimg= new Typecho_Widget_Helper_Form_Element_Text('alipayimg', NULL, NULL, _t('支付宝二维码'), _t('填写图片URL，留空则输出默认。图片为主题img/目录下alipay.png'));
    $form->addInput($alipayimg);
    
        
    $icpNum = new Typecho_Widget_Helper_Form_Element_Text('icpNum', NULL, NULL, _t('网站备案号'), _t('在这里填入网站备案号'));
    $form->addInput($icpNum);
    $siteStat = new Typecho_Widget_Helper_Form_Element_Textarea('siteStat', NULL, NULL, _t('统计代码'), _t('在这里填入网站统计代码'));
    $form->addInput($siteStat);
    $bgPhoto = new Typecho_Widget_Helper_Form_Element_Text('bgPhoto', NULL, NULL, _t('网站背景图'), _t('在这里填入背景图网址'));
    $form->addInput($bgPhoto);        
    
    //附件源地址
    $src_address = new Typecho_Widget_Helper_Form_Element_Text('src_add', NULL, NULL, _t('替换前地址'), _t('即你的附件存放地址，如http://www.yourblog.com/usr/uploads/'));
    $form->addInput($src_address);
    //替换后地址
    $cdn_address = new Typecho_Widget_Helper_Form_Element_Text('cdn_add', NULL, NULL, _t('替换后'), _t('即你的七牛云存储域名，如http://yourblog.qiniudn.com/'));
    $form->addInput($cdn_address);
    
	
    //默认缩略图
    $default = new Typecho_Widget_Helper_Form_Element_Text('default_thumb', NULL, '', _t('默认缩略图'),_t('文章没有图片时显示的默认缩略图，为空时表示不显示'));
    $form->addInput($default);
    //默认宽度
    $width = new Typecho_Widget_Helper_Form_Element_Text('thumb_width', NULL, '200', _t('缩略图默认宽度'));
    $form->addInput($width);
    //默认高度
    $height = new Typecho_Widget_Helper_Form_Element_Text('thumb_height', NULL, '140', _t('缩略图默认高度'));
    $form->addInput($height);
    
    $iconCss = new Typecho_Widget_Helper_Form_Element_Textarea('iconCss', NULL, NULL, _t('图标样式'), _t('在这里填入图标样式代码'));
    $form->addInput($iconCss);
        
    $listStyle = new Typecho_Widget_Helper_Form_Element_Checkbox('listStyle',
        array('excerpt' => _t('显示摘要'),
            'thumb' => _t('显示缩略图')),
        array('excerpt', 'thumb'), _t('列表显示'));
    
    $form->addInput($listStyle);
    
}
/**
 * 获取gravatar头像地址
 *
 * @param string $mail
 * @param int $size
 * @param string $rating
 * @param string $default
 * @return string
 */
function gravatarUrl($mail, $size=32, $rating=null, $default=null){
	$url = Typecho_Widget::widget('Widget_Options')->avatarDomain;
	$url .= '/avatar/';

	if (!empty($mail)) {
		$url .= md5(strtolower(trim($mail)));
	}

	$url .= '?s=' . $size;
	$url .= '&amp;r=' . ($rating==null?Typecho_Widget::widget('Widget_Options')->commentsAvatarRating : $rating);
	$url .= '&amp;d=' . $default;

	return $url;
}
function showThumb($obj,$size=null,$link=false,$pattern='<div class="post-thumb"><a class="thumb" href="{permalink}" title="{title}" style="background-image:url({thumb})"></a></div>'){

    preg_match_all( "/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/", $obj->content, $matches );
    $thumb = '';
    $options = Typecho_Widget::widget('Widget_Options');
    if(isset($matches[1][0])){
        $thumb = $matches[1][0];;
        if(!empty($options->src_add) && !empty($options->cdn_add)){
            $thumb = str_ireplace($options->src_add,$options->cdn_add,$thumb);
        }
        if($size!='full'){
            $thumb_width = $options->thumb_width;
            $thumb_height = $options->thumb_height;
    
            if($size!=null){
                $size = explode('x', $size);
                if(!empty($size[0]) && !empty($size[1])){
                    list($thumb_width,$thumb_height) = $size;
                }
            }
            $thumb .= '?imageView2/4/w/'.$thumb_width.'/h/'.$thumb_height;
        }
    }

	if(empty($thumb) && empty($options->default_thumb)){
	    return '';
	}else{
	    $thumb = empty($thumb) ? $options->default_thumb : $thumb;
	}
	if($link){
	    return $thumb;
	}
	echo str_replace(
	    array('{title}','{thumb}','{permalink}'),
	    array($obj->title,$thumb,$obj->permalink),
	    $pattern);
}
/**
 * 解析内容以实现附件加速
 * @access public
 * @param string $content 文章正文
 * @param Widget_Abstract_Contents $obj
 */
function parseContent($obj){
    $options = Typecho_Widget::widget('Widget_Options');
    if(!empty($options->src_add) && !empty($options->cdn_add)){
        $obj->content = str_ireplace($options->src_add,$options->cdn_add,$obj->content);
    }
    if (!empty($options->lightbox) && 'lightboxtrue' == $options->lightbox){
	$pattern="/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?alt=[\'|\"](.*?)[\'|\"].*?[\/]?>/";
	preg_match_all($pattern,$obj->content,$match);
	if(count($match[1])>0){
		$div = $match[0];
		$imgs = $match[1];
		$alt = $match[2];
		foreach($imgs as $k=>$v){
			$obj->content = str_replace($div[$k],"<a href='".$v."' data-lightbox='".$v."'>".$alt[$k]."</a>",$obj->content);
		}
	}
}
    echo trim($obj->content);
}
/**
 * 实现静态资源的加速
 * @param string $params
 */
function themeCdnUrl($params=null){
    $options = Typecho_Widget::widget('Widget_Options');
    if(!empty($options->src_add) && !empty($options->cdn_add)){
        echo $options->cdn_add.$params;
    }else{
        $options->themeUrl($params);
    }
}

/**
 * 格式化时间
 * @param int $time 时间戳
 * @param string $str 显示格式
 * @return string
 */
function formatTime($time,$str='Y-m-d'){
    isset($str)?$str:$str='m-d';
    $way = time() - $time;
    $r = '';
    if($way < 60){
        $r = '刚刚';
    }elseif($way >= 60 && $way <3600){
        $r = floor($way/60).'分钟前';
    }elseif($way >=3600 && $way <86400){
        $r = floor($way/3600).'小时前';
    }elseif($way >=86400 && $way <2592000){
        $r = floor($way/86400).'天前';
    }elseif($way >=2592000 && $way <15552000){
        $r = floor($way/2592000).'个月前';
    }else{
        $r = date("$str",$time);
    }
    return $r;
}
/**
 * 生成随机颜色值
 * @return string
 */
function randColor(){
    return rand(120,200).','.rand(120,200).','.rand(120,200);
}
/**
 * 显示标签
 * @param string $parse 解析模版
 * @param number $limit 显示条数 为0时表示显示全部
 * @param string $sort 排序字段
 * @param number $desc 默认为0,表示倒序
 * @return void
 */
function showTagCloud($parse=null,$limit=30,$sort='mid',$desc=0){
    $parse = is_null($parse) ? '<li><a href="{permalink}" title="{count}个话题" style="{background}">{name}({count})</a></li>': $parse;
    Typecho_Widget::widget('Widget_Metas_Tag_Cloud', 'sort='.$sort.'&ignoreZeroCount=1&desc='.$desc.'&limit='.$limit)->to($tags);
    $output = '';
    if($tags->have()){
        while ($tags->next()){
            $color = 'color: rgb('.randColor().');';
            $background = 'background-'.$color;
            $output .= str_replace(
                array('{permalink}','{count}','{name}','{background}','{color}'),
                array($tags->permalink,$tags->count,$tags->name,$background,$color),
                $parse);
        }
    }
    echo $output;
}
/**
 * 重写评论显示函数
 */
function threadedComments($comments, $options){
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }

    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
    ?>
<li itemscope itemtype="http://schema.org/UserComments" id="<?php $comments->theId(); ?>" class="comment-body<?php
    if ($comments->levels > 0) {
        echo ' comment-child';
        $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
    } else {
        echo ' comment-parent';
    }
    $comments->alt(' comment-odd', ' comment-even');
    echo $commentClass;
?>">
    
    <div class="comment-meta">
        <div class="comment-meta-author" itemprop="creator" itemscope itemtype="http://schema.org/Person">
            <span itemprop="image">
			<img class="avatar" src="<?php echo gravatarUrl($comments->mail, $options->avatarSize, null,$options->defaultAvatar);?>" width="<?php echo $options->avatarSize;?>">
			</span>
            <cite class="fn" itemprop="name"><?php $options->beforeAuthor();
            $comments->author();
            $options->afterAuthor(); ?></cite>
        </div>
        <div class="comment-meta-time">
        <a href="<?php $comments->permalink(); ?>"><time itemprop="commentTime" datetime="<?php $comments->date('c'); ?>"><?php $options->beforeDate();
        $comments->date($options->dateFormat);
        $options->afterDate(); ?></time></a>
        </div>
        <?php if ('waiting' == $comments->status) { ?>
        <em class="comment-awaiting-moderation"><?php $options->commentStatus(); ?></em>
        <?php } ?>
        <div class="comment-meta-reply">
            <?php $comments->reply($options->replyWord); ?>
        </div>
    </div>
    <div class="comment-content" itemprop="commentText">
    <?php $comments->content(); ?>
    </div>
    
    <?php if ($comments->children) { ?>
    <div class="comment-children" itemprop="discusses">
        <?php $comments->threadedComments(); ?>
    </div>
    <?php } ?>
</li>
<?php
}