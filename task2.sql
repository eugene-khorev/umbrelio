/**
 * Author:  eugene
 * Created: Nov 5, 2017
 */

DROP TABLE IF EXISTS users;

CREATE TABLE users (
	id bigserial, 
	group_id bigint
);


INSERT INTO users (group_id) 
	VALUES (1), (1), (1), (2), (1), (3);;
	
--INSERT INTO users (group_id) 
--	VALUES (1), (1), (1), (2), (1), (1), (3), (2), (2), (3);

SELECT 
	group_id,
	MIN(id) AS start_id,
	COUNT(*) AS size
FROM (
	SELECT 
		id,
		group_id,
		ROW_NUMBER() OVER (ORDER BY id) - ROW_NUMBER() OVER (PARTITION BY group_id ORDER BY id) AS woffs
	FROM users
) AS result 
GROUP BY group_id, woffs
ORDER BY start_id;
