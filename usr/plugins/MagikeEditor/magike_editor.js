/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net

	modify by Hanny: http://www.imhan.com
********************************************/   
var start = 0;
var end = 0;
var hasPrepare = false;
var magikeTextareaScrolltop = 0;
var range = null;
var magikeTextarea;
var editorDraftChange = false;

var magikeToolbar;
var magikeEditor;
var insertImageToEditor;
var insertLinkToEditor;

function addButtonCtx(button_ctx)
{
	jQuery(button_ctx).appendTo(magikeToolbar);
}

function addButton(label ,left_tag, right_tag, accesskey)
{
	addButtonCtx("<button type=\"button\" accesskey=\"" + accesskey + "\" onclick=\"editorAdd('" + left_tag + "','" + right_tag + "');\">" + label + "</button>");
}

//创建工具栏区
function createToolbar()
{
	jQuery("<p class=\"toolbarspace\"></p>").prependTo(magikeTextarea.parentNode);
	jQuery("<div id=\"tttt\" class=\"toolbar\"></div>").prependTo(magikeTextarea.parentNode);
	magikeToolbar = magikeTextarea.parentNode.firstChild;
}

function createDefaultButtons()
{
	addButton('<strong>B</strong>', '<strong>', '</strong>', 'b');
	addButton('<em>I</em>', '<em>', '</em>', 'i');
	addButton('<del>del</del>', '<del>', '</del>', 'd');
	addButton('quote', '<blockquote>', '</blockquote>', 'ctrl+b');
	addButton('code', '<code>', '</code>', 'c');
	addButtonCtx("<button type=\"button\" accesskey=\"a\" style=\"color:#0000EE;text-decoration: underline;\" onclick=\"editorInsertLink('插入一个链接','资源地址','标题','目标','插入','取消','请输入一个链接URL。');\">link</button>");
	addButtonCtx("<button type=\"button\" accesskey=\"ctrl+i\" onclick=\"editorInsertImage('插入一张图片','图片地址','标题','对齐','插入','取消','请输入一个图片URL');\">img</button>");
	addButton('more', '<!--more-->', '', 'm');
}

/*
	insmode 说明
	BIT：
	0：是否安装附件管理器
		0: 未安装附件管理器
		1: 已安装附件管理器
	2~1：图片插入的方式
		0: 默认的图片插入方式
		1: 仅插入图片
		2: 插入图片+链接
		3: 保留（默认的插入方式）
*/
function initEditorInterface(elid, attime, resize, atsav, atmsgel, atmsg, rsurl, insmode)
{
	magikeEditor = new Typecho.textarea(elid, {
		autoSaveTime: attime,
		resizeAble: resize,
		autoSave: atsav,
		autoSaveMessageElement: atmsgel,
		autoSaveLeaveMessage: atmsg,
		resizeUrl: rsurl
	});
	
	attach_mode = insmode & 0x1;
	if (attach_mode) {
		//已经安装附件管理器插件
		insertLinkToEditor = function (title, url, link, cid) {
			magikeEditor.setContent('<attach>' + cid + '</attach>', '');
			new Fx.Scroll(window).toElement($(document).getElement('textarea'+elid));
		};
	} else {
		//默认的插入方式
		insertLinkToEditor = function (title, url, link, cid) {
			magikeEditor.setContent('<a href="' + url + '" title="' + title + '">' + title + '</a>', '');
			new Fx.Scroll(window).toElement($(document).getElement('textarea'+elid));
		};
	}
	
	picture_mode = (insmode >> 1) & 0x3;
	if (picture_mode == 1) {
		//仅插入图片
		insertImageToEditor = function (title, url, link, cid) {
			magikeEditor.setContent('<img src="' + url + '" alt="' + title + '" />', '');
			new Fx.Scroll(window).toElement($(document).getElement('textarea'+elid));
		};
	} else if (picture_mode == 2) {
		//插入图片链接
		insertImageToEditor = function (title, url, link, cid) {
			magikeEditor.setContent('<a href="' + url + '" title="点击查看原图" target="_blank"><img src="' + url + '" alt="' + title + '" /></a>', '');
			new Fx.Scroll(window).toElement($(document).getElement('textarea'+elid));
		};
	} else {
		//默认的插入方式
		insertImageToEditor = function (title, url, link, cid) {
			magikeEditor.setContent('<a href="' + link + '" title="' + title + '"><img src="' + url + '" alt="' + title + '" /></a>', '');
			new Fx.Scroll(window).toElement($(document).getElement('textarea'+elid));
		};
	}
	
}

function initEditor(elid, atsav, atmsg, rsurl, insmode)
{
	magikeTextarea = document.getElementById(elid);
	initEditorInterface('#'+elid, 30, true, atsav, 'auto-save-message', atmsg, rsurl, insmode);
	createToolbar();
	createDefaultButtons();
}

function editPrepare()
{
	magikeTextareaScrolltop = magikeTextarea.scrollTop;
	if(typeof(magikeTextarea.selectionStart) == "number")
	{
		magikeTextarea.focus();
		start = magikeTextarea.selectionStart;
		end = magikeTextarea.selectionEnd;
	}
	
	else if(document.selection)
	{
		magikeTextarea.focus();
		range = document.selection.createRange();
	}
	
	hasPrepare = true;
}

function editorAdd(flg1,flg2)
{
	if(!hasPrepare)
	{
		editPrepare();
	}


	editorDraftChange = true;

	if(typeof(magikeTextarea.selectionStart) == "number")
	{
		pre = magikeTextarea.value.substr(0, start);
		post = magikeTextarea.value.substr(end);
		center = magikeTextarea.value.substr(start,end-start);
		magikeTextarea.value = pre + flg1 +center+ flg2+ post;
		
		magikeTextarea.setSelectionRange(start+flg1.length,start+flg1.length);
	}

	else if(document.selection)
	{
		if(range.text.length > 0)
		{
			range.text = flg1 + range.text + flg2;
		}
		else
		{
			range.text = flg1 + flg2;
		}
	}

	setTimeout('magikeTextarea.scrollTop = magikeTextareaScrolltop',0);
	magikeTextarea.focus();
	hasPrepare = false;
	return true;
}

var editorInsertLinkError;
function editorInsertLink(popupTitle,urlWord,titleWord,openType,okText,cancelText,errorWord)
{
	editPrepare();

	div = jQuery(document.createElement("div"));
	editorInsertLinkError = errorWord;
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(urlWord);
	input = jQuery(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","url");
	input.attr("value","http://");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(titleWord);
	input = jQuery(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","title");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(openType);
	select = magikeCreateSelect({none:"",_blank:"_blank"});
	select.attr("name","link");
	p.append(span);
	p.append(select);
	div.append(p);
	
	magikeUI.createPopup({title: popupTitle,center: true,width: 400,height: 175,text:div,ok:okText,cancel:cancelText,handle:editorInsertLinkHandle});
}

function editorInsertLinkHandle()
{
	var url = jQuery("input[@name=url]",jQuery((this.parentNode).parentNode)).val();
	var ititle = jQuery("input[@name=title]",jQuery((this.parentNode).parentNode)).val();
	var link = jQuery("select[@name=link]",jQuery((this.parentNode).parentNode)).val();
	
	if(url && url != "http://")
	{
		editorAdd('<a href="' + url + '"' + (ititle ? ' title="' + ititle + '"' : '') + (link ? ' target="' + link + '"' : '') + '>','</a>');
		jQuery(((this.parentNode).parentNode).parentNode).remove();
	}
	else
	{
		alert(editorInsertLinkError);
	}
}

var editorInsertImageError;
function editorInsertImage(popupTitle,urlWord,titleWord,alignType,okText,cancelText,errorWord)
{
	editPrepare();

	div = jQuery(document.createElement("div"));
	editorInsertImageError = errorWord;
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(urlWord);
	input = jQuery(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","img_url");
	input.attr("value","http://");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(titleWord);
	input = jQuery(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","img_title");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = jQuery(document.createElement("p"));
	span = jQuery(document.createElement("span"));
	span.text(alignType);
	select = magikeCreateSelect({无:"",左:"left",中:"center",右:"right"});
	select.attr("name","img_align");
	p.append(span);
	p.append(select);
	div.append(p);
	
	magikeUI.createPopup({title: popupTitle,center: true,width: 400,height: 175,text:div,ok:okText,cancel:cancelText,handle:editorInsertImageHandle});
}

var editorInsertImageIsImage = true;
function editorInsertImageHandle()
{
	var url = jQuery("input[@name=img_url]",jQuery((this.parentNode).parentNode)).val();
	var ititle = jQuery("input[@name=img_title]",jQuery((this.parentNode).parentNode)).val();
	var align = jQuery("select[@name=img_align]",jQuery((this.parentNode).parentNode)).val();
	
	if(url && url != "http://")
	{
		if(editorInsertImageIsImage)
		{
			editorAdd('<img src="' + url + '"' + (ititle ? ' alt="' + ititle + '"' : '') + (align ? ' align="' + align + '"' : '') + '/>','');
		}
		else
		{
			editorAdd('<a href="' + url + '"' + (ititle ? ' title="' + ititle + '"' : '') + '/>','</a>');
		}
		jQuery(((this.parentNode).parentNode).parentNode).remove();
	}
	else
	{
		alert(editorInsertImageError);
	}
}