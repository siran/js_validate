<?php
App::uses('JsValidateAppController', 'JsValidate.Controller');
/**
 * 
 */
class JsValidateController extends JsValidateAppController {
/**
 * Name
 *
 * @var string
 */
	public $name = 'JsValidate';

/**
 * Helpers
 *
 * @var string
 */
	public $helpers = array(
		'Javascript',
		'JsValidate.JsValidate');

/**
 * Components
 *
 * @var string
 */
	public $components = array('RequestHandler');

/**
 * Models - none
 *
 * @var array
 */
	public $uses = array();

	public function beforeFilter() {
	}

/**
 * Field
 *
 * @param string $fieldId
 * @return void
 */
	public function field($fieldId) {
		Configure::write('debug', 0);

		$modelName = array_shift(array_keys($this->data));
		$Model = ClassRegistry::init($modelName);
		$Model->data = $this->data;

		$fieldName = array_shift(array_keys(array_shift($this->data)));

		$output = array('field' => $fieldId);
		$output['result'] = $Model->validates(array('fieldList' => array($fieldName)));

		$errors = $Model->validationErrors;
		if ($errors) {
			$output['message'] = array_pop($errors);
		}

		$this->set('output', $output);
	}

	public function test() {
		if (Configure::read('debug') < 1) {
			die(__('Debug setting does not allow access to this url.', true));
		}
	}

	public function test_min() {
		if (Configure::read('debug') < 1) {
			die(__('Debug setting does not allow access to this url.', true));
		}
	}
}