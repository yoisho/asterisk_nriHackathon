<?php $this->assign('title', '所持状況'); ?>

<br />

<table>
<tr>
        <th class="list">車体番号</th>
        <th class="list"></th>
        <th class="list"></th>
</tr>
<?php foreach($assets as $asset): ?>
<?php if ($asset->balance > 0) { ?>
<tr>
        <td class="list"><?php echo h($asset->assetsName); ?> </td>
        <td class="list"><?php echo $this->Html->link("譲渡", ["controller" => "nodes", "action" => "transfer", $asset->assetsName]); ?> </td>
        <td class="list"><?php echo $this->Html->link("履歴確認", ["controller" => "history", "action" => "index", "assetsName" => $asset->assetsName]); ?> </td>
</tr>
<?php } ?>
<?php endforeach; ?>
