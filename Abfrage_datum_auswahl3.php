<html>

<head>
    <title>Hello!</title>
    <style>


  table, th, td  {border: 1px solid black; border-spacing: 1px;}
  th, td         {padding: 1px;}
  #tabelle       {background-color: #8a9da8;}
  #spalte        {background-color: #5c82d9;}
  #zeilengruppe  {background-color: #8db243; color: #ffffff;}
  #zeile         {background-color: #e7c157;}
  #zelle         {background-color: #c32e04;}
   body {
	background-color: #8a9da8;
	color: midnightblue;

</style>



</head>

<body>

<table>
     <div style="position:absolute; left:50px; top:3cm">
  <table id="tabelle" border = 4 cellspacing="0"  height= "90">
   <caption align="top">Roentgenbuch</caption>
   <tr>
    <tr></tr>
   <tr>
        <div style="position:absolute; left:50px; top:3cm">
    <th style='border:0; width:180'>Aufnahme Datum</th>
    <th style='border:0; width:180'>PatientID</th>
    <th style='border:0; width:180'>Patient</th>
    <th style='border:0; width:180'>Geburtsdatum</th>
    <th style='border:0; width:180'>Geschlecht</th>
    <th style='border:0; width:180'>Bildparameter</th>
    <th style='border:0; width:180'>Hersteller</th>
        </div>
   </tr>

   <tr>



<?php

    session_start();


        // $patientID=$_SESSION["x"]
       //  echo  $_SESSION["newpaID"];
         // $peng =  $_SESSION["newpaID"];
        $peng = "9706";
          // echo $peng;
          //$modalities2 = "IO";
 // Datum Übergabe
           // $modalities2 = "IO";
         // var_dump($modalities2);


    //If($datumVon != Null) { $datumVon=$_POST["datumVon"];
 // echo $datumVon;
        $datumVon = $_POST["datumVon"];
        $jetzt = $datumVon;
        $search=   '-' ;
        $replace = '' ;
        $string= str_replace( $search,$replace,$jetzt) ;
     // echo $string;
   // } else { $datumVon = 'keine Daten';}


 //   If($datumBis != Null){$datumBis=$_POST["datumBis"] ;
        $datumBis=$_POST["datumBis"] ;
        $jetzt2 = $datumBis;
        $search=   '-' ;
        $replace = '' ;
        $string2= str_replace( $search,$replace,$jetzt2) ;
     // echo $string2;
   //  } else { $datumBis = 'keine Daten';}


              $modalities2 = $_POST["DVT"];
              echo $modalities2;


   // $modalities2 = "IO";
         //echo($modalities2);



$curl = curl_init();

// Sending GET

//###############################################################  Auswakl



//###################################################
// Telling curl to store JSON
//#############################################
curl_setopt($curl,
CURLOPT_RETURNTRANSFER, true);

// Executing curl
$response = curl_exec($curl);


     if($e = curl_error($curl)) {
         echo $e;
        } else {

        // Decoding JSON data
                 $decodedData =
                    json_decode($response, true);

     }

// The data to send to the API
$postData = array(
            "Level" => "Study",
            "Query" => array( "PatientID" => "$peng","StudyDate" => "$string-$string2"),

   //  "Query" => array( "PatientID" => "$_SESSION[newpaID]","StudyDate" => "$string-$string2"),
  //  "Query" => array( "StudyDate" => "$string-$string2"),
     // 'Query' => array('PatientID' => '3883'),
    //'title' => 'A new orthanc post',
    //'content' => 'With <b>exciting</b> content...'
             );

    //var_dump($postData);


// Create the context for the request
  $context = stream_context_create(array(
            'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($postData)
             )
             ));

// Send the request

$response = file_get_contents('http://127.0.0.1:8042/tools/find', FALSE, $context);

// Check for errors
       if($response === FALSE){
            die('Error');
        echo "keine Daten";
        }

// Decode the response

 //#########################
$responseData = json_decode($response, TRUE);


//var_dump($responseData) ;

// Print the date from the response
 //echo '<pre>'; print_r($responseData); echo '</pre>';

 //echo json_encode($responseData);



 //echo var_dump ($responseData);

 //geaendert
//If($responseData != Null) {



//#########################################
  $studydatum  = $responseData;




// } else { $studydatum = 'keine Daten';}
// var_dump ($studydatum) ;
 //echo  $responseData;

 //###################################################Tabelle


  //################################################################Tabelle ende



    foreach($studydatum as $studie){
    // echo $studie;

      curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/$studie");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // Executing curl
      $response = curl_exec($curl);
      $decodedData =
            json_decode($response, true);


     // var_dump($decodedData);
       // echo $decodedData;
      //######################################################
     //####################################versuch daten zu speichern#############################

    // curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/d1ddbf1c-8c86d1c1-5e5c9ce1-389e8365-a69f3228/archive");
     curl_setopt($curl, CURLOPT_URL,"http://localhost:8042/studies/$studie/archive");
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
     $daten = curl_exec($curl);
   // var_dump($daten);


      //versuch daten zu speichern#############################

     //if($decodedData != Null) {

                  // $decodedData['PatientMainDicomTags']['PatientID'];
         // if(isset($decodedData['MainDicomTags']['AccessionNumber'])){ $AccesNr = $decodedData['MainDicomTags']['AccessionNumber']; } else
                  // {$AccesNr = "keine Daten";}
          //echo  $AccesNr;
          if(isset($decodedData['MainDicomTags']['StudyDate'])){ $StudDate = $decodedData['MainDicomTags']['StudyDate']; } else
                   {$StudDate = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyID'])){ $StudID = $decodedData['MainDicomTags']['StudyID']; } else
                   {$StudID = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyInstanceUID'])){ $StudyINS = $decodedData['MainDicomTags']['StudyInstanceUID']; } else
                   {$StudyINS = "keine Daten";}
          if(isset($decodedData['MainDicomTags']['StudyTime'])){ $STudyZeit = $decodedData['MainDicomTags']['StudyTime']; } else
                   {$STudyZeit = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientID'])){ $PatientenID = $decodedData['PatientMainDicomTags']['PatientID']; } else
                  {$PatientenID = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientName'])){echo $Patientenname = $decodedData['PatientMainDicomTags']['PatientName']; } else
                  {$Patientenname = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientBirthDate'])){ $patientGeb= $decodedData['PatientMainDicomTags']['PatientBirthDate'];} else
                  {$patientGeb = "keine Daten";}
          if(isset($decodedData['PatientMainDicomTags']['PatientSex'])){$PatientenWM= $decodedData['PatientMainDicomTags']['PatientSex'];} else
                  {$PatientenWM = "keine Daten";}
          //$Patype= $decodedData['PatientMainDicomTags']['Type'];
           //####### $ParentPat =  $decodedData['ParentPatient'][0];
           // $Serien =  $decodedData['Series'][0];
          $Serien =  $decodedData['Series'];
               //echo $Serien;



  //###################################################################
  //##################auswahl Daten
  //################################### ###################################################################


        foreach( $Serien as $reihen){
           curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/series/$reihen");
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           $response = curl_exec($curl);

           $decodedData = json_decode($response, true);
              // var_dump($decodedData);

            $update = $decodedData['LastUpdate'];

           if(isset($decodedData['MainDicomTags']['BodyPartExamined'])){$bodypart = $decodedData['MainDicomTags']['BodyPartExamined']; } else
                    {$bodypart = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['Manufacturer'])){$Manfac = $decodedData['MainDicomTags']['Manufacturer']; } else
                    {$Manfac = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['Modality'])){$modali = $decodedData['MainDicomTags']['Modality'];} else
                    {$modali = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDate'])){$date = $decodedData['MainDicomTags']['SeriesDate']; } else
                    {$date = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDescription'])){$descript = $decodedData['MainDicomTags']['SeriesDescription'];} else
                    {$descript = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesInstanceUID'])){$Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];} else
                    {$Instance = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesNumber'])){$Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];} else
                    {$Nummer = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDate'])){$date = $decodedData['MainDicomTags']['SeriesDate'];} else
                    {$date = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesDescription'])){$descript = $decodedData['MainDicomTags']['SeriesDescription'];} else
                    {$$descript = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesInstanceUID'])){$Instance = $decodedData['MainDicomTags']['SeriesInstanceUID']; } else
                    {$Instance = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesNumber'])){$Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];}  else
                    {$Nummer = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['SeriesTime'])){$time = $decodedData['MainDicomTags']['SeriesTime'];} else
                    {$time = "keine Daten";}
           if(isset($decodedData['MainDicomTags']['StationName'])){ $station = $decodedData['MainDicomTags']['StationName'];} else
                    {$station = "keine Daten";}
           if(isset($decodedData['ParentStudy'])){$study = $decodedData['ParentStudy']; } else
                    {$study = "keine Daten";}


  //#############################################################################################################
  //#######################                    Abfrage modalies
  //#######################################################################################################
           ?>




     <td> <?php  echo $StudDate; ?> </td>
     <td> <?php echo $PatientenID; ?></td>
     <td> <?php echo $Patientenname; ?></td>
     <td> <?php echo $patientGeb; ?></td>
     <td> <?php echo $PatientenWM; ?></td>
    <td align=center><?php   if(isset($decodedData['MainDicomTags']['SeriesDescription'])){echo $descript = $decodedData['MainDicomTags']['SeriesDescription'];} else
                    { $descript = "keine Daten";} ?></td>
    <td align=center><?php   if(isset($decodedData['MainDicomTags']['Manufacturer'])){echo $Manfac = $decodedData['MainDicomTags']['Manufacturer']; } else
                    {  $Manfac = "keine Daten";} ?></td>

   </tr>


           <?php



            If($modali == $modalities2)  {


               //$study = $decodedData['ParentStudy'];
                     $stati = $decodedData['Status'];
                     $typ = $decodedData['Type'];
                       // if($decodedData != Null) {
                       //      $Instanzen = $decodedData['Instances'];} else {die ( "keine Daten");}
                 if(isset($decodedData['Instances'])){ $Instanzen = $decodedData['Instances']; } else
                   {$Instanzen = "keine Daten";}
                //$Instanzen = $decodedData['Instances'];
                //  var_dump($Instanzen);


            } elseif ($modali == $modalities2) {

                  //$study = $decodedData['ParentStudy'];
                     $stati = $decodedData['Status'];
                     $typ = $decodedData['Type'];
                       // if($decodedData != Null) {
                       //      $Instanzen = $decodedData['Instances'];} else {die ( "keine Daten");}
                   if(isset($decodedData['Instances'])){ $Instanzen = $decodedData['Instances']; } else
                      {$Instanzen = "keine Daten";}
                       //$Instanzen = $decodedData['Instances'];
                      //  var_dump($Instanzen);     }
                      { echo "keine Daten"; }
             } //elseif ($modali == "PT") {

               //$study = $decodedData['ParentStudy'];
                     $stati = $decodedData['Status'];
                     $typ = $decodedData['Type'];
                       // if($decodedData != Null) {
                       //      $Instanzen = $decodedData['Instances'];} else {die ( "keine Daten");}
                    if(isset($decodedData['Instances'])){ $Instanzen = $decodedData['Instances']; } else
                      {$Instanzen = "keine Daten";}
                      //$Instanzen = $decodedData['Instances'];
                      //  var_dump($Instanzen);

           // }  elseif ($modali == "DK") {

            //$study = $decodedData['ParentStudy'];
                     $stati = $decodedData['Status'];
                     $typ = $decodedData['Type'];
                       // if($decodedData != Null) {
                       //      $Instanzen = $decodedData['Instances'];} else {die ( "keine Daten");}
                   if(isset($decodedData['Instances'])){ $Instanzen = $decodedData['Instances']; } else
                      {$Instanzen = "keine Daten";}
                      //$Instanzen = $decodedData['Instances'];
                      //  var_dump($Instanzen);

          //  }   elseif($modali ==  $modalities2)  {


       // $Instanzen = $decodedData['Instances'];
       // var_dump ($Instanzen);
     foreach( $Instanzen as $value){

         curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/tags");
         //curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$Instanzen");
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($curl);
          $decodedData = json_decode($response, true);
       //   var_dump($decodedData);

          // $fileG = $decodedData['FileSize'];
          //$fileU = $decodedData['FileUuid'];
          //$IDnr = $decodedData['ID'];
          //$IDIserie =  $decodedData['IndexInSeries'];
          // if( $PAIDent =  $decodedData['0010,0020']['Value'] == $patientID){
         if(isset($decodedData['0020,0012']['Value'])){$aquiNR = $decodedData['0020,0012']['Value'];} else
            {$aquiNR = "keine Daten";}
         if(isset($decodedData['0008,0012']['Value'])){$creatdate = $decodedData['0008,0012']['Value'];} else
            {$creatdate = "keine Daten";}
         if(isset($decodedData['0008,0013']['Value'])){$creattime = $decodedData['0008,0013']['Value']; } else
            {$$creattime = "keine Daten";}

         if(isset($decodedData['0020,0013']['Value'])){$nummerI = $decodedData['0020,0013']['Value'];} else
            {$nummerI = "keine Daten";}
         if(isset($decodedData['0028,0008']['Value'])){$AnzahlF =   $decodedData['0028,0008']['Value'];} else
            {$AnzahlF = "keine Daten";}
         if(isset($decodedData['0008,1090']['Value'])){$type = $decodedData['0008,1090']['Value']; } else
            {$type = "keine Daten";}
            //  $kvp_Wert =  $decodedData['0018,0060']['Value'];
         if(isset($decodedData['0018,0060']['Value'])){ $kvp_Wert = $decodedData['0018,0060']['Value']; } else
            {$kvp_Wert = "keine Daten";}
         if(isset($decodedData['0018,1150']['Value'])){ $expotime = $decodedData['0018,1150']['Value']; } else
            { $expotime = "keine Daten";}
         if(isset($decodedData['0018,1151']['Value'])){ $xrayTC = $decodedData['0018,1151']['Value'];} else
            {$xrayTC = "keine Daten";}

        echo "<div style='position:relative;top:2cm;left:10px'>\n"  ;
        echo "<table style=' id:tabelle; cellspacing:0; border:1px solid; align:center;'>\n";
        echo "<tr>\n";

         //( http://127.0.0.1:8042/instances/dce124bb-4de12a17-d3da45cd-7cf4c0d4-ae6574b5/preview
      //  echo "<th> ".'<img src='."http://127.0.0.1:8042/instances/2b9459cf-60f068a3-f285146f-ca4db519-98757af0/preview".' alt="?" height="75" width="120"/>'."</th>";
         echo "<th> ".'<img src='."http://127.0.0.1:8042/instances/$value/preview".' alt="?" height="45" width="90"/>'."</th>";

        echo "<th style='border:0; width:170px'>AcquisitionNumber</th>\n";
        echo "<th style='border:0; width:160px'>InstanceCreationDate</th>\n";
        echo "<th style='border:1; width:100px'>InstanceNumber</th>\n";
        echo "<th style='border:1; width:70px'>KVP</th>\n";
        echo "<th style='border:1; width:130px'>Exposure Time</th>\n";
        echo "<th style='border:1; width:150px'>xRay Tube Current</th>\n";
        echo "<th style= 'width:70px'>Modality</th>\n";
        echo "<th style= 'width:150px'>Koerperbereich</th>\n";

        echo "</tr>\n";

           echo "<tr style='align:center'>\n";
            // echo "<td> ".'<img src='."http://127.0.0.1:8042/instances/2b9459cf-60f068a3-f285146f-ca4db519-98757af0/preview".' alt="?" height="100" width="120"/>'."</td>";


             echo "<td style='border:0; align:center'></td>\n";
             echo "<td align='center'>$aquiNR</td>\n";
             echo "<td align='center'>$creatdate</td>\n";
             echo "<td align='center'> $nummerI</td>\n";
             echo "<td align='center'>$kvp_Wert</td>";
             echo "<td align='center'>$expotime</td>\n";
             echo"<td align='center'>$xrayTC</td>";
             echo "<td align='center'>$modali</td>\n";
             echo "<td align='center'>$bodypart</td>\n";;


          echo "</tr>\n";

        echo "</table>\n";
        echo "</div>";

               }
         }
        }

       // }

       ?>







       <?php








// Closing curl
curl_close($curl);

   ?>





