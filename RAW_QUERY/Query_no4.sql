select "e"."id" as "employee_id", "e"."nik", "e"."name", CASE WHEN e.is_active = TRUE THEN 'TRUE' ELSE 'FALSE' END AS
is_active, DATE_PART('YEAR', AGE(CURRENT_DATE, ep.date_of_birth)) || ' Years old' AS age, "ed"."name" as "school_name",
"ed"."level", CASE
WHEN total_suami > 0 OR total_anak_sambung > 0 OR total_istri > 0 OR total_anak > 0 THEN
CONCAT(
CASE WHEN total_suami > 0 THEN CONCAT(total_suami, ' suami') ELSE '' END,
CASE WHEN total_istri > 0 THEN
CASE WHEN total_suami > 0 THEN ' & ' ELSE '' END || CONCAT(total_istri, ' istri')
ELSE '' END,
CASE WHEN total_anak > 0 THEN
CASE WHEN total_istri > 0 THEN ' & ' ELSE '' END || CONCAT(total_anak, ' anak')
ELSE '' END,
CASE WHEN total_anak_sambung > 0 THEN
CASE WHEN total_anak > 0 THEN ' & ' ELSE '' END || CONCAT(total_anak, ' anak sambung')
ELSE '' END
)
ELSE '-'
END AS family_data from "employee" as "e" inner join "employee_profile" as "ep" on "e"."id" = "ep"."employee_id" inner
join "education" as "ed" on "e"."id" = "ed"."employee_id" left join (
SELECT
COALESCE(COUNT(CASE WHEN relation_status = 'Istri' THEN 1 END), 0) AS total_istri,
COALESCE(COUNT(CASE WHEN relation_status = 'Anak' THEN 1 END), 0) AS total_anak,
COALESCE(COUNT(CASE WHEN relation_status = 'Suami' THEN 1 END), 0) AS total_suami,
COALESCE(COUNT(CASE WHEN relation_status = 'Anak Sambung' THEN 1 END), 0) AS total_anak_sambung,
employee_id
FROM
employee_family
GROUP BY
employee_id
) AS counts on "e"."id" = "counts"."employee_id"