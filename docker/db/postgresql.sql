DO
$body$
BEGIN
   IF NOT EXISTS (
      SELECT *
      FROM   pg_catalog.pg_user
      WHERE  usename = 'energy'
   )
   THEN
      CREATE ROLE energy SUPERUSER LOGIN;
   END IF;
END
$body$;
