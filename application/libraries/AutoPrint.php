<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH . "/third_party/pdf_js.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class AutoPrint extends PDF_JavaScript {

    public function __construct() {
        parent::__construct();
    }

    function AutoPrint() {
        //Open the print dialog or start printing immediately on the standard printer
        $script = "print(false);";
        $this->IncludeJS($script);
    }

    function AutoPrintToPrinter() {
        //Print on a shared printer (requires at least Acrobat 6)
        $printer = "Microsoft Print to PDF";
        $script = "var pp = getPrintParams();";
        //if ($dialog)
        //  $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        //else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
        $printer = str_replace('\\', '\\\\', $printer);
        //$script .= "pp.interactive = pp.constants.interactionLevel.full;";
        $script .= "pp.printerName = '$printer'";
        $script .= "print(pp);";
        $this->IncludeJS($script);
    }

}
?>;