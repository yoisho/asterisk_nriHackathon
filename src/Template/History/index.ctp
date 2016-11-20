<?php
	if($this->request->query('assetsName') != null){
		$this->assign('title', '車体履歴確認：'.$this->request->query('assetsName'));
	}else{
		$this->assign('title', '車体履歴確認');
	}
?>
<div style="padding:10px 20%;">
<?php echo $this->Form->create('History', ['url' => ['action' => 'index'], 'type' => 'get']); ?>
<?php echo $this->Form->input('assetsName', ['label' => '車体番号', 'value' => $this->request->query('assetsName')]); ?>
<?php echo $this->Form->submit('照合'); ?>
<?php echo $this->Form->end(); ?>
</div>
<table>
<tr>
	<th class="list">車体番号</th>
	<th class="list">売り主</th>
	<th class="list">買い主</th>
	<th class="list">取引日時</th>
</tr>
<?php foreach($history as $val): next($history); ?>
<tr<?php if (current($history) === false) { ?> style="background-color:yellow;"<?php } ?>>
	<td class="list"><?php echo h($val->assetsName); ?> </td>
	<td class="list"><?php echo h($match[$val->senderEthAddress]["name"] ?? "メーカー"); ?> </td>
	<td class="list"><?php echo h($match[$val->recceiverEthAddress]["name"] ?? "メーカー"); ?> </td>
	<td class="list"><?php echo h($val->tradingDate); ?> </td>
</tr>
<?php endforeach; ?>
</table>
<?php //var_dump($match); ?>
