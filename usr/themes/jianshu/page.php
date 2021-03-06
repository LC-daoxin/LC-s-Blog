<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="main-container">
    <article class="post preview" itemscope itemtype="http://schema.org/BlogPosting">
        <h1 class="post-title" itemprop="name headline"><a itemtype="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
        <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
        <?php if (!empty($this->options->PageShow) && in_array('Showpolddonate', $this->options->PageShow)): ?>
          <div class="post-olddonate">
          <p><i class="fa fa-rmb"></i> 打赏几块钱，帮我买杯咖啡吧，谢谢大家！<button class="btn s2 fr" onclick="$('#alipay-form').submit();"><i class="fa fa-cny"> <?php _e('打赏支持');?></i></button></p>
        <?php if (!empty($this->options->PageShow) && in_array('Showpolddonateimg', $this->options->PageShow)): ?>
          <div class="post-olddonate-img">
          <p>支付宝二维码</p>
          <img src="<?php if ($this->options->alipayimg): ?><?php $this->options->alipayimg() ?><?php else: ?><?php $this->options->themeUrl('img/alipay.png'); ?><?php endif; ?>">
          </div>
	      <?php endif; ?>
          <form id="alipay-form" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="POST" target="_blank" accept-charset="GBK">
          <input type="hidden" name="optEmail" value="<?php $this->options->alipayAccount();?>">
          <input type="hidden" name="payAmount" value="<?php $this->options->alipayAmount();?>">
          <input type="hidden" name="title" value="<?php _e('打赏：');$this->title();?>">
          </form>
          </div>
      	<?php endif; ?>
    <?php if (!empty($this->options->PageShow) && in_array('Showptool', $this->options->PageShow)): ?>                
    <div class="post-tool">
			<span class="post-like"><a class="btn s3 btn-like" data-cid="<?php $this->cid();?>" href="#"><i class="fa fa-thumbs-up"></i> <?php _e('赞'); ?> <span class="post-likesnum"><?php $this->likesNum();?></span></a></span>
			<span class="post-share"><a class="btn s3 btn-dialog" data-dialog="#dialog-share" href="#"><i class="fa fa-share-alt"></i> <?php _e('分享'); ?></a></span>
			<span class="post-donate">
				<a href="#" class="btn s3 btn-dialog" data-dialog="#dialog-donate"><?php _e('赏'); ?></a>
				
			</span>
			<div class="dialog" id="dialog-donate" >
				<h4><?php _e('您可以选择一种方式赞助本站'); ?></h4>
				<form id="alipay-form" action="https://shenghuo.alipay.com/send/payment/fill.htm" method="POST" target="_blank" accept-charset="GBK">
					<input type="hidden" name="optEmail" value="<?php $this->options->alipayAccount();?>">
					<input type="hidden" name="payAmount" value="<?php $this->options->alipayAmount();?>">
					<input type="hidden" name="title" value="<?php _e('赞助');$this->options->title();?>">
					<input type="hidden" name="memo" value="<?php _e('《');$this->title();_e('》写的很好，打赏一个!');?>">
					<input title="点击此按钮赞助本站" name="pay" src="<?php $this->options->themeUrl('img/alipay_btn.png')?>" type="image" value="赞助本站">
				</form>
				<p><?php _e('支付宝扫码赞助'); ?></p>
				<img src="<?php $this->options->themeUrl('img/alipay.png')?>" alt="支付宝扫码赞助" width="200" />
			</div>
			<div class="dialog" id="dialog-share">
				<div class="bdsharebuttonbox" data-tag="share_1">
					<a class="bds_tsina fa fa-weibo" data-cmd="tsina"></a>
	        <a class="bds_sqq fa fa-qq" data-cmd="sqq"></a>
					<a class="bds_weixin fa fa-weixin" data-cmd="weixin"></a>
					<a class="bds_mail fa fa-envelope" data-cmd="mail"></a>
					<a class="bds_more fa fa-plus" data-cmd="more"></a>
				</div>
				<script>
					window._bd_share_config = {
						common:{
							bdText:'<?php $this->title();?>',
							bdDesc:'<?php $this->description();?>',
							bdUrl:'<?php $this->permalink();?>',
							bdPic:''
						},
						share : [{
							bdCustomStyle:'<?php $this->options->themeUrl('css/bdshare.css');?>'
						}],
					}
					with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
				</script>
			</div>
		</div>
	  <?php endif; ?> 
	  <?php if (!empty($this->options->PageShow) && in_array('Showpfavatar', $this->options->PageShow)): ?>   
	  <div class="post-fauthor">
	      	<div class="author-ava"><img src="<?php if ($this->options->favatar): ?><?php $this->options->favatar() ?><?php else: ?><?php $this->options->themeUrl('img/avatar.png'); ?><?php endif; ?>" ></div>
	      	<p><?php $this->options->tips(); ?></p>
       </div>
      <?php endif; ?>
	      <?php if (!empty($this->options->PageShow) && in_array('Showptags', $this->options->PageShow)): ?>
        <div class="post-foot clearfix">
        <div class="post-tags"><?php _e('标签: '); ?><?php $this->tags(', ', true, 'none'); ?></div>
        </div>
        <?php endif; ?>
    </article>
    <?php $this->need($this->options->comment.'.php'); ?>
</div><!-- end #main-->

<?php $this->need('footer.php'); ?>