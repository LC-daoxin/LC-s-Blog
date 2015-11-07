<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
    </div>
</div>
<footer> 
   <div class="footer-inner">
   <?php if (!empty($this->options->Show) && in_array('Showfooter', $this->options->Show)): ?> 
    <p>
    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
        <?php while($pages->next()): ?>
        <a href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a> | 
        <?php endwhile; ?> 
    <a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a> | 
        <a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a>
	</p>
	<?php endif; ?> 
    <p> &copy; <?php echo date('Y');?> <a href="<?php $this->options->siteUrl(); ?>" target="_blank"> <?php $this->options->title() ?> </a>
        <?php _e(' / Powered by <a href="http://www.typecho.org" target="_blank">Typecho</a>'); ?>
        <?php _e(' / <a href="http://minwenlsm.pw/" target="_blank">Theme(变异版1.2)</a> by <a href="http://lixianhua.com" target="_blank">绛木子</a>'); ?>
        <?php if ($this->options->icpNum): ?>
           / <a href="http://www.miitbeian.gov.cn/" target="blank"><?php $this->options->icpNum(); ?></a>
        <?php endif; ?>
	</p> 
   </div> 
</footer>
<div class="fixed-btn">
    <a class="back-to-top" href="#" title="返回顶部"><i class="fa fa-chevron-up"></i></a>
     <?php if($this->is('post')): ?>
    <a class="go-comments" href="#comments" title="评论"><i class="fa fa-comments"></i></a>
    <?php endif; ?>
</div>
<?php $this->footer(); ?>
<script src="<?php $this->options->themeUrl('js/common.js'); ?>"></script>
<?php if ($this->is('post')) :?>
<?php if ($this->options->highlight == 'true' ): ?>
<script src="<?php $this->options->themeUrl('js/highlight.min.js'); ?>"></script>
<?php endif; ?>
<script src="<?php $this->options->themeUrl('js/qrcode.js'); ?>"></script>
<?php if ($this->options->lightbox == 'lightboxtrue'): ?>
<script src="<?php $this->options->themeUrl('js/lightbox.min.js'); ?>"></script>
<?php endif; ?>
<script>
$(function(){
<?php if ($this->options->highlight == 'true' ): ?>
	$(window).load(function(){
	     $('pre code').each(function(i, block) {
      hljs.highlightBlock(block);
      });
	});
<?php endif; ?>
	var qrcode = new QRCode(document.getElementById("qrcode-img"), {
        width : 96,//设置宽高
        height : 96
    });
	qrcode.makeCode("<?php $this->permalink();?>");
})
</script>
<?php endif;?>
	<?php if ($this->options->Navigator == 'PageAjax'): ?>
    <script>
      window.isArchive = <?php if($this->is('index') || $this->is('archive')){echo 'true';}else{echo 'false';}?>;
    </script>
	<?php endif; ?>
	<?php if ($this->options->Ajax == 'UseAjax'): ?>
<script>
    $(document).pjax('a[target!=_blank]', '.main', {fragment:'.main', timeout:8000});
</script>
	<?php endif; ?>
<?php if ($this->options->siteStat): ?><?php $this->options->siteStat(); ?><?php endif; ?>
</body>
</html>