<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use App\Models\Url;
use App\Models\LlavePrimariaModel;
//use App\Models\Contenido;

class LlavePrimariaController extends Controller
{
    public function index()
    {
        $values = array();
        
        $catalogo_llaveprimaria = new LlavePrimariaModel();
        $llaveprimaria = $catalogo_llaveprimaria->buscar_llaveprimaria();

        $_llaveprimaria = array();
        $_counter = 0;
    }
}
?>