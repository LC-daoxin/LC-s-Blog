<?php
/**
 * 由清馨雅致基于绛木子仿简书主题最新版修改。<br/>你可以访问<a href="http://minwenlsm.pw/">Min's博客</a>查看详情。<br/>
 * 
 * @package JianShu(变异版)
 * @author 绛木子、<a href="http://minwenlsm.pw/">清馨雅致修改</a>
 * @version 1.2
 * @link http://lixianhua.com
 *
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
 $this->need('header.php');
?>
<div id="main-container" class="main-container">
<?php while($this->next()): ?>
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
    <?php if(!empty($this->options->listStyle) && in_array('thumb',$this->options->listStyle)): ?>
      <?php showThumb($this);?>
    <?php endif; ?>
    <ul class="post-meta">
        <li><?php $this->category(','); ?></li>
        <li><?php $this->dateWord(); ?></li>
      <li><?php _e('阅读');$this->viewsNum(); ?></li>
      <li><?php _e('喜欢');$this->likesNum(); ?></li>
      <?php if ($this->options->comment == 'comments'): ?>
      <li href="<?php $this->permalink() ?>#<?php $this->respondId(); ?>"><?php $this->commentsNum('评论%d'); ?></a></li>
      <?php endif; ?>
      <?php if ($this->options->comment == 'comments_duoshuo'): ?>
      <li itemprop="interactionCount"><span class="fa fa-comments-o"></span><a itemprop="discussionUrl" href="<?php $this->permalink() ?>#comments" class="ds-thread-count" data-thread-key="<?php $this->cid(); ?>"></a><li>
      <?php endif; ?>
    </ul>
    <h2 class="post-title" itemprop="name headline"><a itemtype="url" href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h2>
        <?php if(!empty($this->options->listStyle) && in_array('excerpt',$this->options->listStyle)): ?>
      <div class="post-content" itemprop="articleBody">
      <?php $this->description(); ?>
    </div>
    <?php endif; ?>
    </article>
<?php endwhile; ?>
  <?php if ($this->options->Navigator == 'PageNav'): ?>
    <div class="page-navigator">
         <?php $this->pageNav() ;?>
    </div>
  <?php endif; ?>
  <?php if ($this->options->Navigator == 'PageAjax'): ?>
    <div id="ajax-page" class="ajax-navigator">
        <?php $this->pageLink('更多','next');?>
    </div>
  <?php endif; ?>
</div>
<?php $this->need('footer.php'); ?>