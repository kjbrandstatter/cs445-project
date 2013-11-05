<?php

interface DatabaseInterface {
   public function search_beds($parameters);
   public function search_reservations($parameters);
   public function record_reservation($resv);
   public function delete_reservation($resv);
   public function add_customer($cust);
}

require_once("Bed.php");
require_once("Hostel.php");
require_once("Reservation.php");

class MemoryDatabase implements DatabaseInterface {
   protected $hostels;
   protected $reservations;
   protected $customer;

   // fix the constructor to read from the XML file
   public function __construct($xml_file) {
      $this->reservations = array();
      $this->customers = array();
      $this->hostels = array();
      $this->load_from_xml($xml_file);
   }

   public function load_from_xml($xml_file) {
      $dom = simplexml_load_file($xml_file);
   }

   // City, start date, end date/numdays
   public function search_beds($args) {
      $hst = array();
      $results = array();
      if (isset ($args["city"])) {
         foreach ($this->hostels as $host) {
            if ($host->getCity() == $args["city"]) {
               $hst[] = $host;
            }
         }
      }
      else {
         $hst = $this->hostels;
      }
      foreach ($hst as $host) {
         $res = $host->get_available_beds($args["start_date"], $args["end_date"]);
         array_merge($results, $res);
      }
      return $results;
   }

   public function search_reservations($parameters){
      return $this->reservations;
   }

   public function record_reservation($resv){
      $this->reservations[] = $resv;
   }
   public function delete_reservation($resv) {
      $new_arr = array();
      foreach ($this->reservation as $r) {
         if ($r != $resv)
            $new_arr[] = $r;
      }
      $this->reservations = $new_arr;
   }
   public function add_customer($cust){
      $this->customers[] = $cust;
   }
}

?>
