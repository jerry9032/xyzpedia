<?php 

if (! session_id ()) {
	session_start ();
}

	function getFile($dir) {
		$fileArray[]=NULL;
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".."&&strpos($file,".")) {
					
					$fileArray[$i]="./imageroot/current/".$file;
					
					if($i==100){
						break;
					}
					$i++;
				}
			}
			//关闭句柄
			closedir ( $handle );
		}
		return $fileArray;
	}
	$url="";
	if(!empty($_SESSION['url'])){
		$url=$_SESSION['url'];
	}else{
		$urls=getFile("imageroot/current");
		if(count($urls)==1){
			echo "<script>alert('所有图片修改完成');</script>";
			echo "<script>window.close;</script>";
		}else if(count($urls)==2){
			$url=$urls[0];
		}else{
			$url=$urls[rand(1,count($urls))-1];
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=GB2312" /> 
		<title>截图工具</title>
		<script src="js/jquery.min.js" type="text/javascript"></script>
		<script src="js/jquery.Jcrop.js" type="text/javascript"></script>
		<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="css/imgtool.css" type="text/css" />
		<script type="text/javascript">

		jQuery(function($){

      $('#target').Jcrop({
        onChange:   showCoords,
        onSelect:   showCoords,
        onRelease:  clearCoords
      });

    });

    // Simple event handler, called from onChange and onSelect
    // event handlers, as per the Jcrop invocation above
    function showCoords(c)
    {
      $('#x1').val(c.x);
      $('#y1').val(c.y);
      $('#x2').val(c.x2);
      $('#y2').val(c.y2);
      $('#w').val(c.w);
      $('#h').val(c.h);
    };

    function clearCoords()
    {
      $('#coords input').val('');
      $('#h').css({color:'red'});
      window.setTimeout(function(){
        $('#h').css({color:'inherit'});
      },500);
    };

	//等比例缩小图片,并且计算出比例，否在在服务器端截图时候会错位
    function DrawImage(ImgD,FitWidth,FitHeight){
        var image=new Image();
        image.src=ImgD.src;
        if(image.width>0 && image.height>0){
            if(image.width/image.height>= FitWidth/FitHeight){
                if(image.width>FitWidth){
                    ImgD.width=FitWidth;
                    ImgD.height=(image.height*FitWidth)/image.width;
                }else{
                    ImgD.width=image.width; 
                   ImgD.height=image.height;
                }
                $("#scale").val(image.width/ImgD.width);
            } else{
                if(image.height>FitHeight){
                    ImgD.height=FitHeight;
                    ImgD.width=(image.width*FitHeight)/image.height;
                }else{
                    ImgD.width=image.width; 
                   ImgD.height=image.height;
                } 
                $("#scale").val(image.width/ImgD.width);
           }
        }
    }
	function check(){
			if($("#x1").val()==""||$("#src").val()==""){
				alert("请选择区域");
				return false;
			}
			return true;
		}
	</script>
	</head>
	<body>
	<div id="outer">
	<div class="jcExample">
	<div class="article" >
		<img src="<?=$url ?>" alt="target" id="target" onload="javascript:DrawImage(this,500,450);"/>
		
		<form id="coords"  class="coords" action="imgresize.php" method="get" onsubmit="return check();">
	      <div>
	      		<!-- 起始位置的x坐标 -->
				<label>X<input type="text" size="10" id="x1" name="x" readonly/></label>
				<!-- 起始位置的y坐标 -->
				<label>Y<input type="text" size="10" id="y1" name="y" readonly/></label>
				<!-- 宽 -->
				<label>宽<input type="text" size="10" id="w" name="w" readonly/></label>
				<!-- 高 -->
				<label>高<input type="text" size="10" id="h" name="h" readonly/></label>
                <input type="hidden" size="10" id="src" name="src" value="<?=$url ?>" />
                <br />
               	<label>源图片/当前图片=<input type="text" size="10" id="scale" name="scale" readonly/></label>
               	<label>继续在此张图片截图<input name="again" type="checkbox" /></label>
			<label>
				<input type="submit" value="保存" style="height:50px; width:200px; font-size:24px; background-color:green;border:green thin solid;"/>
			</label>
	      </div>
		</form>
	</div>
	</div>
	</div>
	</body>

</html>
