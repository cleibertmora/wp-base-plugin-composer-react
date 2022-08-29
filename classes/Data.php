<?php 
/**
 *
 * @package WP_VicEntrenoPlugin
 */

namespace VicEntrenoApp;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Data {

    public static function get_pod_post_data( $the_ID )
    {
        $pod = pods($the_ID);
        $data = null;

        if( $pod ) {
            // GET CLIENT GENERAL INFO
            $data = array(
                "id" => pods_field_display( 'ID' ),
                "nombre" => get_the_title(),
                "nro_celular" => pods_field_display( 'nro_celular' ),
                "inicio_hora_entranamiento" => pods_field_display( 'inicio_hora_entranamiento' ),
                "fin_hora_entranamiento" => pods_field_display( 'fin_hora_entranamiento' ),
                "fecha_inicio" => date( 'd/m/Y',  strtotime(pods_field_display( 'fecha_de_inicio' )) ),
                "fecha_nacimiento" => date( 'd/m/Y',  strtotime(pods_field_display( 'fecha_nacimiento' )) ),
                "patologia" => pods_field_display( 'patologia' ),
                "alergia" => pods_field_display( 'alergico' ),
                "lesion" => pods_field_display( 'lesion' ),
                "email" => pods_field_display( 'email' ),
                "contacto" => pods_field_display( 'nro_celular' ),
                "descripcion" => pods_field_display( 'descripcion' ),
                "today" => date( 'd/m/Y' ),
                "foto_cliente" => get_the_post_thumbnail_url( get_the_ID(), "thumbnail" ),
                "fichas" => pods_field( 'evoluciones', true ),
                "pagos" => pods_field( 'pagos', true )
            );

            // GET FICHAS CLIENT
            if( isset($data['fichas']) ){
                $fichasFormatted = [];

                foreach($data['fichas'] as $item){
                    $ficha_pod = pods( 'ficha', $item["ID"] );
                    
                    array_push($fichasFormatted, array(
                        "id" => $ficha_pod->display("ID"),
                        "titulo" => $ficha_pod->display("title"),
                        "peso" => $ficha_pod->display("peso"),
                        "estatura" => $ficha_pod->display("estatura"),
                        "cuello" => $ficha_pod->display("cuello"),
                        "pecho" => $ficha_pod->display("pecho"),
                        "cintura" => $ficha_pod->display("cintura"),
                        "pierna_derecha" => $ficha_pod->display("pierna_derecha"),
                        "pierna_izquierda" => $ficha_pod->display("pierna_izquierda"),
                        "observacion" => $ficha_pod->display("observacion"),
                        "imagenes" => $ficha_pod->display("imagenes")
                    ));				
                }
                
                $data['fichas'] = $fichasFormatted;
            }

            // GET PAYMENTS CLIENT
            if( isset($data['pagos']) ){
                $pagos = self::populate_pagos($data['pagos']);
                
                $data['pagos'] = $pagos;
            } 
        }

        return $data;
    }

    public static function retrive_active_payment( $the_ID )
    {
        $pod = pods('cliente', $the_ID);
        $pagos = array();

        if( $pod ) {
            $pagosDB = $pod->field( 'pagos', true );
            
            if( count($pagosDB) > 0 ){
                $pagos = self::populate_pagos($pagosDB);
            }
        }

        if( $pagos > 0 ){
            $mostRecent= 0;
            $index = null;
            
            foreach($pagos as $key => $pago){
                $valido_hasta = str_replace('/', '-', $pago['valido_hasta']);
                $curDate = strtotime($valido_hasta);
                $now = time();

                if($curDate > $now){
                    if ($curDate > $mostRecent) {
                        $mostRecent = $curDate;
                        $index = $key;
                    }
                }

            }

            return $pagos[$index];
        }

        return false;
    }

    protected function populate_pagos($pagos)
    {
        $pagosFormatted = array();
        
        foreach($pagos as $item){
            $pago_pod = pods( 'pago', $item["ID"] );
            
            array_push($pagosFormatted, array(
                "id" => $pago_pod->display("ID"),
                "titulo" => $pago_pod->display("title"),
                "fecha_pago" => $pago_pod->display("fecha_de_pago"),
                "valido_hasta" => $pago_pod->display("valido_hasta"),
                "detalle" => $pago_pod->display("detalle"),
                "comprobante" => $pago_pod->display("comprobante")
            ));
        }

        return $pagosFormatted;
    }
}