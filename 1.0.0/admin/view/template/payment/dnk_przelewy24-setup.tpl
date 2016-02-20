<?php echo $header; ?>
<div id="content">
	<div class="container">

		<!-- Breadcrumbs -->
		<?php $this->showBreadcrumbs(); ?>

		<?php if($errors): // installation error ?>
			<div class="box">
				<div class="warning"><?php print $errors; ?></div>
				<a class="button" href="<?php print $link_back; ?>"><?php $this->lng('button_back'); ?></a>
			</div>
		<?php else: ?>
			<div class="box">
				<div class="heading"><h1><img alt="" src="view/image/payment.png"> <?php $this->lng('text_uninstall'); ?>: <?php $this->lng('heading_title2'); ?></h1></div>
				<div class="content">
					<h2>Zaznacz elementy, które chcesz usunąć:</h2>
					<form action="<?php print $this->formAction; ?>" method="POST">
					<table class="list">
						<thead>
							<tr>
								<td class="right" style="white-space: nowrap; width: 1%;"><label for="select_all">Wszystko </label><input type="checkbox" id="select_all" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
								<td class="left">Wartość</td>
								<td class="left">Opis</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->orderStatuses as $status): ?>
								<tr>
									<td class="right"><input type="checkbox" value="order_status_<?php print $status['order_status_id']; ?>" id="order_status_<?php print $status['order_status_id']; ?>" name="selected[]"></td>
									<td class="left"><label for="order_status_<?php print $status['order_status_id']; ?>"><?php print $status['name']; ?></label></td>
									<td class="left">Order Status</td>
								</tr>
							<?php endforeach; ?>

							<?php if(isset($dnk_p24files)): ?>
							<tr>
								<td class="right" style="vertical-align: top;"><input type="checkbox" value="files" name="selected[]"></td>
								<td class="left">
									<ul>
										<?php foreach($dnk_p24files as $file => $status): ?>
											<li title="<?php print $file; ?>"><code><?php print mb_substr($file, 0, 30); ?> &hellip; <?php print mb_substr($file, -30); ?></code> (<span class="file_status_<?php print $status; ?>"><?php $this->lng('file_status_'.$status); ?></span>)</li>
										<?php endforeach; ?>
									</ul>
								</td>
								<td class="left" style="vertical-align: top;">Wszystkie pliki modułu</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
					<input style="float:left;" type="submit" class="button" name="p24Uninstall" value="Odinstaluj">
					<div style="float:right;">Copyright &copy;2013/<?php print date('Y'); ?> <a target="_blank" href="//daniel.kuruc.pl/?rel=dnk_przelewy24&uninstall">Daniel Kuruc</a> &amp; <a target="_blank" href="//dnk.net.pl/?rel=dnk_przelewy24&uninstall">DNK</a>. All Rights Reserved.</div>
					</form>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
<style> .file_status_0 { color: red; } .file_status_1 { color: green; } </style>
<?php print $footer; ?>