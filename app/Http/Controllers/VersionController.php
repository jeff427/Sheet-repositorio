<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Test2Controller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function generateData()
    {
        $faker = \Faker\Factory::create();

        /*$initDate = Carbon::parse('2024-01-01');
        $finishDate = Carbon::parse('2024-01-03');
        $diffDays = $finishDate->diffInDays($initDate);*/

        //0 obteber un registro aleatorio de la tabla sheet
        //1 crear registro en la tabla version donde la columna stateId sea 2(nueva), y la columna sheetId sea el Id de la Sheet del paso 0
        //2 crear un registro en la tabla sheet_history donde la columna hasVersionInReview sea 1, y la columna sheetId sea el Id de la Sheet del paso 0
        /*3 crear un registro en la tabla version_history, y la columna sheetId sea el Id de la Sheet del paso 0,
         asi mismo insertar el Id creado en la tabla sheet_history en la columna sheetHistoryId*/
         /*4 generar estados de forma aleatoroia en la tabla version_history*/

         //paso 0 obteber un registro aleatorio de la tabla sheet
        $randomSheet = DB::table('sheet')->get()->random();

        /*1 crear registro en la tabla version donde la columna stateId sea 2(nueva), y la columna 
        sheetId sea el Id de la Sheet del paso 0*/
        $stateIds = DB::table('state')->pluck('id');
        $manangerCodes = DB::table('collaborator')->pluck('code');
        $approverCodes = DB::table('collaborator')->pluck('code');
        $strategyCodes = DB::table('collaborator')->pluck('code');
        $serviceCodes = DB::table('collaborator')->pluck('code');
        $userCodes = DB::table('version_comment')->pluck('userCode');


        $versionHasConflict = $faker->boolean();
        $createdAt = $faker->dateTime(); // Utilizar la fecha de acuerdo a los dias transcurridos
        $newVersion = [
            'sheetId' => $randomSheet->id,
            'stateId' => 2, // Estado: Nueva
            'managerCode' => $manangerCodes->random(),
            'approverCode' => $approverCodes->random(),
            'strategyCode' => $strategyCodes->random(),
            'serviceCode' => $serviceCodes->random(),
            'isAcceptedByManager' => 0,
            'isAcceptedByApprover' => 0,
            'isAcceptedByStrategy' => 0,
            'isAcceptedByService' => 0,
            'number' => $faker->randomNumber(2), // El calculo del numero de versión se debe hacer consecutivo a los números de versión previos
            'inReview' => 0,
            'isCurrent' => 0,
            'requestType' => $faker->randomElement(['Solicitado por Manager', 'Solicitado por Service', 'Actualización masiva']),
            'massiveUpdateCode' => null,
            'isMinorChange' => $faker->boolean(),
            'hasConflict' => $versionHasConflict,
            'detailConflict' => $versionHasConflict ? $faker->text() : null,
            'userCodeConflict' => $userCodes->random(), // Preguntar cómo se determina este campo
            'publishedAt' => null,
            'commentOfRejection' => null,
            'limitOfFunctions' => $faker->randomNumber(2),
            'createdAt' => $createdAt,
            'updatedAt' => $createdAt,
            'deletedAt' => null,
        ];
        $newVersionId = DB::table('version')->insertGetId($newVersion);

        /*2 crear un registro en la tabla sheet_history donde la columna hasVersionInReview sea 1, y la columna 
        sheetId sea el Id de la Sheet del paso 0 */


        DB::table('sheet')->where('id', $randomSheet->id)->update(['hasVersionInReview' => 1]);
                
        $newSheetHistory = [
            'sheetId' => $randomSheet->id,
            'code' => $randomSheet->code,
            'jobProfileCode' => $randomSheet->jobProfileCode,
            'positionCode' => $randomSheet->positionCode,
            'areaCode' => $randomSheet->areaCode,
            'workforcePlanCode' => $randomSheet->workforcePlanCode,
            'unitCode' => $randomSheet->unitCode,
            'teamCode' => $randomSheet->teamCode,
            'uuid' => $randomSheet->uuid,
            'empty' => $randomSheet->empty,
            'isActive' => $randomSheet->isActive,
            'hasVersionInReview' => 1,
            'spreadSheet' => $randomSheet->spreadSheet,
            'createdAt' => $createdAt,
        ];
        $newSheetHistoryId = DB::table('sheet_history')->insertGetId($newSheetHistory);

        /*3 crear un registro en la tabla version_history, y la columna sheetId sea el Id de la Sheet del paso 0,
         asi mismo insertar el Id creado en la tabla sheet_history en la columna sheetHistoryId*/
 
         $newVersionHistory = [
            'versionId' => $newVersionId,
            'sheetHistoryId' => $newSheetHistoryId,
            'stateId' => 2, // Estado: Nueva
            'managerCode' => $newVersion['managerCode'],
            'approverCode' => $newVersion['approverCode'],
            'strategyCode' => $newVersion['strategyCode'],
            'serviceCode' => $newVersion['serviceCode'],
            'isAcceptedByManager' => $newVersion['isAcceptedByManager'],
            'isAcceptedByApprover' => $newVersion['isAcceptedByApprover'],
            'isAcceptedByStrategy' => $newVersion['isAcceptedByStrategy'],
            'isAcceptedByService' => $newVersion['isAcceptedByService'],
            'number' => $newVersion['number'],
            'inReview' => $newVersion['inReview'],
            'isCurrent' => $newVersion['isCurrent'],
            'requestType' => $newVersion['requestType'],
            'massiveUpdateCode' => $newVersion['massiveUpdateCode'],
            'isMinorChange' => $newVersion['isMinorChange'],
            'hasConflict' => $newVersion['hasConflict'],
            'detailConflict' => $newVersion['detailConflict'],
            'userCodeConflict' => $newVersion['userCodeConflict'],
            'publishedAt' => $newVersion['publishedAt'],
            'commentOfRejection' => $newVersion['commentOfRejection'],
            'limitOfFunctions' => $newVersion['limitOfFunctions'],
            'createdAt' => $createdAt,
            'userCode' => $newVersion['serviceCode'], // Preguntar cómo se determina este campo
            'description' => $faker->text(), // Revisar en la bd los textos que se ponen aquí
            'passedMin' => $faker->randomNumber(2), // Llevar un conteo de esto para agregarlo a la tabla version_ans al final
        ];
        DB::table('version_history')->insert($newVersionHistory);

        $response = [
            'message' => 'Data generated successfully',
            'sheetsModified' => $sheetsModified,
        ];
        return response()->json($response, 200);
    }
}