<?php 
/**
 *
 * @package WP_VicEntrenoPlugin
 */

namespace VicEntrenoApp;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class FrontEnd {
    
    public function __construct()
	{
		add_filter( 'the_content', array($this,'add_content_to_cliente_post_type'), 1 );
	}

	public function add_content_to_cliente_post_type( $content )
	{
		global $post;

			if ( is_singular() && in_the_loop() && 'cliente' === get_post_type() && is_main_query() ) { 
				
				wp_enqueue_style('vic-entreno-app-styles', plugins_url( '../assets/build/frontend.css', __FILE__ ));
				wp_enqueue_script( 'vic-entreno-app-front-end', plugins_url( '../assets/build/frontend.js', __FILE__ ), array('wp-element'), '1.0', true );

                $ficha_info = Data::get_pod_post_data( $post->ID );
				return $this->render_ficha_ui( $ficha_info );		

        } else {
			return $content;
		}
	}

	public function render_ficha_ui( $data ) 
    {
    ob_start();    
     ?>
        <div class="vic-entreno-app-styles">

            <?php 
                $lastPayment = Data::retrive_active_payment( $data["id"] );
                
                if(!$lastPayment){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="text-center">
                                <strong class="mx-2">Cliente inactivo o con membresía vencida</strong> <br>Por favor registrar pago de su membresía para continuar con el servicio.
                            </p>
                        </div>
                    <?php
                }
            ?>

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
			    <p class="text-center">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
						<path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
						<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
					</svg>
					<strong class="mx-2">¡Horario de Entrenamiento!</strong><br>Su horario asignado es: <b><?php echo $data["inicio_hora_entranamiento"] ?></b> a <b><?php echo $data["fin_hora_entranamiento"] ?></b> de lunes a viernes.
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</p>
			</div>
            <div class="row">
                <div class="col-sx-12 col-lg-4">
                    <div class="card shadow-sm">
                        <h5 class="card-header">Datos del cliente</h5>
                        <div class="card-body">
                        <div class="text-center">
                            <?php if($data['foto_cliente']): ?>
                                <img class="rounded-circle" src="<?php echo $data['foto_cliente'] ?>">
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            <?php endif; ?>
                            <h2><?php echo $data['nombre']?></h2>
                        </div>
                        <p class="card-text">
                            <?php if( $lastPayment ):?>
                                <div class="alert alert-success show text-center" role="alert">
                                    Membresía válida hasta: <b><?php echo $lastPayment['valido_hasta'] ?></b> <br>
                                </div>
                            <?php endif;?>
                            <b>Fecha inicio:</b> <?php echo $data['fecha_inicio'] ?><br>
                            <b>Fecha de nacimiento:</b> <?php echo $data['fecha_nacimiento'] ?><br>
                            <b>Edad:</b> <?php 
                                $fecha_nac = date_create($data['fecha_nacimiento']);
                                $today = date_create($data['today']);
                                
                                $calculateAge = intval(date("Y")) - intval(date_format($fecha_nac, "Y"));
                                $age = $calculateAge ? $calculateAge : "n/a";
                                echo $age;
                            ?><br>
                            <hr>
                            <h4 class="text-center">Test Salud</h4>
                            <b>Patología:</b> <?php echo $data['patologia'] ?><br>
                            <b>Alergías:</b> <?php echo $data['alergia'] ?><br>
                            <b>Lesiones:</b> <?php echo  $data['lesion'] ?><br>
                            <hr>
                            <h4 class="text-center">Contacto</h4>
                            <b>Email:</b> <?php echo $data['email'] ?><br>
                            <b>Contacto:</b> <?php echo $data['contacto'] ?><br>
                            <hr>
                            <h4 class="text-center">Observación del Entrenador</h4>
                            <?php echo $data['descripcion'] ?>
                        </p>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-5 mb-5">
                        <h5 class="card-header">Pagos</h5>
                        <?php if( isset($data['pagos']) ): ?>
                        <div class="accordion" id="accordionPagos">
                            <?php foreach( $data['pagos'] as $pago ): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo $pago["id"] ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $pago["id"] ?>" aria-expanded="true" aria-controls="collapse<?php echo $pago["id"] ?>">
                                            <?php echo $pago["titulo"] ?>               
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $pago["id"] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $pago["id"] ?>" data-bs-parent="#accordionPagos">
                                        <div class="accordion-body">
                                            <p>
                                                <b>Fecha pago:</b> <?php echo $pago['fecha_pago'] ?><br>
                                                <b>Valido hasta:</b> <?php echo $pago['valido_hasta'] ?><br>
                                                <b>Detalle:</b> <?php echo esc_html( strip_tags($pago['detalle']) ) ?><br>
                                            </p>
                                            <p class="text-center">
                                                <a target="_blank" href="<?php echo $pago['comprobante'] ?>">
                                                    <button class="btn btn-success">Ver comprobante</button>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center card-body">
                            <p>No hay pagos registrados.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-sx-12 col-lg-8">
                    <?php if($data['fichas']): ?>                        
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <?php foreach( $data['fichas'] as $key => $ficha ): ?>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link <?php echo $key == 0 ? 'active' : '' ?>" id="home-tab" data-bs-toggle="tab" data-bs-target="#ficha-<?php echo $ficha['id'] ?>" type="button" role="tab" aria-controls="home" aria-selected="true"><?php echo $ficha['titulo'] ?></button>
                                </li>

                            <?php endforeach; ?>
                        </ul>
                        <div class="card shadow-sm"><div class="card-body"><div class="tab-content">
                            <?php foreach( $data['fichas'] as $key => $ficha ): ?>
                                <?php
                                    $fichasImg = explode(" ", $ficha['imagenes']);

                                    $imgLinks = array_map(function($img){
                                        return '<a target="_blank" href="'. $img .'"><img class="img-fluid" src="'. $img .'" /></a><br>';
                                    }, $fichasImg);
                                ?>
                                
                                <div class="tab-pane <?php echo $key == 0 ? 'active' : '' ?>" id="ficha-<?php echo $ficha['id'] ?>" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                    <b>Estatura:</b> <?php echo $ficha['estatura'] ?><br> 
						            <b>Peso:</b> <?php echo $ficha['peso'] ?><br>
                                    <b>Estatura:</b> <?php echo $ficha['estatura'] ?><br>
                                    <b>Peso:</b> <?php echo $ficha['peso'] ?><br> 
                                    <b>Medida Cuello:</b> <?php echo $ficha['cuello'] ?><br>
                                    <b>Medida Pecho:</b> <?php echo  $ficha['pecho'] ?><br>
                                    <b>Medida Cintura:</b> <?php echo $ficha['cintura'] ?><br> 
                                    <b>Medida Pierna Derecha:</b> <?php echo $ficha['pierna_derecha'] ?><br> 
                                    <b>Medida Pierna Izquierda:</b> <?php echo $ficha['pierna_izquierda'] ?><br> 
                                    <b>Observación:</b> <?php echo esc_html( strip_tags($ficha['observacion']) ) ?><br><br>
                                    <?php echo implode("", $imgLinks) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    ob_get_contents();
    }
}
