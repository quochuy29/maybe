<?php

namespace Modules\Member\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Member\Entities\Member;
use Illuminate\Support\Facades\Storage;
use Modules\Member\Service\ImportMember;
use Modules\Member\Service\ValidateDataCSV;
use Modules\Member\Service\ValidateFileCSV;
use Illuminate\Contracts\Support\Renderable;
use Modules\Member\Service\DataTranferMember;

class MemberController extends Controller
{
    protected $memberImport;
    protected $validateFile;

    public function __construct() {
        $this->memberImport = new ImportMember();
        $this->validateFile = new ValidateFileCSV();
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('member::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('member::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('member::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('member::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function uploadFile(Request $request)
    {
        if ($request->file('file')) {
            $errMsg = '';
            $path = $request->file('file')->getRealPath();
            $errCode = $this->validateFile->validateFileCsv($path, $errMsg);

            if ($errCode == 1) {
                return response()->json([
                    'error' => $errMsg
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            Storage::putFileAs('csv', $request->file('file'), 'members.csv');

            return response()->json([
                'success' => 'ok file'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'upload fileeeeeeeeeeeeee'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function importMember(ValidateDataCSV $validateDataCsv)
    {
        $path = Storage::path('csv/members.csv');

        $this->memberImport->excuteTempTable($path);
        $dataTranfer = (new DataTranferMember())->getDataTempTable();
        $intIsOk = $this->validateFile->validateFormatDataCsv($validateDataCsv, $dataTranfer, $aryError);

        if ($intIsOk === 0) {
            $fileName = $this->writeImportLogFile($aryError);

            return response()->json([
                'status' => 'Import failed',
                'file_name' => $fileName,
                'url_error' => "/storage/logs/{$fileName}"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $originTable = app(Member::class)->getTable();
            $tempTable = 'member_temp';

            try {
                $this->memberImport->executeTempTableData($tempTable);
                list($resultNumberUpdate, $resultNumberInsert) = $this->memberImport->updateMemberTable($originTable, $tempTable);
                return response()->json([
                    'status' => 200,
                    'message' => 'Import member successfully'
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                throw $e;
            }
        }
    }

    public function writeImportLogFile($errorMsg)
    {
        $content = '';
        $logs = config('member.path_write_file_log');
        $fileName = date('Ymd', time()) . '_' . date('His', time()) . '.txt';
        foreach ($errorMsg as $msg) {
            $line = $msg . PHP_EOL;
            $content .= $line;
        }
        if (!Storage::disk('local')->exists($logs)) {
            Storage::disk('local')->makeDirectory($logs);
        }
        Storage::disk('local')->put($logs . $fileName, $content);

        return $fileName;
    }
}
