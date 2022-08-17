<?php
        
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Helpers\DependenciasHelper;

class Dependencias extends Model
{
    use HasFactory;
    use DependenciasHelper;
    protected $table = 'Dependencias';
    private $_catalogo;

    /* ********************************************************
                        Metodos del modelo
    ******************************************************** */

    /* ------------------------------------------------------------------------
                    Función para devolver todos los registros
    ------------------------------------------------------------------------ */

    function buscar_Dependencias() {
        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where('borrado','=', 0)
            ->get();

        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            $objarrayresult[$_counter]->set_ejecutora($row->ejecutora);
            $objarrayresult[$_counter]->set_id($row->id);
            $objarrayresult[$_counter]->set_ejecutora_padre($row->ejecutora_padre);
            $objarrayresult[$_counter]->set_id_padre($row->id_padre);
            $objarrayresult[$_counter]->set_clave($row->clave);
            $objarrayresult[$_counter]->set_siglas($row->siglas);
            $objarrayresult[$_counter]->set_nombre($row->nombre);
            $objarrayresult[$_counter]->set_titular($row->titular);
            $objarrayresult[$_counter]->set_cargotitular($row->cargotitular);
            $objarrayresult[$_counter]->set_calle($row->calle);
            $objarrayresult[$_counter]->set_numeroexterior($row->numeroexterior);
            $objarrayresult[$_counter]->set_numerointerior($row->numerointerior);
            $objarrayresult[$_counter]->set_colonia($row->colonia);
            $objarrayresult[$_counter]->set_codigopostal($row->codigopostal);
            $objarrayresult[$_counter]->set_ciudad($row->ciudad);
            $objarrayresult[$_counter]->set_municipio($row->municipio);
            $objarrayresult[$_counter]->set_estado($row->estado);
            $objarrayresult[$_counter]->set_pais($row->pais);
            $objarrayresult[$_counter]->set_centralizado($row->centralizado);
            $objarrayresult[$_counter]->set_tokenauth($row->tokenauth);
            $objarrayresult[$_counter]->set_mostrararbol($row->mostrararbol);
            $objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                Función para buscar un registro por la llave(s) primaria(s)
    ------------------------------------------------------------------------ */
    
    function buscar_Dependencias_id($busqueda_value) {
        $array_where = array(
            ['borrado', '=', 0]
        );
		if(is_array($busqueda_value))
            {
                $array_where[] = ['Ejecutora', '=', $busqueda_value[0]];
                $array_where[] = ['ID', '=', $busqueda_value[1]];
                }

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();  
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			$objarrayresult[$_counter]->set_ejecutora($row->ejecutora);
            $objarrayresult[$_counter]->set_id($row->id);
            $objarrayresult[$_counter]->set_ejecutora_padre($row->ejecutora_padre);
            $objarrayresult[$_counter]->set_id_padre($row->id_padre);
            $objarrayresult[$_counter]->set_clave($row->clave);
            $objarrayresult[$_counter]->set_siglas($row->siglas);
            $objarrayresult[$_counter]->set_nombre($row->nombre);
            $objarrayresult[$_counter]->set_titular($row->titular);
            $objarrayresult[$_counter]->set_cargotitular($row->cargotitular);
            $objarrayresult[$_counter]->set_calle($row->calle);
            $objarrayresult[$_counter]->set_numeroexterior($row->numeroexterior);
            $objarrayresult[$_counter]->set_numerointerior($row->numerointerior);
            $objarrayresult[$_counter]->set_colonia($row->colonia);
            $objarrayresult[$_counter]->set_codigopostal($row->codigopostal);
            $objarrayresult[$_counter]->set_ciudad($row->ciudad);
            $objarrayresult[$_counter]->set_municipio($row->municipio);
            $objarrayresult[$_counter]->set_estado($row->estado);
            $objarrayresult[$_counter]->set_pais($row->pais);
            $objarrayresult[$_counter]->set_centralizado($row->centralizado);
            $objarrayresult[$_counter]->set_tokenauth($row->tokenauth);
            $objarrayresult[$_counter]->set_mostrararbol($row->mostrararbol);
            $objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
            Función para buscar por un campo distinto a la llave primaria
    ------------------------------------------------------------------------ */
    
    function buscar_Dependencias_campo_especifico($busqueda_field, $busqueda_value) {
        $array_where = array(
            ['borrado', '=', 0]
        );

        if ($busqueda_field != '') {
            $array_where[] = [$busqueda_field, '=', $busqueda_value];
        }

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			$objarrayresult[$_counter]->set_ejecutora($row->ejecutora);
            $objarrayresult[$_counter]->set_id($row->id);
            $objarrayresult[$_counter]->set_ejecutora_padre($row->ejecutora_padre);
            $objarrayresult[$_counter]->set_id_padre($row->id_padre);
            $objarrayresult[$_counter]->set_clave($row->clave);
            $objarrayresult[$_counter]->set_siglas($row->siglas);
            $objarrayresult[$_counter]->set_nombre($row->nombre);
            $objarrayresult[$_counter]->set_titular($row->titular);
            $objarrayresult[$_counter]->set_cargotitular($row->cargotitular);
            $objarrayresult[$_counter]->set_calle($row->calle);
            $objarrayresult[$_counter]->set_numeroexterior($row->numeroexterior);
            $objarrayresult[$_counter]->set_numerointerior($row->numerointerior);
            $objarrayresult[$_counter]->set_colonia($row->colonia);
            $objarrayresult[$_counter]->set_codigopostal($row->codigopostal);
            $objarrayresult[$_counter]->set_ciudad($row->ciudad);
            $objarrayresult[$_counter]->set_municipio($row->municipio);
            $objarrayresult[$_counter]->set_estado($row->estado);
            $objarrayresult[$_counter]->set_pais($row->pais);
            $objarrayresult[$_counter]->set_centralizado($row->centralizado);
            $objarrayresult[$_counter]->set_tokenauth($row->tokenauth);
            $objarrayresult[$_counter]->set_mostrararbol($row->mostrararbol);
            $objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                Función para realizar una busqueda por semejanza
    ------------------------------------------------------------------------ */
    
    function buscar_Dependencias_like($busqueda_field, $busqueda_value) {
        $array_where = array(
            ['borrado', '=', 0]
        );

        if ($busqueda_field != '') {
            $array_where[] = [$busqueda_field, 'like', $busqueda_value];             
        }
        
        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where($array_where)
            ->get();

        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			$objarrayresult[$_counter]->set_ejecutora($row->ejecutora);
            $objarrayresult[$_counter]->set_id($row->id);
            $objarrayresult[$_counter]->set_ejecutora_padre($row->ejecutora_padre);
            $objarrayresult[$_counter]->set_id_padre($row->id_padre);
            $objarrayresult[$_counter]->set_clave($row->clave);
            $objarrayresult[$_counter]->set_siglas($row->siglas);
            $objarrayresult[$_counter]->set_nombre($row->nombre);
            $objarrayresult[$_counter]->set_titular($row->titular);
            $objarrayresult[$_counter]->set_cargotitular($row->cargotitular);
            $objarrayresult[$_counter]->set_calle($row->calle);
            $objarrayresult[$_counter]->set_numeroexterior($row->numeroexterior);
            $objarrayresult[$_counter]->set_numerointerior($row->numerointerior);
            $objarrayresult[$_counter]->set_colonia($row->colonia);
            $objarrayresult[$_counter]->set_codigopostal($row->codigopostal);
            $objarrayresult[$_counter]->set_ciudad($row->ciudad);
            $objarrayresult[$_counter]->set_municipio($row->municipio);
            $objarrayresult[$_counter]->set_estado($row->estado);
            $objarrayresult[$_counter]->set_pais($row->pais);
            $objarrayresult[$_counter]->set_centralizado($row->centralizado);
            $objarrayresult[$_counter]->set_tokenauth($row->tokenauth);
            $objarrayresult[$_counter]->set_mostrararbol($row->mostrararbol);
            $objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }
    /* ------------------------------------------------------------------------
            Función para buscar un grupo de valores en un campo especifico
    ------------------------------------------------------------------------ */
    
    function buscar_Dependencias_in($busqueda_field, $busqueda_value) {
        if ($busqueda_field == '') {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->where('borrado', '=', 0)
                ->get();
        } elseif (!is_array($busqueda_value)) {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->where($busqueda_field, '=', $busqueda_value)
                ->where('borrado', '=', 0)
                ->get();
        } else {
            $resultado = DB::connection($this->connection)
                ->table($this->table)
                ->whereIn($busqueda_field, $busqueda_value)
                ->where('borrado', '=', 0)
                ->get();            
        }
        
        $_counter = 0;
        $objarrayresult = array();

        foreach ($resultado as $row) {
            $objarrayresult[$_counter] = new self();
            
			$objarrayresult[$_counter]->set_ejecutora($row->ejecutora);
            $objarrayresult[$_counter]->set_id($row->id);
            $objarrayresult[$_counter]->set_ejecutora_padre($row->ejecutora_padre);
            $objarrayresult[$_counter]->set_id_padre($row->id_padre);
            $objarrayresult[$_counter]->set_clave($row->clave);
            $objarrayresult[$_counter]->set_siglas($row->siglas);
            $objarrayresult[$_counter]->set_nombre($row->nombre);
            $objarrayresult[$_counter]->set_titular($row->titular);
            $objarrayresult[$_counter]->set_cargotitular($row->cargotitular);
            $objarrayresult[$_counter]->set_calle($row->calle);
            $objarrayresult[$_counter]->set_numeroexterior($row->numeroexterior);
            $objarrayresult[$_counter]->set_numerointerior($row->numerointerior);
            $objarrayresult[$_counter]->set_colonia($row->colonia);
            $objarrayresult[$_counter]->set_codigopostal($row->codigopostal);
            $objarrayresult[$_counter]->set_ciudad($row->ciudad);
            $objarrayresult[$_counter]->set_municipio($row->municipio);
            $objarrayresult[$_counter]->set_estado($row->estado);
            $objarrayresult[$_counter]->set_pais($row->pais);
            $objarrayresult[$_counter]->set_centralizado($row->centralizado);
            $objarrayresult[$_counter]->set_tokenauth($row->tokenauth);
            $objarrayresult[$_counter]->set_mostrararbol($row->mostrararbol);
            $objarrayresult[$_counter]->set_fecha($row->fecha);
            $objarrayresult[$_counter]->set_usuario_idc($row->usuario_idc);
            $objarrayresult[$_counter]->set_borrado($row->borrado);

            $_counter++;
        }

        return $objarrayresult;
    }

    /* ------------------------------------------------------------------------
                        Función para guardar un registro
    ------------------------------------------------------------------------ */
    
    function guardar_Dependencias($objeto_Dependencias) {
        $this->_catalogo = $objeto_Dependencias;

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->insertGetId([
            'Ejecutora_Padre' => $this->_catalogo->get_ejecutora_padre(),
            'ID_Padre' => $this->_catalogo->get_id_padre(),
            'Clave' => $this->_catalogo->get_clave(),
            'Siglas' => $this->_catalogo->get_siglas(),
            'Nombre' => $this->_catalogo->get_nombre(),
            'Titular' => $this->_catalogo->get_titular(),
            'CargoTitular' => $this->_catalogo->get_cargotitular(),
            'Calle' => $this->_catalogo->get_calle(),
            'NumeroExterior' => $this->_catalogo->get_numeroexterior(),
            'NumeroInterior' => $this->_catalogo->get_numerointerior(),
            'Colonia' => $this->_catalogo->get_colonia(),
            'CodigoPostal' => $this->_catalogo->get_codigopostal(),
            'Ciudad' => $this->_catalogo->get_ciudad(),
            'Municipio' => $this->_catalogo->get_municipio(),
            'Estado' => $this->_catalogo->get_estado(),
            'Pais' => $this->_catalogo->get_pais(),
            'Centralizado' => $this->_catalogo->get_centralizado(),
            'TokenAuth' => $this->_catalogo->get_tokenauth(),
            'MostrarArbol' => $this->_catalogo->get_mostrararbol(),
            'fecha' => now(),
            'usuario_idc' => Auth::id(),
            'borrado' => 0
            ]);
        
        return $resultado;
    }

    /* ------------------------------------------------------------------------
                        Función para actualizar el registro
    ------------------------------------------------------------------------ */
    
    function actualizar_menu($obj_menu) {
        $this->_cat = $obj_menu;

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where([
                ['Ejecutora', $this->_catalogo->get_ejecutora()],
                ['ID', $this->_catalogo->get_id()],
                ])
            ->update([
            'Ejecutora_Padre' => $this->_catalogo->get_ejecutora_padre(),
            'ID_Padre' => $this->_catalogo->get_id_padre(),
            'Clave' => $this->_catalogo->get_clave(),
            'Siglas' => $this->_catalogo->get_siglas(),
            'Nombre' => $this->_catalogo->get_nombre(),
            'Titular' => $this->_catalogo->get_titular(),
            'CargoTitular' => $this->_catalogo->get_cargotitular(),
            'Calle' => $this->_catalogo->get_calle(),
            'NumeroExterior' => $this->_catalogo->get_numeroexterior(),
            'NumeroInterior' => $this->_catalogo->get_numerointerior(),
            'Colonia' => $this->_catalogo->get_colonia(),
            'CodigoPostal' => $this->_catalogo->get_codigopostal(),
            'Ciudad' => $this->_catalogo->get_ciudad(),
            'Municipio' => $this->_catalogo->get_municipio(),
            'Estado' => $this->_catalogo->get_estado(),
            'Pais' => $this->_catalogo->get_pais(),
            'Centralizado' => $this->_catalogo->get_centralizado(),
            'TokenAuth' => $this->_catalogo->get_tokenauth(),
            'MostrarArbol' => $this->_catalogo->get_mostrararbol(),
            'fecha' => now(),
            'usuario_idc' => Auth::id(),
            'borrado' => 0
            ]);
        
        return $resultado;
    }

    /* ------------------------------------------------------------------------
                        Función para borrado lógico
    ------------------------------------------------------------------------ */
    
    function borrar_menu($obj_menu) {
        $this->_cat = $obj_menu;

        $resultado = DB::connection($this->connection)
            ->table($this->table)
            ->where([
                ['Ejecutora', $this->_catalogo->get_ejecutora()],
                ['ID', $this->_catalogo->get_id()],
                
            ])
            ->update([
                'fecha' => now(),
                'usuario_idc' => Auth::id(),
                'borrado' => 1
            ]);

        return $resultado;
    }
}
?>