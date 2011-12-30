<?php
App::uses('Controller', 'Controller');
App::uses('Model', 'Model');
App::uses('View', 'View');
App::uses('JsValidateHelper', 'JsValidate.View/Helper');
App::uses('HtmlHelper', 'View/Helper');
App::uses('JsHelper', 'View/Helper');
App::uses('JqueryEngineHelper', 'View/Helper');

class JsValidate extends Model {

	public $name = 'JsValidate';

	public $useTable = false;

	public $validate = array(
		'alphaNumeric' => array('rule1' => array('rule' => 'alphaNumeric')),
		'between' => array('rule1' => array('rule' => array('between', 5, 10))),
		'blank' => array('rule1' => array('rule' => 'blank')),
		'boolean' => array('rule1' => array('rule' => 'boolean')),
		'cc' => array('rule1' => array('rule' => 'cc')),
		'comparison' => array('rule1' => array('rule' => array('comparison', '>=', 18)),
		'rule2' => array('rule' => array('comparison', 'is less', 3))),
		'date' => array('rule1' => array('rule' => 'date')),
		'decimal' => array('rule1' => array('rule' => 'decimal'),
		'rule2' => array('rule' => array('decimal', 4))),
		'email' => array('rule1' => array('rule' => 'email')),
		'equalTo' => array('rule1' => array('rule' => array('equalTo', 'cake'))),
		'extension' => array('rule1' => array('rule' => 'extension'),
		'rule2' => array('rule' => array('extension', array('psd')))),
		'ip' => array('rule1' => array('rule' => 'ip')),
		'isUnique' => array('rule1' => array('rule' => 'isUnique')),
		'minLength' => array('rule1' => array('rule' => array('minLength', '5'))),
		'maxLength' => array('rule1' => array('rule' => array('maxLength', '7'))),
		'money' => array('rule1' => array('rule' => 'money')),
		'multiple' => array('rule1' => array('rule' => array('multiple', array('in' => array('foo', 'bar'), 'min' => 1, 'max' => 3)))),
		'inList' => array('rule1' => array('rule' => array('inList', array('foo', 'bar')))),
		'numeric' => array('rule1' => array('rule' => 'numeric')),
		'notEmpty' => array('rule1' => array('rule' => 'notEmpty')),
		'phone' => array('rule1' => array('rule' => array('phone', null, 'us'))),
		'postal' => array('rule1' => array('rule' => array('postal', null, 'us'))),
		'range' => array('rule1' => array('rule' => array('range', 0, 10))),
		'ssn' => array('rule1' => array('rule' => array('ssn', null, 'us'))),
		'url' => array('rule1' => array('rule' => 'url'))
	);
}

class ValidationTestCase extends CakeTestCase {

	public $JsValidate = null;

	public $Validation = null;

	public function setUp() {
		parent::setUp();
		Configure::write('App.base', '');
		$this->Controller = new Controller();
		$this->View = new View($this->Controller);

		$this->JsValidate = & ClassRegistry::init('JsValidate');
		$this->JsValidate->backupValidate = $this->JsValidate->validate;
		$this->JsValidate->validate = array();

		$this->Validation = new JsValidateHelper($this->View);
		$this->Validation->Js = new JsHelper($this->View);
		$this->Validation->Js->JqueryEngine = new JqueryEngineHelper($this->View);
		$this->Validation->Html = new HtmlHelper($this->View);
	}

	public function testInstances() {
		$this->assertTrue(is_a($this->JsValidate, 'JsValidate'));
		$this->assertTrue(is_a($this->Validation, 'ValidationHelper'));
		$this->assertTrue(is_a($this->Validation->Js, 'JsHelper'));
	}

	public function testNoValidation() {
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$this->assertEqual('[]', $validation);
	}

	public function testAlphaNumeric() {
		$this->JsValidate->validate = array('alphaNumberic' => $this->JsValidate->backupValidate['alphaNumeric']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateAlphaNumberic":[{"rule":"\/^[0-9A-Za-z]+$\/","message":"There was a problem with the field AlphaNumberic"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testBetween() {
		$this->JsValidate->validate = array('between' => $this->JsValidate->backupValidate['between']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateBetween":[{"rule":"\/^.{5,10}$\/","message":"There was a problem with the field Between"}]}';
		$this->assertEqual($expected, $validation);

	}

	public function testBlank() {
		$this->JsValidate->validate = array('blank' => $this->JsValidate->backupValidate['blank']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateBlank":[{"rule":"\/[^\\\\s]\/","message":"There was a problem with the field Blank","negate":true}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testBoolean() {
		$this->JsValidate->validate = array('boolean' => $this->JsValidate->backupValidate['boolean']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateBoolean":[{"rule":{"rule":"boolean"},"message":"There was a problem with the field Boolean"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testCC() {
		$this->JsValidate->validate = array('cc' => $this->JsValidate->backupValidate['cc']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateCc":[{"rule":{"rule":"cc","params":[]},"message":"There was a problem with the field Cc"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testComparison() {
		$this->JsValidate->validate = array('comparison' => $this->JsValidate->backupValidate['comparison']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateComparison":[{"rule":{"rule":"comparison","params":[">=",18]},"message":"There was a problem with the field Comparison"},{"rule":{"rule":"comparison","params":["<",3]},"message":"There was a problem with the field Comparison"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testDate() {
		$this->JsValidate->validate = array('date' => $this->JsValidate->backupValidate['date']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateDate":[{"rule":{"rule":"date","params":"ymd"},"message":"There was a problem with the field Date"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testDecimal() {
		$this->JsValidate->validate = array('decimal' => $this->JsValidate->backupValidate['decimal']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateDecimal":[{"rule":"\/^[-+]?[0-9]*\\\\.{1}[0-9]+(?:[eE][-+]?[0-9]+)?$\/","message":"There was a problem with the field Decimal"},{"rule":"\/^[-+]?[0-9]*\\\\.{1}[0-9]{4}$\/","message":"There was a problem with the field Decimal"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testEmail() {
		$this->JsValidate->validate = array('email' => $this->JsValidate->backupValidate['email']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateEmail":[{"rule":"\/^([a-zA-Z0-9_\\\\.\\\\-])+\\\\@(([a-zA-Z0-9\\\\-])+\\\\.)+([a-zA-Z0-9]{2,4})+$\/","message":"There was a problem with the field Email"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testEqualTo() {
		$this->JsValidate->validate = array('equalTo' => $this->JsValidate->backupValidate['equalTo']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateEqualTo":[{"rule":"\/^cake$\/","message":"There was a problem with the field EqualTo"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testExtension() {
		$this->JsValidate->validate = array('extension' => $this->JsValidate->backupValidate['extension']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateExtension":[{"rule":"\/\\\\.(gif|jpeg|png|jpg)$\/","message":"There was a problem with the field Extension"},{"rule":"\/\\\\.(psd)$\/","message":"There was a problem with the field Extension"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testInList() {
		$this->JsValidate->validate = array('inList' => $this->JsValidate->backupValidate['inList']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateInList":[{"rule":{"rule":"inList","params":["foo","bar"]},"message":"There was a problem with the field InList"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testIp() {
		$this->JsValidate->validate = array('ip' => $this->JsValidate->backupValidate['ip']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateIp":[{"rule":"\/^[\\\\d]{1,3}\\\\.[\\\\d]{1,3}\\\\.[\\\\d]{1,3}\\\\.[\\\\d]{1,3}$\/","message":"There was a problem with the field Ip"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testIsUnique() {
		$this->JsValidate->validate = array('isUnique' => $this->JsValidate->backupValidate['isUnique']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateIsUnique":[{"rule":{"rule":"isUnique","params":[]},"message":"There was a problem with the field IsUnique"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testMinLength() {
		$this->JsValidate->validate = array('minLength' => $this->JsValidate->backupValidate['minLength']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateMinLength":[{"rule":"\/^[\\\\s\\\\S]{5,}$\/","message":"There was a problem with the field MinLength"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testMaxLength() {
		$this->JsValidate->validate = array('maxLength' => $this->JsValidate->backupValidate['maxLength']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateMaxLength":[{"rule":"\/^[\\\\s\\\\S]{0,7}$\/","message":"There was a problem with the field MaxLength"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testIsMoney() {
		$this->JsValidate->validate = array('money' => $this->JsValidate->backupValidate['money']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateMoney":[{"rule":{"rule":"money"},"message":"There was a problem with the field Money"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testIsMultiple() {
		$this->JsValidate->validate = array('multiple' => $this->JsValidate->backupValidate['multiple']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateMultiple":[{"rule":{"rule":"multiple","params":{"in":["foo","bar"],"max":3,"min":1}},"message":"There was a problem with the field Multiple"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testNumeric() {
		$this->JsValidate->validate = array('numeric' => $this->JsValidate->backupValidate['numeric']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateNumeric":[{"rule":"\/^[+-]?[0-9|.]+$\/","message":"There was a problem with the field Numeric"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testNotEmpty() {
		$this->JsValidate->validate = array('notEmpty' => $this->JsValidate->backupValidate['notEmpty']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateNotEmpty":[{"rule":{"rule":"notEmpty"},"message":"There was a problem with the field NotEmpty"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testPhone() {
		$this->JsValidate->validate = array('phone' => $this->JsValidate->backupValidate['phone']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidatePhone":[{"rule":"\/^(?:\\\\+?1)?[-. ]?\\\\(?[2-9][0-8][0-9]\\\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}$\/","message":"There was a problem with the field Phone"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testPostal() {
		$this->JsValidate->validate = array('postal' => $this->JsValidate->backupValidate['postal']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidatePostal":[{"rule":"\/^[0-9]{5}(?:-[0-9]{4})?$\/i","message":"There was a problem with the field Postal"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testRange() {
		$this->JsValidate->validate = array('range' => $this->JsValidate->backupValidate['range']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateRange":[{"rule":{"rule":"range","params":[0,10]},"message":"There was a problem with the field Range"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testSsn() {
		$this->JsValidate->validate = array('ssn' => $this->JsValidate->backupValidate['ssn']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = '{"JsValidateSsn":[{"rule":"\/^[0-9]{3}-[0-9]{2}-[0-9]{4}$\/i","message":"There was a problem with the field Ssn"}]}';
		$this->assertEqual($expected, $validation);
	}

	public function testUrl() {
		$this->JsValidate->validate = array('url' => $this->JsValidate->backupValidate['url']);
		$validation = $this->Validation->bind('JsValidate.JsValidate', array('form' => false));
		$expected = "{\"JsValidateUrl\":[{\"rule\":\"\\/^(?:(?:https?|ftps?|file|news|gopher):\\\\\\/\\\\\\/)?(?:(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\\\\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])|\\\\[((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}(:|((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4}){0,4}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(((25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})(\\\\.(25[0-5]|2[0-4]\\\\d|[01]?\\\\d{1,2})){3})))(%.+)?\\\\]|(?:[a-z0-9][-a-z0-9]*\\\\.)*(?:[a-z0-9][-a-z0-9]{0,62})\\\\.(?:(?:[a-z]{2}\\\\.)?[a-z]{2,4}|museum|travel))(?::[1-9][0-9]{0,4})?(?:\\\\\\/?|\\\\\\/([\\\\!\\\"\\\\$&'\\\\(\\\\)\\\\*\\\\+,\\\\-\\\\.@_\\\\:;\\\\=~\\\\\\/0-9a-z\\\\p{L}\\\\p{N}]|(%[0-9a-f]{2}))*)?(?:\\\\?([\\\\!\\\"\\\\$&'\\\\(\\\\)\\\\*\\\\+,\\\\-\\\\.@_\\\\:;\\\\=~\\\\\\/0-9a-z\\\\p{L}\\\\p{N}]|(%[0-9a-f]{2}))*)?(?:#([\\\\!\\\"\\\\$&'\\\\(\\\\)\\\\*\\\\+,\\\\-\\\\.@_\\\\:;\\\\=~\\\\\\/0-9a-z\\\\p{L}\\\\p{N}]|(%[0-9a-f]{2}))*)?$\\/iu\",\"message\":\"There was a problem with the field Url\"}]}";
		$this->assertEqual($expected, $validation);
	}
}