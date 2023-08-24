<?php

namespace Modules\Member\Service;

class ValidateDataCSV
{
    private $_ruleList = [
        'member_code' => [
            ['Require' => []], ['MaxLength' => ['45']], ['FormatCode' => []], ['DuplicateMemberCode' => []]
        ],
        'member_password' => [
            ['PasswordEmpty' => []], ['PasswordLength' => ['6', '20'], ['FormatPassword' => []]]
        ],
        'member_email' => [
            ['Require' => []], ['MaxLength' => ['256']], ['FormatMail' => []], ['DuplicateMail' => []]
        ],
        'member_phone_number' => [
            ['MaxLength' => ['20']], ['FormatPhone' => []]
        ]

    ];
    private $_rules = [
        'Require' => 'Vui lòng nhập %s vào cột %s dòng %s.',
        'RequireIF' => 'Vui lòng nhập %s vào cột %s dòng %s.',
        'FormatCode' => 'Các ký tự có thể được sử dụng cho %s trong cột %s dòng %s là bảng chữ cái nửa chiều rộng (chữ hoa và chữ thường), số nửa chiều rộng và ký hiệu nửa chiều rộng（!#$%%\'()-^\@[;],./\=~|`{+*}?_）',
        'FormatMail' => 'Định dạng của địa chỉ email chính tại dòng %s, cột %s không hợp lệ. Ví dụ ：phanquochuyqthm@gmail.com.',
        'FormatPhone' => 'Sử dụng số một byte và (-), (*) và (+) cho %s trong hàng %s cột %s.',
        'MaxLength' => 'Vui lòng nhập %s vào dòng %s cột %s trong %s ký tự.',
        'PasswordEmpty' => 'Vui lòng nhập mật khẩu tại dòng %s, cột %s.',
        'PasswordLength' => 'Độ dài của mật khẩu tại dòng %s, cột %s không hợp lệ.',
        'FormatPassword' => 'Độ phức tạp của mật khẩu ở dòng %s, cột %s không hợp lệ.',
        'DuplicateMemberCode' => 'ID người dùng bị trùng lặp tại %s dòng %s cột.',
        'DuplicateMail' => 'Địa chỉ email chính tại dòng %s cột %s bị trùng lặp.',
    ];

    private $_pattern = [
        'formatCode' => "/^[a-z0-9\!\#\$\%\(\)\-\^\@\[\;\]\,\.\/\\\=\~\|\`\{\+\*\}\?\_\']+$/i",
        'formatMail' => '/^[a-zA-Z0-9?#$\-%^*&+=_.]+@[a-zA-Z0-9-]+[a-zA-Z0-9-.]+$/',
        'formatPhone' => '/^([0-9\-\*\+]*)$/',
        'formatPassword' => '/^[A-Za-z0-9\!\"\#\$\%\&\'\(\)\-\^\@\[\;\:\]\,\.\/\\\=\~\|\`\{\+\*\}\<\>\?\_]*$/',
    ];

    private $_data = [];

    private $_errors = [];

    private $_userList = [];

    private $_duplicateLoginName = [];

    private $_duplicateMail = [];

    private $memberEloquent;

    /** 
     * _duplicateMail lấy ra danh sách mail của từng user để so sánh
     * dữ liệu import
     * _userList lấy ra danh sách member login name của từng user
     * member login name sẽ được gán vào member code và validate
     * sẽ tìm member trong danh sách member login name
    **/

    public function __construct()
    {
        $this->memberEloquent = resolve(DataTranferMember::class);
        $this->_duplicateMail = $this->memberEloquent->getListMemberMail();
        $this->_userList = $this->memberEloquent->getListUserCode();
        $this->reBuildRuleList();
    }

    /**
     * hàm reBuildRuleList xử lý build lại rule các trường
    **/

    public function reBuildRuleList()
    {
        $passwordLengthMin = 1;
        $passwordLengthMax = 20;
        $this->_ruleList['member_password'][1] = [
            'PasswordLength' => [$passwordLengthMin, $passwordLengthMax],
        ];
    }

    /**
     * hàm setData gán data dữ liệu từ hàm validateFormatDataCsv 
    **/

    public function setData($Data)
    {
        $this->_data = $Data;
        return $this;
    }

    /**
     * hàm getData sẽ lấy data sau khi gán từ hàm setData 
    **/

    public function getData()
    {
        return $this->_data;
    }

    /**
     * hàm __call sẽ xử lý nếu như gọi đến 1 hàm không tồn tại 
    **/

    public function __call($name, $args)
    {
        if (method_exists($this, $name)) {
            return $this->$name($args);
        }
        return true;
    }

    /**
     * hàm isValid sẽ kiểm tra sau khi validate hết tất cả data
     * được gọi ở hàm validateFormatDataCsv 
    **/

    public function isValid()
    {
        if (empty($this->_errors)) {
            return true;
        }
        return false;
    }

    /**
     * hàm emptyError sẽ đc làm mới mảng chứa error
     * được gọi ở hàm validateFormatDataCsv sau khi đã lấy ra hết lỗi 
    **/

    public function emptyError()
    {
        $this->_errors = [];
    }

    /**
     * hàm setError sẽ set Error để in ra vị trí của lỗi 
    **/

    public function setError($rule, $errorPosition)
    {
        while (!empty($errorPosition)) {
            $messages = $this->_rules[$rule] ?? $rule;
            $position = array_shift($errorPosition);
            $messages = sprintf($messages, ...$position);
            $this->_errors[] = $messages;
        }
    }

    /**
     * hàm getError sẽ lấy ra hết các lỗi được đưa vào biến _errors 
    **/

    public function getError()
    {
        return $this->_errors;
    }

    /**
     * hàm validate dùng để gọi ra các hàm xử lý validate từ _ruleList
     * hàm getData để lấy ra data từ import
     * setError xử lý lỗi error và đưa vào mảng _errors 
    **/

    public function validate()
    {
        $data = $this->getData();

        foreach ($this->_ruleList as $field => $ruleGroup) {
            foreach ($ruleGroup as $aryRule) {
                $skipValidate = false;

                foreach ($aryRule as $ruleName => $params) {
                    $errorPosition = [];
                    $action = "check{$ruleName}";
                    $valid = $this->$action($data, $errorPosition, $field, ...$params);
                    if (!$valid) {
                        $this->setError($ruleName, $errorPosition);

                        $skipValidate = true;
                    }
                }

                if ($skipValidate) {
                    break;
                }
            }
        }
    }

    public function checkRequire($data, &$errorPosition, $field)
    {
        if ($data[$field]['value'] == '') {
            $errorPosition[] = [
                $data[$field]['header'],
                $data[$field]['col'],
                $data[$field]['row']
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkFormatCode($data, &$errorPosition, $field)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }

        $result = preg_match($this->_pattern['formatCode'], $data[$field]['value']);
        if (!$result) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header']
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkFormatMail($data, &$errorPosition, $field)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }

        $isContainSpecialChars = (strpos($data[$field]['value'], '.@') !== false || strpos($data[$field]['value'], '@.') !== false);
        $domain = explode('@', $data[$field]['value'])[1] ?? '';
        $isContainSpecialCharsInDomain = (strpos($domain, '-.') !== false || strpos($domain, '.-') !== false);

        if (!@preg_match($this->_pattern['formatMail'], $data[$field]['value']) || $isContainSpecialChars || $isContainSpecialCharsInDomain) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header']
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkFormatPhone($data, &$errorPosition, $field)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }
        if (!preg_match($this->_pattern['formatPhone'], $data[$field]['value'])) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header']
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkMaxLength($data, &$errorPosition, $field, $max)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }

        if (mb_strlen($data[$field]['value']) > $max) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header'],
                $max
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkPasswordLength($data, &$errorPosition, $field, $min, $max)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }

        if (mb_strlen($data[$field]['value']) < $min || mb_strlen($data[$field]['value']) > $max) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkFormatPassword($data, &$errorPosition, $field)
    {
        if ($data[$field]['value'] == '') {
            return true;
        }
        if (!@preg_match($this->_pattern['formatPassword'], $data[$field]['value'])) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkNewLine($data, &$errorPosition, $field)
    {
        if (str_replace(["\n", "\r"], '', $data[$field]['value']) != $data[$field]['value']) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header'],
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkPasswordEmpty($data, &$errorPosition, $field)
    {
        if (str_contains($data[$field]['value'], ' ')) {
            return true;
        }
        $code = mb_strtolower($data['member_code']['value']);

        if (in_array($code, $this->_userList)) {
            return true;
        }
        if ($data[$field]['value'] == '') {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
            ];
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkDuplicateMemberCode($data, &$errorPosition, $field)
    {
        $code = mb_strtolower($data[$field]['value']);
        if (isset($this->_duplicateLoginName[$code])) {
            $errorPosition[] = [
                $data[$field]['row'],
                $data[$field]['col'],
                $data[$field]['header'],
            ];
        } else {
            $this->_duplicateLoginName[$code] = false;
        }

        if (!empty($errorPosition)) {
            return false;
        }
        return true;
    }

    public function checkDuplicateMail($data, &$errorPosition, $field)
    {
        $mail = $data[$field]['value'];
        $loginName = $data['member_code']['value'] ?? '';

        if (!array_search($mail, $this->_duplicateMail)) {
            $this->_duplicateMail[$loginName] = $mail;
            return true;
        }

        if ((string)array_search($mail, $this->_duplicateMail) === (string)$loginName) {
            return true;
        }

        $errorPosition[] = [
            $data[$field]['row'],
            $data[$field]['col'],
        ];

        return false;
    }

}

?>