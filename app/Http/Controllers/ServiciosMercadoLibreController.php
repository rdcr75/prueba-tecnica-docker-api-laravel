<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiciosMercadoLibreController extends Controller
{
    public function search(Request $request)
    {
        # Recoger datos
        $query = $request->input('query');
		$sort = $request->input('sort');

        # Validacion de Cache
        /*if(Cache::has($query) && Cache::has($sort)){
            $datos = Cache::get($query);
        }else{*/
            # API REST a consumir
            $cliente = new \GuzzleHttp\Client();
            $respuesta = $cliente->request('GET','https://api.mercadolibre.com/sites/MLA/search?q='.$query.'&sort='.$sort);
            $r = $respuesta->getBody();
            $params_array = json_decode($r, true);
        
            for( $i=0; $i<count($params_array['results']); $i++){
                $datos[$i]['id'] = $params_array['results'][$i]['id'];
                $datos[$i]['title'] = $params_array['results'][$i]['title'];
                $datos[$i]['price'] = $params_array['results'][$i]['price'];
				$datos[$i]['currency_id'] = $params_array['results'][$i]['currency_id'];
                $datos[$i]['available_quantity'] = $params_array['results'][$i]['available_quantity'];
                $datos[$i]['thumbnail'] = $params_array['results'][$i]['thumbnail'];
                $datos[$i]['condition'] = $params_array['results'][$i]['condition'];
            } 

            Cache::put($query, $datos);
			Cache::put($sort, $datos);
        //}

        # Salida
		/*
        $data = array(
            'code' => 200,
            'status' => 'success',
            'msg' => 'OK',
            'resultados' => $datos
        );
		*/
        
        return response()->json($datos);
    }
}
