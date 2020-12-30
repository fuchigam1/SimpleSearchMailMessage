<?php
/**
 * [ModelEventListener] SimpleSearchMailMessage
 *
 * @link http://www.materializing.net/
 * @author arata
 * @package SimpleSearchMailMessage
 * @license MIT
 */
class SimpleSearchMailMessageModelEventListener extends BcModelEventListener {

	public $events = [
		'MailMessage.beforeFind',
		'Mail.MailMessage.beforeFind',
	];

	/**
	 * 受信一覧のモデル名: 受信一覧は動的に切り替わるため定義
	 *
	 * @var string
	 */
	private $modelName = 'MailMessage';

	/**
	 * messageBeforeFind
	 * - 受信一覧に検索条件を付与する
	 *
	 * @param CakeEvent $event
	 */
	public function mailMessageBeforeFind(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			$objRequest = Router::getRequest();
			if (!in_array($objRequest->params['controller'], ['mail_messages'], true)) {
				return $event->data;
			}
			if (!in_array($objRequest->params['action'], ['admin_index'], true)) {
				return $event->data;
			}

			$Model = $event->subject();
			$conditions[$Model->alias] = $objRequest['data']['MailMessage'];
			$searchCondition = $this->createAdminIndexConditions($conditions);
			if (!is_null($event->data[0]['conditions'])) {
				$event->data[0]['conditions'] = Hash::merge($event->data[0]['conditions'], $searchCondition);
			}
		}

		return $event->data;
	}

	/**
	 * mailMessageBeforeFind
	 * - 受信データCSVダウンロード時に検索条件を適用する
	 *
	 * @param CakeEvent $event
	 */
	public function mailMailMessageBeforeFind(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			$objRequest = Router::getRequest();
			if (!in_array($objRequest->params['controller'], ['mail_fields'], true)) {
				return $event->data;
			}
			if (!in_array($objRequest->params['action'], ['admin_download_csv'], true)) {
				return $event->data;
			}

			// 受信一覧画面の検索条件を取得する
			$filter = [];
			App::import('Component', 'Session');
			$Session = new SessionComponent(new ComponentCollection());
			if ($Session->check("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage" . $objRequest->params['Content']['entity_id'])) {
				// 受信一覧画面で検索した条件を取得する
				$filter = $Session->read("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage" . $objRequest->params['Content']['entity_id']);
			}
			unset($Session);

			if ($filter) {
				$Model = $event->subject();
				$conditions[$Model->alias] = $filter;
				$this->modelName = $Model->alias;  // CSVでの受信一覧時のモデル名は、フォーム名の受信用テーブル名となる
				$searchCondition = $this->createAdminIndexConditions($conditions);
				if (!empty($event->data[0]['conditions'])) {
					$event->data[0]['conditions'] = Hash::merge($event->data[0]['conditions'], $searchCondition);
				} else {
					$event->data[0]['conditions'] = $searchCondition;
				}
			}
		}

		return $event->data;
	}

	/**
	 * 一覧用の検索条件を生成する
	 *
	 * @param array $data
	 * @return array $conditions
	 */
	private function createAdminIndexConditions($data) {
		$conditions = [];
		unset($data['_Token']);

		// 条件指定のないフィールドを解除
		foreach ($data[$this->modelName] as $key => $value) {
			if ($value === '') {
				unset($data[$this->modelName][$key]);
			}
		}

		// 期間を検索する条件指定
		if (!empty($data[$this->modelName]['created_begin'])) {
			$conditions[] = [
				$this->modelName . '.created >=' => date('Y-m-d 00:00:00', strtotime($data[$this->modelName]['created_begin'])),
			];
		}
		if (!empty($data[$this->modelName]['created_end'])) {
			$conditions[] = [
				$this->modelName . '.created <=' => date('Y-m-d 23:59:59', strtotime($data[$this->modelName]['created_end'])),
			];
		}

		return $conditions;
	}

}
