<?php $this->assign('title', '登録ユーザ一覧'); ?>

<br />

<table>
<tr>
	<th class="list" style="width:30px;">ID</th>
	<th class="list">eth_address</th>
	<th class="list">roll_id</th>
	<th class="list">name</th>
</tr>
<?php foreach($user_data as $val): ?>
<tr>
	<td class="list" style="width:30px;"><?php echo h($val["id"]); ?> </td>
	<td class="list"><?php echo $this->Html->link("ログイン", ["controller" => "nodes", "action" => "mine", $val["api_ether_address"]]); ?> </td>
	<td class="list"><?php echo h($rolls[$val["roll_id"]] ?? "不明"); ?> </td>
	<td class="list"><?php echo h($val["name"]); ?> </td>
</tr>
<?php endforeach; ?>
