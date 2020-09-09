<?php
/**
 * Description of Ayudas_textos
 *
 * @author leas
 */
class Ayudas_textos
{

    const POR_ELEMENTO = 1,
            OPCIONES_CATALOGO = 2,
            ALL = 3
            ;

    private $busqueda_texto;
    private $busqueda_ayuda;

    //put your code here
    public function __construct()
    {
        $this->CI = & get_instance();
    }

    public function codifica_catalogo($nombre_catalogo)
    {
        $this->CI->load->library('Seguridad');
        return $this->CI->seguridad->encrypt_ecb($nombre_catalogo);
    }

    public function decodifica_catalogo($catalogo_codificado)
    {
        $this->CI->load->library('Seguridad');
        return $this->CI->seguridad->decrypt_ecb($catalogo_codificado);
    }

    function get_busqueda_texto()
    {
        if (is_null($this->busqueda_texto))
        {
            $this->busqueda_texto = 'nombre';
        }
        return $this->busqueda_texto;
    }

    function get_busqueda_ayuda()
    {
        if (is_null($this->busqueda_ayuda))
        {
            $this->busqueda_ayuda = 'descripcion';
        }
        return $this->busqueda_ayuda;
    }

    function set_busqueda_texto($busqueda_texto)
    {
        $this->busqueda_texto = $busqueda_texto;
    }

    function set_busqueda_descripcion($busqueda_ayuda)
    {
        $this->busqueda_ayuda = $busqueda_ayuda;
    }

}
