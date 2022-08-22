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
                "fichas" => pods_field( 'evoluciones', true )
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
        }

        return $data;
    }
}