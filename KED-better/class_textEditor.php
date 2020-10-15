<?php class textEditor
{
    public function renderForm($POST)
    {
        //Check the existence of the file name
        if(isset($POST["filename"]) && $POST["filename"]!='')  $fullNamePath=$POST["filename"];
        else return "Invalid filename. (".$POST["filename"].")";

        //Load javscript function for Input File managment
        $res .= "<script>".$this->renderScript($POST,$uniqueID)."</script>";

        //READING THE WHOLE CONTENT FILE
        $strFile = file_get_contents($fullNamePath);
        $res .= "<div><textarea disabled id=\"fileTextArea".$uniqueID."\" style=\"width: 100%; font-size:12pt;\">".$strFile."</textarea></div>";
        return $res;
    }

    private function renderScript($POST,$uniqueID)
    {      
        $res .= "
                $(document).ready(function()
                {
                   var h = $(window).height();
                    if( h < $(document).height() ) h = $(document).height() ;
                    $('#fileTextArea".$uniqueID."').height('700px');
                });
            ";
        return $res;
    }   
}