<?php
class JsValidateController extends JsValidateAppController {
	var $name = 'JsValidate';
	var $helpers = array('Javascript', 'JsValidate.Validation');
	var $components = array('RequestHandler');

	function beforeFilter() {
		parent::beforeFilter();
	}

	function field($fieldId) {
		Configure::write('debug', 0);

		$modelName = array_shift(array_keys($this->data));

		foreach ($this->data as $cycleModelName => $data) {
			if (strpos($fieldId, $cycleModelName) === 0) {
				$modelName = $cycleModelName;
				break;
			}
			$modelName = null;
		}
		if (empty($modelName)) return true;
		$Model = ClassRegistry::init($modelName);
		$Model->data = $this->data;

		$fieldName = Inflector::underscore(str_replace($modelName, '', $fieldId));

		$output = array('field' => $fieldId);
		$output['result'] = $Model->validates(array('fieldList' => array($fieldName)));

		$errors = $Model->validationErrors;
		if ($errors) {
			$output['message'] = array_pop($errors);
		}

		$this->set('output', $output);
	}

	function test() {
		if (Configure::read('debug') < 1) {
			die(__('Debug setting does not allow access to this url.', true));
		}
	}

	function test_min() {
		if (Configure::read('debug') < 1) {
			die(__('Debug setting does not allow access to this url.', true));
		}
	}
}
?>