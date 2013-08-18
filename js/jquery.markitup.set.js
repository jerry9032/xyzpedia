// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2011 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
var mySettings = {
	onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:  	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>'},
	onTab:    		{keepDefault:false, replaceWith:'    '},
	markupSet:  [ 	
		{name:'加粗', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
		{name:'斜体', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
		{name:'下划线', key:'U', openWith:'<u>', closeWith:'</u>' },
		{separator:'---------------' },
		{name:'无序列表', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>'},
		{name:'有序列表', openWith:'    <li>', closeWith:'</li>', multiline:true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>'},
		{separator:'---------------' },
		{name:'插入图片', key:'P', replaceWith:'<img src="[![图片地址:!:http://]!]" alt="[![说明文字]!]" />' },
		{name:'插入链接', key:'L', openWith:'<a href="[![链接地址:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
		{separator:'---------------' },
		{name:'居中', key:'G', openWith:'<center>', closeWith:'</center>' },
		//{name:'Clean', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },		
		//{name:'Preview', className:'preview',  call:'preview'}
	]
}
