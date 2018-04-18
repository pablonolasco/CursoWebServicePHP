<?php

    function paginar_todo($tabla,$pagina_solicitada=1,$por_pagina=20,$campos=array()){

        $CI=&get_instance();
        $CI->load->database();
        //hacer un puntero porque no existe this en los helpers
        if(!isset($por_pagina)){
            $por_pagina=20;//sino existe se le asigna el valor por default
        }
        if(!isset($pagina_solicitada)){
            $pagina_solicitada=1;//sino existe se le asigna el valor por default
        }

        $total_registros=$CI->db->count_all($tabla);
        $total_paginas=ceil($total_registros/$por_pagina);//cell funcion que redondea

        if($pagina_solicitada > $total_paginas){
            //si solicitan una pagina mayor al total de paginas 55, 10 paginas en total
            $pagina_solicitada=$total_paginas;//asigna la ultima pagina

        }

        $pagina_solicitada-=1;//para que empiece en 0
        $desde=$pagina_solicitada*$por_pagina;//segundo parametro del limit

        if($pagina_solicitada>=$total_paginas-1){
            //si llega a la ultima pagina y le das siguiente, se le asigna 1
            $pagina_siguiente=1;

        }else{
            $pagina_siguiente=$pagina_solicitada+2;
        }

        if($pagina_solicitada<1){
            $pagina_anterior=$total_paginas;
        }else{
            $pagina_anterior=$pagina_solicitada;
        }

        $CI->db->select($campos);
        $query=$CI->db->get($tabla,$por_pagina,$desde);
        $respuesta=array(
            'err'=>FALSE,
            'cuantos'=>$total_registros,
            'total_paginas'=>$total_paginas,
            'pagina_actual'=>($pagina_solicitada+1),
            'pagina_siguiente'=>($pagina_siguiente),
            'pagina_anterior'=>($pagina_anterior),
           $tabla=>$query->result()
        );
        return $respuesta;
    }
?>