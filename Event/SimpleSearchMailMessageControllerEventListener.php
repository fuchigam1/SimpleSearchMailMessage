<?php
/**
 * [ControllerEventListener] SimpleSearchMailMessage
 *
 * @link http://www.materializing.net/
 * @author arata
 * @package SimpleSearchMailMessage
 * @license MIT
 */
class SimpleSearchMailMessageControllerEventListener extends BcControllerEventListener {

	public $events = [
		'Mail.MailMessages.startup',
		'Mail.MailMessages.beforeRender',
	];

	/**
	 * mailMailMessagesStartup
	 * - フォーム別の検索条件をセッションに保存する
	 *
	 * @param CakeEvent $event
	 */
	public function mailMailMessagesStartup(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			$filter = [];
			$Controller = $event->subject();
			if ($Controller->Session->check("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage" . $Controller->mailContent['MailContent']['id'])) {
				$filter = $Controller->Session->read("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage" . $Controller->mailContent['MailContent']['id']);
			}
			$Controller->Session->write("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage", $filter);
		}
	}

	/**
	 * mailMailMessagesBeforeRender
	 * - 受信一覧画面で検索ボックスを表示させる
	 * - このイベントでは、検索ボックス用エレメント自体を呼び出すことはできないため定義を行っておく
	 * - 定義を行っておき、実際のエレメント呼び出しは SimpleSearchMailMessageViewEventListener 側で実施する
	 *
	 * @param CakeEvent $event
	 */
	public function mailMailMessagesBeforeRender(CakeEvent $event) {
		if (BcUtil::isAdminSystem()) {
			$Controller = $event->subject();
			if (in_array($Controller->request->params['action'], ['admin_index'], true)) {
				$Controller->search = 'simple_search_mail_message';
				// セッションに保存された検索条件は、どのフォームの受信一覧画面でも共通で利用されるため、共通利用されるセッションの検索条件にフォーム別の検索条件を設定する
				$Controller->Session->write("Baser.viewConditions.MailMessagesAdminIndex.filter.MailMessage" . $Controller->mailContent['MailContent']['id'], $Controller->request->data('MailMessage'));
			}
		}
	}

}
