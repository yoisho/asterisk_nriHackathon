<?php
namespace App\Controller;

use App\Consts;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class HistoryController extends AppController{
	
	public $components = ["NriBC"];

	public function index() {
                //DBから全ユーザデータ取得
                $this->loadModel('Node');
                $this->Node = TableRegistry::get('Nodes');
                $user_data = $this->Node->find()->all();
                //ユーザ名と車体番号マージ
                foreach($user_data as $val){
                        $match[$val->api_ether_address] = $val;
                }
                $this->set('match', $match);

		//ブロックチェーンAPIから指定された車体番号ヒストリーを取得
                $history = [];
		if($this->request->query('assetsName') != null){
			// 以下、初回は絶対にブロックチェーン親ユーザーが発行している前提のコード
			$res = $this->NriBC->CallAssetsHistoryApi($this->request->query('assetsName'));
			if (!empty($res[0]->historyList))
                        {
				$tmpArr = array_reverse($res[0]->historyList);
                                $history[] = array_shift($tmpArr);
				$isLast = false;
				while (!$isLast)
				{
					$isLast = $this->nextHistory($history, $match);
				}
                        }
		}
		$this->set('history', $history);
		$this->render('index');
	}

	private function nextHistory(array &$history, array $match)
	{
		$tmpLast = array_pop($history);
		array_push($history, $tmpLast);
		if (!array_key_exists($tmpLast->recceiverEthAddress, $match))
			return true;

		$matcher = $match[$tmpLast->recceiverEthAddress];
		$this->NriBC->initialize(["key" => $matcher['api_access_key'], "pw" => $matcher['api_access_pw']]);
		$res = $this->NriBC->CallAssetsHistoryApi($this->request->query('assetsName'));
		if (empty($res[0]->historyList))
			return true;

		$hisHistory = array_reverse($res[0]->historyList);
		foreach ($hisHistory as $his)
		{
			if (strtotime($his->tradingDate) > strtotime($tmpLast->tradingDate))
			{
				$history[] = $his;
				return false;
			}
		}
		return true;
	}
}
?>
