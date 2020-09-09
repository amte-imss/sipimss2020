<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Plantilla_model
 *
 * @author chrigarc
 */
class Plantilla_model extends MY_Model
{

    const P_DEFAULT = 0, BIENVENIDA_REGISTRO = 1, EMAIL_PRUEBA = 'zurgcom@gmail.com', 
            SUBJECT_PRUEBA = 'Mensaje de prueba', CUERPO_PRUEBA = 'Hola mundo';

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }

    /**
     * Metodo para enviar un email
     * @param type $plantilla
     * @param type $subject
     * @param type $address
     * @param type $parametros
     */
    public function send_mail($plantilla = Plantilla_model::P_DEFAULT, 
            $subject = Plantilla_model::SUBJECT_PRUEBA, $address = Plantilla_model::EMAIL_PRUEBA, $parametros = [])
    {
        $this->load->config('email');
        $this->load->library('My_phpmailer');
        $mailStatus = $this->my_phpmailer->phpmailerclass();
        $contenido = $this->view($plantilla, $parametros);
        $mailStatus->addAddress($address);
        $mailStatus->Subject = $subject;
        $mailStatus->msgHTML(utf8_decode($contenido));
        $mailStatus->send();
    }

    /**
     * 
     * @param type $plantilla - int id que representa la plantilla dentro de la base de datos
     * @param type $parametros - arreglo asociativo con los valores que deben ser reemplazados en la plantilla
     * @return type string - plantilla con los parametros colocados
     */
    public function view($plantilla = Plantilla_model::P_DEFAULT, $parametros = [])
    {
        $cuerpo = Plantilla_model::CUERPO_PRUEBA;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('contenido');
        $this->db->where('id_plantilla', $plantilla);
        $this->db->where('activa', true);

        $cuerpo = $this->db->get('ui.plantillas')->result_array();
        if ($cuerpo)
        {
            $cuerpo = $this->set_params($cuerpo[0], $parametros);
        }
        $this->db->flush_cache();
        $this->db->reset_query();

        return $cuerpo;
    }

    private function set_params(&$cuerpo, &$parametros)
    {
        if (!is_null($cuerpo) && !is_null($parametros))
        {
            foreach ($parametros as $key => $value)
            {
                $cuerpo = str_replace('$$' . $key . '$$', $value, $cuerpo);
            }
        }
    }

}
