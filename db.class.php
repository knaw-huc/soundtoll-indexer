<?php
class db {
    var $con;

    function __construct() {
        $this->con = new mysqli("localhost", "root", "bonzo", "soundtoll");
    }

    function getPassages($count) {
        $results = $this->con->query("SELECT `id_doorvaart`, `jaar`,  `schipper_achternaam`, `schipper_plaatsnaam`, `tmp` as schipper_naam FROM `doorvaarten` WHERE `indexed` = 0  LIMIT $count");
        return $this->ass_arr($results);
    }

    function setPassages($count) {
        $results = $this->con->query("UPDATE doorvaarten SET `indexed` = 1 WHERE `indexed` = 0 LIMIT $count");
        return $results;
    }

    function getPlaces() {
        $results = $this->con->query("SELECT Modern_name as name, letter FROM places_standard ORDER BY Modern_name");
        return $this->ass_arr($results);
    }

    function getDepartures($id) {
        $results = $this->con->query("SELECT DISTINCT van AS plaats FROM ladingen WHERE id_doorvaart = $id");
        return $this->ass_arr($results);
    }

    function getDestinations($id) {
        $results = $this->con->query("SELECT DISTINCT naar AS plaats FROM ladingen WHERE id_doorvaart = $id");
        return $this->ass_arr($results);
    }

    function standardPlaces($list) {
        $condArr = array();
        foreach ($list as $element) {
            $condArr[] = "'" . str_replace("'", "\'",$element["plaats"]) . "'";
        }
        $condStr = implode(", ", $condArr);
        $results = $this->con->query("SELECT DISTINCT st.Modern_name AS plaats FROM `places_standard` as st, places_source AS so WHERE so.place IN ($condStr) AND so.soundcoding = st.Kode");
        if ($results) {
            return $this->ass_arr($results);
        } else {
          return array(array("plaats" => "Unknown"));
        }
    }

    function getCommodities($id) {
        $results = $this->con->query("SELECT DISTINCT soort FROM ladingen WHERE id_doorvaart = $id");
        return $this->ass_arr($results);
    }

    function regionBig($list) {
        $condArr = array();
        foreach ($list as $element) {
            $condArr[] = "'" . str_replace("'", "\'",$element["plaats"]) . "'";
        }
        $condStr = implode(", ", $condArr);
        $results = $this->con->query("SELECT DISTINCT IF(big_category = '', 'none', big_category) AS name FROM `places_standard` WHERE Modern_name IN ($condStr)");
        if ($results) {
            return $this->ass_arr($results);
        } else {
            return array();
        }

    }

    function regionSmall($list) {
        $condArr = array();
        foreach ($list as $element) {
            $condArr[] = "'" . str_replace("'", "\'",$element["plaats"]) . "'";
        }
        $condStr = implode(", ", $condArr);
        $results = $this->con->query("SELECT DISTINCT IF(small_category = '', 'none', small_category) AS name FROM `places_standard` WHERE Modern_name IN ($condStr)");
        if ($results) {
            return $this->ass_arr($results);
        } else {
            return array();
        }
    }

    private function ass_arr($results) {
        $retArray = array();
        while ($row = $results->fetch_assoc()) {
            $retArray[] = $row;
        }
        return $retArray;
    }
}