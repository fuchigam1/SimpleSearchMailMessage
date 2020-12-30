<?php
/**
 * [ADMIN] SimpleSearchMailMessage
 *
 * @link http://www.materializing.net/
 * @author arata
 * @package SimpleSearchMailMessage
 * @license MIT
 */
?>
<?php echo $this->BcForm->create('MailMessage', ['url' => ['action' => 'index', $mailContent['MailContent']['id']]]) ?>
<p class="bca-search__input-list">
	<span class="bca-search__input-item">
		<?php if ($siteConfig['admin_theme']): ?>
			<span class="bca-datetimepicker__group">
				<span class="bca-datetimepicker__start">
					<?php echo $this->BcForm->label('MailMessage.created_begin', '受信日', [
						'class' => 'bca-search__input-item-label',
						'style' => 'align-items: center;display: flex;justify-content: center;margin-bottom:0;'
					]) ?>
					<?php echo $this->BcForm->input('MailMessage.created_begin', [
						'type' => 'datePicker', 'size' => 12, 'maxlength' => 10,
						'div' => ['tag' => '', 'class' => 'bca-datetimepicker__date-input']
					], true) ?>
				</span>
					<span class="bca-datetimepicker__delimiter">〜</span>
				<span class="bca-datetimepicker__end">
					<?php echo $this->BcForm->input('MailMessage.created_end', [
						'type' => 'datePicker', 'size' => 12, 'maxlength' => 10,
						'div' => ['tag' => '', 'class' => 'bca-datetimepicker__date-input']
					], true) ?>
				</span>
			</span>
		<?php else: ?>
			<span>[受信日]</span>&nbsp;
			<?php echo $this->BcForm->datePicker('MailMessage.created_begin', ['size' => 12, 'maxlength' => 10], true) ?>
			&nbsp;〜&nbsp;
			<?php echo $this->BcForm->datePicker('MailMessage.created_end', ['size' => 12, 'maxlength' => 10], true) ?>
		<?php endif; ?>
		<?php echo $this->BcForm->error('MailMessage.created_begin') ?>
		<?php echo $this->BcForm->error('MailMessage.created_end') ?>
	</span>
	<?php echo $this->BcSearchBox->dispatchShowField() ?>
</p>
<div class="button bca-search__btns submit">
	<?php if ($siteConfig['admin_theme']): ?>
		<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn', 'data-bca-btn-type' => 'search']) ?></div>
		<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn', 'data-bca-btn-type' => 'clear']) ?></div>
	<?php else: ?>
		<?php echo $this->BcForm->button(__d('baser', '検索'), ['class' => 'button', 'id' => 'BtnSearchSubmit']) ?>
		<?php echo $this->BcForm->button(__d('baser', 'クリア'), ['class' => 'button', 'id' => 'BtnSearchClear']) ?>
	<?php endif; ?>
</div>
<?php echo $this->BcForm->end() ?>
