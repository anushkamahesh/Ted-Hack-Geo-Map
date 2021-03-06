<?php

require_once 'DbManager.php';

class DriverSearch {
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
		$earth_radius = 6371;  
		  
		$dLat = deg2rad($latitude2 - $latitude1);  
		$dLon = deg2rad($longitude2 - $longitude1);  
		  
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
		$c = 2 * asin(sqrt($a));  
		$d = $earth_radius * $c;  
		  
		return $d;  
	}

    /*function getNearByLocations($originLocation, $radius) {
        list($latitudeX, $longitudeX) = $originLocation;
        $latRange = array($latitudeX - 1, $latitudeX + 1); // getLatRange($latitudeX, $radius);
        $longRange = array($longitudeX - 1, $longitudeX + 1); // getLongRange($longitudeX, $radius);
        $query = 'select id, haversine(`lat`, `log`, ?, ?) as distance from `location` where (`lat` BETWEEN ? AND ?) AND (`log` BETWEEN ? AND ?) having distance > ?';
        $sth = DbManager::getConnection()->prepare($query);
        $params = array($latitudeX, $longitudeX, $latRange[0], $latRange[1], $longRange[0], $longRange[1], $radius);
        $sth->execute($params);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (function_exists('array_column')) {
            return array_column($result, 'distance', 'id');
        } else {
            $ret = array();
            foreach ($result as $row) {
                $ret[$row['id']] = $row['distance'];
            }
            return $ret;
        }
    }

    function getRouteIdsByLocations($locations) {
        $placeHolder = array_fill(0, count($locations), '?');
        $query = 'select `route_id` from `way_point` where `location_id` IN (' . implode(',', $placeHolder) . ')';
        $sth = DbManager::getConnection()->prepare($query);
        $sth->execute($locations);
        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    function getMatchingRoutes($source, $destination) {
        $radius = 5; // getVicinityRadius($source, $destination);
        $sourceNearbyLocations = $this->getNearByLocations($source, $radius);
        if ($sourceNearbyLocations) {
            $destinationNearBYLocations = $this->getNearByLocations($destination, $radius);
            if ($destinationNearBYLocations) {
                $routesThoughSource = $this->getRouteIdsByLocations(array_keys($sourceNearbyLocations));
                $routesThoughDestination = $this->getRouteIdsByLocations(array_keys($destinationNearBYLocations));
                $matchingRoutes = array_intersect($routesThoughSource, $routesThoughDestination);
                // sortRoutes($matchingRoutes, $sourceNearbyLocations, $destinationNearBYLocations);
                return $matchingRoutes;
            }
        }
        return array();
    }*/

    function getVicinityRadius($source, $destination) {
        list($latitudeSource, $longitudeSource) = $source;
        list($latitudeDestination, $longitudeDestination) = $destination;
        $distance = $this->getDistance($latitudeSource, $longitudeSource, $latitudeDestination, $longitudeDestination);
        return $distance < 50 ? $distance / 10 : 5;
    }

    function getMatchingRoutesThroughLocation($location, $radius) {
        list($latitudeX, $longitudeX) = $location;
        $latRange = array($latitudeX - 0.5, $latitudeX + 0.5); // getLatRange($latitudeX, $radius);
        $longRange = array($longitudeX - 0.5, $longitudeX + 0.5); // getLongRange($longitudeX, $radius);
        $query = 'select route_id, haversine(`lat`, `log`, ?, ?) as distance, `order` from way_point where (`lat` BETWEEN ? AND ?) AND (`log` BETWEEN ? AND ?) having distance <= ? order by distance';
        $sth = DbManager::getConnection()->prepare($query);
        $params = array($latitudeX, $longitudeX, $latRange[0], $latRange[1], $longRange[0], $longRange[1], $radius);
        logFile("Query: " . $query . "\n");
        logFile("Parameters: " . var_export($params, true) . "\n");
        $sth->execute($params);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach ($result as $row) {
            if (!isset($ret[$row['route_id']])) {
                $ret[$row['route_id']] = $row; // preserve the route with lowest distance to the given location
            }
        }
        return $ret;
    }

    function getMatchingRoutes($source, $destination) {
        $radius = 5; // getVicinityRadius($source, $destination);
        $routesThoughSource = $this->getMatchingRoutesThroughLocation($source, $radius);
        logFile("Routes though source: " . print_r($routesThoughSource, true) . "\n");
        if ($routesThoughSource) {
            $routesThoughDestination = $this->getMatchingRoutesThroughLocation($destination, $radius);
            logFile("Routes though destination: " . print_r($routesThoughDestination, true) . "\n");
            if ($routesThoughDestination) {
                $candidateRouteIds = array();
                foreach ($routesThoughSource as $routeId => $source) {
                    if (isset($routesThoughDestination[$routeId]) && $routesThoughDestination[$routeId]['order'] > $source['order']) {
                        $candidateRouteIds[] = $routeId;
                    }
                }
                return $candidateRouteIds;
            }
        }
        return array();
    }

	public function getMatchingDrivers($source, $destination, $limit = 5) {
		$routeIds = $this->getMatchingRoutes($source, $destination);
		if (empty($routeIds)) {
			return array();
		}
		$limit = (int) $limit;
		$driverIds = DbManager::getConnection()->query('select driver_id from `route` where `id` in (' . implode(',', $routeIds) . ") LIMIT $limit")->fetchAll(PDO::FETCH_COLUMN);
		$query = 'select * from `driver` where `id` in (' . implode(',', $driverIds) . ")";
		return DbManager::getConnection()->query($query)->fetchAll(PDO::FETCH_ASSOC);
	}

    public function findDriversNearSourceLocation($source) {
        list($latitudeX, $longitudeX) = $source;
        $latRange = array($latitudeX - 0.5, $latitudeX + 0.5);
        $longRange = array($longitudeX - 0.5, $longitudeX + 0.5);
        $q = 'SELECT id, first_name, mobile_number FROM driver WHERE TIMESTAMPDIFF(MINUTE,last_update_time,CURRENT_TIMESTAMP) < 5 AND (lat BETWEEN ? AND ?) AND (log BETWEEN ? AND ?) AND haversine(lat, log, ?, ?) < 5;';
        $sth = DbManager::getConnection()->prepare($q);
        $params = array_merge($latRange, $longRange, $source);
        logFile("Query: " . $q . "\n");
        logFile("Parameters: " . var_export($params, true) . "\n");
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
