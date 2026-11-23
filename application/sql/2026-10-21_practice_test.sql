-- ---------------------------------------------------------------------------
-- Practice tests (interactive MCQ) + student access period
-- Date: 2026-10-21
-- Run ONCE on the `hkgravity` database (phpMyAdmin or mysql CLI).
-- Tables follow the CMS convention: id_<table>, deleted, created_date,
-- updated_date, deleted_date, sort_order, status, title.
-- ---------------------------------------------------------------------------

-- One practice test per lesson (table `section`, shown as "Chapter" on the site).
CREATE TABLE IF NOT EXISTS `practice_test` (
  `id_practice_test` INT NOT NULL AUTO_INCREMENT,
  `deleted` INT NOT NULL DEFAULT 0,
  `created_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` INT NOT NULL DEFAULT 1,                 -- 1 = published, 0 = draft
  `title` VARCHAR(250) NOT NULL DEFAULT '',
  `id_classes` INT NOT NULL DEFAULT 0,
  `id_material` INT NOT NULL DEFAULT 0,
  `id_unit` INT NOT NULL DEFAULT 0,
  `id_section` INT NOT NULL DEFAULT 0,
  `description` TEXT NULL,
  `questions_per_test` INT NOT NULL DEFAULT 5,     -- 0 = all questions
  `max_attempts` TINYINT NOT NULL DEFAULT 2,
  `grade_style` VARCHAR(10) NOT NULL DEFAULT 'letter',   -- letter | number
  `label_first` VARCHAR(10) NOT NULL DEFAULT 'A',
  `label_second` VARCHAR(10) NOT NULL DEFAULT 'B',
  `label_fail` VARCHAR(10) NOT NULL DEFAULT 'F',
  `points_first` DECIMAL(5,2) NOT NULL DEFAULT 2,
  `points_second` DECIMAL(5,2) NOT NULL DEFAULT 1,
  `points_fail` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `reveal_mode` VARCHAR(10) NOT NULL DEFAULT 'button',   -- button | auto
  `show_total` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_practice_test`),
  KEY `section_pub` (`id_section`, `deleted`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions: text and/or image, four options, one correct, hint + optional explanation.
CREATE TABLE IF NOT EXISTS `practice_question` (
  `id_practice_question` INT NOT NULL AUTO_INCREMENT,
  `deleted` INT NOT NULL DEFAULT 0,
  `created_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` INT NOT NULL DEFAULT 1,
  `title` VARCHAR(250) NOT NULL DEFAULT '',        -- admin label (auto-filled from the text)
  `id_practice_test` INT NOT NULL DEFAULT 0,
  `id_section` INT NOT NULL DEFAULT 0,
  `question_text` LONGTEXT NULL,
  `image` VARCHAR(150) NULL,                        -- file in uploads/practice_question/
  `option_1` TEXT NULL,
  `option_2` TEXT NULL,
  `option_3` TEXT NULL,
  `option_4` TEXT NULL,
  `correct_option` TINYINT NOT NULL DEFAULT 1,     -- 1..4
  `hint` LONGTEXT NULL,
  `explanation` LONGTEXT NULL,
  PRIMARY KEY (`id_practice_question`),
  KEY `test_order` (`id_practice_test`, `deleted`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- One row per finished attempt of a logged-in student.
CREATE TABLE IF NOT EXISTS `practice_result` (
  `id_practice_result` INT NOT NULL AUTO_INCREMENT,
  `deleted` INT NOT NULL DEFAULT 0,
  `created_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` INT NOT NULL DEFAULT 1,
  `title` VARCHAR(250) NOT NULL DEFAULT '',        -- "<student> - <test>"
  `id_members` INT NOT NULL DEFAULT 0,
  `id_practice_test` INT NOT NULL DEFAULT 0,
  `id_section` INT NOT NULL DEFAULT 0,
  `score` DECIMAL(7,2) NOT NULL DEFAULT 0,
  `max_score` DECIMAL(7,2) NOT NULL DEFAULT 0,
  `percent` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `total_questions` INT NOT NULL DEFAULT 0,
  `first_try` INT NOT NULL DEFAULT 0,
  `second_try` INT NOT NULL DEFAULT 0,
  `failed` INT NOT NULL DEFAULT 0,
  `details` TEXT NULL,                              -- JSON: per-question attempts/grade
  PRIMARY KEY (`id_practice_result`),
  KEY `by_member` (`id_members`, `deleted`),
  KEY `by_test` (`id_practice_test`, `deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student profiles: access period (e.g. one school year) + last login.
ALTER TABLE `members`
  ADD COLUMN `access_start` DATE NULL AFTER `title`,
  ADD COLUMN `access_end` DATE NULL AFTER `access_start`,
  ADD COLUMN `last_login` DATETIME NULL AFTER `access_end`,
  ADD COLUMN `notes` VARCHAR(250) NULL AFTER `last_login`;

-- Register the modules in the CMS (recycle bin, privileges). position=0 means
-- "reached by drill-down" (like unit / section / summary), not from the sidebar.
INSERT INTO `content_type`
  (`name`,`url_name`,`icon`,`deleted`,`created_date`,`updated_date`,`deleted_date`,`sort_order`,`position`,`gallery`,`thumb_val_gal`,`resize_status`,`used_name`)
VALUES
  ('practice test','','',0,NOW(),NOW(),NOW(),33,0,0,'',0,'practice_test'),
  ('practice question','','',0,NOW(),NOW(),NOW(),34,0,0,'',0,'practice_question'),
  ('practice result','','',0,NOW(),NOW(),NOW(),35,0,0,'',0,'practice_result');

-- Let the recycle bin's permanent delete remove question images.
INSERT INTO `content_type_attr`
  (`name`,`type`,`length`,`id_content`,`sort_order`,`deleted`,`thumb`,`thumb_val`,`resize_status`,`display`,`validation`,`foreign_key`)
SELECT 'image',5,'',`id_content`,0,0,0,'',0,1,'trim',0 FROM `content_type` WHERE `name`='practice question';

-- Show "members" (student profiles) in the back-office sidebar.
UPDATE `content_type` SET `position`=2, `sort_order`=36 WHERE `name`='members';

-- Privileges for existing limited back-office users (none locally; harmless otherwise).
INSERT INTO `priviledge` (`id_user`,`id_content`,`read_p`,`write_p`,`delete_p`)
SELECT u.`id_user`, c.`id_content`, 1, 1, 1
FROM `user` u JOIN `content_type` c ON c.`name` IN ('practice test','practice question','practice result')
WHERE u.`deleted`=0;

-- ---------------------------------------------------------------------------
-- 2026-10-21 (later the same day): practice tests are a standalone section.
-- "practice test" moves to the back-office sidebar (position=2); the lesson link
-- becomes optional. Questions stay reachable from the test list (position=0).
-- ---------------------------------------------------------------------------
UPDATE `content_type` SET `position`=2, `sort_order`=37 WHERE `name`='practice test';

-- ---------------------------------------------------------------------------
-- 2026-10-21 (3): pass mark per test + "passed" flag on results, and the
-- reports module ("practice result") in the back-office sidebar.
-- ---------------------------------------------------------------------------
ALTER TABLE `practice_test` ADD COLUMN `pass_percent` DECIMAL(5,2) NOT NULL DEFAULT 50 AFTER `show_total`;
ALTER TABLE `practice_result`
  ADD COLUMN `pass_percent` DECIMAL(5,2) NOT NULL DEFAULT 50 AFTER `percent`,
  ADD COLUMN `passed` TINYINT(1) NOT NULL DEFAULT 0 AFTER `pass_percent`;
-- results recorded before this change: evaluate them with the test's pass mark
UPDATE `practice_result` r JOIN `practice_test` t ON t.`id_practice_test` = r.`id_practice_test`
  SET r.`pass_percent` = t.`pass_percent`, r.`passed` = IF(r.`percent` >= t.`pass_percent`, 1, 0);
UPDATE `content_type` SET `position`=2, `sort_order`=38 WHERE `name`='practice result';

-- ---------------------------------------------------------------------------
-- 2026-10-21 (4): time limit, max tries per student, random question selection.
-- practice_result.status: 1 = finished, 0 = started but not finished (still counts as a try).
-- ---------------------------------------------------------------------------
ALTER TABLE `practice_test`
  ADD COLUMN `time_limit` INT NOT NULL DEFAULT 0 AFTER `pass_percent`,          -- minutes, 0 = none
  ADD COLUMN `max_tries` INT NOT NULL DEFAULT 0 AFTER `time_limit`,             -- per student, 0 = unlimited
  ADD COLUMN `random_questions` TINYINT(1) NOT NULL DEFAULT 1 AFTER `max_tries`; -- 1 = pick questions at random
ALTER TABLE `practice_result`
  ADD COLUMN `duration_sec` INT NOT NULL DEFAULT 0 AFTER `passed`,
  ADD COLUMN `timed_out` TINYINT(1) NOT NULL DEFAULT 0 AFTER `duration_sec`;

-- ---------------------------------------------------------------------------
-- 2026-10-21 (5): display mode — all questions on one page (default) or one at a time.
-- ---------------------------------------------------------------------------
ALTER TABLE `practice_test` ADD COLUMN `display_mode` VARCHAR(5) NOT NULL DEFAULT 'all' AFTER `random_questions`;
