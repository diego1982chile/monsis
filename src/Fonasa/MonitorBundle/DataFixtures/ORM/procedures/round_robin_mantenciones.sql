DROP PROCEDURE IF EXISTS `monsis`.`round_robin_mantenciones`;

CREATE DEFINER=`root`@`localhost` PROCEDURE `monsis`.`round_robin_mantenciones`()
    NO SQL
BEGIN	
	DECLARE dentro_horario INT DEFAULT 0;                            
	DECLARE mantenciones_totales INT DEFAULT 0;                            	
    select HOUR(sysdate()) BETWEEN 9 and 18 into dentro_horario;    
            
        SELECT COUNT(*) INTO mantenciones_totales
        FROM mantencion 
        WHERE ID_ESTADO_MANTENCION IN (2,3);          

        update mantencion as m1 
       inner join (select id, count(id) cant from mantencion GROUP BY ID_USUARIO) m2
	   on m1.id=m2.id        
       set hh_efectivas = greatest(0,
                                   hh_efectivas  +  
                                   CAST(TIMESTAMPDIFF(SECOND,fecha_ult_hh,SYSDATE()) AS DECIMAL)/ 
                                   CAST(m2.cant AS DECIMAL)/                          
                                   CAST(60 AS DECIMAL)/
                                   CAST(60 AS DECIMAL))        
        WHERE m.ID_ESTADO_MANTENCION in (2,3);    

        update mantencion            
        set fecha_ult_hh = SYSDATE()
        WHERE ID_ESTADO_MANTENCION in (2,3);       
        		
END