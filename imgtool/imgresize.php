<?php

/**
 * Goofy 2011-11-29
 * ͼ�������ݴ��ݹ��������������x,y,w,h,����Ϊѡȡ��x���꣬y���꣬w��ȣ�h�߶�
 * ͨ��imagecopy()������������copy����һ�������Ŀհ�ͼ����
 * ע�⣬�ڴ���ͼ���ʱ��Ҫ��imagecreatetruecolor()���ɫ����Ȼ��imagecreate()ͼƬ��ʧ��
 */

if (! session_id ()) {
	session_start ();
}

//ҳ�洫�����ı���
$scale=$_GET['scale'];
//��������Գ�����Ӧ�ı���
$x=$_GET['x']*$scale;
$y=$_GET['y']*$scale;
$w=$_GET['w']*$scale;
$h=$_GET['h']*$scale;

//Դ·��
$src=$_GET['src'];

//�Ƿ���������������������ͼƬ��ͼ���ὫԴͼƬɾ��
$again="off";
if(!empty($_GET['again'])){
	$again=$_GET['again'];
}
echo $again;
//��һ�������ݴ����Ŀ��߲�������һ��ͼƬ��Ȼ�����ý���ȡ�Ĳ��������䵽�������
header("Content-type: image/jpeg");
$target = @imagecreatetruecolor($w,$h)
    or die("Cannot Initialize new GD image stream");

//�ڶ���������·����ȡ��Դͼ��,��Դͼ�񴴽�һ��image����
$source = imagecreatefromjpeg($src);

//�����������ݴ����Ĳ�����ѡȡԴͼ���һ������䵽��һ��������ͼ����
imagecopy( $target, $source, 0, 0, $x, $y, $w, $h);

//���Ĳ�,����ͼ��
	//��ȡ����֯��·��
$pos_path= strripos($src, "/");
$newPath=substr($src,0,$pos_path-strlen($src))."_new/";
	//��ȡ����֯������
$pos_name=strripos($src, ".");
$newName=substr($src,0,$pos_name);
$pos_name_= strripos($newName, "/");
	//������ʱ���Ӻ�׺��.jpg������ֹ���ظ����ļ�������У���Ҫ������,���˺�ڲ�����
$newName=substr($newName,$pos_name_-strlen($newName)+1)."_";
	//���ɲ�����׺��ͼƬ
$file=$newPath.$newName;

//����asc���������ļ�
for($i=0;$i<26;$i++){
	//�ж�Դ�ļ��Ƿ���ڣ����ڶ����޸�һ��ͼƬ��ɵĲ���Ԥ֪�Ľ�����и���
	if(!is_file($src)){
		break;
	}
	//���Ŀ¼����
	if(is_dir($newPath)){
		//����ļ����ڣ�����ѭ����ֱ��û���������ļ�
		if(is_file($file.chr(97+$i).".jpg")){
			continue;
		}else{
			//�����ļ�
			imagejpeg($target,$file.chr(97+$i).".jpg",100);
			//�ļ��������ȷ���Ƿ�ɾ��Դ�ļ�,offΪɾ��Դ�ļ�
			if($again=="off"){
				unlink($src);
				unset($_SESSION['url']);
			}else{
				$_SESSION["url"]=$src;
			}
			
			break;
			
		}
	}else{
		//����Ŀ¼
		mkdir($newPath);
		//�����ļ�
		imagejpeg($target,$file.chr(97+$i).".jpg",100);
		//�ļ��������ȷ���Ƿ�ɾ��Դ�ļ�,offΪɾ��Դ�ļ�
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