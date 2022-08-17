<?php

namespace App\Models\Helpers;

class DependenciasHelper
{
    private $_ejecutora;
    private $_id;
    private $_ejecutora_padre;
    private $_id_padre;
    private $_clave;
    private $_siglas;
    private $_nombre;
    private $_titular;
    private $_cargotitular;
    private $_calle;
    private $_numeroexterior;
    private $_numerointerior;
    private $_colonia;
    private $_codigopostal;
    private $_ciudad;
    private $_municipio;
    private $_estado;
    private $_pais;
    private $_centralizado;
    private $_tokenauth;
    private $_mostrararbol;
    
    /*-->[Metodos set para cada una de las variables definidas]<--*/
    public function set_ejecutora( $ejecutora )
    {
        $this->_ejecutora = $ejecutora;
    }
    public function set_id( $id )
    {
        $this->_id = $id;
    }
    public function set_ejecutora_padre( $ejecutora_padre )
    {
        $this->_ejecutora_padre = $ejecutora_padre;
    }
    public function set_id_padre( $id_padre )
    {
        $this->_id_padre = $id_padre;
    }
    public function set_clave( $clave )
    {
        $this->_clave = $clave;
    }
    public function set_siglas( $siglas )
    {
        $this->_siglas = $siglas;
    }
    public function set_nombre( $nombre )
    {
        $this->_nombre = $nombre;
    }
    public function set_titular( $titular )
    {
        $this->_titular = $titular;
    }
    public function set_cargotitular( $cargotitular )
    {
        $this->_cargotitular = $cargotitular;
    }
    public function set_calle( $calle )
    {
        $this->_calle = $calle;
    }
    public function set_numeroexterior( $numeroexterior )
    {
        $this->_numeroexterior = $numeroexterior;
    }
    public function set_numerointerior( $numerointerior )
    {
        $this->_numerointerior = $numerointerior;
    }
    public function set_colonia( $colonia )
    {
        $this->_colonia = $colonia;
    }
    public function set_codigopostal( $codigopostal )
    {
        $this->_codigopostal = $codigopostal;
    }
    public function set_ciudad( $ciudad )
    {
        $this->_ciudad = $ciudad;
    }
    public function set_municipio( $municipio )
    {
        $this->_municipio = $municipio;
    }
    public function set_estado( $estado )
    {
        $this->_estado = $estado;
    }
    public function set_pais( $pais )
    {
        $this->_pais = $pais;
    }
    public function set_centralizado( $centralizado )
    {
        $this->_centralizado = $centralizado;
    }
    public function set_tokenauth( $tokenauth )
    {
        $this->_tokenauth = $tokenauth;
    }
    public function set_mostrararbol( $mostrararbol )
    {
        $this->_mostrararbol = $mostrararbol;
    }
    
    /*-->[Metodos get para cada una de las variables definidas]<--*/
    public function get_ejecutora()
    {
        return $this->_ejecutora;
    }
    public function get_id()
    {
        return $this->_id;
    }
    public function get_ejecutora_padre()
    {
        return $this->_ejecutora_padre;
    }
    public function get_id_padre()
    {
        return $this->_id_padre;
    }
    public function get_clave()
    {
        return $this->_clave;
    }
    public function get_siglas()
    {
        return $this->_siglas;
    }
    public function get_nombre()
    {
        return $this->_nombre;
    }
    public function get_titular()
    {
        return $this->_titular;
    }
    public function get_cargotitular()
    {
        return $this->_cargotitular;
    }
    public function get_calle()
    {
        return $this->_calle;
    }
    public function get_numeroexterior()
    {
        return $this->_numeroexterior;
    }
    public function get_numerointerior()
    {
        return $this->_numerointerior;
    }
    public function get_colonia()
    {
        return $this->_colonia;
    }
    public function get_codigopostal()
    {
        return $this->_codigopostal;
    }
    public function get_ciudad()
    {
        return $this->_ciudad;
    }
    public function get_municipio()
    {
        return $this->_municipio;
    }
    public function get_estado()
    {
        return $this->_estado;
    }
    public function get_pais()
    {
        return $this->_pais;
    }
    public function get_centralizado()
    {
        return $this->_centralizado;
    }
    public function get_tokenauth()
    {
        return $this->_tokenauth;
    }
    public function get_mostrararbol()
    {
        return $this->_mostrararbol;
    }
    
}
?>