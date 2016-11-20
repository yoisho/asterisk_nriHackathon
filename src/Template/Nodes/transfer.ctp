<?php $this->assign('title', $asset->assetsName . "を譲渡"); ?>

<br />

<table>
<tr>
        <th class="list">名前</th>
        <th class="list"></th>
</tr>
<?php foreach($users as $user): ?>
<?php if ($user['api_ether_address'] != $auth['api_ether_address']) { ?>
<tr>
        <td class="list"><?php echo h($user['name']); ?> </td>
        <td class="list"><?php echo $this->Html->link("譲渡", ["controller" => "nodes", "action" => "do", $asset->assetsName, $user['api_ether_address']]); ?> </td>
</tr>
<?php } ?>
<?php endforeach; ?>
