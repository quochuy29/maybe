<?php

namespace Modules\Member\Service;

class ValidateFileCSV 
{
    protected $header;
    protected $item_tranfer;

    public function __construct()
    {
        $this->header = config('member.header_file_import');
        $this->item_tranfer = config('member.item_tranfer');
    }
    public function validateFileCsv($path, &$errMsg = '')
    {
        $headerFile = $this->header;
        $errCode = 0;
        $isValid = true;
        $countLine = 0;
        shell_exec("sed -i '1s/^\xEF\xBB\xBF//' " . $path);
        if (($handle = fopen($path, 'r')) !== false) {
            while(($line = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
                $lineStr = implode(',', $line);
                
                if (empty($line) || (string)$lineStr === "") {
                    continue;
                }

                if (!mb_detect_encoding($lineStr, 'UTF-8', true)) {
                    $isValid = false;
                    $errMsg = "Chuyển file sang mã UTF-8";
                    $errCode = 1;
                    break;
                }
                
                if (empty($line)) {
                    continue;
                }

                $countLine++;

                if (!$this->validateCsvLine($line, $headerFile)) {
                    $isValid = false;
                    $errMsg = "Bị thừa hoặc thiếu một mục import";
                    $errCode = 1;
                    break;
                }

                if ($countLine === 1 && !empty(array_diff($line, $headerFile))) {
                    $isValid = false;
                    $errMsg = "Header không hợp lệ";
                    $errMsg = 1;
                }
            }
        }
        fclose($handle);

        if ($isValid && $countLine < 1) {
            $isValid = false;
            $errCode = 1;
            $errMsg = 'Bị thừa hoặc thiếu một mục import';
        }

        if ($isValid && $countLine < 2) {
            $isValid = false;
            $errCode = 1;
            $errMsg = 'Nội dung của tệp được nhập không hợp lệ và không thể nhập được';
        }

        return $errCode;
    }

    public function validateCsvLine($lineContent, $headerFile)
    {
        if ((int)count($lineContent) != (int)count($headerFile)) {
            return false;
        }
        return true;
    }

    public function validateFormatDataCsv($validator, $dataMemberTemp, &$aryError)
    {
        $fieldItem = $this->item_tranfer;
        $fileHeader = $this->header;
        $aryError = [];
        try {
            while ($record = $dataMemberTemp->fetch(\PDO::FETCH_ASSOC)) {
                $row = $record['id'] + 1;
                unset($record['id']);
                $data = $this->prepareData(array_values($record), $row, $fieldItem, $fileHeader);
                $validator->setData($data);
                $validator->validate();

                if (!$validator->isValid()) {
                    $aryError = array_merge($aryError, $validator->getError());
                    $validator->emptyError();
                }
            }
            if (!empty($aryError)) {
                return 0;
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }
    }

    public function prepareData(array $recordData, $row, $fieldItem, $fieldHeader)
    {
        $formatedData = [];
        foreach ($recordData as $key => $cellData) {
            $value = is_null($cellData) ? '' : $cellData;
            $header = $fieldHeader[$key] ?? '';
            $field = $fieldItem[$key] ?? '';
            $formatedData[$field] = [
                'value' => $value,
                'header' => $header,
                'field' => $field,
                'col' => $key + 1,
                'row' => $row
            ];
        }
        return $formatedData;
    }

}

?>