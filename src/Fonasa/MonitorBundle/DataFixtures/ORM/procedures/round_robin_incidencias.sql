delimiter //
CREATE DEFINER=`root`@`localhost` PROCEDURE IF NOT EXISTS `round_robin_incidencias`()
    NO SQL
BEGIN	
	DECLARE dentro_horario INT DEFAULT 0;                            
	DECLARE dentro_dia_habil INT DEFAULT 0;                            
	DECLARE incidencias_totales INT DEFAULT 0;                            	

        select HOUR(sysdate()) BETWEEN 9 and 18 into dentro_horario;            
        select DAYOFWEEK(sysdate()) BETWEEN 1 and 6 into dentro_dia_habil;            

        IF dentro_dia_habil and dentro_horario THEN
            
        SELECT COUNT(*) INTO incidencias_totales
        FROM incidencia 
        WHERE ID_ESTADO_INCIDENCIA IN (3);          

        update incidencia 
        set hh_efectivas = 
        		   greatest(0,
                   hh_efectivas  +  
                   CAST(TIMESTAMPDIFF(SECOND,fecha_ult_hh,SYSDATE()) AS DECIMAL)/ 
                   CAST(incidencias_totales AS DECIMAL)/                          
                   CAST(60 AS DECIMAL)/
                   CAST(60 AS DECIMAL))
        WHERE ID_ESTADO_INCIDENCIA in (3);    

        update incidencia            
        set fecha_ult_hh = SYSDATE()
        WHERE ID_ESTADO_INCIDENCIA in (3);       
             

        END IF;		
END;//
delimiter ;