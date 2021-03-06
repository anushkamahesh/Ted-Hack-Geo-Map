<?php

/*
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM) 
 * System that captures all the essential functionalities required for any enterprise. 
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com 
 * 
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any 
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc 
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the 
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain 
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property 
 * rights to any design, new software, new protocol, new interface, enhancement, update, 
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for 
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are 
 * reserved to OrangeHRM Inc. 
 * 
 * Please refer http://www.orangehrm.com/Files/OrangeHRM_Commercial_License.pdf for the license which includes terms and conditions on using this software. 
 *  
 */

/**
 * [WayPoints]
 * 
 * [class Description]
 * 
 * @version 1.0
 * @package 
 * @author Nuwan Chathuranga <nuwan@orangehrm.us.com>
 */
class WayPoints {

    protected $routeId;
    protected $locationId;
    protected $lat;
    protected $log;
    protected $order;

    public function getRouteId() {
        return $this->routeId;
    }

    public function getLocationId() {
        return $this->locationId;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getLog() {
        return $this->log;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setRouteId($routeId) {
        $this->routeId = $routeId;
    }

    public function setLocationId($locationId) {
        $this->locationId = $locationId;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLog($log) {
        $this->log = $log;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function save() {
        $query = "Insert INTO  `way_point` (`route_id`, `lat`, `log`, `order`) VALUES (:route_id, :lat, :log, :order)";
        $st = DbManager::getConnection()->prepare($query);
        $st->bindParam(":route_id", $this->routeId);
        $st->bindParam(":lat", $this->lat);
        $st->bindParam(":log", $this->log);
        $st->bindParam(":order", $this->order);
        $st->execute();
    }

    /**
     * @param WayPoints[] $wayPoints collection of way point
     */
    public static function saveCollection($wayPoints) {
        $query = "Insert INTO  `way_point` (`route_id`, `lat`, `log`, `order`) VALUES (:route_id, :lat, :log, :order)";
        $sth = DbManager::getConnection()->prepare($query);
        DbManager::getConnection()->beginTransaction();
        foreach ($wayPoints as $wayPoint) {
            $sth->bindParam(":route_id", $wayPoint->routeId);
            $sth->bindParam(":lat", $wayPoint->lat);
            $sth->bindParam(":log", $wayPoint->log);
            $sth->bindParam(":order", $wayPoint->order);
            $sth->execute();
        }
        return DbManager::getConnection()->commit();
    }

}
