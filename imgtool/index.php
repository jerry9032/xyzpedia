<?php 

if (! session_id ()) {
	session_start ();
}

	function getFile($dir) {
		$fileArray[]=NULL;
		if (false != ($handle = opendir ( $dir ))) {
			$i=0;
			while ( false !== ($file = readdir ( $handle )) ) {
				//ȥ��"��.������..���Լ�����.xxx����׺���ļ�
				if ($file != "." && $file != ".."&&strpos($file,".")) {
					
					$fileArray[$i]="./imageroot/current/".$file;
					
					if($i==100){
						break;
					}
					$i++;
				}
			}
			//�رվ��
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
			echo "<script>alert('����ͼƬ�޸����');</script>";
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
		<title>��ͼ����</title>
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

	//�ȱ�����СͼƬ,���Ҽ���������������ڷ������˽�ͼʱ����λ
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
				alert("��ѡ������");
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
	      		<!-- ��ʼλ�õ�x���� -->
				<label>X<input type="text" size="10" id="x1" name="x" readonly/></label>
				<!-- ��ʼλ�õ�y���� -->
				<label>Y<input type="text" size="10" id="y1" name="y" readonly/></label>
				<!-- �� -->
				<label>��<input type="text" size="10" id="w" name="w" readonly/></label>
				<!-- �� -->
				<label>��<input type="text" size="10" id="h" name="h" readonly/></label>
                <input type="hidden" size="10" id="src" name="src" value="<?=$url ?>" />
                <br />
               	<label>ԴͼƬ/��ǰͼƬ=<input type="text" size="10" id="scale" name="scale" readonly/></label>
               	<label>�����ڴ���ͼƬ��ͼ<input name="again" type="checkbox" /></label>
			<label>
				<input type="submit" value="����" style="height:50px; width:200px; font-size:24px; background-color:green;border:green thin solid;"/>
			</label>
	      </div>
		</form>
	</div>
	</div>
	</div>
	</body>

</html>
