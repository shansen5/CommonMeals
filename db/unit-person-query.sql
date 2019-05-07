select pn.first_name, pn.last_name, pn.person_id, pn.start_date as name_start, 
pn.end_date as name_end,
pu.start_date as unit_start, pu.end_date as unit_end, pu.unit_id, pu.type as occupant_type
from person_names pn join person_units pu
where pu.unit_id = 6 and pn.person_id = pu.person_id and
((pn.start_date <= pu.start_date and (pn.end_date is null or pn.end_date >= pu.start_date)) and
((pn.end_date is null or (pn.end_date >= pu.start_date and pn.end_date <= pu.end_date ))))
order by pu.start_date

select pn.first_name, pn.last_name, pn.person_id, 
pu.start_date as unit_start, pu.end_date as unit_end, pu.type as occupant_type
from person_names pn join person_units pu
where pu.unit_id = 6 and pn.person_id = pu.person_id and
((pn.start_date <= pu.start_date and (pn.end_date is null or pn.end_date >= pu.start_date)) and
((pn.end_date is null or (pn.end_date >= pu.start_date and pn.end_date <= pu.end_date ))))
order by pu.start_date

SELECT p.id as id, p.password as password, p.email as email, 
p.phone_land as phone1, p.phone_mobile as phone2, p.phone_work as phone3,
pn.first_name as first_name, pn.last_name as last_name, pu.unit_id as unit_id 
FROM people p, person_names pn, person_units pu 
WHERE p.id = pn.person_id AND p.id = pu.person_id  ORDER BY first_name
      
      
        [:id] => (int) 0
  [:person_id] => (int) 179
  [:first_name] => (string) Jimmy
  [:last_name] => (string) Root
  [:start_date] => (string) 1985-10-29T21:08:56-0800
  [:end_date] => null
  
  INSERT INTO person_names (person_id, first_name, last_name, start_date, end_date)
                VALUES (:person_id, :first_name, :last_name, :start_date, :end_date)
                
SELECT p.id as id, p.password as password, p.email as email, 
p.phone_land as phone1, p.phone_mobile as phone2, p.phone_work as phone3,
pn.first_name as first_name, pn.last_name as last_name, pu.unit_id as unit_id
FROM people p, person_names pn, person_units pu 
WHERE p.id = pn.person_id AND p.id = pu.person_id 
and pn.end_date is null

 SELECT p.id as id, p.password as password, p.email as email, p.phone_land as phone1, p.phone_mobile as phone2, p.phone_work as phone3, pn.first_name as first_name, pn.last_name as last_name, pu.unit_id as unit_id FROM people p JOIN person_names pn JOIN person_units pu WHERE p.id = pn.person_id AND p.id = pu.person_id  AND pu.start_date <= '2018-02-07' AND ( pu.end_date is null OR pu.end_date >= '2018-02-07') AND pn.start_date <= '2018-02-07' AND ( pn.end_date is null OR pn.end_date >= '2018-02-07') ORDER BY first_name
 
 SELECT pu.id as id, pu.unit_id as unit_id, pu.person_id as person_id, 
 pu.start_date as start_date, pu.end_date as end_date,pu.type as occupant_type, 
 pn.first_name as first_name, pn.last_name as last_name  
 FROM person_units pu JOIN person_names pn 
 WHERE pn.person_id = pu.person_id  AND pu.start_date <= '2018-02-08' 
 AND ( pu.end_date is null OR pu.end_date >= '2018-02-08') 
 AND pn.start_date <= '2018-02-08' AND ( pn.end_date is null OR pn.end_date >= '2018-02-08') 
 AND pu.person_id = 172 ORDER BY pu.start_date
                          
                          
                          B1356im5