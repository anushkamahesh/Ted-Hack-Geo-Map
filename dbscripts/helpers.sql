DELIMITER $$

CREATE FUNCTION `haversine`(  
lat1  numeric (9,6),  
lon1  numeric (9,6),  
lat2  numeric (9,6),  
lon2  numeric (9,6)  
) RETURNS decimal(10,5)  
READS SQL DATA  
BEGIN  
DECLARE  x  decimal (20,10);  
DECLARE  pi  decimal (21,20);  
SET  pi = 3.14159265358979323846;  
SET  x = sin( lat1 * pi/180 ) * sin( lat2 * pi/180  ) + cos(  
lat1 *pi/180 ) * cos( lat2 * pi/180 ) * cos(  abs( (lon2 * pi/180) -  
(lon1 *pi/180) ) );  
SET  x = acos( x );  
RETURN  x * 6371;  
END $$

DELIMITER ;
