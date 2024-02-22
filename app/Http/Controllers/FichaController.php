<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FichaController extends Controller
{
    public function sheeth()
    {
        $faker = \Faker\Factory::create();

        //aca genera fechas del 01/01/2024 al 01/04/2024
        $initDate = Carbon::parse('2024-01-01');
        $finishDate = Carbon::parse('2024-04-01');
        $diffDate = $finishDate->diffInDays($initDate);

        //aca llamamos los datos de las demas tabla sy los gusradamos en variabes
        $version_idDisponibles = DB::table('version')->pluck('id');
        $sheet_history_idDisponibles = DB::table('sheet_history')->pluck('id');
        $state_idDisponibles = DB::table('state')->pluck('id');
        $manangerCodeDisponible = DB::table('collaborator')->pluck('code');
        $approverCodeDisponible = DB::table('collaborator')->pluck('code');
        $strategyCodeDisponible = DB::table('collaborator')->pluck('code');
        $serviceCodeDisponible = DB::table('collaborator')->pluck('code');
        $userCodeDisponible = DB::table('version_comment')->pluck('userCode');
     
        
        //aplicamos el bucle for
       for ($i = 0; $i < $diffDate; $i++) {

        //los datos de las tablas los gusadmos en variables y luego los generamos en de manera ramdom
        $aleatorio1 = random_int(0, $version_idDisponibles->count()-1);
        $aleatorio2 = random_int(0, $sheet_history_idDisponibles->count()-1);
        $aleatorio3 = random_int(0, $state_idDisponibles->count()-1);
        $aleatorio4 = random_int(0, $manangerCodeDisponible->count()-1);
        $aleatorio5 = random_int(0, $approverCodeDisponible->count()-1);
        $aleatorio6 = random_int(0, $strategyCodeDisponible->count()-1);
        $aleatorio7 = random_int(0, $serviceCodeDisponible->count()-1);
        $aleatorio8 = random_int(0, $userCodeDisponible->count()-1);

        $publishedAt = rand(0, 100) < 30 ? $faker->dateTimeBetween($initDate, $finishDate)->format('Y-m-d H:i:s') : null;

            //empezanmos a poblar la base de datos
        $product_faker = [
                  
            'versionId' => $version_idDisponibles[$aleatorio1],
            'sheetHistoryId' => $sheet_history_idDisponibles[ $aleatorio2],
            'stateId' => $state_idDisponibles[$aleatorio3],
            'managerCode' => $manangerCodeDisponible[$aleatorio4],
            'approverCode' => $faker->optional(0.5, $approverCodeDisponible[$aleatorio5])->randomElement([$approverCodeDisponible[$aleatorio5], null ]),
            'strategyCode' => $faker->optional(0.5, $strategyCodeDisponible[$aleatorio6])->randomElement([$strategyCodeDisponible[$aleatorio6], null ]),
            'serviceCode' => $faker->optional(0.5, $serviceCodeDisponible[$aleatorio7])->randomElement([$serviceCodeDisponible[$aleatorio7], null ]),
            'isAcceptedByManager' => $manangerCodeDisponible[$aleatorio4] !== null ? 1 : 0,
            'isAcceptedByApprover' => $approverCodeDisponible[$aleatorio5] !== null ? 1 : 0,
            'isAcceptedByStrategy' => $strategyCodeDisponible[$aleatorio6] !== null ? 1 : 0,
            'isAcceptedByService' => $faker->boolean($serviceCodeDisponible[$aleatorio7] !== null),
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
            'userCode' =>  $userCodeDisponible[$aleatorio8],
            'description' => $faker->sentence(),
            'passedMin' => $faker->numberBetween(1, 10)
    
        ];
        //guardamos el id generado en un variable
        $idInserted1 = DB::table('version_history')->insertGetId($product_faker);
    }
        
        //retornamos los resultados
        $ars = DB::table('version_history')->get();         
        return response()->json(['message' => $ars], 200);
}
}