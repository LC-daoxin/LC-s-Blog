<?php
/**
 * 友情链接
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="main-container">
    <article class="post preview" itemscope itemtype="http://schema.org/BlogPosting">
        <h1 class="post-title" itemprop="name headline"><?php $this->title() ?></h1>
        <div class="post-content" itemprop="articleBody">
            <?php $this->content();?>
        </div>
        <ul class="tag-list flinks">
            <?php Links_Plugin::output(null,0,'');?>
        </ul>
    </article>
    <?php $this->need($this->options->comment.'.php'); ?>
</div><!-- end #main-->

<?php $this->need('footer.php'); ?>
