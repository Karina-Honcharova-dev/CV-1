<?php
class diffTool
{
    public function renderDiffTool($fullNamePath1, $fullNamePath2)
    {
      global $INDEX_PHP;
      $res = '';

      $filename1=basename($fullNamePath1);
      $filename2=basename($fullNamePath2);

      //What file I need to show on top
      $file2show = '1';
      if(isset($POST["file2show"]) && $POST["file2show"]!='')       $file2show=$POST["file2show"];

      $res .= "<div style=\"position:absolute;top:0px;left:0px;z-index:10;background:#ffec86; width:100%;min-height:100%;font-size:12pt;\">";

      $disp1 = "";
      $bg1   = "background:#FF6600;";

        $res .= "<div style=\"clear:both;\"> </div><hr>";

        //FIRST TEXT AREA 
        $res .= "<div id=\"textEditor1\" style=\"".$disp1."\">";
          $res .= "<div style=\"float:left;width:48%;margin-right:1%;margin-left:1%;\">";
            $te1  = new textEditor();
            $po1  = Array();
            $po1['filename'] = $fullNamePath1;
            $res .= $te1->renderForm($po1, '1');
          $res .= "</div>";


          //SECOND TEXT AREA 
          $res .= "<div style=\"float:left;width:48%;margin-right:1%;margin-left:1%;\">";
            $res .= $this->renderDiffColumn($fullNamePath2, $fullNamePath1);
          $res .= "</div>";
        $res .= "</div>";

        
       return $res;         
    }  
   
    private function renderDiffColumn($fullNamePath1, $fullNamePath2)
    {
        $orig      =  explode("\n", file_get_contents($fullNamePath1) );
        $diffRes   =  explode("\n", shell_exec("diff $fullNamePath1 $fullNamePath2") );
        $i=1;
        $lines=1;
        // words

        while(!empty($diffRes))
        {
          $diffRes = $this->retrieveDiffAction($diffRes, $action);

          switch($action['type'])
          {
            case 'a':
                $action['l11']++;
            break;
          }
          // var_dump ($action['l11']);
          // die();
          
          //Mi posiziono all'inizio della differenza
          while($i < $action['l11'])
          {
            if ($lines < 10)
            $finalFile .=  str_pad ("          $lines | ", 8, " ").array_shift($orig)."\n";
            else
            $finalFile .=  str_pad ("         $lines | ", 8, " ").array_shift($orig)."\n";
            $lines++;
            $i++;

          }

          while( ($d=array_shift($action['data'])) )
          {
            // $smok = substr($d,2);
            // echo $smok."<br>";


            
            switch($action['type'])
            {
              // line available only in second file
              case 'a':
                $str = substr($d,2);

                if($str == "") $str = "\n";
                if ($lines < 10)
                $finalFile .= "INS   ".str_pad("$lines | ", 8, " ", STR_PAD_LEFT).$str."\n";
                else
                $finalFile .= "INS   ".str_pad("$lines | ", 8, " ", STR_PAD_LEFT).$str."\n";
                $lines++;
              break;

              // deleted line
              case 'd':
                $str = substr($d,2);
                if($str == "") $str = "\n";
                $finalFile .= "DEL   ".str_pad ("     | ", 8, " ", STR_PAD_LEFT).$str."\n";
              break;

              // changed line 
              case 'c':
                $str = substr($d,2);

                if($str == "") $str = "\n";
                
                if($d[0] == '<' )
                {          
                          if ($lines < 10)
                          $finalFile .= "DEL   ".str_pad ("    | ", 8, " ", STR_PAD_LEFT).$str."\n";
                          else
                          $finalFile .= "DEL   ".str_pad ("    | ", 8, " ", STR_PAD_LEFT).$str."\n";
                }
                else if($d[0] == '>' )
                {
                  // var_dump($d);
                // die();
                          if ($lines < 10)
                          $finalFile .= "INS   ".str_pad ("$lines | ", 8, " ", STR_PAD_LEFT).$str."\n";
                          else
                          $finalFile .= "INS   ".str_pad ("$lines | ", 8, " ", STR_PAD_LEFT).$str."\n";  
                          $lines++;
                }
              break;
            }
          }
          //Preparazione al posizionamento
          switch($action['type'])
          {
            case 'd':
            case 'c':
                array_shift($orig);
                $i++;
            break;
          }

                        
          //Mi posiziono alla fine della differenza 
          while($i <= $action['l12'])
          {
            array_shift($orig);
            $i++;                   
          }
          
        }
        // die();

        while( ($r = array_shift($orig)))
        {
          $finalFile .= str_pad ("$lines | ", 8, " ", STR_PAD_LEFT).$r."\n";
          $lines++;
        }
           
        $res .= "<div id=\"difference\" style=\"font-family:monospace;font-size:8.5pt;line-height:120%;margin-top:5px;\">";
                    $res .= "<pre >";
                    //$res .= "//".shell_exec("diff $fullNamePath1 $fullNamePath2")."//";
        $res .= htmlentities($finalFile);
        $res .= "</pre >";
        $res .= "</div>";


      return $res;
    }
    //*********************************************************************************//
    //*********************************************************************************//
    //*********************************************************************************//
    private function retrieveDiffAction($diffRes, &$action)
    {
      $r=$diffRes[0];
      $action['l11'] = $action['l12'] = $action['l21'] = $action['l22'] = 0;
      
      array_shift($diffRes);	// Rimuovo la riga appena letta
      
      if( ($idxType=strpos ( $r , 'd')) )
      {
        $action['type']="d";
      }
      else if( ($idxType=strpos ( $r , 'c')) )
      {
         $action['type']="c";
      }
      else if( ($idxType=strpos ( $r , 'a')) )
      {
         $action['type']="a";
      }
     
      //Cerco la prima virgola
      // var_dump($r);
      $idxComma=strpos ( $r , ',');
      if(!$idxComma)
      {
        //Se la prima virgola non c'è ho un numero di linea prima e
        //uno dopo
        $action['l1n']=1;
        $action['l2n']=1;
        sscanf($r,"%d%c%d", $action['l11'], $action['type'], $action['l21']);
      }
      else if($idxComma < $idxType)
      {
        //Se la prima virgola e posizionata prima del type
        //allora ho un due numeri di linea prima
        $action['l1n']=2;

        $idxComma=strpos ( $r , ',', $idxComma);
        if(!$idxComma)
        {
          //qui ho un numero di linea dopo
          $action['l2n']=1;
          sscanf($r,"%d,%d%c%d", $action['l11'],$action['l12'], $action['type'], $action['l21']);
        }
        else
        {
          //qui ho due numeri di linea dopo
          //qui ho un numero di linea dopo
          $action['l2n']=1;
          sscanf($r,"%d,%d%c%d,%d", $action['l11'],$action['l12'], $action['type'], $action['l21'], $action['l22']);
        }
      }


      //UNA VOLTA LETTA LA RIGA DELL'AZIONE RICAVO I DATI
      $action['data'] = Array();
      foreach($diffRes as $j => $r)
      {
        $exit=false;

        $c0 = substr($r, 0, 1);
        
        switch($action['type'])
        {
          case 'a':
            if( $c0 != '>') $exit=true;
          break;

          case 'd':
            if( $c0 != '<') $exit=true;
          break;

          case 'c':
            if($c0 != '<' && $c0 != '>' && $c0 != '-') $exit=true;
          break;
        }

        if($exit) break;
        $action['data'][$j]= $r;
        array_shift($diffRes);
      }

      return $diffRes;
    }    
}
?>
