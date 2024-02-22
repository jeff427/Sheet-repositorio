<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SegundoController extends Controller
{
    public function sheeth()
    {
        $faker = \Faker\Factory::create();

            $initDate = Carbon::parse('2024-01-01');
            $finishDate = Carbon::parse('2024-04-01');
            $diffDate = $finishDate->diffInDays($initDate);

            //version_history
            $version_idDisponibles = DB::table('version')->pluck('id');
            $sheet_history_idDisponibles = DB::table('sheet_history')->pluck('id');
            $state_idDisponibles = DB::table('state')->pluck('id');
            $manangerCodeDisponible = DB::table('collaborator')->pluck('code');
            $approverCodeDisponible = DB::table('collaborator')->pluck('code');
            $strategyCodeDisponible = DB::table('collaborator')->pluck('code');
            $serviceCodeDisponible = DB::table('collaborator')->pluck('code');
            $userCodeDisponible = DB::table('version_comment')->pluck('userCode');

            //sheet history
            $sheetIdDisponibles = DB::table('sheet')->pluck('id');
            $jobProfileCodeDisponibles = DB::table('job_profile')->pluck('code');
            $positionCodeDisponibles = DB::table('position')->pluck('code');
            $areaCodeDisponible = DB::table('area')->pluck('code');
            $workforcePlanCodeDisponible = DB::table('workforce_plan')->pluck('code');
            $unitCodeDisponible = DB::table('unit')->pluck('code');
            $teamCodeDisponible = DB::table('team')->pluck('code');

            for ($i = 0; $i < 10; $i++) {
                $sheetIdDAleatorio= random_int(0, $sheetIdDisponibles->count()-1);
                $CodeAleatorio = random_int(0, $jobProfileCodeDisponibles->count()-1);
                $positionCodeAleatorio = random_int(0, $positionCodeDisponibles->count()-1);
                $AreaCodeAleatorio = random_int(0, $areaCodeDisponible->count()-1);
                $workforcePlanCodeAleatorio = random_int(0, $workforcePlanCodeDisponible->count()-1);
                $unitCodeAleatorio = random_int(0, $unitCodeDisponible->count()-1);
                $teamCodeAleatorio = random_int(0, $teamCodeDisponible->count()-1);
                $publishedAt = rand(0, 100) < 30 ? $faker->dateTimeBetween($initDate, $finishDate)->format('Y-m-d H:i:s') : null;
            
            $product_faker = [
                'sheetId' => $sheetIdDisponibles[$sheetIdDAleatorio],
                'code' => $jobProfileCodeDisponibles[$CodeAleatorio] . '-' . $positionCodeDisponibles[$positionCodeAleatorio],
                'jobProfileCode' => $jobProfileCodeDisponibles[$CodeAleatorio],
                'positionCode' => $positionCodeDisponibles[$positionCodeAleatorio],
                'areaCode' => $areaCodeDisponible[$AreaCodeAleatorio],
                'workforcePlanCode' => $workforcePlanCodeDisponible[$workforcePlanCodeAleatorio],
                'unitCode' => $unitCodeDisponible[$unitCodeAleatorio],
                'teamCode' => $teamCodeDisponible[$teamCodeAleatorio],
                'uuid' => $faker->uuid(),
                'empty' => $faker->boolean(),
                'isActive' => $faker->boolean(),
                'hasVersionInReview' => $faker->boolean(),
                'spreadSheet' => $faker->url(),
                'createdAt' => $faker->dateTimeThisDecade()->format('Y-m-d H:i:s')
            ];

            $idsheetHistoy = DB::table('sheet_history')->insertGetId($product_faker);

                $versionIdaleatorio = random_int(0, $version_idDisponibles->count()-1);
                $sheetHistoryIdAleatorio = random_int(0, $state_idDisponibles->count()-1);
                $managerCodeAleatorio = random_int(0, $manangerCodeDisponible->count()-1);
                $approverCodeAleatorio = random_int(0, $approverCodeDisponible->count()-1);
                $strategyCodeAleatorio = random_int(0, $strategyCodeDisponible->count()-1);
                $serviceCodeAleatorio = random_int(0, $serviceCodeDisponible->count()-1);
                $userCodealeatorio = random_int(0, $userCodeDisponible->count()-1);
       
            $product_faker2 = [
                'versionId' => $version_idDisponibles[$versionIdaleatorio],
                'sheetHistoryId' => $idsheetHistoy,
                'stateId' => $state_idDisponibles[$sheetHistoryIdAleatorio],
                'managerCode' => $manangerCodeDisponible[$managerCodeAleatorio],
                'approverCode' => $faker->optional(0.5, $approverCodeDisponible[$approverCodeAleatorio])->randomElement([$approverCodeDisponible[$approverCodeAleatorio], null ]),
                'strategyCode' => $faker->optional(0.5, $strategyCodeDisponible[$strategyCodeAleatorio])->randomElement([$strategyCodeDisponible[$strategyCodeAleatorio], null ]),
                'serviceCode' => $faker->optional(0.5, $serviceCodeDisponible[$serviceCodeAleatorio])->randomElement([$serviceCodeDisponible[$serviceCodeAleatorio], null ]),
                'isAcceptedByManager' => $manangerCodeDisponible[$managerCodeAleatorio] !== null,
                'isAcceptedByApprover' => $approverCodeDisponible[$approverCodeAleatorio] !== null,
                'isAcceptedByStrategy' => $strategyCodeDisponible[$strategyCodeAleatorio] !== null,
                'isAcceptedByService' => $serviceCodeDisponible[$serviceCodeAleatorio] !== null,
                'number' => $faker->numberBetween(1, 10),
                'inReview' => $faker->boolean(),
                'isCurrent' => $faker->boolean(),
                'requestType' => $faker->randomElement(['Solicitado por Manager', 'Solicitado por Service']),
                'massiveUpdateCode' => $faker->optional(0, null)->word(),
                'isMinorChange' => $faker->boolean(),
                'hasConflict' => $faker->boolean(),
                'detailConflict' => $faker->sentence(),
                'userCodeConflict' => $faker->regexify('[A-Z]{2}\d{5}'),
                'publishedAt' => $publishedAt,
                'commentOfRejection' => $faker->sentence(),
                'limitOfFunctions' => 50,
                'createdAt' => $faker->dateTimeThisDecade()->format('Y-m-d H:i:s'),
                'userCode' =>  $userCodeDisponible[$userCodealeatorio],
                'description' => $faker->sentence(),
                'passedMin' => $faker->numberBetween(1, 10)
            ];
            
            DB::table('version_history')->insert($product_faker2);   

            }
            $ars = DB::table('version_history')->get();        
            return response()->json(['message' => $ars], 200);
    }

}