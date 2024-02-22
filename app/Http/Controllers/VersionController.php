<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VersionController extends Controller
{
    public function version()
    {
        $faker = \Faker\Factory::create();
        
        $initDate = Carbon::parse('2024-01-01');
        $finishDate = Carbon::parse('2024-04-01');
        $diffDate = $finishDate->diffInDays($initDate);
        

        $jobProfileCodeDisponibles = DB::table('job_profile')->pluck('code');
        $positionCodeDisponibles = DB::table('position')->pluck('code');
        $areaCodeDisponible = DB::table('area')->pluck('code');
        $workforcePlanCodeDisponible = DB::table('workforce_plan')->pluck('code');
        $unitCodeDisponible = DB::table('unit')->pluck('code');
        $teamCodeDisponible = DB::table('team')->pluck('code');          
        $state_idDisponibles = DB::table('state')->pluck('id');
        $manangerCodeDisponible = DB::table('collaborator')->pluck('code');
        $approverCodeDisponible = DB::table('collaborator')->pluck('code');
        $strategyCodeDisponible = DB::table('collaborator')->pluck('code');
        $serviceCodeDisponible = DB::table('collaborator')->pluck('code');
        $userCodeDisponible = DB::table('version_comment')->pluck('userCode');
    

        $CodeAleatorio = random_int(0, $jobProfileCodeDisponibles->count()-1);
        $positionCodeAleatorio = random_int(0, $positionCodeDisponibles->count()-1);
        $AreaCodeAleatorio = random_int(0, $areaCodeDisponible->count()-1);
        $workforcePlanCodeAleatorio = random_int(0, $workforcePlanCodeDisponible->count()-1);
        $unitCodeAleatorio = random_int(0, $unitCodeDisponible->count()-1);
        $teamCodeAleatorio = random_int(0, $teamCodeDisponible->count()-1);
        $stateIdAleatorio = random_int(0, $state_idDisponibles->count()-1);
        $managerCodeAleatorio = random_int(0, $manangerCodeDisponible->count()-1);
        $approverCodeAleatorio = random_int(0, $approverCodeDisponible->count()-1);
        $strategyCodeAleatorio = random_int(0, $strategyCodeDisponible->count()-1);
        $serviceCodeAleatorio = random_int(0, $serviceCodeDisponible->count()-1);
        $userCodealeatorio = random_int(0, $userCodeDisponible->count()-1);

        $codeidVar = $jobProfileCodeDisponibles[$CodeAleatorio] . '-' . $positionCodeDisponibles[$positionCodeAleatorio];
        $jobProfileCodeVar = $jobProfileCodeDisponibles[$CodeAleatorio];
        $positionCodeVar = $positionCodeDisponibles[$positionCodeAleatorio];
        $areaCodeVar = $areaCodeDisponible[$AreaCodeAleatorio];
        $workforcePlanCodeVar = $workforcePlanCodeDisponible[$workforcePlanCodeAleatorio];
        $unitCodeVar = $unitCodeDisponible[$unitCodeAleatorio];
        $teamCodeVar = $teamCodeDisponible[$teamCodeAleatorio];
        $uuidVar = $faker->uuid();
        $emptyVar = $faker->boolean();
        $isActiveVar = $faker->boolean();
        $hasVersionInReviewVar = $faker->boolean();
        $spreadSheetVar = $faker->url();
        $StateIdVar = $state_idDisponibles[$stateIdAleatorio];
        $managerCodeVar = $manangerCodeDisponible[$managerCodeAleatorio];
        $approverCodeVar = $faker->optional(0.5, $approverCodeDisponible[$approverCodeAleatorio])->randomElement([$approverCodeDisponible[$approverCodeAleatorio], null ]);
        $strategyCodeVar = $faker->optional(0.5, $strategyCodeDisponible[$strategyCodeAleatorio])->randomElement([$strategyCodeDisponible[$strategyCodeAleatorio], null ]);
        $serviceCodeVar = $faker->optional(0.5, $serviceCodeDisponible[$serviceCodeAleatorio])->randomElement([$serviceCodeDisponible[$serviceCodeAleatorio], null ]);
        $isAcceptedByManagerVar = $manangerCodeDisponible[$managerCodeAleatorio] !== null;
        $isAcceptedByApproverVar = $approverCodeDisponible[$approverCodeAleatorio] !== null;
        $isAcceptedByStrategyVar = $strategyCodeDisponible[$strategyCodeAleatorio] !== null;
        $isAcceptedByServiceVar = $serviceCodeDisponible[$serviceCodeAleatorio] !== null;
        $numberVar = $faker->numberBetween(1, 10);
        $inReviewVar = $faker->boolean();
        $isCurrentVar = $faker->boolean();
        $requestTypeVar = $faker->randomElement(['Solicitado por Manager', 'Solicitado por Service']);
        $massiveUpdateCodeVar = $faker->optional(0, null)->word();
        $isMinorChangeVar = $faker->boolean();
        $hasConflictVar = $faker->boolean();
        $detailConflictVar = $faker->sentence();
        $userCodeConflictVar = $faker->regexify('[A-Z]{2}\d{5}');
        $commentOfRejectionVar = $faker->sentence();
        $createdAtVar = $faker->dateTimeBetween($initDate, $finishDate)->format('Y-m-d H:i:s');
        $publishedAtVar = rand(0, 100) < 30 ? $faker->dateTimeBetween($initDate, $finishDate)->format('Y-m-d H:i:s') : null;
        $updatedAtVar = rand(0, 100) < 30 ? $faker->dateTimeBetween($initDate, $finishDate)->format('Y-m-d H:i:s') : null;

            //table Sheet
            $Sheet_faker = [
                'code' => $codeidVar,
                'jobProfileCode' => $jobProfileCodeVar,
                'positionCode' => $positionCodeVar,
                'areaCode' => $areaCodeVar,
                'workforcePlanCode' => $workforcePlanCodeVar,
                'unitCode' => $unitCodeVar,
                'teamCode' => $teamCodeVar,
                'uuid' => $uuidVar,
                'empty' => $emptyVar,
                'isActive' => $isActiveVar,
                'hasVersionInReview' => $hasVersionInReviewVar,
                'spreadSheet' => $spreadSheetVar,
                'createdAt' => $createdAtVar,
                'updatedAt' => $updatedAtVar,
                'deletedAt' => null
            ];
            $idsheetFaker = DB::table('sheet')->insertGetId($Sheet_faker);
        
            //table Sheet_history
            $Sheet_History_faker = [
                'sheetId' => $idsheetFaker,
                'code' => $codeidVar,
                'jobProfileCode' => $jobProfileCodeVar,
                'positionCode' => $positionCodeVar,
                'areaCode' => $areaCodeVar,
                'workforcePlanCode' => $workforcePlanCodeVar,
                'unitCode' => $unitCodeVar,
                'teamCode' => $teamCodeVar,
                'uuid' => $uuidVar,
                'empty' => $emptyVar,
                'isActive' => $isActiveVar,
                'hasVersionInReview' => $hasVersionInReviewVar,
                'spreadSheet' => $spreadSheetVar,
                'createdAt' => $createdAtVar
            ];
            $idsheet_historyFaker = DB::table('sheet_history')->insertGetId($Sheet_History_faker);


            //Table version       
            $version_faker = [
                'sheetId' => $idsheetFaker,
                'stateId' => $StateIdVar,
                'managerCode' => $managerCodeVar,
                'approverCode' => $approverCodeVar,
                'strategyCode' =>  $strategyCodeVar,
                'serviceCode' => $serviceCodeVar,
                'isAcceptedByManager' => $isAcceptedByManagerVar,
                'isAcceptedByApprover' => $isAcceptedByApproverVar,
                'isAcceptedByStrategy' => $isAcceptedByStrategyVar,
                'isAcceptedByService' => $isAcceptedByServiceVar,
                'number' => $numberVar,
                'inReview' => $inReviewVar,
                'isCurrent' => $isCurrentVar,
                'requestType' => $requestTypeVar,
                'massiveUpdateCode' => $massiveUpdateCodeVar,
                'isMinorChange' => $isMinorChangeVar,
                'hasConflict' => $hasConflictVar,
                'detailConflict' => $detailConflictVar,
                'userCodeConflict' => $userCodeConflictVar,
                'publishedAt' => $publishedAtVar,
                'commentOfRejection' => $commentOfRejectionVar,
                'limitOfFunctions' => 50,
                'createdAt' => $createdAtVar,
                'updatedAt' => $updatedAtVar,
                'deletedAt' =>  null
            ];
            $versionIdVar = DB::table('version')->insertGetId($version_faker);
        
        //Table version history
        $userCodeDisponible = DB::table('version_comment')->pluck('userCode');
        $aleatorio8 = random_int(0, $userCodeDisponible->count()-1);

            $product_faker = [
                'versionId' => $versionIdVar,
                'sheetHistoryId' => $idsheet_historyFaker,
                'stateId' => $StateIdVar,
                'managerCode' => $managerCodeVar,
                'approverCode' => $approverCodeVar,
                'strategyCode' => $strategyCodeVar,
                'serviceCode' => $serviceCodeVar,
                'isAcceptedByManager' => $isAcceptedByManagerVar,
                'isAcceptedByApprover' => $isAcceptedByApproverVar,
                'isAcceptedByStrategy' => $isAcceptedByStrategyVar,
                'isAcceptedByService' => $isAcceptedByServiceVar,
                'number' => $numberVar,
                'inReview' => $inReviewVar,
                'isCurrent' => $isCurrentVar,
                'requestType' => $requestTypeVar,
                'massiveUpdateCode' => $massiveUpdateCodeVar,
                'isMinorChange' => $isMinorChangeVar,
                'hasConflict' => $hasConflictVar,
                'detailConflict' => $detailConflictVar,
                'userCodeConflict' => $userCodeConflictVar,
                'publishedAt' => $publishedAtVar,
                'commentOfRejection' => $commentOfRejectionVar,
                'limitOfFunctions' => 50,
                'createdAt' => $createdAtVar,
                'userCode' =>  $userCodeDisponible[$aleatorio8],
                'description' => $faker->sentence(),
                'passedMin' => $faker->numberBetween(1, 10)
        
            ];
        
            DB::table('version_history')->insert($product_faker);
    
        $vsheet = DB::table('version_history')->get();        
        return response()->json(['message' => $vsheet], 200);
    }
}