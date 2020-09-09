<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Description of Admnistracion
*
* @author chrigarc
*/
class Ayuda extends MY_Controller
{

    const LISTA = 'lista', LIMIT = 25;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Ayuda_model', 'ayuda');
    }


    public function ver($clave = 'help')
    {
        $output['contenido'] = $this->ayuda->get($clave);
        $this->load->view('ayuda/modal.tpl.php', $output);
    }

    public function test()
    {
        $vista = $this->load->view('ayuda/test.tpl.php', null, true);
        $this->template->setMainContent($vista);
        $this->template->getTemplate();
    }

    public function opciones()
    {
        if ($this->input->is_ajax_request())
        { //Solo se accede al método a través de una petición ajax
            $data_post = $this->input->post(null, TRUE);
            if ($data_post)
            {//Valida datos por post
                //                $data_post[];
                $this->load->library('Ayudas_textos');

                $opcione_catalogo = json_decode($data_post['opciones']);

                switch ($data_post['tipo_ayuda'])
                {
                    case Ayudas_textos::OPCIONES_CATALOGO:
                    $catalogo = $this->ayudas_textos->decodifica_catalogo($data_post['elemento_ayuda']);
                    $result = $this->get_ayudas_opcion_catalogo(trim($catalogo), $opcione_catalogo);
                    if (!is_null($result))
                    {//Ejecuta las opciones de catálogo
                        $output_ayudas['ayudas'] = $result;
                        $output['contenido'] = $this->load->view('ayuda/general.tpl.php', $output_ayudas, true); //Imprime textos
                        $this->load->view('ayuda/modal.tpl.php', $output);
                    } else
                    {//Ejecuta las ayudas generales
                        $this->get($data_post['id_help']);
                    }
                    break;
                    case Ayudas_textos::ALL:
                    break;
                }
            }
        } else
        {

        }
    }

    private function get_ayudas_opcion_catalogo($catalogo, $opciones = [])
    {
        $this->load->model('Ayuda_model', 'ct');
        return $this->ct->get_ayudas_opcion($catalogo, $opciones, $this->ayudas_textos->get_busqueda_ayuda(), $this->ayudas_textos->get_busqueda_texto());
    }


}
