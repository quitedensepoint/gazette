/*************************
 * These views were created in the TOE database in order for us to progress in the gazette
 * They do NOT exist in the Gazette itself. You may have to run these independently on the
 * TOE database in your local system if they do not already exist
 *************************/
delimiter //

CREATE FUNCTION f_rdp_cycle_action (i_current INT(11) UNSIGNED, i_next INT(11) UNSIGNED) RETURNS char(1) CHARSET latin1
    DETERMINISTIC
    SQL SECURITY INVOKER
BEGIN
	DECLARE l_type CHAR(1) DEFAULT '?' ;
	CASE
		WHEN i_current = i_next THEN					SET l_type = '=' ;
		WHEN i_current <= 0 AND i_next > 0 THEN			SET l_type = '+' ;
		WHEN i_current > 0 AND i_next > i_current THEN	SET l_type = '>' ;
		WHEN i_next > 1 THEN							SET l_type = '<' ;
		WHEN i_next = 1 THEN							SET l_type = '-' ;
		WHEN i_next = 0 THEN							SET l_type = 'x' ;
	END CASE ;
	RETURN l_type ;
END;

delimiter ;

CREATE SQL SECURITY INVOKER VIEW toe.v_rdp_current_cycle_data AS
SELECT
	countryID,
	goal,
	cycle_num
FROM
	wwiionline.wwii_rdp_cycles AS cy2 INNER JOIN
	wwiionline.wwii_country AS c ON cy2.cycle_id=c.current_cycle;

CREATE SQL SECURITY INVOKER VIEW toe.v_rdp_production_data AS
SELECT
	f.originalcountry,
	COUNT(sfo.contributing) AS totalProduction
FROM
	wwiionline.strat_factory_outputs AS sfo INNER JOIN
	wwiionline.strat_facility AS f USING (facility_oid) INNER JOIN
	wwiionline.strat_object AS o USING (facility_oid)
WHERE
	f.facility_type=2 AND
	f.facility_subtype=7 AND o.object_type=2 AND
	o.objectname='factoryblock1'
GROUP BY f.originalcountry;

​
CREATE SQL SECURITY INVOKER VIEW toe.v_rdp_completion_stats AS
SELECT
	g.countryID,
	g.cycle_num AS currentCycleNum,
	SUM(cy.goal) AS totalGoal,
	p.totalProduction,
	SUM(cy.goal) - g.goal AS accomplishedGoal,
	p.totalProduction  - (SUM(cy.goal) - g.goal) AS productionThisCycle,
	g.goal AS currentGoal,
	(p.totalProduction  - (SUM(cy.goal) - g.goal)) / g.goal AS completion
FROM
	toe.v_rdp_production_data AS p RIGHT JOIN
	toe.v_rdp_current_cycle_data AS g ON p.originalcountry=g.countryID LEFT JOIN
	wwii_rdp_cycles AS cy ON cy.country_id=g.countryID
GROUP BY g.countryID;

CREATE SQL SECURITY INVOKER VIEW toe.v_rdp_in_progress_2 AS
SELECT
	c.countryID AS country_id,
	c.currentCycleNum AS cycle_in_progress,
	c.completion AS percent_done,
	v.id AS toe_template,
	vt.fullName AS veh_name,
	vt.categoryID AS veh_category_id,
	vt.classID AS veh_class_id,
	vt.typeID AS veh_type_id,
	toe.f_rdp_cycle_action(v.capacity, toe.rdp.capacity) AS action,
	v.capacity AS current_capacity,
	toe.rdp.capacity AS next_capacity
FROM 
	toe.vehicles AS v INNER JOIN
	toe.rdp ON v.id=toe.rdp.id AND v.vehtype_oid=toe.rdp.vehtype_oid INNER JOIN
	wwiionline.wwii_vehtype AS vt ON v.vehtype_oid=vt.vehtype_oid INNER JOIN
	toe.v_rdp_completion_stats AS c ON vt.countryID=c.countryID
WHERE
	toe.rdp.cycle=c.currentCycleNum;

CREATE SQL SECURITY INVOKER VIEW strat.v_hcunits_rdp_in_progress_2 AS
SELECT
    u.unit_id AS hc_unit_id,
    u.title AS hc_unit_name,
    v1.country_id AS country_id,
    v1.cycle_in_progress AS cycle_in_progress,
    v1.percent_done AS percent_done,
    v1.toe_template AS toe_template,
    v1.veh_name AS veh_name,
    v1.veh_category_id AS veh_category_id,
    v1.veh_class_id AS veh_class_id,
    v1.veh_type_id AS veh_type_id,
    v1.action AS action,
    v1.current_capacity AS current_capacity,
    v1.next_capacity AS next_capacity
FROM
	wwiionline.strat_hc_units AS u INNER JOIN
	toe.v_rdp_in_progress_2 v1 ON u.toe_template=v1.toe_template;
​


​

​

​

