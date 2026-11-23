-- 2026-11-23  "Simulations Class" for each class (table `material`, shown as "Classes" in the admin).
-- Rich text (links, text or an embedded simulation) shown on the public site when the class is clicked.
-- Safe to run more than once: adds the column, or widens it to TEXT if it already exists.
SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
           WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'material' AND COLUMN_NAME = 'simulations_class');
SET @s := IF(@c = 0,
  'ALTER TABLE `material` ADD COLUMN `simulations_class` TEXT NULL AFTER `title`',
  'ALTER TABLE `material` MODIFY `simulations_class` TEXT NULL');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;
