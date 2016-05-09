CREATE DEFINER=`root`@`localhost` EVENT IF NOT EXISTS `scheduler_hh_incidencias` 
ON SCHEDULE EVERY '0 0:1' DAY_MINUTE 
STARTS sysdate()
ON COMPLETION NOT PRESERVE ENABLE 
DO 
call round_robin_incidencias()