<?php

/**
 * Goofy 2011-11-30
 * getDir()ȥ�ļ����б�getFile()ȥ��Ӧ�ļ���������ļ��б�,���ߵ����������ж���û�С�.����׺���ļ���������һ��
 */

//��ȡ�ļ�Ŀ¼�б�,�÷�����������
function getDir($dir) {
	$dirArray[]=NULL;
	if (false != ($handle = opendir ( $dir ))) {
		$i=0;
		while ( false !== ($file = readdir ( $handle )) ) {
			//ȥ��"��.������..���Լ�����.xxx����׺���ļ�
			if ($file != "." && $file != ".."&&!strpos($file,".")) {
				$dirArray[$i]=$file;
				$i++;
			}
		}
		//�رվ��
		closedir ( $handle );
	}
	return $dirArray;
}

//��ȡ�ļ��б�
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

//���÷���getDir("./dir")����
?> 

