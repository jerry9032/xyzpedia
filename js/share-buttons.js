document.write( "<strong>分享到：</strong>" );

var _w = 72 , _h = 16;
var param = {
	url: location.href,
	type: '3',
	count: '1',
	appkey: '560274513',
	title: post_title + " - " + post_excerpt,
	pic: post_thumbnail,
	ralateUid:'2182339254',
	language:'zh_cn',
	rnd: new Date().valueOf()
}
var temp = [];
for( var p in param ){
	temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
}
document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')

document.write( "<div class=\"share-button\"><a href=\"javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})()\"><img src=\"http://img2.douban.com/pics/fw2douban_s.png\" alt=\"推荐到豆瓣\" title=\"分享到豆瓣\" /></a></div>" );

document.write( "<div class=\"share-button\"><a rel=\"nofollow\" class=\"fav_renren\" href=\"javascript:window.open('http://share.renren.com/share/buttonshare.do?link='+encodeURIComponent(document.location.href)+'&title='+encodeURIComponent(document.title));void(0)\"><img src=\"/images/renren.png\" title=\"分享到人人网\" alt=\"推荐到人人\" /></a></div>" );

document.write( "<div class=\"share-button\"><a href=\"javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(kaixin=window.open('http://t.sohu.com/third/post.jsp?&url='+escape(d.location.href)+'&amp;title='+escape(d.title)+'&amp;rcontent='+escape(d.title),'kaixin'));kaixin.focus();\"><img src='/images/sohu.png' title='分享到搜狐微博' alt='搜狐微博' border='0' height='16' width='16'></a></div>" );

document.write( "<div class='share-button'><a href='http://twitter.com/home?status="+ post_title+ " "+ post_permalink +"' title='分享到 Twitter' target='_blank' rel='nofollow'><img src='/images/twitter.gif'></a></div>" );