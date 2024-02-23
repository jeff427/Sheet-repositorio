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

        $initDate = Carbon::parse('2024-01-01');
        $finishDate = Carbon::parse('2024-01-03');
        $diffDays = $finishDate->diffInDays($initDate);

        $stateIds = DB::table('state')->pluck('id');
        $manangerCodes = DB::table('collaborator')->pluck('code');
        $approverCodes = DB::table('collaborator')->pluck('code');
        $strategyCodes = DB::table('collaborator')->pluck('code');
        $serviceCodes = DB::table('collaborator')->pluck('code');
        $userCodes = DB::table('version_comment')->pluck('userCode');

        $sheets = DB::table('sheet')->get();
        $versions = DB::table('version')->get();

        $sheetsModified = [];
        for ($i = 0; $i < $diffDays; $i++) {
            // Generará 5 versiones por día para 5 fichas distintas elegidas aleatoriamente
            for ($j = 0; $j < 5; $j++) {
                $randomSheet = $sheets->random();

                // Creación de una nueva versión
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


                $sheetsModified[] = [
                    'sheet' => $randomSheet,
                    'version' => $newVersion,
                ];

                // Modificación y registro de la modificación de la ficha
                DB::table('sheet')->update(['hasVersionInReview' => 1]);
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

                // Modificación y registro de la modificación de la versión
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

                $randomStateId = $stateIds->random();
                //$randomStateId = rand(3, 15);
                // Modificamos la versión mientras aun no haya terminado su ciclo de vida

                while ($randomStateId != 3 && $randomStateId != 15) {

                    // Modiifcamos la versión de acuerdo al estado
                    DB::table('version')->where('id', $newVersionId)->update(['stateId' => $randomStateId]);

                    // Guardamos el historial de esa modificación
                    $newVersionHistory['stateId'] = $randomStateId;
                    // Agregar variaciones de los campos de acuerdo al estado
                    DB::table('version_history')->insert($newVersionHistory);

                    $randomStateId = $stateIds->random();
                }

                // Guardar el nuevo cambio de la ficha en sheetHistory
                $newSheetHistory['hasVersionInReview'] = 0;
                $newSheetHistory['empty'] = $randomStateId == 16 ? 0 : $newSheetHistory['empty'];
                $newSheetHistoryId = DB::table('sheet_history')->insertGetId($newSheetHistory);

                // Modiifcamos la versión de acuerdo a su último estado
                DB::table('version')->where('id', $newVersionId)->update(['stateId' => $randomStateId]);

                // Guardamos el historial de esa modificación vinculado al nuevo historial de la ficha
                $newVersionHistory['sheetHistoryId'] = $newSheetHistoryId;
                $newVersionHistory['stateId'] = $randomStateId;
                // Agregar variaciones de los campos de acuerdo al estado
                DB::table('version_history')->insert($newVersionHistory);
            }
        }

        $response = [
            'message' => 'Data generated successfully',
            'sheetsModified' => $sheetsModified,
        ];
        return response()->json($response, 200);
    }

}


