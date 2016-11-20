<?php
namespace App\Controller;
 
use App\Controller\AppController;
use App\Consts;
use Cake\ORM\TableRegistry;

/**
 * Nodes Controller
 *
 * @property \App\Model\Table\NodesTable
 */
class NodesController extends AppController
{
	public $components = ["Flash"];

	// 登録ユーザ一覧表示メソッド
	public function index()
        {
		$this->request->session()->delete("auth");

		$this->loadModel('Node');
		// db find
		$this->Node = TableRegistry::get('Nodes');
		$user_data = $this->Node->find()->all();

		$this->set('user_data', $user_data);
		$this->set('rolls', \App\Consts\Consts::$ROLL_NAMES);
		$this->render('index');
	}

	// 自身の所持自転車情報（兼ログイン）
	public function mine($ether_id = null)
	{
		$session = $this->request->session();

		if (isset($ether_id))
		{
			$this->loadModel('Node');
			// db find
			$this->Node = TableRegistry::get('Nodes');
			$user_datas = $this->Node->find()->all();
			// ここからクソコード
			$matched_data = null;
			foreach ($user_datas as $user_data)
			{
				if ($user_data["api_ether_address"] == $ether_id)
				{
					$matched_data = $user_data;
					break;
				}
			}
			if (is_null($matched_data))
				throw new Exception();

			$session->write("auth", $matched_data);
		}

		$auth = $session->read("auth");
		if (is_null($auth))
			throw new Exception();


		$this->loadComponent("NriBC", array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
		$this->NriBC->initialize(array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
                $this->set("assets", $this->NriBC->CallMyAssetsApi());
	}

	public function transfer($assetsName = null)
	{
		if (!isset($assetsName))
			throw new Exception();

                $auth = $this->request->session()->read("auth");
                if (is_null($auth))
                        throw new Exception();
                

                $this->loadComponent("NriBC", array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
                $this->NriBC->initialize(array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
                $asset = $this->NriBC->CallMyAssetApi($assetsName);
                if ($asset->balance < 1)
			throw new Exception();

                $this->loadModel('Node');
                // db find
                $this->Node = TableRegistry::get('Nodes');
                $user_datas = $this->Node->find()->all();

                $this->set('users', $user_datas);
                $this->set('asset', $asset);

	}

	public function do($assetsName = null, $address = null)
        {
		if (!isset($assetsName) || !isset($address))
			throw new Exception();

                $auth = $this->request->session()->read("auth");
                $this->loadComponent("NriBC", array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
                $this->NriBC->initialize(array('key' => $auth['api_access_key'], 'pw' => $auth['api_access_pw']));
		$this->NriBC->CallSendMyAssetApi($assetsName, $address);
		$this->Flash->success("移譲処理に成功しました！ブロックチェーンへのデータ反映に数十秒かかります。");
		$this->redirect("/nodes");
	}
}
?>
