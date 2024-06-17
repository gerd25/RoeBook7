<HTML>
    <HEAD>

     <meta charset="utf-8">

<style>


  table, th, td  {border: 1px solid black; border-spacing: 1px;}
  th, td         {padding: 1px;}
  #tabelle       {background-color: #8a9da8;}
  #spalte        {background-color: #5c82d9;}
  #zeilengruppe  {background-color: #8db243; color: #ffffff;}
  #zeile         {background-color: #e7c157;}
  #zelle         {background-color: #c32e04;}

  #DVT {
position: absolute;
  left= "40";
}
#CT {
position: absolute;
  left= "180";
}
#PZ {
position: left;

}
#VT {
position: left;

}
 body {
	background-color: gray;
	color: midnightblue;
}
#Null{
     {display: none}
}

</style>

    </HEAD>

  <body>



<div style="position:relative; left:160px; top:1.0cm">

 <form method="post" action="http://localhost/test/Abfrage_datum_auswahl3.php" >


       <label for="DatumVon">Datum von </label>

       <input id="DatumVon" name="datumVon" type="date" value="DatumVon">

       <label for="DatumBis">bis </label>

       <input id="DatumBis" name="datumBis" type="date" value="DatumBis">



       <INPUT id ="px" TYPE = "RADIO" name="DVT" VALUE="PX"> DVT
       <INPUT id = "OI" TYPE = "RADIO" name="DVT" VALUE="IO"> CT
       <INPUT  TYPE = "RADIO" name="DVT" VALUE="PZ"> KB
       <INPUT  TYPE = "RADIO" name="DVT" VALUE="VT"> ROE1
       <INPUT TYPE = "RADIO"  name="DVT" VALUE="Null"  style="display:none"  checked>

        <input type="submit" value="Submit">


  </form>


   </div>

    <div style="position:relative; left:0px; top:1.0cm">


     <div position:absolute; left:10px; top:1px>

   <table id="tabelle" border = "4" cellspacing="0"  height= "90">
   <caption align="top">Roentgenbuch</caption>
   <tr>
    <tr></tr>
   <tr>

    <th style=' width:180'>PatientID</th>
    <th style=' width:180'>Patient</th>
    <th style=' width:180'>Geburtsdatum</th>
    <th style=' width:180'>Geschlecht</th>
    <th style=' width:180'>Bildparameter</th>
    <th style=' width:180'>Hersteller</th>
   </tr>


<?php


 session_start();
 //####################################################################################
//##  Zugang Datenbank   ############################
//##################################################################################

//  Zugang Datenbank

$zugang = pg_connect("host=localhost dbname=orthanc_db user=postgres password=user");
 if(!$zugang) {
      echo "Error : Unable to open database\n";
  } else {
     // echo "Opened database successfully\n";
   }
   $stat = pg_connection_status($zugang);
    if($stat === PGSQL_CONNECTION_OK){
 // echo 'Connection OK';
    } else {
        echo 'An error occurred';
    }

$result = pg_query($zugang, "SELECT * FROM datenaustausch");
       while ($row = pg_fetch_row($result)) {
     // echo "phpid: $row[0]  patid: $row[1]";
       //echo "<br />\n";
      $PID = $row[1];
     // echo $PID;
 }
       //  echo $PID;
          $_SESSION["newpaID"]=$PID;

    if (!$result) {
       echo "Ein Fehler ist aufgetreten.\n";
    exit;
    }

  $eintrag = pg_query($zugang,"INSERT INTO public.datenaustausch(phpid,patid,lastname,firstname,birthday,street,city,zip,sex,confirm,commit) VALUES ('29',$PID,'oeller','Manni','03.03.2020','Weg','Herne','55555','m','t','OK')");


    if (!$eintrag) {
       echo "Ein Fehler ist aufgetreten.\n";
    exit;
    }


$result = pg_query($zugang, "UPDATE public.datenaustausch SET confirm= 'O', commit='gelesen' WHERE patid ='9706'");

   if (!$result) {
      echo "Ein Fehler ist aufgetreten.\n";
   exit;
    }




//########################################################################################
//############### Eine PatientId suchen ##################################################
//########################################################################################
// The data to send to the API



$postData = array(
    'Level' => 'patients',
   // 'Query' => array('PatientID' =>  $PID),
    'Query' => array('PatientID' =>  '9706'),
    //'title' => 'A new orthanc post',
    //'content' => 'With <b>exciting</b> content...'
);


// Create the context for the request
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
         'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($postData)
        )
        ));


   // Send the request  findet patienten ID
$response = file_get_contents('http://127.0.0.1:8042/tools/find', FALSE, $context);

// Check for errors
   if($response === FALSE){
      die('Error');
   }

//  Patientendaten
// Decode the response
$responseData = json_decode($response, TRUE);

// Print the date from the response
 //echo '<pre>'; print_r($responseData); echo '</pre>';

// echo json_encode($responseData);
 $patientID = $responseData[0];
 // echo $patientID;

 //echo var_dump ($responseData);



 $curl = curl_init();

//########################################################################################
//############### Patient aus Orthanc lesen ##############################################
//########################################################################################
// Sending GET
curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/patients/$patientID");

// Telling curl to store JSON

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
//var_dump($decodedData);
       $row =  $decodedData;
    //   var_dump($row);

   $patientGeb = $decodedData['MainDicomTags']['PatientBirthDate'];
   $PatientenID = $decodedData['MainDicomTags']['PatientID'];
       // echo($PatientenID);
   $Patientenname = $decodedData['MainDicomTags']['PatientName'];
   $PatientenWM = $decodedData['MainDicomTags']['PatientSex'];
   $Patype = $decodedData['Type'];
           ?>

            <tr>
    <td align=center><?php echo "Not possible"; ?></td>
    <!-- <td align=center><?php echo $StudDate; ?></td>  !-->
    <td align=center><?php echo $PatientenID; ?></td>
    <td align=center><?php echo $Patientenname; ?></td>
    <td align=center><?php echo $patientGeb; ?></td>
    <td align=center><?php echo $PatientenWM; ?></td>
    <!-- <td align=center><?php echo $descript; ?></td>
    <td align=center><?php echo $Manfac; ?></td>     !-->
   </tr>
     </table>
  </div>




   <?php
     // Studien Patient

 // $studien = $decodedData['Studies'][0];
     $studien = $decodedData['Studies'];

    //if($decodedData != Null) {
    foreach( $studien as $studs){
      curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/studies/$studs");
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // Executing curl
      $response = curl_exec($curl);
      $decodedData =
        json_decode($response, true);
     //  var_dump($decodedData);

     // Decode the response
     $responseData = json_decode($response, TRUE);

    // $AccesNr =  $decodedData['MainDicomTags']['AccessionNumber'];
     if(isset($decodedData['MainDicomTags']['AccessionNumber'])){ $AccesNr =$decodedData['MainDicomTags']['AccessionNumber']; } else
                    {$AccesNr = "keine Daten";}
     //$StudDate = $decodedData['MainDicomTags']['StudyDate'];
     if(isset($decodedData['MainDicomTags']['StudyDate'])){ $StudDate =$decodedData['MainDicomTags']['StudyDate']; } else
                    {$StudDate = "keine Daten";}




     $StudID =  $decodedData['MainDicomTags']['StudyID'];
     $StudyINS =  $decodedData['MainDicomTags']['StudyInstanceUID'];
     $STudyZeit= $decodedData['MainDicomTags']['StudyTime'];
     $ParentPat =  $decodedData['ParentPatient'][0];
     $Serien =  $decodedData['Series'];
     // $Serien =  $decodedData['Series'];
    // echo $Serien;
      // var_dump($Serien) ;




         foreach( $Serien as $reihen){
          curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/series/$reihen");
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $response = curl_exec($curl);
       // var_dump ($response) ;
          $decodedData = json_decode($response, true);

     // var_dump($decodedData);
         }
        $update = $decodedData['LastUpdate'];
        $bodypart = $decodedData['MainDicomTags']['BodyPartExamined'];
        $Manfac = $decodedData['MainDicomTags']['Manufacturer'];
        $modali = $decodedData['MainDicomTags']['Modality'];
        $date = $decodedData['MainDicomTags']['SeriesDate'];
        $descript = $decodedData['MainDicomTags']['SeriesDescription'];
        $Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];
        $Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];
        $date = $decodedData['MainDicomTags']['SeriesDate'];
        $descript = $decodedData['MainDicomTags']['SeriesDescription'];
        $Instance = $decodedData['MainDicomTags']['SeriesInstanceUID'];
        $Nummer =  $decodedData['MainDicomTags']['SeriesNumber'];
        $time = $decodedData['MainDicomTags']['SeriesTime'];
        $station = $decodedData['MainDicomTags']['StationName'];
        $study = $decodedData['ParentStudy'];
        $stati = $decodedData['Status'];
        $typ = $decodedData['Type'];
        $Instanzen = $decodedData['Instances'];
       //  var_dump($Instanzen);


            foreach( $Instanzen as $value){
          // var_dump($value);
              curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$value/tags");

             //curl_setopt($curl, CURLOPT_URL, "http://localhost:8042/instances/$Instanzen");
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
             $response = curl_exec($curl);
             $decodedData = json_decode($response, true);
         //  var_dump($decodedData);
        //   if( $decodedData != Null) {                                        0
          // $fileG = $decodedData['FileSize'];
          // $fileU = $decodedData['FileUuid'];
          // $IDnr = $decodedData['ID'];
          // $IDIserie =  $decodedData['IndexInSeries'];

        $aquiNR =    $decodedData['0020,0012']['Value'];
        $creatdate = $decodedData['0008,0012']['Value'];
        $creattime = $decodedData['0008,0013']['Value'];
        $nummerI =   $decodedData['0020,0013']['Value'];
        $AnzahlF =   $decodedData['0028,0008']['Value'];
        $type =      $decodedData['0008,1090']['Value'];
               if(isset($decodedData['0018,0060']['Value'])){ $kvp_Wert = $decodedData['0018,0060']['Value']; } else
                    {$kvp_Wert = "keine Daten";}
               if(isset($decodedData['0018,1150']['Value'])){ $expotime = $decodedData['0018,1150']['Value']; } else
                    {$expotime = "keine Daten";}
               if(isset($decodedData['0018,1151']['Value'])){ $xrayTC = $decodedData['0018,1151']['Value'];} else
                    {$xrayTC = "keine Daten";}
        $modali =    $decodedData['0008,0060']['Value'];
        $bodypart =  $decodedData['0018,0015']['Value'];
        $station  =  $decodedData['0008,1010']['Value'];
        $PatientenWM =$decodedData['0010,0040']['Value'];

         ?>
            <div style='position:relative;top:8px;left:1px'>
        <table  id=tabelle cellspacing=0 border=1px solid align:center' >

          <tr>
          <th><img src="http://127.0.0.1:8042/instances/<?php echo $value; ?>/preview".' alt="?" height="75" width="120" </th>
          <th style='border:1; width:180'>AcquisitionNumber</th>
          <th style='border:1; width:180'>InstanceCreationDate</th>
          <th style='border:1; width:150'>InstanceNumber</th>
          <th style='border:1; width:120'>KVP</th>
          <th style='border:1; width:130'>Exposure Time</th>
          <th style='border:1; width:160'>xRay Tube Current</th>
          <th style='border:1; width:130'>Modality</th>
          <th style='border:1; width:150'>Koerperbereich</th>
          </tr>
          <tr>
          <td></td>
          <td align=center><?php echo $aquiNR; ?></td>
          <td align=center><?php echo $creatdate; ?></td>
          <td align=center><?php echo $nummerI; ?></td>
          <td align=center><?php echo $kvp_Wert; ?></td>
          <td align=center><?php echo $expotime; ?></td>
          <td align=center><?php echo $xrayTC; ?></td>
          <td align=center><?php echo $modali; ?></td>
          <td align=center><?php echo $bodypart;?></td>
          </tr>



         <?php




                  }
                  }





    // Closing curl
    curl_close($curl);

  //######################################################################
  //#####  Auswahl
  //##############################################################
         ?>




                        </div>
         </table>

                                             <





   </body>
   </html>