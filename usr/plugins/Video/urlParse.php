<?php
/////////////////////////////////////////////////////////////////
//云边开源轻博, Copyright (C)   2010 - 2011  qing.thinksaas.cn
//EMAIL:nxfte@qq.com QQ:234027573
//$Id: urlParse.php 1096 2012-06-30 09:48:58Z anythink $


class urlParse {

	private $url  = '';
	private $desc = '';

    public function __construct() {}

	/*这里只解析音乐*/
	public function setmusic($url,$desc=''){
		if(!function_exists('curl_init')){
			return array('error'=>'服务器不支持curl扩展,无法使用本功能');
		}
		$sitelist = array(
			'yinyuetai.com'    => '_webVideo'
		); //注册引用解析

		$path =  pathinfo($url);
		$ext = $path['extension']; //页面后缀
		$domain = $this->getDomain($url);//引用页地址
		if($ext == 'mp3' || $ext == 'wma'){
			$data = $this->_webMusicGetMp3($url);
		}else{
			//在约束数组中查找
			if (array_key_exists($domain, $sitelist)) {   //网页解析
				$data = $this->$sitelist[$domain]($url);
			}
		}

		if(is_array($data)){
			if($desc != ''){$data['desc'] = $desc;}
			if($data['type'] == '' || $data['id'] == '' || $data['img'] == '' || $data['title'] == ''){
				print_r($data);
				return array('error'=>'解析的内容不全，请更换网站');
			}
			return $data;
		}else{
			return array('error'=>'音乐频道目前只能解析音乐台网站');
		}
	}


	public function setvideo($url,$desc='',$type='music')
	{
		if(!function_exists('curl_init')){
			return array('error'=>'服务器不支持curl扩展,无法使用本功能');
		}

		$sitelist = array(
			'xiami.com'    => '_webMusicGetXiami',
			'musicyun.com' => '_webMusicGetYinyueyun',
			'youku.com'        => '_webVideo',
			'tudou.com'        => '_webVideo',
			'ku6.com'          => '_webVideo',
			'56.com'           => '_webVideo',
			'sina.com.cn'      => '_webVideo',
			'my.tv.sohu.com'   => '_webVideo',
			'sohu.com'         => '_webVideo',
			'qq.com'           => '_webVideo',
			'yinyuetai.com'    => '_webVideo'
		); //注册引用解析

		$path =  pathinfo($url);
		$ext = $path['extension']; //页面后缀
		$domain = $this->getDomain($url);//引用页地址

		//在约束数组中查找
		if (array_key_exists($domain, $sitelist)) {   //网页解析
			$data = $this->$sitelist[$domain]($url);
		}else{
			$data = $this->$sitelist[$domain]($url);
		}


		if(is_array($data)){

			if($desc != ''){$data['desc'] = $desc;}
			if($data['type'] == '' || $data['id'] == '' || $data['img'] == '' || $data['title'] == '')
			{
				return array('error'=>'解析的内容不全，请更换网站');
			}
			return $data;
		}else{
			return array('error'=>'无法获取地址,请更换一个链接');
		}

	}

	/*解析豆瓣电影*/
	public function setmovie($url){
		if(!function_exists('curl_init')){
			return array('error'=>'服务器不支持curl扩展,无法使用本功能');
		}

		$domain = $this->getDomain($url);//引用页地址
		if($domain != 'douban.com'){
			return array('error'=>'电影解析只支持豆瓣地址');
		}
		$data = $this->_webMovieDouban($url);
		if(is_array($data)){

			if($desc != ''){$data['desc'] = $desc;}
			if($data['type'] == '' || $data['url'] == '' || $data['img'] == '' || $data['title'] == '')
			{
				return array('error'=>'解析的内容不全，请稍后尝试');
			}
			return $data;
		}else{
			return array('error'=>'电影解析只支持豆瓣地址');
		}
		return $rs;
	}

	/*获取视频内容主方法*/
	function _webVideo($url){
		if($info = VideoUrlParser::parse($url)){
			return $info;
		}
		return false;
	}


	/*获取商品解析*/
	function setShopdesc($url,$desc='')
	{
		$sitelist = array('taobao.com'=>'_webShopTaobao',
						  'tmall.com'=>'_webShopTaobao',
						  '360buy.com'=>'_webShop360buy',
						  'vancl.com'=>'_webShopVancl',
						  'dangdang.com'=>'_webShopDangdang',
						  'amazon.cn'=>'_webShopAmazon'
		); //注册引用解析
		$path =  pathinfo($url);
		$ext = $path['extension']; //页面后缀
		$domain = $this->getDomain($url);//引用页地址
		if (array_key_exists($domain, $sitelist)) {   //网页解析
			$data = $this->$sitelist[$domain]($url);
		}
		if(is_array($data))
		{
			return $data;
		}else{
			return array('error'=>'我们无法解析这个地址,请尝试更换方法');
		}
	}

	/*设置自定义的referer*/
	public function getRefereData($url,$refere)
	{
		return $this->formPost($url,'',$refere);
	}

	/*引用解析器 解析MP3*/
	private function _webMusicGetMp3($url)
	{
		$base = pathinfo($url);
		return array('type'=>'weblink','id'=>$url,'url'=>$url,'img'=>'','title'=>urldecode($base['basename']));
	}

	private function _webMovieDouban($url){
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['type'] = 'douban';
		$data['url'] = $url;
		$data['img'] = $html->find('img[rel=v:image]',0)->src;
		$data['title'] = $html->find('span[property=v:itemreviewed]',0)->innertext;
		$data['directe'] = $html->find('a[rel=v:directedBy]',0)->innertext;
		$data['average'] = $html->find('strong[property=v:average]',0)->innertext;
		$data['runtime'] = $html->find('span[property=v:runtime]',0)->innertext;
		$data['initialReleaseDate']    = $html->find('span[property=v:initialReleaseDate]',0)->innertext;
		$data['summary'] = $html->find('span[property=v:summary]',0)->innertext;
		$data['summary'] = str_replace('<span class="pl">&nbsp; &nbsp;<a href="http://www.douban.com/about?topic=copyright"> &copy; 豆瓣</a></span>','',$data['summary']);
		$movieurl = $html->find('#mainpic a.trail_link',0)->href;
		if($movieurl){
			//$movieurl = parse_url($movieurl);
			//$movieurl = explode('/',$movieurl['path']);
			$data['movie'] = $movieurl;
		}

		foreach($html->find('span[property=v:genre]') as $element){
			$data['genre'][] =  $element->innertext;
		}

		foreach($html->find('a[rel=v:starring]') as $element){
			$data['starring'][] =  $element->innertext;
		}
		return $data;
	}


	/*引用解析器 解析雅燃*/
	private function _webMusicGetYinyueyun($url){
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['type'] = 'musicyun';
		$data['id'] = pathinfo($url,PATHINFO_BASENAME);
		$data['img'] = 'http://http://www.musicyun.com'.$html->find('#conter img',0)->src;
		$data['title'] = $html->find('.tracktitle a',0)->innertext;
		return $data;
	}

	/*获取淘宝商品
	正常页面没问题
	*/
	private function _webShopTaobao($url){
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['title'] =  iconv('GB2312', 'UTF-8',  $html->find('.tb-detail-hd h3 a',0)->innertext );
		if($data['title'] == '' ){ return false; }
		$data['price'] =  $html->find('#J_StrPrice',0)->innertext;
		if($data['price'] == ''){$html->find('#J_SpanLimitProm',0)->innertext;}
		$data['count'] =   $html->find('.tb-sold-out em',0)->innertext;
		$data['img'] = $html->find('#J_ImgBooth',0)->src;
		$data['type'] = 'taobao';
		$data['url'] = $url;

		print_r($data);

		return $data;
	}

	/*360 未完成*/
	private function _webShop360buy($url)
	{
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['title'] =  iconv('GB2312', 'UTF-8',  $html->find('#name h1',0)->innertext );
		$data['price'] =  $html->find('.fl .price img',0)->innertext;
		$data['count'] =  $html->find('#star532356 a',0)->innertext;
		#star532356 a
		return $data;
	}

	/*凡客 正常页面没问题
	http://item.vancl.com/0159148.html?ref=s_category_1516_1
	*/
	private function _webShopVancl($url)
	{
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['title'] =   strreplaces(strip_tags($html->find('#productname',0)->innertext));
		$data['price'] =  $html->find('.cuxiaoPrice span strong',0)->innertext;
		$data['count'] =  str_replace(array('(',')'),array('',''),$html->find('.RsetTabMenu strong',0)->innertext);
		$data['img'] = $html->find('#midimg',0)->src;
		$data['type'] = 'vancl';
		return $data;
	}

	/*未完成*/
	private function _webShopDangdang($url)
	{
		import('htmlDomNode.php');
		$html = file_get_html($url);

		$data['title'] =  print_r($html->find('title',0));
		//$data['price'] =  str_replace('￥','',$html->find('.price_d span',0)->innertext);
		//$data['count'] = intval($html->find('.RsetTabMenu strong',0)->innertext);
		//$data['img'] = $html->find('div .pic img',0)->src;
		$data['type'] = 'dangdang';
		return $data;
	}

	private function _webShopAmazon($url)
	{
		import('htmlDomNode.php');
		$html = file_get_html($url);
		$data['title'] =  print_r($html->find('title',0));
		//$data['price'] =  str_replace('￥','',$html->find('.price_d span',0)->innertext);
		//$data['count'] = intval($html->find('.RsetTabMenu strong',0)->innertext);
		//$data['img'] = $html->find('div .pic img',0)->src;
		$data['type'] = 'amazon';
		return $data;;

	}

	private function _webMusicGetOther($url){
		$urlArr = parse_url($url);
		$domainArr = explode('.',$urlArr['host']);
		$data['type'] = $domainArr[count($domainArr)-2];
		$str = $this->formPost('http://share.pengyou.com/index.php?mod=usershare&act=geturlinfo',array('url'=>$url));
		$arr = json_decode($str,true);
		$data['id'] = $arr['result']['flash'];
		$data['title'] = $arr['result']['title'];
		$data['img'] = $arr['result']['coverurl'];
		$data['url'] = $url;
		if($data['title'] == '') return false;
		return $data;
	}

	/*获取域名*/
	private function getDomain($url){
		$pattern = "/[\w-]+\.(com|net|org|gov|cc|biz|info|cn|co)(\.(cn|hk))*/";
		preg_match($pattern, $url, $matches);
		if(count($matches)>0)
		{
			return $matches[0];
		}else{
		$rs = parse_url($url);
		$main_url = $rs["host"];

			if(!strcmp((sprintf("%u",ip2long($main_url))),$main_url)) {
				return $main_url;
			}else{
				$arr = explode(".",$main_url);
				$count=count($arr);
				$endArr = array("com","net","org","3322");//com.cn  net.cn 等情况

				if (in_array($arr[$count-2],$endArr)){
					$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
				}else{
					$domain =  $arr[$count-2].".".$arr[$count-1];
				}
			return $domain;
			}
		}
	}

	private function formPost($url,$post_data,$referer='')
	{
		if(is_array($post_data))
		{
			$o='';
			foreach ($post_data as $k=>$v){
				$o.= "$k=".urlencode($v)."&";
			}
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		if($referer){curl_setopt($ch, CURLOPT_REFERER, $referer);}
		if($post_data)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);

		$result = curl_exec($ch);
		return $result;
	}
}

class VideoUrlParser{
    const USER_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko)
        Chrome/8.0.552.224 Safari/534.10";
    const CHECK_URL_VALID = "/(youku\.com|yinyuetai\.com|tudou\.com|ku6\.com|56\.com|letv\.com|video\.sina\.com\.cn|(my\.)?tv\.sohu\.com|v\.qq\.com)/";

    /**
     * parse
     *
     * @param string $url
     * @param mixed $createObject
     * @static
     * @access public
     * @return void
     */
    static public function parse($url='', $createObject=true){
        $lowerurl = strtolower($url);
        preg_match(self::CHECK_URL_VALID, $lowerurl, $matches);

        if(!$matches) return false;

        switch($matches[1]){
        case 'youku.com':
            $data = self::_parseYouku($url);
            break;
        case 'tudou.com':
            $data = self::_parseTudou($url);
            break;
        case 'ku6.com':
            $data = self::_parseKu6($url);
            break;
        case '56.com':
            $data = self::_parse56($url);
            break;
        case 'video.sina.com.cn':
            $data = self::_parseSina($url);
            break;
        case 'my.tv.sohu.com':
        case 'tv.sohu.com':
        case 'sohu.com':
            $data = self::_parseSohu($url);
            break;
        case 'v.qq.com':
            $data = self::_parseQq($url);
            break;
		case 'yinyuetai.com':
            $data = self::_parseYinyuetai($url);
            break;
        default:
            $data = false;
        }

        if($data && $createObject) $data['object'] = "<embed src=\"{$data['swf']}\" quality=\"high\" width=\"480\" height=\"400\" align=\"middle\" allowNetworking=\"all\" allowScriptAccess=\"always\" type=\"application/x-shockwave-flash\"></embed>";
        return $data;
    }
    /**
     * 腾讯视频  腾讯的有问题。。。
     * http://v.qq.com/cover/o/o9tab7nuu0q3esh.html?vid=97abu74o4w3_0
     * http://v.qq.com/play/97abu74o4w3.html
     * http://v.qq.com/cover/d/dtdqyd8g7xvoj0o.html
     * http://v.qq.com/cover/d/dtdqyd8g7xvoj0o/9SfqULsrtSb.html
     * http://imgcache.qq.com/tencentvideo_v1/player/TencentPlayer.swf?_v=20110829&vid=97abu74o4w3&autoplay=1&list=2&showcfg=1&tpid=23&title=%E7%AC%AC%E4%B8%80%E7%8E%B0%E5%9C%BA&adplay=1&cid=o9tab7nuu0q3esh
     */
    private function _parseQq($url){
        if(preg_match("/\/play\//", $url)){
            $html = self::_fget($url);
            preg_match("/url=[^\"]+/", $html, $matches);
            if(!$matches); return false;
            $url = $matches[0];
        }
        preg_match("/vid=([^\_]+)/", $url, $matches);
        $vid = $matches[1];
        $html = self::_fget($url);
        // query
        preg_match("/flashvars\s=\s\"([^;]+)/s", $html, $matches);
        $query = $matches[1];

        if(!$vid){
            preg_match("/vid\s?=\s?vid\s?\|\|\s?\"(\w+)\";/i", $html, $matches);
            $vid = $matches[1];
        }

        $query = str_replace('"+vid+"', $vid, $query);
        parse_str($query, $output);
        $data['img']   = "http://vpic.video.qq.com/{$$output['cid']}/{$vid}_1.jpg";
        $data['url']   = $url;
        $data['title'] = $output['title'];
        $data['id']    = "http://imgcache.qq.com/tencentvideo_v1/player/TencentPlayer.swf?".$query;
		$data['type']  = 'qq';
        return $data;
    }

	private function _parseYinyuetai($url){

		$html = self::_cget($url);
		//$parser = 'http://www.flvcd.com/parse.php?flag=&format=&kw='.$url;
		//$source = self::_cget($parser);
		//print_r($source);
		preg_match("/<meta\sproperty=\"og:title\"\scontent=\"(.*?)\"\/>/i", $html, $title);
		preg_match("/<meta\sproperty=\"og:videosrc\"\scontent=\"(.*?)\"\/>/i", $html, $id);
		preg_match("/<meta\sproperty=\"og:url\"\scontent=\"(.*?)\"\/>/i", $html, $url);
		preg_match("/<meta\sproperty=\"og:image\"\scontent=\"(.*?)\"\/>/i", $html, $image);
		//preg_match("/clipurl\s=\s\"(.*?)\"/i", $source, $flv);
		$data['img']   = $image[1];
        $data['title'] = trim($title[1]);
        $data['url']   = $url[1];
        $data['id']    = $id[1];
		//$data['flv']  = $flv[1];
		$data['type']  = 'yinyuetai';
		return $data;
	}



    /**
     * 优酷网
     * http://v.youku.com/v_show/id_XMjI4MDM4NDc2.html
     * http://player.youku.com/player.php/sid/XMjU0NjI2Njg4/v.swf
     */
    private function _parseYouku($url){
        preg_match("#id\_(\w+)#", $url, $matches);

        if (empty($matches)){
            preg_match("#v_playlist\/#", $url, $mat);
            if(!$mat) return false;

            $html = self::_fget($url);

            preg_match("#videoId2\s*=\s*\'(\w+)\'#", $html, $matches);
            if(!$matches) return false;
        }

        $link = "http://v.youku.com/player/getPlayList/VideoIDS/{$matches[1]}/timezone/+08/version/5/source/out?password=&ran=2513&n=3";

        $retval = self::_cget($link);
        if ($retval) {
            $json = json_decode($retval, true);
            $data['img']   = $json['data'][0]['logo'];
            $data['title'] = $json['data'][0]['title'];
            $data['url']   = $url;
            $data['id']    = "http://player.youku.com/player.php/sid/{$matches[1]}/v.swf";
			$data['type']  = 'youku';
            return $data;
        } else {
            return false;
        }
    }

	private function _parseTudou($url){
        preg_match("#view/([-\w]+)/#", $url, $matches);

        if (empty($matches)) {
            if (strpos($url, "/playlist/") == false) return false;

            if(strpos($url, 'iid=') !== false){
                $quarr = explode("iid=", $lowerurl);
                if (empty($quarr[1]))  return false;
            }elseif(preg_match("#p\/l(\d+).#", $lowerurl, $quarr)){
                if (empty($quarr[1])) return false;
            }

            $html = self::_fget($url);
            $html = iconv("GB2312", "UTF-8", $html);

            preg_match("/lid_code\s=\slcode\s=\s[\'\"]([^\'\"]+)/s", $html, $matches);
            $icode = $matches[1];

            preg_match("/iid\s=\s.*?\|\|\s(\d+)/sx", $html, $matches);
            $iid = $matches[1];

            preg_match("/listData\s=\s(\[\{.*\}\])/sx", $html, $matches);

            $find = array("/\n/", '/\s/', "/:[^\d\"]\w+[^\,]*,/i", "/(\{|,)(\w+):/");
            $replace = array("", "", ':"",', '\\1"\\2":');
            $str = preg_replace($find, $replace, $matches[1]);
            //var_dump($str);
            $json = json_decode($str);
            //var_dump($json);exit;
            if(is_array($json) || is_object($json) && !empty($json)){
                foreach ($json as $val) {
                    if ($val->iid == $iid) {
                        break;
                    }
                }
            }

            $data['img'] = $val->pic;
            $data['title'] = $val->title;
            $data['url'] = $url;
            $data['id'] = "http://www.tudou.com/l/{$icode}/&iid={$iid}/v.swf";
			$data['type'] = 'tudou';
            return $data;
        }

        $host = "www.tudou.com";
        $path = "/v/{$matches[1]}/v.swf";

        $ret = self::_fsget($path, $host);

        if (preg_match("#\nLocation: (.*)\n#", $ret, $mat)) {
            parse_str(parse_url(urldecode($mat[1]), PHP_URL_QUERY));

            $data['img'] = $snap_pic;
            $data['title'] = $title;
            $data['url'] = $url;
            $data['id'] = "http://www.tudou.com/v/{$matches[1]}/v.swf";
			$data['type'] = 'tudou';

            return $data;
        }
        return false;
    }

    /**
     * 酷6网
     * http://v.ku6.com/film/show_520/3X93vo4tIS7uotHg.html
     * http://v.ku6.com/special/show_4926690/Klze2mhMeSK6g05X.html
     * http://v.ku6.com/show/7US-kDXjyKyIInDevhpwHg...html
     * http://player.ku6.com/refer/3X93vo4tIS7uotHg/v.swf
     */
    private function _parseKu6($url){
        if(preg_match("/show\_/", $url)){
            preg_match("#/([-\w]+)\.html#", $url, $matches);
            $url = "http://v.ku6.com/fetchVideo4Player/{$matches[1]}.html";
            $html = self::_fget($url);

            if ($html) {
                $json = json_decode($html, true);
                if(!$json) return false;

                $data['img'] = $json['data']['picpath'];
                $data['title'] = $json['data']['t'];
                $data['url'] = $url;
                $data['id'] = "http://player.ku6.com/refer/{$matches[1]}/v.swf";
				$data['type'] = 'ku6';
                return $data;
            } else {
                return false;
            }
        }elseif(preg_match("/show\//", $url, $matches)){
            $html = self::_fget($url);
            preg_match("/ObjectInfo\s?=\s?([^\n]*)};/si", $html, $matches);
            $str = $matches[1];
            // img
            preg_match("/cover\s?:\s?\"([^\"]+)\"/", $str, $matches);
            $data['img'] = $matches[1];
            // title
            preg_match("/title\"?\s?:\s?\"([^\"]+)\"/", $str, $matches);
            $jsstr = "{\"title\":\"{$matches[1]}\"}";
            $json = json_decode($jsstr, true);
            $data['title'] = $json['title'];
            // url
            $data['url'] = $url;
            // query
            preg_match("/\"(vid=[^\"]+)\"\sname=\"flashVars\"/s", $html, $matches);
            $query = str_replace("&amp;", '&', $matches[1]);
            preg_match("/\/\/player\.ku6cdn\.com[^\"\']+/", $html, $matches);
            $data['id'] = 'http:'.$matches[0].'?'.$query;
			$data['type'] = 'ku6';

            return $data;
        }
    }

    /**
     * 56网
     * http://www.56.com/u73/v_NTkzMDcwNDY.html
     * http://player.56.com/v_NTkzMDcwNDY.swf
     */
    private function _parse56($url){
        preg_match("#/v_(\w+)\.html#", $url, $matches);

        if (empty($matches)) return false;

        $link="http://vxml.56.com/json/{$matches[1]}/?src=out";
        $retval = self::_cget($link);


        if ($retval) {
            $json = json_decode($retval, true);

            $data['img'] = $json['info']['img'];
            $data['title'] = $json['info']['Subject'];
            $data['url'] = $url;
            $data['id'] = "http://player.56.com/v_{$matches[1]}.swf";
			$data['type'] = 'video56';

            return $data;
        } else {
            return false;
        }
    }



    // 搜狐TV http://my.tv.sohu.com/u/vw/5101536
    private function _parseSohu($url){
        $html = self::_fget($url);
        $html = iconv("GB2312", "UTF-8", $html);
        preg_match_all("/og:(?:title|image|videosrc)\"\scontent=\"([^\"]+)\"/s", $html, $matches);
        $data['img'] = $matches[1][1];
        $data['title'] = $matches[1][0];
        $data['url'] = $url;
        $data['id'] = $matches[1][2];
		$data['type'] = 'sohu';
        return $data;
    }

    /*
     * 新浪播客
     * http://video.sina.com.cn/v/b/48717043-1290055681.html
     * http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=48717043_1290055681_PUzkSndrDzXK+l1lHz2stqkP7KQNt6nki2O0u1ehIwZYQ0/XM5GdatoG5ynSA9kEqDhAQJA4dPkm0x4/s.swf
     */
    private function _parseSina($url){
        preg_match("/(\d+)(?:\-|\_)(\d+)/", $url, $matches);
        $url = "http://video.sina.com.cn/v/b/{$matches[1]}-{$matches[2]}.html";
        $html = self::_fget($url);
        preg_match("/video\s?:\s?([^<]+)}/", $html, $matches);
        $find = array("/\n/", "/\s*/", "/\'/", "/\{([^:,]+):/", "/,([^:]+):/", "/:[^\d\"]\w+[^\,]*,/i");
        $replace = array('', '', '"', '{"\\1":', ',"\\1":', ':"",');
        $str = preg_replace($find, $replace, $matches[1]);
        $arr = json_decode($str, true);

        $data['img'] = $arr['pic'];
        $data['title'] = $arr['title'];
        $data['url'] = $url;
        $data['id'] = $arr['swfOutsideUrl'];
		$data['type'] = 'sina';

        return $data;
    }

    /*
     * 通过 file_get_contents 获取内容
     */
    private function _fget($url=''){
        if(!$url) return false;
        $html = file_get_contents($url);
        // 判断是否gzip压缩
        if($dehtml = self::_gzdecode($html))
            return $dehtml;
        else
            return $html;
    }

    /*
     * 通过 fsockopen 获取内容
     */
    private function _fsget($path='/', $host='', $user_agent=''){
        if(!$path || !$host) return false;
        $user_agent = $user_agent ? $user_agent : self::USER_AGENT;

        $out = <<<HEADER
GET $path HTTP/1.1
Host: $host
User-Agent: $user_agent
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-cn,zh;q=0.5
Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n\r\n
HEADER;
        $fp = @fsockopen($host, 80, $errno, $errstr, 10);
        if (!$fp)  return false;
        if(!fputs($fp, $out)) return false;
        while ( !feof($fp) ) {
            $html .= fgets($fp, 1024);
        }
        fclose($fp);
        // 判断是否gzip压缩
        if($dehtml = self::_gzdecode($html))
            return $dehtml;
        else
            return $html;
    }

    /*
     * 通过 curl 获取内容
     */
    private function _cget($url='', $user_agent=''){
        if(!$url) return;

        $user_agent = $user_agent ? $user_agent : self::USER_AGENT;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if(strlen($user_agent)) curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        ob_start();
        curl_exec($ch);
        $html = ob_get_contents();
        ob_end_clean();

        if(curl_errno($ch)){
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        if(!is_string($html) || !strlen($html)){
            return false;
        }
        return $html;
        // 判断是否gzip压缩
        if($dehtml = self::_gzdecode($html))
            return $dehtml;
        else
            return $html;
    }

    private function _gzdecode($data) {
        $len = strlen ( $data );
        if ($len < 18 || strcmp ( substr ( $data, 0, 2 ), "\x1f\x8b" )) {
            return null; // Not GZIP format (See RFC 1952)
        }
        $method = ord ( substr ( $data, 2, 1 ) ); // Compression method
        $flags = ord ( substr ( $data, 3, 1 ) ); // Flags
        if ($flags & 31 != $flags) {
            // Reserved bits are set -- NOT ALLOWED by RFC 1952
            return null;
        }
        // NOTE: $mtime may be negative (PHP integer limitations)
        $mtime = unpack ( "V", substr ( $data, 4, 4 ) );
        $mtime = $mtime [1];
        $xfl = substr ( $data, 8, 1 );
        $os = substr ( $data, 8, 1 );
        $headerlen = 10;
        $extralen = 0;
        $extra = "";
        if ($flags & 4) {
            // 2-byte length prefixed EXTRA data in header
            if ($len - $headerlen - 2 < 8) {
                return false; // Invalid format
            }
            $extralen = unpack ( "v", substr ( $data, 8, 2 ) );
            $extralen = $extralen [1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                return false; // Invalid format
            }
            $extra = substr ( $data, 10, $extralen );
            $headerlen += 2 + $extralen;
        }

        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string file NAME data in header
            if ($len - $headerlen - 1 < 8) {
                return false; // Invalid format
            }
            $filenamelen = strpos ( substr ( $data, 8 + $extralen ), chr ( 0 ) );
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                return false; // Invalid format
            }
            $filename = substr ( $data, $headerlen, $filenamelen );
            $headerlen += $filenamelen + 1;
        }

        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data in header
            if ($len - $headerlen - 1 < 8) {
                return false; // Invalid format
            }
            $commentlen = strpos ( substr ( $data, 8 + $extralen + $filenamelen ), chr ( 0 ) );
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                return false; // Invalid header format
            }
            $comment = substr ( $data, $headerlen, $commentlen );
            $headerlen += $commentlen + 1;
        }

        $headercrc = "";
        if ($flags & 1) {
            // 2-bytes (lowest order) of CRC32 on header present
            if ($len - $headerlen - 2 < 8) {
                return false; // Invalid format
            }
            $calccrc = crc32 ( substr ( $data, 0, $headerlen ) ) & 0xffff;
            $headercrc = unpack ( "v", substr ( $data, $headerlen, 2 ) );
            $headercrc = $headercrc [1];
            if ($headercrc != $calccrc) {
                return false; // Bad header CRC
            }
            $headerlen += 2;
        }

        // GZIP FOOTER - These be negative due to PHP's limitations
        $datacrc = unpack ( "V", substr ( $data, - 8, 4 ) );
        $datacrc = $datacrc [1];
        $isize = unpack ( "V", substr ( $data, - 4 ) );
        $isize = $isize [1];

        // Perform the decompression:
        $bodylen = $len - $headerlen - 8;
        if ($bodylen < 1) {
            // This should never happen - IMPLEMENTATION BUG!
            return null;
        }
        $body = substr ( $data, $headerlen, $bodylen );
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8 :
                    // Currently the only supported compression method:
                    $data = gzinflate ( $body );
                    break;
                default :
                    // Unknown compression method
                    return false;
            }
        } else {
            //...
        }

        if ($isize != strlen ( $data ) || crc32 ( $data ) != $datacrc) {
            // Bad format!  Length or CRC doesn't match!
            return false;
        }
        return $data;
    }
}


?>