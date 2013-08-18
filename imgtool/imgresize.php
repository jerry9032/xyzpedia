<?php

/**
 * Goofy 2011-11-29
 * 图像处理：根据传递过来的坐标参数，x,y,w,h,依次为选取的x坐标，y坐标，w宽度，h高度
 * 通过imagecopy()方法将该区域copy至第一步创建的空白图像中
 * 注意，在创建图像的时候要用imagecreatetruecolor()真彩色，不然用imagecreate()图片会失真
 */

if (! session_id ()) {
	session_start ();
}

//页面传过来的比例
$scale=$_GET['scale'];
//下面的属性乘以相应的比例
$x=$_GET['x']*$scale;
$y=$_GET['y']*$scale;
$w=$_GET['w']*$scale;
$h=$_GET['h']*$scale;

//源路径
$src=$_GET['src'];

//是否继续？如果不继续在这张图片截图，会将源图片删除
$again="off";
if(!empty($_GET['again'])){
	$again=$_GET['again'];
}
echo $again;
//第一步，根据传来的宽，高参数创建一幅图片，然后正好将截取的部分真好填充到这个区域
header("Content-type: image/jpeg");
$target = @imagecreatetruecolor($w,$h)
    or die("Cannot Initialize new GD image stream");

//第二步，根据路径获取到源图像,用源图像创建一个image对象
$source = imagecreatefromjpeg($src);

//第三步，根据传来的参数，选取源图像的一部分填充到第一步创建的图像中
imagecopy( $target, $source, 0, 0, $x, $y, $w, $h);

//第四步,保存图像
	//截取并组织新路径
$pos_path= strripos($src, "/");
$newPath=substr($src,0,$pos_path-strlen($src))."_new/";
	//截取并组织新名称
$pos_name=strripos($src, ".");
$newName=substr($src,0,$pos_name);
$pos_name_= strripos($newName, "/");
	//这里暂时不加后缀“.jpg”，防止有重复的文件，如果有，需要重命名,加了后悔不方便
$newName=substr($newName,$pos_name_-strlen($newName)+1)."_";
	//生成不带后缀的图片
$file=$newPath.$newName;

//附加asc码重命名文件
for($i=0;$i<26;$i++){
	//判断源文件是否存在，对于多人修改一张图片造成的不可预知的结果进行干扰
	if(!is_file($src)){
		break;
	}
	//如果目录存在
	if(is_dir($newPath)){
		//如果文件存在，继续循环，直到没有重名的文件
		if(is_file($file.chr(97+$i).".jpg")){
			continue;
		}else{
			//创建文件
			imagejpeg($target,$file.chr(97+$i).".jpg",100);
			//文件创建完成确定是否删除源文件,off为删除源文件
			if($again=="off"){
				unlink($src);
				unset($_SESSION['url']);
			}else{
				$_SESSION["url"]=$src;
			}
			
			break;
			
		}
	}else{
		//创建目录
		mkdir($newPath);
		//创建文件
		imagejpeg($target,$file.chr(97+$i).".jpg",100);
		//文件创建完成确定是否删除源文件,off为删除源文件
		if($again=="off"){
				unlink($src);
				unset($_SESSION['url']);
		}else{
				$_SESSION["url"]=$src;
		}
		break;
	}
}
Header("Location: index.php"); 
?> 