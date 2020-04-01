<?php
$db = new db();

function indexPassages($count)
{
    global $db;
    $total = 0;
    $toIndex = 1;

    while ($toIndex) {
        $passages = $db->getPassages($count);
        $toIndex = count($passages);
        $total += $toIndex;

        foreach ($passages as $passage) {
            $tempArray = $db->standardPlaces(array(0 => array("plaats" => $passage["schipper_plaatsnaam"])));
            if (count($tempArray)) {
                $passage["plaats_standaard"] = $tempArray[0]["plaats"];
            } else {
                $passage["plaats_standaard"] = "";
            }

            $tempArray = $db->regionBig(array(0 => array("plaats" => $passage["plaats_standaard"])));
            if (count($tempArray)) {
                $passage["plaats_regio_groot"] = $tempArray[0]["name"];
            } else {
                $passage["plaats_regio_groot"] = "";
            }

            $tempArray = $db->regionSmall(array(0 => array("plaats" => $passage["plaats_standaard"])));
            if (count($tempArray)) {
                $passage["plaats_regio_klein"] = $tempArray[0]["name"];
            } else {
                $passage["plaats_regio_klein"] = "";
            }

            $passage["van"] = $db->getDepartures($passage["id_doorvaart"]);
            $passage["naar"] = $db->getDestinations($passage["id_doorvaart"]);
            $passage["van_standaard"] = $db->standardPlaces($passage["van"]);
            $passage["naar_standaard"] = $db->standardPlaces($passage["naar"]);
            $passage["van_regio_groot"] = $db->regionBig($passage["van_standaard"]);
            $passage["van_regio_klein"] = $db->regionSmall($passage["van_standaard"]);
            $passage["naar_regio_groot"] = $db->regionBig($passage["naar_standaard"]);
            $passage["naar_regio_klein"] = $db->regionSmall($passage["naar_standaard"]);
            $passage["lading"] = $db->getCommodities($passage["id_doorvaart"]);
            publish($passage, INDEX_URL);
            //echo json_encode($passage);
        }
        $done = $db->setPassages($count);
        echo "$total passages indexed...\n";
    }
}


function publish($passage, $url)
{
    //$id = $passage["id_doorvaart"];
    $json_struc = json_encode($passage);
    $options = array('Content-type: application/json', 'Content-Length: ' . strlen($json_struc));
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $options);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_struc);
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    //echo "$id indexed\n";
}

