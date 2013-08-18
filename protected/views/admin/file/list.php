
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<td>ID</td>
			<td>文件标题</td>
			<td>上传时间</td>
			<td>预计发送</td>
			<td>上传者</td>
			<td>类型</td>
			<td>点击</td>
		</tr>
	</thead>
	<tbody>
		<? foreach ($files as $file) { ?>
		<tr>
			<td><?=$file->ID?></td>
			<td>
				<abbr title="文件名：<?=$file->filename?>"><?=$file->title?></abbr>
				<a href="/download?id=<?=$file->ID?>">
					<i class="icon-download icon-blue"></i>
				</a>
			</td>
			<td><?=timeFormat($file->created, "full")?></td>
			<td><?=timeFormat($file->appoint, "full")?></td>
			<td><?=$file->uploader->nick_name?></td>
			<td><?=$file->group?></td>
			<td><a href="/admin/file/log/<?=$file->ID?>"><?=$file->hits?></a></td>
		</tr>
		<? } ?>
	</tbody>
	<tfoot>
		<tr>
			<td>ID</td>
			<td>文件标题</td>
			<td>上传时间</td>
			<td>预计发送</td>
			<td>上传者</td>
			<td>类型</td>
			<td>点击</td>
		</tr>
	</tfoot>
</table>
