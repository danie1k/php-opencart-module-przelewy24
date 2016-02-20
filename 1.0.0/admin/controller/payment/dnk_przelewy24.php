<?php
if(!defined('VERSION')){
	die('No direct access allowed.');
}

class ControllerPaymentDnkPrzelewy24 extends Controller {
	protected $MODULE_NAME = 'dnk_przelewy24';

	private $paymentsFile = 'dnk_przelewy24.paychannels.json';
	private $error = null;

	protected $formAction = null;

	private $topMessagesSuccess = null;
	private $topMessagesError = null;

	private $totalStores = null;
	protected $currentStore = 0;

	protected $languages = null;
	protected $orderStatuses = null;
	protected $paymentMethods = null;

	/**
	 * module countable parameters
	 */
	protected $config_orderStatuses = array(
		'order_status_sms0',
		'order_status_sms1',
		'order_status0',
		'order_status1',
		'order_status2',
	);

	protected $config_p24language = array(
		array('code' => 'pl', 'name' => 'Polski'),
		array('code' => 'en', 'name' => 'Angielski'),
		array('code' => 'es', 'name' => 'Hiszpański'),
		array('code' => 'de', 'name' => 'Niemiecki'),
		array('code' => 'it', 'name' => 'Włoski'),
	);


	/**
	 * Functions displaying elements at config form
	 */
	protected function lng($lng, $print = true){
		switch($lng){
			case 'text_0': $lng = 'text_disabled'; break;
			case 'text_1': $lng = 'text_enabled'; break;
		}

		if($print) {
			print $this->language->get($lng);
		}
		else {
			return $this->language->get($lng);
		}
	}

	protected function showBreadcrumbs(){
		print '<div class="breadcrumb">';
		foreach ($this->document->breadcrumbs as $b){
			print $b['separator'].'<a href="'.$b['href'].'">'.$b['text'].'</a>';
		}
		print '</div>';
	}

	protected function showTopMessages(){
		if(isset($this->topMessagesError)){
			print'<div class="alert alert-danger">
				<span class="glyphicon glyphicon-warning-sign"></span> '.(is_bool($this->topMessagesError) ? $this->lng('topMessagesError', false) : $this->topMessagesError).'
			</div>';
		}

		if(isset($this->topMessagesSuccess)){
			print'<div class="alert alert-success">
				<span class="glyphicon glyphicon-ok"></span> '.(is_bool($this->topMessagesSuccess) ? $this->lng('topMessagesSuccess', false) : $this->topMessagesSuccess).'
			</div>';
		}

		if(!isset($this->topMessagesError) && !isset($this->topMessagesSuccess)){
			print '<div class="alert alert-warning">
				<span class="glyphicon glyphicon-info-sign"></span> '.$this->lng('config_save_info', false).'
			</div>';
		}
	}

	protected function showPanelStores(){
		if(count($this->totalStores) == 1){
			return;
		}

		print '<form action="'.$this->url->link('', '', 'SSL').'" method="GET" role="form" id="config_multistore">
			<input type="hidden" name="route" value="payment/'.$this->MODULE_NAME.'">
			<input type="hidden" name="token" value="'.$this->session->data['token'].'">
			<div class="panel panel-default">
				<div class="panel-heading" data-toggle="collapse" data-target="#multistorepanel"><a><span class="glyphicon glyphicon-import"></span> '.$this->lng('PanelStores', false).'</a> <span class="pull-right"><strong>'.$this->lng('PanelStores_current', false).'</strong> '.$this->currentStore['name'].'</span></div>
				<div class="panel-body collapse" id="multistorepanel">
					<p class="help-block">'.$this->lng('PanelStores_currentLong', false).' <strong>'.$this->currentStore['name'].'</strong></p>
					<div class="form-group">
						<div class="input-group">
							<label class="input-group-addon minw" for="change_store">'.$this->lng('PanelStores_select', false).'</label>
							<select id="change_store" name="change_store" class="form-control">';
							foreach($this->totalStores as $store){
								print '<option value="'.$store['id'].'"';
								if($store['id'] == $this->currentStore['id']) print ' selected="selected"';
								print '>'.$store['name'].'</option>';
							}
							print '</select>
							<span class="input-group-btn">
								<input class="btn btn-default" type="submit" name="submit_change_store" value="'.$this->lng('PanelStores_submit', false).'">
							</span>
						</div>
					</div>
				</div>
			</div>
		</form>';
	}

	protected function showOrderEmailPlaceholders(){
		$this->load->model('sale/customer');

		$placeholders['store_url'] = array(
			'code' => '{store_url}',
			'desc' => $this->lng('dnk_p24_ph-store_url', false),
			'ex' => $this->config->get('config_url')
		);

		$placeholders['store_name'] = array(
			'code' => '{store_name}',
			'desc' => $this->lng('dnk_p24_ph-store_name', false),
			'ex' => $this->config->get('config_name')
		);

		$placeholders['store_title'] = array(
			'code' => '{store_title}',
			'desc' => $this->lng('dnk_p24_ph-store_title', false),
			'ex' => $this->config->get('config_title')
		);

		$placeholders['store_owner'] = array(
			'code' => '{store_owner}',
			'desc' => $this->lng('dnk_p24_ph-store_owner', false),
			'ex' => $this->config->get('config_owner')
		);

		$placeholders['store_email'] = array(
			'code' => '{store_email}',
			'desc' => $this->lng('dnk_p24_ph-store_email', false),
			'ex' => $this->config->get('config_email')
		);

		$placeholders['store_telephone'] = array(
			'code' => '{store_telephone}',
			'desc' => $this->lng('dnk_p24_ph-store_telephone', false),
			'ex' => $this->config->get('config_telephone')
		);

		$customer_first_name = ''; $customer_last_name = ''; $customer_email = ''; $customer_telephone = '';
		$customers = $this->model_sale_customer->getCustomers();
		if(count($customers)) {
			$customer = reset($customers);
			$customer_first_name = $customer['firstname'];
			$customer_last_name = $customer['lastname'];
			$customer_email = $customer['email'];
			$customer_telephone = $customer['telephone'];
		}

		$placeholders['customer_first_name'] = array(
			'code' => '{customer_first_name}',
			'desc' => $this->lng('dnk_p24_ph-customer_first_name', false),
			'ex' => $customer_first_name
		);

		$placeholders['customer_last_name'] = array(
			'code' => '{customer_last_name}',
			'desc' => $this->lng('dnk_p24_ph-customer_last_name', false),
			'ex' => $customer_last_name
		);

		$placeholders['customer_email'] = array(
			'code' => '{customer_email}',
			'desc' => $this->lng('dnk_p24_ph-customer_email', false),
			'ex' => $customer_email
		);

		$placeholders['customer_telephone'] = array(
			'code' => '{customer_telephone}',
			'desc' => $this->lng('dnk_p24_ph-customer_telephone', false),
			'ex' => $customer_telephone
		);


		$placeholders['order_id'] = array(
			'code' => '{order_id}',
			'desc' => $this->lng('dnk_p24_ph-order_id', false),
			'ex' => '#13'
		);

		$order_url = str_replace('admin/', '', $this->url->link('account/order/info', 'order_id=13', 'SSL'));
		$placeholders['order_url'] = array(
			'code' => '{order_url}',
			'desc' => $this->lng('dnk_p24_ph-order_url', false),
			'ex' => '<span data-toggle="tooltip" title="'.$order_url.'">'.mb_substr($order_url, 0, 20).'&hellip;'.mb_substr($order_url, -20).'</span>'
		);

		$placeholders['order_total'] = array(
			'code' => '{order_total}',
			'desc' => $this->lng('dnk_p24_ph-order_total', false),
			'ex' => $this->currency->format(123.45)
		);

		$order_statuses = $this->orderStatuses;
		
		$order_status = next($order_statuses);
		$placeholders['order_old_status'] = array(
			'code' => '{order_old_status}',
			'desc' => $this->lng('dnk_p24_ph-order_old_status', false),
			'ex' => $order_status['name']
		);

		$order_status = next($order_statuses);
		$placeholders['order_new_status'] = array(
			'code' => '{order_new_status}',
			'desc' => $this->lng('dnk_p24_ph-order_new_status', false),
			'ex' => $order_status['name']
		);
		
		foreach($placeholders as $code => $p){
			print '<tr>
				<td><code>'.$p['code'].'</code></td>
				<td>'.$p['desc'].'</td>
				<td><em>'.$p['ex'].'</em></td>
			</tr>';
		}
	}


	public function index(){
		$this->formAction = $this->url->link('payment/'.$this->MODULE_NAME, 'token='.$this->session->data['token'], 'SSL');

		/**
		 * load languages (translations)
		 */
		$this->load->language('extension/payment');
		$this->load->language('payment/'.$this->MODULE_NAME);

		/**
		 * load models
		 */
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/language');
		$this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('localisation/geo_zone');

		/**
		 * multistore mode settings
		 */
		$this->getTotalStores();
		$this->setCurrentStore();

		$this->languages = $this->model_localisation_language->getLanguages();
		$this->orderStatuses = $this->model_localisation_order_status->getOrderStatuses();
		$this->paymentMethods = $this->getPaymentMethods();

		/**
		 * installation success message
		 */
		if(isset($_SESSION[$this->MODULE_NAME.'_install_success'])){
			if(isset($this->request->post['updatePayments'])){
				$this->topMessagesSuccess = $this->lng('success_update', false);
			}
			elseif(isset($this->request->post['resetConfiguration'])){
				$this->topMessagesSuccess = $this->lng('success_reset', false);
			}
			else{
				$this->topMessagesSuccess = $this->lng('success_install', false);
			}

			unset($_SESSION[$this->MODULE_NAME.'_install_success']);
		}

		/**
		 * module settings update submit
		 * and
		 * load/save module settings
		 */
		if(isset($this->request->post['updateConfiguration'])){
			$this->data['settings'] = $this->moduleSettings('update');

			if(count($this->error)){
				$this->data['settingsError'] = $this->error;
				$this->topMessagesError = true;
			}
			else{
				$this->topMessagesSuccess = true;
			}
		}
		else {
			$this->data['settings'] = $this->moduleSettings('get');
		}

		/**
		 * payments methods update submit
		 */
		if(isset($this->request->post['updatePayments'])){
			$this->install(true);
		}

		/**
		 * configuration reset submit 
		 */
		if(isset($this->request->post['resetConfiguration'])){
			$this->moduleSettings('reset');
		}

		/**
		 * installation error message
		 */
		if(isset($_SESSION[$this->MODULE_NAME.'_install_error'])){
			$this->topMessagesError = $_SESSION[$this->MODULE_NAME.'_install_error'];
			unset($_SESSION[$this->MODULE_NAME.'_install_error']);
		}

		/**
		 * Document html headers
		 */
		$this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js');

		// Twitter Bootstrap 3.0.1
		$this->document->addStyle( '//cdn.dnk.net.pl/libs/bootstrap/3.0.1/css/bootstrap.min.css');
		$this->document->addScript('//cdn.dnk.net.pl/libs/bootstrap/3.0.1/js/bootstrap.min.js');

		// Twitter Bootstrap Bootswatch Theme
		$this->document->addStyle('//cdn.dnk.net.pl/css/bootswatch/3.0.1/spacelab.min.css');

		// jQuery multiselect
		$this->document->addStyle( '//cdn.dnk.net.pl/libs/lou-multi-select/0.9.8/css/multi-select.css');
		$this->document->addScript('//cdn.dnk.net.pl/libs/lou-multi-select/0.9.8/js/jquery.multi-select.js');

		// Module
		$this->document->addStyle( '//cdn.dnk.net.pl/modules/opencart/'.$this->MODULE_NAME.'/1.0.0/admin/css/'.$this->MODULE_NAME.'.css');
		$this->document->addScript('//cdn.dnk.net.pl/modules/opencart/'.$this->MODULE_NAME.'/1.0.0/admin/js/'.$this->MODULE_NAME.'.js');

		// <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		// <!--[if lt IE 9]>
  		//$this->document->addScript('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js');
  		//$this->document->addScript('https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js');
		// <![endif]-->

		$this->setBreadcrumbs(array(
			'href' => $this->formAction,
			'text' => $this->lng('heading_title2', false)
		));

		/**
		 * configuration fields preparing
		 */

		// p24id
		$this->data['p24id_class'] = empty($this->data['settings']['p24id']) ? ' has-warning' : ' has-success';
		
		// geo_zones
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		$this->data['geo_zones_link'] = $this->url->link('localisation/geo_zone', 'token='.$this->session->data['token'], 'SSL');

		// order_statuses
		$this->data['order_status_page'] = $this->url->link('localisation/order_status', 'token='.$this->session->data['token'], 'SSL');
		$this->data['order_status_link'] = $this->url->link('localisation/order_status/update', 'token='.$this->session->data['token'], 'SSL');

		// currencies
		$isPLN = (int)$this->currency->getId('PLN'); 
		$this->data['isPLN'] = (bool)$isPLN;
		$this->data['currency_panel_icon'] = $isPLN ? '' : 'exclamation';

		// payment_methods
		if($this->paymentMethods === false) {
			$this->data['payment_methods_error'] = $this->lng('payment_methods_error', false);
		}
		else{
			$this->data['payment_methods_updated'] = date($this->lng('date_format_short', false).', '.$this->lng('time_format', false), strtotime($this->paymentMethods[0][1]));
			unset($this->paymentMethods[0]);
			$this->data['payment_methods'] = &$this->paymentMethods;
		}

		// p24_language
		$this->data['p24_language'] = $this->config_p24language;
		
		foreach($this->data['p24_language'] as &$lang){
			if(isset($this->languages[$lang['code']])){
				$lang['name'] = $this->languages[$lang['code']]['name'];
			}
		}

		$this->data['paymentsFancyPreview'] = $this->url->link('payment/'.$this->MODULE_NAME.'/payments', 'token='.$this->session->data['token'], 'SSL');
		$this->data['button_back'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');
		$this->data['button_uninstall'] = $this->url->link('extension/payment/uninstall', 'extension='.$this->MODULE_NAME.'&token='.$this->session->data['token'], 'SSL');

		$this->template = 'payment/'.$this->MODULE_NAME.'.tpl';
		$this->children = array('common/header', 'common/footer');
		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	public function install($updateOnly = false){
		if(!$updateOnly){ // everything is loaded when only updating payment methods
			/**
			 * load languages (translations)
			 */
			$this->load->language('extension/payment');
			$this->load->language('payment/'.$this->MODULE_NAME);

			/**
			 * load models
			 */
			$this->load->model('localisation/language');
			$this->load->model('localisation/order_status');

			$this->getTotalStores();
			$this->languages = $this->model_localisation_language->getLanguages();
			$this->orderStatuses = $this->model_localisation_order_status->getOrderStatuses();
		}

		unset($_SESSION[$this->MODULE_NAME.'_install_error'], $_SESSION[$this->MODULE_NAME.'_install_success']);

		if(!$this->updatePayments()) {
			$_SESSION[$this->MODULE_NAME.'_install_error'] = $this->lng('error_payments_update', false);
			$_SESSION[$this->MODULE_NAME.'_install_error'] .= '<ul>';
			if(is_array($this->error['updatePayments'])){
				foreach($this->error['updatePayments'] as $err){
					$_SESSION[$this->MODULE_NAME.'_install_error'] .= '<li>'.$err.'</li>';
				}
			}
			else{
				$_SESSION[$this->MODULE_NAME.'_install_error'] .= '<li>'.$this->error['updatePayments'].'</li>';
			}
			$_SESSION[$this->MODULE_NAME.'_install_error'] .= '</ul>';

			if(!$updateOnly) {
				$_SESSION[$this->MODULE_NAME.'_install_error'] .= '<br>'.$this->lng('error_install', false);
				
				// redirecting to uninstall due to errors
				// Opencart had already put settins into DB, so we have to remove them
				$this->getChild('extension/payment/uninstall');
			}
		}
		else{
			$_SESSION[$this->MODULE_NAME.'_install_success'] = true;

			if(!$updateOnly){
				/**
				 * new order statuses installation
				 */

				// getting translations
				foreach($this->languages as $lang){
					$file = DIR_LANGUAGE.$lang['directory'].'/payment/'.$this->MODULE_NAME.'.php';
					if(file_exists($file)){
						$install_language[$lang['language_id']] = new Language($lang['directory']);
						$install_language[$lang['language_id']]->load('payment/'.$this->MODULE_NAME);
					}
					else{
						// Default lang if no translation
						$install_language[$lang['language_id']] = new Language('english');
						$install_language[$lang['language_id']]->load('payment/'.$this->MODULE_NAME);
					}
				}

				$new_order_statuses = array();
				foreach($this->config_orderStatuses as $i => $os){
					$new_order_status = array(); // $language_id => order_status[x]
					foreach($install_language as $language_id => &$lang){
						$new_order_status[$language_id] = array('name' => $lang->get('new_'.$os, false));
					}
					
					
					$this->model_localisation_order_status->addOrderStatus(array('order_status' => $new_order_status));
					$new_order_statuses[] = $this->db->getLastId();

				}

				foreach($this->totalStores as $store_id => $s){
					$this->moduleSettings('set', $new_order_statuses);
				}

				$this->redirect($this->url->link('payment/'.$this->MODULE_NAME, 'token='.$this->session->data['token'], 'SSL'));
			}
		}
	}

	public function uninstall(){
		/**
		 * load languages (translations)
		 */
		$this->load->language('extension/payment');
		$this->load->language('payment/'.$this->MODULE_NAME);

		/**
		 * load models
		 */
		$this->load->model('localisation/order_status');

		$this->getTotalStores();
		$this->orderStatuses = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['errors'] = false;
		$this->formAction = $this->url->link('extension/payment/install', 'extension='.$this->MODULE_NAME.'&token='.$this->session->data['token'], 'SSL');

		// Called when installation failed
		if(isset($_SESSION[$this->MODULE_NAME.'_install_error'])){
			$this->data['errors'] = $_SESSION[$this->MODULE_NAME.'_install_error'];
			unset($_SESSION[$this->MODULE_NAME.'_install_error']);

			$this->setBreadcrumbs(array(
				'href' => $this->formAction,
				'text' => $this->lng('text_install', false).': '.$this->lng('heading_title2', false),
			), 'install');
		
			$this->data['link_back'] = $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL');
		}
		// Module uninstall
		else{
			// getting list of module files to remove
			if(file_exists(DIR_APPLICATION.'controller/payment/'.$this->MODULE_NAME.'.filelist')){
				$dnk_p24files = file_get_contents(DIR_APPLICATION.'controller/payment/'.$this->MODULE_NAME.'.filelist');
				$xml = simplexml_load_string($dnk_p24files);

				$this->data['dnk_p24files'] = array();

				foreach($xml as $config_type => $files){
					switch($config_type){
						case 'admin':
							foreach($files as $file){
								//$this->data['dnk_p24files'] = 
							}
							break;
						default:
							$configFilePath = realpath(DIR_SYSTEM.'../config.php');

							// Getting actual config defines
							$config = preg_replace('/\s+/', '', @file_get_contents($configFilePath));
							preg_match_all('/\(([\'|"])(.*?)\\1,(.*?)\)/', $config, $defines);
							$config = array();
							for($i = 0; $i < count($defines[0]); $i++){
								$config[$defines[2][$i]] = $defines[3][$i];
							}
							$configSatics = &$defines[2];
							$configSaticsRegex = implode('|', $configSatics);
							foreach($config as $key => &$val){
								$val = str_replace(array('."', ".'", '"', "'"), array('', '', '', ''), $val);
								foreach($configSatics as $cs){
									if(stripos($val, $cs) !== false) $val = str_replace($cs, $config[$cs], $val);
								}
							}

							foreach($files as $file){
								if($file->attributes() && $file->attributes()->prefix){
									$prefix = $file->attributes()->prefix[0];
									$file = $prefix.$file;
								}
								$file = strtr($file, $config);
								$this->data['dnk_p24files'] = array($file => (int)file_exists($file));
							}
					}
				}
			}
			
			if(isset($this->request->post['p24Uninstall'])){
			
				if(isset($this->request->post['selected']) && is_array($this->request->post['selected'])){
					$selected = array_values($this->request->post['selected']);
					$selected = implode('#', $selected).'#';
					preg_match_all('/order_status_([0-9]{1,})#/', $selected, $selected_order_statuses);

					if(isset($selected_order_statuses[1])){
						$order_statuses_to_remove = $selected_order_statuses[1];
						foreach($order_statuses_to_remove as $id){
							$this->model_localisation_order_status->deleteOrderStatus($id);
						}
					}

					if(in_array('files', $this->request->post['selected'])){
						if(isset($this->data['dnk_p24files']) && is_array($this->data['dnk_p24files'])){
							foreach($this->data['dnk_p24files'] as $path => $void){
								if(is_file($path)){
									unlink($path);
								}
							}
						}
					}

				}

				// check: is here any possibility for errors to appear? (errors so important to break uninstall process)
				$this->redirect($this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'));
			}

			foreach($this->totalStores as $store_id => $s){
				$this->moduleSettings('delete', null, $store_id);
			}

			$this->formAction = $this->url->link('extension/payment/uninstall', 'extension='.$this->MODULE_NAME.'&token='.$this->session->data['token'], 'SSL');

			$this->setBreadcrumbs(array(
				'href' => $this->formAction,
				'text' => $this->lng('text_uninstall', false).': '.$this->lng('heading_title2', false),
			), 'uninstall');

			$this->template = 'payment/'.$this->MODULE_NAME.'-setup.tpl';
			$this->children = array('common/header', 'common/footer');
			$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
			$this->response->output();
			exit(1);
		}
	}


	private function moduleSettings($action = 'get', $new_order_statuses = array(), $forceStoreId = false){
		if(!isset($this->model_setting_setting)){
			$this->load->model('setting/setting');
		}

		switch($action){
			/**
			 * removing module settings
			 */
			case 'delete':
				if($forceStoreId){
					$oldStoreId = $this->currentStore['id'];
					$this->currentStore['id'] = $forceStoreId;
				}

				$this->model_setting_setting->deleteSetting($this->MODULE_NAME, $this->currentStore['id']);

				if($forceStoreId){
					$this->currentStore = $oldStoreId;
				}
				break;

			/**
			 * loading module settings
			 */
			 case 'get':
				return $this->model_setting_setting->getSetting($this->MODULE_NAME, $this->currentStore['id']);
				break;

			/**
			 * storing module settings
			 * action: reset, set, update
			 */
			default:
				$default_settings = $this->getDefaultSettings();

				/**
				 * update settings
				 */
				if($action == 'update'){
					$this->error = array();
					$settings = array();

					$this->verifySanitizeSettings($default_settings, $settings);

					if(!count($this->error)){
						$this->model_setting_setting->editSetting($this->MODULE_NAME, $settings, $this->currentStore['id']);
					}

					return $settings;
				}

				/**
				 * reset settings
				 */
				if($action == 'reset'){
					$_SESSION[$this->MODULE_NAME.'_install_success'] = true;
				}

				/**
				 * set settings
				 */
				if($action == 'set' && count($new_order_statuses)){
					// Panel order statuses
					foreach($this->config_orderStatuses as $i => $os){
						$default_settings[$os] = $new_order_statuses[$i];
					}
				}
				elseif($action == 'set' && !count($new_order_statuses)){
					die('Installation error! DEBUG: No new order satuses');
				}

				/**
				 * set settings (during installation) & reset settings afer reset button submit
				 */
				foreach($this->totalStores as $store){
					$this->model_setting_setting->editSetting($this->MODULE_NAME, $default_settings, $store['id']);
				}

				// end switch default
		}
	}

	private function getDefaultSettings(){
		$default_settings = array(
			// Panel Basic
			'p24id' => '',
			'payment_mode' => 2,
			'geo_zone' => 0,
			$this->MODULE_NAME.'_status' => 0,
			$this->MODULE_NAME.'_sort_order' => 0,
			'sms_mode' => 0,

			// Panel SMS
			'sms_mode_dp' => 'DP',
			'sms_mode_number' => '',
			'sms_mode_price' => 0,
			'sms_mode_text' => '',
			
			// Panel order statuses - see below
			//

			// Panel currrencies
			'currency_conversion_mode' => intval((bool)$this->currency->getId('PLN')), 
			'nbp_xml_url' => 'http://www.nbp.pl/kursy/xml/LastA.xml',

			// Panel payments
			'payment_methods' => 0,
			'payment_method_force' => 0,
			'payment_methods_sel' => array(),
			'payment_methods_sel_default' => 0,
			'payment_method_last_user' => 0,
			'payment_method_fancy' => 0,

			// Order emails - also see below
			'order_status_email' => array(
				'order_status1' => array(
					'owner' => 0, 'customer' => 0,
				),
				'order_status2' => array(
					'owner' => 1, 'customer' => 0,
				),
				'order_status3' => array(
					'owner' => 1, 'customer' => 1,
				),
			),

			'p24_language' => 0,
			'p24_crc1' => 0,
			'p24_crc2' => '',

			'use_local_files' => 0,

			'store_default_lang' => $this->config->get('config_language'),
		);

		// Panel order statuses
		foreach($this->config_orderStatuses as $i => $os){
			$default_settings[$os] = 0;
		}

		// Order emails
		foreach($this->languages as $lang){
			for($i = 1; $i <= 3; $i++){ // order statuses 1-3
				$default_settings['order_status_email']['order_status'.$i][$lang['code']]['title'] = $this->lng('default_email_'.$i.'_title', false);
				$default_settings['order_status_email']['order_status'.$i][$lang['code']]['body'] = $this->lng('default_email_'.$i.'_body', false);
			}
		}

		return $default_settings;
	}


	private function getPaymentMethods(){
		if(!file_exists(DIR_CACHE.$this->paymentsFile)){
			$this->updatePayments();
		}
		
		$paymentMethods = file_get_contents(DIR_CACHE.$this->paymentsFile);
		if($paymentMethods === false){
			return false;
		}
		
		$paymentMethods = json_decode($paymentMethods);
		if($paymentMethods === null){
			return false;
		}

		$return = array();
		foreach($paymentMethods as $key => $p){ 
			if(!$key){
				$return[0] = $p;
			}
			else{
				$return[(int)$p[0]] = $p;
			}
		}

		return $return;
	}

	private function updatePayments(){
		$return = array();
		$updated = false;
		$tryMax = 5;

		if(file_exists(DIR_CACHE.$this->paymentsFile) && !$this->is_writable(DIR_CACHE.$this->paymentsFile)){
			$this->error['updatePayments'] = 'File '.DIR_CACHE.$this->paymentsFile.' is not writable!';
			$this->log->write('DNK Przelewy24 ERROR: '.$this->error['updatePayments']);
			return false;
		}
		
		for($tryCounter = 0, $p24id = 1234; $tryCounter < $tryMax; $tryCounter++, $p24id++){
			if(isset($this->error['updatePayments'])) unset($this->error['updatePayments']);

			$fp = fsockopen('ssl://secure.przelewy24.pl', 443, $errno, $errstr, 1);

			if(!$fp){
				$this->error['updatePayments'][$p24id][] = 'fsockopen error: '.$errstr;
				$this->log->write('DNK Przelewy24 UPDATE_PAYMENTS_FSOCKOPEN: '.$errstr);
			}
			else{
				$response = '';
				fwrite($fp, "GET /external/formy.php?id=".$p24id."&sort=2 HTTP/1.0\r\n" . "Host: secure.przelewy24.pl\r\n\r\n"); 

				while(!feof($fp))
					$response .= fgets($fp, 128); 

				fclose($fp);
				
				preg_match("/content-length:+.*([0-9]{1,})\r\n/i", $response, $content_length);
				if(isset($content_length[1]) && (int)$content_length[1] === 0) continue;

				preg_match("/content-type:+.*charset=(.*?)\r\n/i", $response, $response_charset);
				$response_charset = isset($response_charset[1]) ? strtoupper($response_charset[1]) : 'ISO-8859-2';

				$table = false;
				
				preg_match("/(<table.*)/", $response, $table);
				if($table){
					$matches = false;
					preg_match_all("/<label(.*?)>(.*?)<\/label>/", $table[0], $maches);

					if($maches){
						$matches2 = false;
						$_paymentList_attributes = &$maches[1];
						preg_match_all("/m_metoda_sel\(([0-9]{1,4})\)/", implode("\n", $_paymentList_attributes), $maches2);

						if($maches2){
							$_paymentList_ids = &$maches2[1];
							$_paymentList_names = &$maches[2];

							if(count($_paymentList_ids) === count($_paymentList_names)){
								$tryCounter = $tryMax;

								for($i = 0; $i < count($_paymentList_ids); $i++){
									$return[] = array($_paymentList_ids[$i], iconv($response_charset, 'UTF-8', $_paymentList_names[$i]));
								}
							}
							else $this->error['updatePayments'][$p24id][] = 'count#paymentList';
						}
						else $this->error['updatePayments'][$p24id][] = 'preg_match_all#3#maches2';
					}
					else $this->error['updatePayments'][$p24id][] = 'preg_match_all#2#maches';
				}
				else $this->error['updatePayments'][$p24id][] = 'preg_match_all#1#table';
			}
		}

		if(!isset($this->error['updatePayments'])){
			array_unshift($return, array('Last update', date('c')));
			if(file_put_contents(DIR_CACHE.$this->paymentsFile, json_encode($return))){
				return true;
			}
		}
		else{
			foreach($this->error['updatePayments'] as $updErr){
				$this->log->write('DNK Przelewy24 UPDATE_PAYMENTS_ERROR: '.$updErr);
			}
		}

		return false;
	}

	public function payments(){ // payments preview window
		header('Content-Type:text/html;charset=utf8');

		$this->load->language('payment/'.$this->MODULE_NAME);

		$settings = $this->moduleSettings();

		$paymentMethodsAll = $this->getPaymentMethods();
		$paymentMethods = array();

		if(is_array($settings['payment_methods_sel']) && count($settings['payment_methods_sel'])){
			foreach($paymentMethodsAll as $pm){
				if(in_array($pm[0], $settings['payment_methods_sel'])) $paymentMethods[$pm[0]] = $pm;
			}
		}
		else{
			unset($paymentMethodsAll[0]);
			$paymentMethods = &$paymentMethodsAll;
		}

		if((int)$settings['payment_methods_sel_default']){
			$activeMethod = $settings['payment_methods_sel_default'];
		}
		else {
			$activeMethod = array_rand($paymentMethods);
		}

		print '<html><head><title>'.$this->lng('heading_title2', false).'</title><style>
		body{background:#F0F0F0;overflow:hidden;text-align:center;}*{margin:0;padding:0}
		.container{background:#FFF;width:770px;border:1px solid #CCC;position:relative;overflow:hidden;margin:5px auto;padding:0 4px 8px}
		ul,li{list-style:none}ul{width:774px;position:relative}
		li{cursor:pointer;float:left;display:block;height:50px;margin:8px 4px 0 4px}
		li a{cursor:pointer;display:block;width:144px;height:48px;border:1px solid #CCC;background:#FFF;text-decoration:none;-webkit-border-top-left-radius:5px;-webkit-border-bottom-right-radius:5px;-moz-border-radius-topleft:5px;-moz-border-radius-bottomright:5px;border-top-left-radius:5px;border-bottom-right-radius:5px;-webkit-box-shadow:1px 1px 1px 1px #eee;box-shadow:1px 1px 1px 1px #eee}
		li a input{-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=60);filter:alpha(opacity=60);-moz-opacity:0.6;-khtml-opacity:0.6;opacity:0.6}
		li a:hover,li a:focus,li a.active{-webkit-box-shadow:0 0 5px -1px #800;box-shadow:0 0 5px -1px #800;border-color:#E89090}
		li a:hover input,li a:focus input,li a.active input{-ms-filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=100);filter:alpha(opacity=100);-moz-opacity:1;-khtml-opacity:1;opacity:1}
		li a,li a input{-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}
		input{cursor:pointer;border:0;text-indent:-9999px;display:block;height:100%;width:100%;background:50% 50% no-repeat;color:#fff;font-size:0}
		input.noimage{font-size:2em;font-weight:bold;text-indent:0;font-size:inherit;color:#000}
		</style></head><body><div class="container">
		<ul>';
		

		foreach($paymentMethods as $i => $pm){
			if(file_exists(DIR_IMAGE.'dnk_przelewy24/logo_'.$pm[0].'.gif')){
				$image = HTTPS_IMAGE.'dnk_przelewy24/logo_'.$pm[0].'.gif';
				$class = '';
			}
			else{
				$image = HTTPS_IMAGE.'no_image.jpg';
				$class = 'noimage';
			}

			if($activeMethod == $pm[0]) $active = 'active';
			else $active = '';
			
			print '<li><a class="'.$active.'" href="#p24_'.$pm[0].'" title="'.$pm[1].'"><input class="'.$class.'" style="background-image:url(\''.$image.'\');" type="button" name="payment['.$pm[0].']" value="'.$pm[0].'"></a></li>';
		}
		
		print '</ul></div></body></html>';
	}
	
	private function getTotalStores(){
		if(!isset($this->model_setting_store)){
			$this->load->model('setting/store');
		}

		$this->totalStores = array(
			0 => array('id' => 0, 'name' => $this->config->get('config_name'))
		);

		if($this->model_setting_store->getTotalStores() > 0) {
			foreach($this->model_setting_store->getStores() as $s) {
				$this->totalStores[(int)$s['store_id']] = array('id' => (int)$s['store_id'], 'name' => $s['name']);
			}
		}
	}

	private function setCurrentStore(){
		if(isset($this->request->post['current_store']) && isset($this->totalStores[(int)$this->request->post['current_store']])){
			$this->currentStore = $this->totalStores[(int)$this->request->post['current_store']];
		}
		elseif(isset($this->request->get['change_store']) && isset($this->totalStores[(int)$this->request->get['change_store']])){
			$this->currentStore = $this->totalStores[(int)$this->request->get['change_store']];
		}
		else{
			$this->currentStore = $this->totalStores[0];
		}
	}


	private function setBreadcrumbs($b = array(), $action = ''){
  		$this->document->breadcrumbs = array(
			array(
				'href' => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
				'text' => $this->lng('text_home', false),
				'separator' => false
			),
			array(
				'href' => $this->url->link('extension/payment', 'token='.$this->session->data['token'], 'SSL'),
				'text' => $this->lng('text_payment', false),
				'separator' => $this->lng('text_separator', false)
			)
		);
		
		if(count($b)){
			$b['separator'] = $this->lng('text_separator', false);
			$this->document->breadcrumbs[] = $b;
			if(!empty($action)){
				$this->document->setTitle($this->lng('text_'.$action, false).': '.$this->lng('heading_title2', false));
			}
			else{
				$this->document->setTitle($this->lng('heading_title2', false));
			}
		}
	}


	private function verifySanitizeSettings(&$default_settings, &$settings){
		$this->error = array();

		// BOOLEAN [status, sms_mode, currency_conversion_mode, payment_method_last_user, payment_method_fancy, p24_crc1, ]
		foreach(array($this->MODULE_NAME.'_status', 'sms_mode', 'currency_conversion_mode', 'payment_method_last_user', 'payment_method_fancy', 'p24_crc1', 'use_local_files') as $field_name){
			if(isset($this->request->post[$field_name])){
				$settings[$field_name] = $this->sanitizeField('boolean', $this->request->post[$field_name]);
			}
			else{
				$settings[$field_name] = $default_settings[$field_name];
			}
		}

		// SANITIZE [payment_mode, geo_zone, sms_mode_price, payment_methods, payment_method_force, payment_methods_sel, payment_methods_sel_default, p24_language]
		foreach(array('payment_mode', 'geo_zone', 'sms_mode_price', 'payment_methods', 'payment_method_force', 'payment_methods_sel', 'payment_methods_sel_default', 'p24_language') as $field_name){
			if(isset($this->request->post[$field_name])){
				$settings[$field_name] = $this->sanitizeField($field_name, $this->request->post[$field_name]);
			}
			else{
				$settings[$field_name] = $default_settings[$field_name];
			}
		}

		// VALIDATE [p24id, sort_order, sms_mode_dp, sms_mode_number, nbp_xml_url]
		foreach(array('p24id', $this->MODULE_NAME.'_sort_order', 'sms_mode_dp', 'sms_mode_number', 'nbp_xml_url') as $field_name){
			if(isset($this->request->post[$field_name])){
				if(false === $this->validateField($field_name, $this->request->post[$field_name])){
					$this->error[$field_name] = $this->lng('error_field_'.$field_name, false);
				}
				$settings[$field_name] = $this->sanitizeField($field_name, $this->request->post[$field_name]);
			}
			else{
				$settings[$field_name] = $default_settings[$field_name];
			}
		}

		// sms_mode_text
		if(isset($this->request->post['sms_mode_text'])){
			$sms_mode_text = html_entity_decode($this->request->post['sms_mode_text'], ENT_COMPAT);

			if(!(strcmp($sms_mode_text, strip_tags($sms_mode_text)) == 0)){
				$this->error['sms_mode_text'] = $this->lng('error_field_sms_mode_text', false);
			}
			$settings['sms_mode_text'] = $this->sanitizeField('removehtml', $this->request->post[$sms_mode_text]);
		}
		else{
			$settings['sms_mode_text'] = $default_settings['sms_mode_text'];
		}

		// order_status
		foreach($this->config_orderStatuses as $i => $os){
			if(isset($this->request->post[$os])){
				$settings[$os] = $this->sanitizeField('order_status', $this->request->post[$os]);
			}
			else{
				$settings[$os] = $default_settings[$os];
			}
		}

		// order status emails
		$settings['order_status_email'] = array();
		for($i = 1; $i <= 3; $i++){
			//// yes/no
			$settings['order_status_email']['order_status'.$i] = array(
				'owner' => $this->sanitizeField('boolean', $this->request->post['order_status_email']['order_status'.$i]['owner']),
				'customer' => $this->sanitizeField('boolean', $this->request->post['order_status_email']['order_status'.$i]['customer']),
			);
			//// languages
			foreach($this->request->post['order_status_email']['order_status'.$i] as $email_option => $values){
				if($email_option == 'owner' || $email_option == 'customer') continue;
				
				if(false !== $this->validateField('language_code', $email_option)){
					if(isset($this->request->post['order_status_email']['order_status'.$i][$email_option]['title'])){
						// Opencart $reques->clean reverse
						$title = html_entity_decode($this->request->post['order_status_email']['order_status'.$i][$email_option]['title'], ENT_COMPAT);
					}
					else{
						$title = $default_settings['order_status_email']['order_status'.$i][$email_option]['title'];
					}
					if(isset($this->request->post['order_status_email']['order_status'.$i][$email_option]['body'])){
						// Opencart $reques->clean reverse
						$body = html_entity_decode($this->request->post['order_status_email']['order_status'.$i][$email_option]['body'], ENT_COMPAT);
					}
					else{
						$body = $default_settings['order_status_email']['order_status'.$i][$email_option]['body'];
					}

					$settings['order_status_email']['order_status'.$i][$email_option] = array(
						'title' => $this->sanitizeField('removehtml', $title),
						'body' => $this->sanitizeField('removehtml', $body),
					);

					if(!(strcmp($title, strip_tags($title)) == 0)){
						$this->error['order_status_email']['order_status'.$i][$email_option]['title'] = $this->lng('error_field_order_status_email', false);
					}
					if(!(strcmp($body, strip_tags($body)) == 0)){
						$this->error['order_status_email']['order_status'.$i][$email_option]['body'] = $this->lng('error_field_order_status_email', false);
					}

				}
			}
		}

		$p24_crc1 = isset($this->request->post['p24_crc1']) ? (int)$this->request->post['p24_crc1'] : 0;

		// p24_crc2
		if(isset($this->request->post['p24_crc2'])){
			// Opencart $request->clean reverse
			$p24_crc2 = html_entity_decode($this->request->post['p24_crc2'], ENT_COMPAT);

			
			if(empty($p24_crc2) && $p24_crc1){
				$this->error['p24_crc2'] = $this->lng('error_field_p24_crc2', false);
			}
			else{
				if(!empty($p24_crc2) && !$p24_crc1){
					if(!$this->validateField('p24_crc2', $p24_crc2)){
						$this->error['p24_crc2'] = $this->lng('error_field_p24_crc2', false);
					}
				}
				elseif(!empty($p24_crc2) && $p24_crc1){
					if(!$this->validateField('p24_crc2', $p24_crc2)){
						$this->error['p24_crc2'] = $this->lng('error_field_p24_crc2', false);
					}
				}
			}

			$settings['p24_crc2'] = $this->sanitizeField('p24_crc2', $p24_crc2);
		}
		else{
			if($p24_crc1){
				$this->error['p24_crc2'] = $this->lng('error_field_p24_crc2', false);
			}
			$settings['p24_crc2'] = $default_settings['p24_crc2'];
		}

		// store_default_lang
		$settings['store_default_lang'] = $default_settings['store_default_lang'];
	}

	private function validateField($field, $value){
		switch($field){
			case 'p24id':
				return filter_var($value, FILTER_VALIDATE_INT, array('options' => array('min_range' => 100, 'max_range' => 999999)));
				break;
			case 'payment_mode':
				return filter_var($value, FILTER_VALIDATE_INT, array('options' => array('default' => 2, 'min_range' => 1, 'max_range' => 3)));
				break;
			case 'payment_methods':
				return filter_var($value, FILTER_VALIDATE_INT, array('options' => array('default' => 0, 'min_range' => 0, 'max_range' => 2)));
				break;
			case $this->MODULE_NAME.'_sort_order':
			case 'sms_mode_number':
				return (isset($this->request->post['sms_mode']) && $this->request->post['sms_mode'] == 1) ? (is_numeric($value) && (intval($value) >= 0)) : (empty($value) || (!empty($value) && is_numeric($value) && (intval($value) >= 0)));
				break;
			case 'sms_mode_dp':
				return (isset($this->request->post['sms_mode']) && $this->request->post['sms_mode'] == 1) ? (strpos($value, 'DP') === 0) : (empty($value) || (trim($value) === 'DP') || (!empty($value) && (strpos($value, 'DP') === 0)));
				break;
			case 'nbp_xml_url':
				return (bool)stripos('http', $value) || (bool)stripos('nbp', $value) || (bool)filter_var($value, FILTER_VALIDATE_URL, array('flags' => array(FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_SCHEME_REQUIRED, FILTER_FLAG_PATH_REQUIRED)));
				break;
			case 'language_code':
				$lang_code = filter_var($value, FILTER_SANITIZE_STRING);
				$languages = $this->model_localisation_language->getLanguages();
				foreach($languages as &$lang){ $lang = $lang['code']; }
				return in_array($lang_code, $languages);
				break;
			case 'p24_crc2':
				$crc2 = $this->sanitizeField('p24_crc2', $value);
				return (strlen($crc2) == strlen($value)) && (strlen($crc2) == 16);
				break;
		
		}
	}

	private function sanitizeField($field, $value){
		switch($field){
			// FILTER_SANITIZE_NUMBER_INT
			case 'p24id':
			case 'payment_mode':
				return (int)$this->validateField($field, filter_var($value, FILTER_SANITIZE_NUMBER_INT));
				break;

			// ABSOLUTE INTEGER
			case $this->MODULE_NAME.'_sort_order':
			case 'sms_mode_number':
				return abs(intval($value));
				break;

			// FILTER_VALIDATE_BOOLEAN
			case 'boolean':
				return (int)filter_var($value, FILTER_VALIDATE_BOOLEAN);
				break;

			// REMOVE HTML
			case 'removehtml':
				return trim(filter_var($value, FILTER_SANITIZE_STRING));
				break;

			case 'geo_zone':
				$geo_zone = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
				$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
				foreach($geo_zones as &$zone){ $zone = (int)$zone['geo_zone_id']; }
				return in_array($geo_zone, $geo_zones) ? $geo_zone : 0;
				break;
				
			case 'sms_mode_price':
				$maxVal = count(explode('|', $this->lng('sms_price', false))) - 1;
				$val = (int)$value;
				return ($val > $maxVal) ? 0 : $val;
				break;
				
			case 'order_status':
				$order_status = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
				$order_statuses = $this->model_localisation_order_status->getOrderStatuses();
				foreach($order_statuses as &$status){ $status = (int)$status['order_status_id']; }
				return in_array($order_status, $order_statuses) ? $order_status : $order_statuses[0];
				break;
				
			case 'nbp_xml_url':
				return filter_var($value, FILTER_SANITIZE_URL);
				break;

			case 'payment_methods':
				$val = (int)$this->validateField($field, filter_var($value, FILTER_SANITIZE_NUMBER_INT));
				return ($val >= 0 && $val <= 2) ? $val : 0;
				break;

			case 'payment_method_force':
			case 'payment_methods_sel':
			case 'payment_methods_sel_default':
				$return = null;

				if(false === $this->paymentMethods){ // only filtering integers
					if(is_array($value)){
						foreach($value as $v){
							$return[] = (int)filter_var($v, FILTER_SANITIZE_NUMBER_INT);
						}
					}
					else $return = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
				}
				else {
					$payment_methods = $this->paymentMethods;
					array_shift($payment_methods);
					foreach($payment_methods as &$method){ $method = (int)$method[0]; }
					
					if(is_array($value)){
						foreach($value as $v){
							if(in_array((int)$v, $payment_methods)){
								$return[] = (int)filter_var($v, FILTER_SANITIZE_NUMBER_INT);
							}
						}
					}
					else $return = in_array((int)$value, $payment_methods) ? (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT) : 0;
				}
				return $return;
				break;

			case 'p24_language':
				$p24language = filter_var($value, FILTER_SANITIZE_STRING);
				$p24languages = array();
				foreach($this->config_p24language as $p24lng){
					$p24languages[] = $p24lng['code'];
				}
				return in_array($p24language, $p24languages) ? $p24language : 0;
				break;

			case 'p24_crc2':
				return trim(filter_var($value, FILTER_SANITIZE_STRING));
				break;
		}
	}
	
	/**
	 * is_writable workaround for Windows* systems
	 * php.net/manual/en/function.is-writable.php#68098
	 */
	function is_writable($path){
		if($path{strlen($path)-1} == '/') return $this->is_writable($path.uniqid(mt_rand()).'.tmp');

		if(file_exists($path)) {
			if (!($f = @fopen($path, 'r+'))) return false;
			fclose($f);
			return true;
		}

		if (!($f = @fopen($path, 'w'))) return false;
		fclose($f);
		unlink($path);
		return true;
	}
}
