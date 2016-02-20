<?php echo $header; ?>
<div id="content">
	<div class="container">
		<meta name="config_save_warning" content="<?php $this->lng('config_save_warning'); ?>">

		<!-- Breadcrumbs -->
		<?php $this->showBreadcrumbs(); ?>

		<!-- Messages: Error, Success, Saving tip -->
		<?php $this->showTopMessages(); ?>

		<!-- PANEL: Multistore select -->
		<?php $this->showPanelStores(); ?>

		<form action="<?php print $this->formAction; ?>" method="POST" autocomplete="off" role="form" id="config_module" name="mainConfigForm">
			<input type="hidden" name="current_store" value="<?php print $this->currentStore['id']; ?>">

			<!-- PANEL: Basic settings -->
			<div class="panel panel-primary">
				<div class="panel-heading"><?php print $this->lng('PanelBasic'); ?></div>
				<div class="panel-body">
					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9 <?php if(isset($settingsError['p24id'])) print 'has-error'; else print $p24id_class; ?>">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="p24id"> <?php $this->lng('p24id'); ?></label>
								<input type="text" maxlength="6" value="<?php print $settings['p24id']; ?>" class="form-control <?php if(isset($settingsError['p24id'])) print 'error-field" data-content="'.$settingsError['p24id']; ?>" id="p24id" name="p24id">
							</div>
						</div>	
					</div>	
				
					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<span class="input-group-addon minw"><?php $this->lng('payment_mode'); ?></span>
								<div class="form-control btn-group" data-toggle="buttons">
									<?php $classes = array(0, 'primary', 'success', 'danger');
									for($i = 1; $i <= 3; $i++): ?>
										<label class="btn btn-xs btn-<?php print $classes[$i]; if($i == $settings['payment_mode']) print ' active'; ?> ?>"><input type="radio" name="payment_mode" value="<?php print $i; ?>" <?php if($i == $settings['payment_mode']) print ' checked="checked"'; ?>> <?php $this->lng('payment_mode'.$i); ?></label>
									<?php endfor; ?>
								</div>
							</div>
						</div>	
					</div>
				
					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="geo_zone"><?php $this->lng('geo_zone'); ?></label>
								<select id="geo_zone" name="geo_zone" class="form-control">
									<option value="0"<?php if($settings['geo_zone'] == 0) print ' selected="selected"'; ?>><?php $this->lng('text_all_zones'); ?></option>
									<?php foreach($geo_zones as $zone): ?>
										<option title="<?php print $zone['description']; ?>" value="<?php print $zone['geo_zone_id']; ?>"<?php if($settings['geo_zone'] == $zone['geo_zone_id']) print ' selected="selected"'; ?>><?php print $zone['name']; ?></option>
									<?php endforeach; ?>
								</select>
								<span class="input-group-btn">
									<a class="btn btn-default" href="<?php print $geo_zones_link; ?>" target="_blank"><?php $this->lng('text_edit'); ?></a>
								</span>
							</div>
						</div>
					</div>
				
					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="status"><?php $this->lng('column_status'); ?></label>
								<div class="form-control btn-group" data-toggle="buttons">
									<?php $classes = array('primary', 'info');
									for($i = 1; $i >= 0; $i--): ?>
										<label class="btn btn-xs btn-<?php print $classes[$i]; if($settings[$this->MODULE_NAME.'_status'] == $i) print ' active'; ?>"><input type="radio" name="<?php print $this->MODULE_NAME; ?>_status" value="<?php print $i; ?>"<?php if($settings[$this->MODULE_NAME.'_status'] == $i) print ' checked="checked"'; ?>> <?php print $this->lng('text_'.$i); ?></label>
									<?php endfor; ?>
								</div>
							</div>
						</div>
					</div>			

					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9 <?php if(isset($settingsError[$this->MODULE_NAME.'_sort_order'])) print 'has-error'; ?>">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="sort_order"><?php $this->lng('column_sort_order'); ?></label>
								<input type="text" maxlength="3" value="<?php print $settings[$this->MODULE_NAME.'_sort_order']; ?>" class="form-control <?php if(isset($settingsError[$this->MODULE_NAME.'_sort_order'])) print 'error-field" data-content="'.$settingsError[$this->MODULE_NAME.'_sort_order']; ?>" id="sort_order" name="<?php print $this->MODULE_NAME; ?>_sort_order">
								<div class="btn-group input-group-btn">
									<span class="input-group-btn"><button type="button" class="btn btn-sm" id="sort_order_less"><span class="glyphicon glyphicon-minus"></span></button></span>
									<span class="input-group-btn"><button type="button" class="btn btn-sm" id="sort_order_more"><span class="glyphicon glyphicon-plus"></span></button></span>
								</div>
							</div>
						</div>	
					</div>	

					<!-- ROW -->
					<div class="row">
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="sms_mode"><strong><?php $this->lng('sms_mode'); ?></strong></label>
								<div class="form-control btn-group" data-toggle="buttons">
									<?php $classes = array('primary', 'info');
									for($i = 0; $i <= 1; $i++): ?>
										<label class="btn btn-xs btn-<?php print $classes[$i]; if(isset($settings['sms_mode']) && $settings['sms_mode'] == $i) print ' active'; ?>"><input type="radio" name="sms_mode" value="<?php print $i; ?>"<?php if(isset($settings['sms_mode']) && $settings['sms_mode'] == $i) print ' checked="checked"'; ?>> <?php $this->lng('text_'.$i); ?></label>
									<?php endfor; ?>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>

			<!-- PANEL: SMS mode settings -->
			<div id="smsmodepanel" class="panel panel-primary <?php if(!isset($settingsError['sms_mode_dp']) && !isset($settingsError['sms_mode_number']) && !isset($settingsError['sms_mode_text'])): ?>sms_mode_shown<?php endif; ?>">
				<div class="panel-heading"><?php $this->lng('PanelSms'); ?></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-4 col-md-3 col-lg-3 pull-right">
							<div class="alert alert-warning"><small><?php $this->lng('sms_mode_tip'); ?></small></div>
						</div>	
						<div class="form-group col-sm-8 col-md-9 col-lg-9 <?php if(isset($settingsError['sms_mode_dp'])) print 'has-error'; ?>">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="sms_mode_dp"><?php $this->lng('sms_mode_dp'); ?></label>
								<input type="text" value="<?php print $settings['sms_mode_dp']; ?>" class="form-control <?php if(isset($settingsError['sms_mode_dp'])) print 'error-field" data-content="'.$settingsError['sms_mode_dp']; ?>" id="sms_mode_dp" name="sms_mode_dp">
							</div>
						</div>	
						<div class="form-group col-sm-8 col-md-9 col-lg-9 <?php if(isset($settingsError['sms_mode_number'])) print 'has-error'; ?>">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="sms_mode_number"><?php $this->lng('sms_mode_number'); ?></label>
								<input type="text" value="<?php print $settings['sms_mode_number']; ?>" class="form-control <?php if(isset($settingsError['sms_mode_number'])) print 'error-field" data-content="'.$settingsError['sms_mode_number']; ?>" id="sms_mode_number" name="sms_mode_number">
							</div>
						</div>	
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="sms_mode_price"><?php $this->lng('sms_mode_price'); ?></label>
								<select id="" name="sms_mode_price" class="form-control">
									<?php foreach(explode('|', $this->lng('sms_price', false)) as $i => $sp): ?>
										<option value="<?php print $i; ?>"<?php if($settings['sms_mode_price'] == $i) print ' selected="selected"'; ?>><?php print $sp; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>	
						<div class="form-group col-sm-8 col-md-9 col-lg-9 <?php if(isset($settingsError['sms_mode_text'])) print 'has-warning'; ?>">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" style="vertical-align:top;" for="sms_mode_text"><?php $this->lng('sms_mode_text'); ?></label>
								<textarea rows="3" style="height:6em;" class="form-control <?php if(isset($settingsError['sms_mode_text'])) print 'error-field" data-content="'.$settingsError['sms_mode_text']; ?>" id="" name=""><?php print $settings['sms_mode_text']; ?></textarea>
							</div>
						</div>	
					</div>	
				</div>
			</div>

			<!-- PANEL: Order statuses -->
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php $this->lng('PanelOrderStatuses'); ?> 
					<a href="<?php print $order_status_page; ?>" class="pull-right btn btn-xs btn-default" target="_blank"><?php $this->lng('text_edit'); ?></a>
				</div>
				<div class="panel-body">
					<?php foreach($this->config_orderStatuses as $i => $os): ?>
					<div class="row sms_mode_<?php print (stripos($os, 'sms') !== false) ? 'shown' : 'hidden'; ?>">
						<div class="form-group col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm">
								<label class="input-group-addon minw" for="<?php print $os; ?>"><?php $this->lng($os); ?></label>
								<select id="<?php print $os; ?>" name="<?php print $os; ?>" class="form-control">
									<?php if(count($this->orderStatuses)): foreach($this->orderStatuses as $status): ?>
										<option value="<?php print $status['order_status_id']; ?>"<?php if($status['order_status_id'] == $settings[$os]) print ' selected="selected"'; ?>><?php print $status['name']; ?></option>
									<?php endforeach; endif; ?>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-default edit_order_status" data-edit-url="<?php print $order_status_link; ?>" data-order-status="<?php print $os; ?>"><?php $this->lng('text_edit'); ?></button>
								</span>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- PANEL: Currency control -->
			<div class="panel panel-<?php print $isPLN ? 'info' : 'danger'; if(!isset($settingsError['nbp_xml_url'])) print ' sms_mode_hidden'; ?>">
				<?php if($isPLN): ?>
					<div class="panel-heading" data-toggle="collapse" data-target="#currencypanel"><a><span class="glyphicon glyphicon-import"></span> <?php $this->lng('PanelCurrencies'); ?></a></div>
				<?php else: ?>
					<div class="panel-heading"><span class="glyphicon glyphicon-exclamation-sign"></span> <?php $this->lng('PanelCurrencies'); ?></div>
				<?php endif; ?>

				<div class="panel-body <?php if($isPLN && !isset($settingsError['nbp_xml_url'])) print 'collapse'; ?>" id="currencypanel">
					<?php if(!$isPLN): ?>
						<div class="alert alert-danger text-center"><small><?php $this->lng('PanelCurrencies_error'); ?></small></div>
					<?php else: ?>
						<div class="alert alert-success text-center"><small><span class="glyphicon glyphicon-hand-right"></span> <?php $this->lng('PanelCurrencies_success'); ?></small></div>
					<?php endif; ?>

					<div class="row">
						<label class="col-lg-12 control-label"><?php $this->lng('currency_conversion_mode'); ?></label>

						<div class="form-group col-sm-4 col-md-3 col-lg-3 pull-right">
							<div class="alert alert-warning"><sup>1</sup> <small><?php $this->lng('currency_conversion_mode_tip'); ?></small></div>
						</div>	
						<div class="col-sm-7 col-md-8 col-lg-8 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
							<div class="form-group">
								<label class="input-group input-group-sm">
									<span class="input-group-addon"><input type="radio" name="currency_conversion_mode" value="0" <?php if($settings['currency_conversion_mode'] == 0 || !$isPLN) print ' checked="checked"'; ?>></span>
									<span class="form-control"><?php $this->lng('currency_conversion_mode0'); ?></span>
								</label>
								<label class="input-group input-group-sm">
									<span class="input-group-addon"><input type="radio" name="currency_conversion_mode" value="1" <?php if(!$isPLN) print 'disabled="disabled"';  if($settings['currency_conversion_mode'] == 1 && $isPLN) print ' checked="checked"'; ?>></span>
									<span class="form-control"><?php $this->lng('currency_conversion_mode1'); ?></span>
								</label>
							</div>
						</div>	
						<div class="col-sm-8 col-md-9 col-lg-9">
							<div class="input-group input-group-sm <?php if(isset($settingsError['nbp_xml_url'])) print 'has-error'; ?>">
								<label class="input-group-addon minw" for="nbp_xml_url"><?php $this->lng('nbp_xml_url'); ?> <sup>1</sup></label>
								<input type="text" maxlength="128" value="<?php print $settings['nbp_xml_url']; ?>" class="form-control <?php if(isset($settingsError['nbp_xml_url'])) print 'error-field" data-content="'.$settingsError['nbp_xml_url']; ?>" id="nbp_xml_url" name="nbp_xml_url">
							</div>
						</div>	
					</div>	
				</div>
			</div>
			
			<div class="help text-center sms_mode_hidden"><?php $this->lng('advPartTip'); ?></div><hr>
			
			<!-- PANEL: Payment methods -->
			<div class="panel panel-info sms_mode_hidden">
				<div class="panel-heading" data-toggle="collapse" data-target="#paymentsopt"><a><span class="glyphicon glyphicon-import"></span> <?php $this->lng('PanelPaymentMethods'); ?></a></div>
				<div class="panel-body collapse" id="paymentsopt">
					<?php if(isset($payment_methods_error)): ?>
						<div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php print $payment_methods_error; ?></div>
					<?php else: ?>
						<div class="row">
							<div class="col-sm-8 col-md-9 col-lg-9">
								<div class="form-group">
									<div data-toggle="buttons" class="btn-group">
										<?php $classes = array('success', 'primary', 'info');
										for($i = 0; $i < 3; $i++): ?>
											<label class="btn btn-sm btn-<?php print $classes[$i]; if($settings['payment_methods'] == $i) print ' active"'; ?>"><input type="radio" value="<?php print $i; ?>" name="payment_methods" <?php if($settings['payment_methods'] == $i) print 'checked="checked"'; ?>> <?php $this->lng('payment_methods'.$i); ?></label>
										<?php endfor; ?>
									</div>
								</div>

								<div class="divider"></div>

								<!-- Payment methods 1-->
								<div class="form-group payment_method_group <?php if($settings['payment_methods'] == 1) print 'payment_method_selected'; ?>" id="payment_method1">
									<div class="input-group">
										<label class="input-group-addon minw" for="payment_method_force"><?php $this->lng('payment_methods1'); ?></label>
										<select id="payment_method_force" name="payment_method_force" class="form-control">
											<option value="0"<?php if($settings['payment_method_force'] == 0) print ' selected="selected"'; ?>><?php $this->lng('text_none'); ?></option>
											<?php foreach($payment_methods as $pm): ?>
												<option value="<?php print $pm[0]; ?>"<?php if($settings['payment_method_force'] == $pm[0]) print ' selected="selected"'; ?>><?php print $pm[1]; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<!-- Payment methods 2-->
								<div class="form-group payment_method_group <?php if($settings['payment_methods'] == 2) print 'payment_method_selected'; ?>" id="payment_method2">
									<div id="selectedPayments" data-selectable-header="<?php $this->lng('payment_methods_selectable'); ?>" data-selection-header="<?php $this->lng('payment_methods_selection'); ?>">
										<div class="select-block">
											<select multiple="multiple" id="payment_methods_sel" name="payment_methods_sel[]">
												<?php foreach($payment_methods as $pm): ?>
													<option value="<?php print $pm[0]; ?>"<?php if(in_array($pm[0], $settings['payment_methods_sel'])) print ' selected="selected"'; ?>><?php print $pm[1]; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="divider">&nbsp;</div>
									<div class="input-group input-group-sm">
										<label class="input-group-addon minw" for="payment_methods_sel_default"><?php $this->lng('payment_methods2_default'); ?></label>
										<select id="payment_methods_sel_default" name="payment_methods_sel_default" class="form-control">
											<option value="0" <?php if($settings['payment_methods_sel_default'] == 0) print 'selected'; ?>><?php $this->lng('text_none'); ?></option>
											<?php foreach($settings['payment_methods_sel'] as $pm): ?>
												<option value="<?php print $pm; ?>"<?php if($settings['payment_methods_sel_default'] == $pm) print ' selected="selected"'; ?>><?php print $payment_methods[$pm][1]; ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<div class="divider">&nbsp;</div>
									<div class="input-group input-group-sm">
										<label class="input-group-addon minw" for="payment_method_last_user"><?php $this->lng('payment_method_last_user'); ?></label>
										<div class="form-control btn-group" data-toggle="buttons">
											<?php $classes = array('primary', 'info');
											for($i = 0; $i <= 1; $i++): ?>
												<label class="btn btn-xs btn-<?php print $classes[$i]; if($settings['payment_method_last_user'] == $i) print ' active'; ?>"><input type="radio" name="payment_method_last_user" value="<?php print $i; ?>"<?php if($settings['payment_method_last_user'] == $i) print ' checked="checked"'; ?>> <?php print $this->lng('text_'.$i); ?></label>
											<?php endfor; ?>										
										</div>
									</div>

									<div class="divider">&nbsp;</div>
									<div class="input-group input-group-sm">
										<label class="input-group-addon minw" for="payment_method_fancy"><?php $this->lng('payment_method_fancy'); ?></label>
										<div class="form-control btn-group" data-toggle="buttons">
											<?php $classes = array('primary', 'success');
											for($i = 0; $i <= 1; $i++): ?>
												<label class="btn btn-xs btn-<?php print $classes[$i]; if($settings['payment_method_fancy'] == $i) print ' active'; ?>"><input type="radio" name="payment_method_fancy" value="<?php print $i; ?>"<?php if($settings['payment_method_fancy'] == $i) print ' checked="checked"'; ?>> <?php print $this->lng('payment_method_fancy'.$i); ?></label>
											<?php endfor; ?>										
										</div>
									</div>
								</div>

							</div>
							<div class="col-sm-4 col-md-3 col-lg-3">
								<div class="alert alert-warning" id="paymentsUpdate"><span class="glyphicon glyphicon-info-sign"></span> <small><?php $this->lng('payment_methods_tip'); ?></small></div>
							</div>
							<div class="divider">&nbsp;</div>
							<div class="col-sm-4 col-md-3 col-lg-3 pull-right">
								<div class="alert alert-warning"><small><a id="paymentsFancyPreview" href="<?php print $paymentsFancyPreview; ?>" target="_blank" class="btn btn-xs btn-success"><?php $this->lng('paymentsFancyPreview_prefix'); ?></a> <?php $this->lng('paymentsFancyPreview_suffix'); ?></small></div>
							</div>									
						</div>
						<p class="clearfix help"><?php $this->lng('payment_last_update'); ?> <?php print $payment_methods_updated; ?> GMT+0</p>
					<?php endif; ?>
				</div>
			</div>

			<!-- PANEL: Order status emails -->
			<div class="panel panel-warning <?php if(!isset($settingsError['order_status_email'])) print ' sms_mode_hidden'; ?>">
				<div class="panel-heading" data-toggle="collapse" data-target="#emailopt"><a><span class="glyphicon glyphicon-import"></span> <?php $this->lng('PanelEmails'); ?></a></div>
				<div class="panel-body <?php if(!isset($settingsError['order_status_email'])) print 'collapse'; ?>" id="emailopt">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th><?php $this->lng('email1_h'); ?></th>
								<th><?php $this->lng('email2_h'); ?></th>
								<th><?php $this->lng('email3_h'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php for($i = 1; $i <= 3; $i++): ?>
								<tr>
									<td><?php $this->lng('order_status'.$i); ?></td>
									<td class="form-inline">
										<div class="form-group"><label class="input-group input-group-sm">
											<span class="input-group-addon"><input type="radio" name="order_status_email[order_status<?php print $i; ?>][owner]" value="1" <?php if($settings['order_status_email']['order_status'.$i]['owner']) print 'checked="checked"'; ?>></span>
											<span class="form-control"><?php $this->lng('text_yes'); ?></span>
										</label></div>
										<div class="form-group"><label class="input-group input-group-sm">
											<span class="input-group-addon"><input type="radio" name="order_status_email[order_status<?php print $i; ?>][owner]" value="0" <?php if(!$settings['order_status_email']['order_status'.$i]['owner']) print 'checked="checked"'; ?>></span>
											<span class="form-control"><?php $this->lng('text_no'); ?></span>
										</label></div>
									</td>
									<td class="form-inline">
										<div class="form-group"><label class="input-group input-group-sm">
											<span class="input-group-addon"><input type="radio" name="order_status_email[order_status<?php print $i; ?>][customer]" value="1" <?php if($settings['order_status_email']['order_status'.$i]['customer']) print 'checked="checked"'; ?>></span>
											<span class="form-control"><?php $this->lng('text_yes'); ?></span>
										</label></div>
										<div class="form-group"><label class="input-group input-group-sm">
											<span class="input-group-addon"><input type="radio" name="order_status_email[order_status<?php print $i; ?>][customer]" value="0" <?php if(!$settings['order_status_email']['order_status'.$i]['customer']) print 'checked="checked"'; ?>></span>
											<span class="form-control"><?php $this->lng('text_no'); ?></span>
										</label></div>
									</td>
								</tr>
							<?php endfor; ?>
						</tbody>
					</table>
					<div class="panel panel-default">
						<div class="panel-heading"><small><?php $this->lng('email4_h'); ?></small></div>
						<div class="panel-body">
							<ul class="nav nav-tabs" id="langEmails">
							<?php foreach($this->languages as $lang): ?>
								<li<?php if($lang['code'] == $settings['store_default_lang']) print ' class="active"'; ?>><a href="#lang_<?php echo $lang['code']; ?>"><img src="view/image/flags/<?php echo $lang['image']; ?>"> <?php echo $lang['name']; ?></a></li>
							<?php endforeach; ?>
							</ul>
							<div class="tab-content" id="langEmailsPanes">
								<?php foreach($this->languages as $lang): ?>
									<div class="tab-pane<?php if($lang['code'] == $settings['store_default_lang']) print ' active'; ?>" id="lang_<?php echo $lang['code']; ?>">
										<label class="lang-name control-label"><img src="view/image/flags/<?php echo $lang['image']; ?>"> <?php echo $lang['name']; ?> </label>
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<td>&nbsp;</td>
														<?php for($i = 1; $i <= 3; $i++): ?>
															<td><?php $this->lng('order_status'.$i); ?></td>
														<?php endfor; ?>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?php $this->lng('email_title'); ?></td>
														<?php for($i = 1; $i <= 3; $i++): ?>
															<td<?php if(isset($settingsError['order_status_email']['order_status'.$i][$lang['code']]['title'])) print ' class="has-error"';?>>
																<label class="sr-only" for="order_status_email<?php print $i; ?>_title"><?php $this->lng('email_title'); ?></label>
																<input type="text" id="order_status_email<?php print $i; ?>_<?php print $lang['code']; ?>_title" name="order_status_email[order_status<?php print $i; ?>][<?php print $lang['code']; ?>][title]" value="<?php
																	if(isset($settings['order_status_email']['order_status'.$i][$lang['code']])) print $settings['order_status_email']['order_status'.$i][$lang['code']]['title']; 
																?>" class="form-control input-sm">
															</td>
														<?php endfor; ?>
													</tr>
													<tr>
														<td><?php $this->lng('email_body'); ?></td>
														<?php for($i = 1; $i <= 3; $i++): ?>
															<td<?php if(isset($settingsError['order_status_email']['order_status'.$i][$lang['code']]['body'])) print ' class="has-error"';?>>
																<label class="sr-only" for="order_status_email<?php print $i; ?>_<?php print $lang['code']; ?>_body"><?php $this->lng('email_body'); ?></label>
																<textarea id="order_status_email<?php print $i; ?>_<?php print $lang['code']; ?>_body" name="order_status_email[order_status<?php print $i; ?>][<?php print $lang['code']; ?>][body]" rows="6" class="form-control input-sm"><?php
																	if(isset($settings['order_status_email']['order_status'.$i][$lang['code']])) print $settings['order_status_email']['order_status'.$i][$lang['code']]['body']; 
																?></textarea>
															</td>
														<?php endfor; ?>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-9 col-lg-9">
							<div class="panel panel-info"> 
								<div class="panel-heading" data-toggle="collapse" data-target="#emailplaceholders"><a><small><span class="glyphicon glyphicon-import"></span> <?php $this->lng('email_placeholders'); ?></small></a></div>
								<table class="table table-condensed collapse" id="emailplaceholders">
									<thead>
										<tr>
											<th><?php $this->lng('email_tip_h1'); ?></th>
											<th><?php $this->lng('email_tip_h2'); ?></th>
											<th><?php $this->lng('email_tip_h3'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php $this->showOrderEmailPlaceholders(); ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-md-3 col-lg-3">
							<div class="alert alert-danger"><span class="glyphicon glyphicon-ban-circle"></span> <small><?php $this->lng('email_tip_html'); ?></small></div>
						</div>
					</div>
				</div>
			</div>

			<!-- PANEL 5 -->
			<div class="panel panel-warning <?php if(!isset($settingsError['p24_crc2'])) print 'sms_mode_hidden'; ?>">
				<div class="panel-heading" data-toggle="collapse" data-target="#advancedopt1"><a><span class="glyphicon glyphicon-import"></span> <?php $this->lng('PanelOther'); ?></a></div>
				<div class="panel-body <?php if(!isset($settingsError['p24_crc2'])) print 'collapse'; ?>" id="advancedopt1">

					<div class="row">
						<div class="col-sm-8 col-md-9 col-lg-9">
							<div class="form-group ">
								<div class="input-group input-group-sm">
									<label class="input-group-addon minw" for="p24_language"><?php $this->lng('p24_language'); ?></label>
									<select id="p24_language" name="p24_language" class="form-control">
										<option value="0"<?php if($settings['p24_language'] == 0) print ' selected="selected"'; ?>><?php $this->lng('text_none'); ?></option>
										<?php foreach($p24_language as $lang): ?>
											<option value="<?php print $lang['code']; ?>"<?php if($settings['p24_language'] == $lang['code']) print ' selected="selected"'; ?>><?php print $lang['name']; ?> (<?php print $lang['code']; ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="divider">&nbsp;</div>
					<div class="row">
						<div class="pull-right col-sm-4 col-md-3 col-lg-3">
							<div class="alert alert-warning" id="paymentsUpdate"><small><?php $this->lng('p24_crc1_tip2'); ?></small></div>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-4">
							<label class="input-group input-group-sm">
								<span class="input-group-addon"><input type="checkbox" name="p24_crc1" value="1" <?php if($settings['p24_crc1']) print 'checked="checked"'; ?>></span>
								<span class="form-control"><?php $this->lng('p24_crc1'); ?></span>
							</label>
						</div>
						<div class="col-sm-4 col-md-5 col-lg-5">
							<label class="input-group input-group-sm <?php if(isset($settingsError['p24_crc2'])) print 'has-error'; ?>">
								<label class="input-group-addon" for="p24_crc2"><?php $this->lng('p24_crc2'); ?></label>
								<input type="text" maxlength="16" value="<?php print $settings['p24_crc2']; ?>" class="form-control <?php if(isset($settingsError['p24_crc2'])) print 'error-field" data-content="'.$settingsError['p24_crc2']; ?>" id="p24_crc2" name="p24_crc2">
							</label>
						</div>
						<div class="col-sm-8 col-md-9 col-lg-9">
							<div class="text-muted text-right"><small><?php $this->lng('p24_crc1_tip1'); ?></small></div>
						</div>
					</div>

					<div class="divider">&nbsp;</div><hr>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-danger">
								<strong><?php $this->lng('compatibility'); ?></strong><br>
									<div class="checkbox">
										<label><input type="checkbox" name="use_local_files" <?php if($settings['use_local_files']) print 'checked="checked"'; ?>> <?php $this->lng('use_local_files'); ?></label>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- LAST PANELS -->
			<div class="panel panel-default">
				<div class="panel-body text-center">
					<button type="submit" name="updateConfiguration" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> <?php $this->lng('button_save'); ?></button>
				</div>
			</div>
			<div class="alert alert-danger clearfix">
				<a href="<?php print $button_back; ?>" class="btn btn-xs btn-default pull-left"> <?php $this->lng('button_back'); ?></a>
				<a href="<?php print $button_uninstall; ?>" class="btn btn-xs btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span> <?php $this->lng('text_uninstall'); ?></a>
				<div class="pull-right">&nbsp;</div>
				<button type="submit" name="resetConfiguration" class="btn btn-xs btn-warning pull-right" data-message="<?php $this->lng('button_reverse_msg'); ?>"><span class="glyphicon glyphicon-refresh"></span> <?php $this->lng('button_reverse'); ?></button>
			</div>
		</form>
		<p class="text-info text-right"><small><?php $this->lng('api_verion_info'); ?></small></p>
	</div>
	<div class="container">
		<div class="well well-sm text-center text-muted">Copyright &copy;2013/<?php print date('Y'); ?> <a target="_blank" href="//daniel.kuruc.pl/?rel=dnk_przelewy24">Daniel Kuruc</a> &amp; <a target="_blank" href="//dnk.net.pl/?rel=dnk_przelewy24">DNK</a>. All Rights Reserved.</div>
	</div>
</div>
<?php if((int)$settings['sms_mode']): ?>
<style>.sms_mode_hidden { display: none; }</style>
<?php else: ?>
<style>.sms_mode_shown { display: none; }</style>
<?php endif;?>
<noscript><style>
[data-toggle="buttons"] > .btn > input[type="radio"], [data-toggle="buttons"] > .btn > input[type="checkbox"] { display: inline-block; }
.payment_method_group, .collapse, #langEmailsPanes .tab-pane, #langEmailsPanes .tab-pane .lang-name { display: block; }
#langEmails { display: none; }
</style></noscript>
<?php echo $footer; ?>