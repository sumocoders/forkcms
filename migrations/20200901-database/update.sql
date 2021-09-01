# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.34)
# Database: forkdeploy
# Generation Time: 2021-09-01 13:38:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table backend_navigation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `backend_navigation`;

CREATE TABLE `backend_navigation` (
                                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                      `parent_id` int(11) NOT NULL,
                                      `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                      `selected_for` text COLLATE utf8mb4_unicode_ci,
                                      `sequence` int(11) NOT NULL,
                                      PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `backend_navigation` WRITE;
/*!40000 ALTER TABLE `backend_navigation` DISABLE KEYS */;

INSERT INTO `backend_navigation` (`id`, `parent_id`, `label`, `url`, `selected_for`, `sequence`)
VALUES
    (1,0,'Dashboard','dashboard/index',NULL,1),
    (2,0,'Modules',NULL,NULL,4),
    (3,0,'Settings',NULL,NULL,999),
    (4,3,'Translations','locale/index','a:4:{i:0;s:10:\"locale/add\";i:1;s:11:\"locale/edit\";i:2;s:13:\"locale/import\";i:3;s:14:\"locale/analyse\";}',4),
    (5,3,'General','settings/index',NULL,1),
    (6,3,'Advanced',NULL,NULL,2),
    (7,6,'Email','settings/email',NULL,1),
    (8,6,'SEO','settings/seo',NULL,2),
    (9,6,'Tools','settings/tools',NULL,3),
    (10,3,'Modules',NULL,NULL,6),
    (11,3,'Themes',NULL,NULL,7),
    (12,3,'Users','users/index','a:2:{i:0;s:9:\"users/add\";i:1;s:10:\"users/edit\";}',4),
    (13,3,'Groups','groups/index','a:2:{i:0;s:10:\"groups/add\";i:1;s:11:\"groups/edit\";}',5),
    (14,10,'Overview','extensions/modules','a:2:{i:0;s:24:\"extensions/detail_module\";i:1;s:24:\"extensions/upload_module\";}',1),
    (15,11,'ThemesSelection','extensions/themes','a:2:{i:0;s:23:\"extensions/detail_theme\";i:1;s:23:\"extensions/upload_theme\";}',1),
    (16,11,'Templates','extensions/theme_templates','a:2:{i:0;s:29:\"extensions/add_theme_template\";i:1;s:30:\"extensions/edit_theme_template\";}',2),
    (17,0,'Pages','pages/index','a:2:{i:0;s:9:\"pages/add\";i:1;s:10:\"pages/edit\";}',2),
    (18,10,'Pages','pages/settings',NULL,2),
    (19,2,'Search',NULL,NULL,1),
    (20,19,'Statistics','search/statistics',NULL,1),
    (21,19,'Synonyms','search/synonyms','a:2:{i:0;s:18:\"search/add_synonym\";i:1;s:19:\"search/edit_synonym\";}',2),
    (22,10,'Search','search/settings',NULL,3),
    (23,2,'ContentBlocks','content_blocks/index','a:2:{i:0;s:18:\"content_blocks/add\";i:1;s:19:\"content_blocks/edit\";}',2),
    (24,2,'Tags','tags/index','a:1:{i:0;s:9:\"tags/edit\";}',3),
    (25,0,'MediaLibrary','media_library/media_item_index','a:2:{i:0;s:31:\"media_library/media_item_upload\";i:1;s:29:\"media_library/media_item_edit\";}',3),
    (26,2,'Blog',NULL,NULL,4),
    (27,26,'Articles','blog/index','a:3:{i:0;s:8:\"blog/add\";i:1;s:9:\"blog/edit\";i:2;s:21:\"blog/import_wordpress\";}',1),
    (28,26,'Comments','blog/comments','a:1:{i:0;s:17:\"blog/edit_comment\";}',2),
    (29,26,'Categories','blog/categories','a:2:{i:0;s:17:\"blog/add_category\";i:1;s:18:\"blog/edit_category\";}',3),
    (30,10,'Blog','blog/settings',NULL,4);

/*!40000 ALTER TABLE `backend_navigation` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog_categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_categories`;

CREATE TABLE `blog_categories` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `meta_id` int(11) NOT NULL,
                                   `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;

INSERT INTO `blog_categories` (`id`, `meta_id`, `language`, `title`)
VALUES
    (1,10,'en','Default');

/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_comments`;

CREATE TABLE `blog_comments` (
                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                 `post_id` int(11) NOT NULL,
                                 `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `created_on` datetime NOT NULL,
                                 `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `website` text COLLATE utf8mb4_unicode_ci,
                                 `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'comment',
                                 `status` varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'moderation',
                                 `data` text COLLATE utf8mb4_unicode_ci COMMENT 'Serialized array with extra data',
                                 PRIMARY KEY (`id`),
                                 KEY `idx_post_id_status` (`post_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;

INSERT INTO `blog_comments` (`id`, `post_id`, `language`, `created_on`, `author`, `email`, `website`, `text`, `type`, `status`, `data`)
VALUES
    (1,1,'en','2021-08-26 17:39:00','Davy Hellemans','forkcms-sample@spoon-library.com','http://www.spoon-library.com','awesome!','comment','published',NULL),
    (2,1,'en','2021-08-26 17:39:00','Tijs Verkoyen','forkcms-sample@sumocoders.be','https://www.sumocoders.be','wicked!','comment','published',NULL);

/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
                              `id` int(11) NOT NULL COMMENT 'The real post id',
                              `revision_id` int(11) NOT NULL AUTO_INCREMENT,
                              `category_id` int(11) NOT NULL,
                              `user_id` int(11) NOT NULL,
                              `meta_id` int(11) NOT NULL,
                              `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `introduction` text COLLATE utf8mb4_unicode_ci,
                              `text` text COLLATE utf8mb4_unicode_ci,
                              `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                              `status` varchar(244) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `publish_on` datetime NOT NULL,
                              `created_on` datetime NOT NULL,
                              `edited_on` datetime NOT NULL,
                              `hidden` tinyint(1) NOT NULL DEFAULT '0',
                              `allow_comments` tinyint(1) NOT NULL DEFAULT '0',
                              `num_comments` int(11) NOT NULL,
                              PRIMARY KEY (`revision_id`),
                              KEY `idx_status_language_hidden` (`status`,`language`,`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;

INSERT INTO `blog_posts` (`id`, `revision_id`, `category_id`, `user_id`, `meta_id`, `language`, `title`, `introduction`, `text`, `image`, `status`, `publish_on`, `created_on`, `edited_on`, `hidden`, `allow_comments`, `num_comments`)
VALUES
    (1,1,1,1,11,'en','Nunc sediam est','<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<ul>\n    <li>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</li>\n    <li>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.</li>\n    <li>Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>\n</ul>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n','<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<ul>\n    <li>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</li>\n    <li>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.</li>\n    <li>Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>\n</ul>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n',NULL,'active','2021-08-26 17:39:00','2021-08-26 17:39:00','2021-08-26 17:39:00',0,1,2),
    (2,2,1,1,12,'en','Lorem ipsum','<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<ul>\n    <li>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</li>\n    <li>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.</li>\n    <li>Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>\n</ul>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n','<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n\n<ul>\n    <li>Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</li>\n    <li>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.</li>\n    <li>Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>\n</ul>\n\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n',NULL,'active','2021-08-26 17:38:00','2021-08-26 17:38:00','2021-08-26 17:38:00',0,1,0);

/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table content_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `content_blocks`;

CREATE TABLE `content_blocks` (
                                  `revision_id` int(11) NOT NULL AUTO_INCREMENT,
                                  `id` int(11) NOT NULL,
                                  `user_id` int(11) NOT NULL,
                                  `extra_id` int(11) NOT NULL,
                                  `template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default.html.twig',
                                  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:locale)',
                                  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `text` longtext COLLATE utf8mb4_unicode_ci,
                                  `hidden` tinyint(1) NOT NULL DEFAULT '0',
                                  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT '(DC2Type:content_blocks_status)',
                                  `created_on` datetime NOT NULL COMMENT '(DC2Type:datetime)',
                                  `edited_on` datetime NOT NULL COMMENT '(DC2Type:datetime)',
                                  PRIMARY KEY (`revision_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `parameters` text COLLATE utf8mb4_unicode_ci COMMENT 'serialized array containing default user module/action rights',
                          PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `parameters`)
VALUES
    (1,'admin',NULL);

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups_rights_actions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups_rights_actions`;

CREATE TABLE `groups_rights_actions` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `group_id` int(11) NOT NULL,
                                         `module` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'name of the module',
                                         `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'name of the action',
                                         `level` double NOT NULL DEFAULT '1' COMMENT 'unix type levels 1, 3, 5 and 7',
                                         PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `groups_rights_actions` WRITE;
/*!40000 ALTER TABLE `groups_rights_actions` DISABLE KEYS */;

INSERT INTO `groups_rights_actions` (`id`, `group_id`, `module`, `action`, `level`)
VALUES
    (1,1,'Dashboard','Index',7),
    (2,1,'Locale','Add',7),
    (3,1,'Locale','Analyse',7),
    (4,1,'Locale','Delete',7),
    (5,1,'Locale','Edit',7),
    (6,1,'Locale','Export',7),
    (7,1,'Locale','ExportAnalyse',7),
    (8,1,'Locale','Import',7),
    (9,1,'Locale','Index',7),
    (10,1,'Locale','SaveTranslation',7),
    (11,1,'Settings','Email',7),
    (12,1,'Settings','Index',7),
    (13,1,'Settings','Seo',7),
    (14,1,'Settings','TestEmailConnection',7),
    (15,1,'Users','Add',7),
    (16,1,'Users','Delete',7),
    (17,1,'Users','Edit',7),
    (18,1,'Users','Index',7),
    (19,1,'Users','UndoDelete',7),
    (20,1,'Groups','Add',7),
    (21,1,'Groups','Delete',7),
    (22,1,'Groups','Edit',7),
    (23,1,'Groups','Index',7),
    (24,1,'Extensions','DetailModule',7),
    (25,1,'Extensions','InstallModule',7),
    (26,1,'Extensions','Modules',7),
    (27,1,'Extensions','UploadModule',7),
    (28,1,'Extensions','AddThemeTemplate',7),
    (29,1,'Extensions','DeleteThemeTemplate',7),
    (30,1,'Extensions','EditThemeTemplate',7),
    (31,1,'Extensions','ExportThemeTemplates',7),
    (32,1,'Extensions','ThemeTemplates',7),
    (33,1,'Extensions','DetailTheme',7),
    (34,1,'Extensions','InstallTheme',7),
    (35,1,'Extensions','Themes',7),
    (36,1,'Extensions','UploadTheme',7),
    (37,1,'Pages','Add',7),
    (38,1,'Pages','Delete',7),
    (39,1,'Pages','Edit',7),
    (40,1,'Pages','Copy',7),
    (41,1,'Pages','GetInfo',7),
    (42,1,'Pages','Index',7),
    (43,1,'Pages','Move',7),
    (44,1,'Pages','RemoveUploadedFile',7),
    (45,1,'Pages','Settings',7),
    (46,1,'Pages','UploadFile',7),
    (47,1,'Search','AddSynonym',7),
    (48,1,'Search','DeleteSynonym',7),
    (49,1,'Search','EditSynonym',7),
    (50,1,'Search','Settings',7),
    (51,1,'Search','Statistics',7),
    (52,1,'Search','Synonyms',7),
    (53,1,'ContentBlocks','Add',7),
    (54,1,'ContentBlocks','Delete',7),
    (55,1,'ContentBlocks','Edit',7),
    (56,1,'ContentBlocks','Index',7),
    (57,1,'Tags','Autocomplete',7),
    (58,1,'Tags','Edit',7),
    (59,1,'Tags','Index',7),
    (60,1,'Tags','MassAction',7),
    (61,1,'Tags','GetAllTags',7),
    (62,1,'MediaLibrary','MediaFolderAdd',7),
    (63,1,'MediaLibrary','MediaFolderDelete',7),
    (64,1,'MediaLibrary','MediaFolderEdit',7),
    (65,1,'MediaLibrary','MediaFolderFindAll',7),
    (66,1,'MediaLibrary','MediaFolderGetCountsForGroup',7),
    (67,1,'MediaLibrary','MediaFolderInfo',7),
    (68,1,'MediaLibrary','MediaFolderMove',7),
    (69,1,'MediaLibrary','MediaItemAddMovie',7),
    (70,1,'MediaLibrary','MediaItemDelete',7),
    (71,1,'MediaLibrary','MediaItemEdit',7),
    (72,1,'MediaLibrary','MediaItemEditTitle',7),
    (73,1,'MediaLibrary','MediaItemFindAll',7),
    (74,1,'MediaLibrary','MediaItemGetAllById',7),
    (75,1,'MediaLibrary','MediaItemIndex',7),
    (76,1,'MediaLibrary','MediaItemMassAction',7),
    (77,1,'MediaLibrary','MediaItemUpload',7),
    (78,1,'MediaLibrary','MediaBrowser',7),
    (79,1,'MediaLibrary','MediaBrowserImages',7),
    (80,1,'MediaLibrary','MediaBrowserVideos',7),
    (81,1,'Blog','Add',7),
    (82,1,'Blog','Delete',7),
    (83,1,'Blog','Edit',7),
    (84,1,'Blog','ImportWordpress',7),
    (85,1,'Blog','Index',7),
    (86,1,'Blog','AddCategory',7),
    (87,1,'Blog','Categories',7),
    (88,1,'Blog','DeleteCategory',7),
    (89,1,'Blog','EditCategory',7),
    (90,1,'Blog','Comments',7),
    (91,1,'Blog','DeleteSpam',7),
    (92,1,'Blog','EditComment',7),
    (93,1,'Blog','MassCommentAction',7),
    (94,1,'Blog','Settings',7);

/*!40000 ALTER TABLE `groups_rights_actions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups_rights_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups_rights_modules`;

CREATE TABLE `groups_rights_modules` (
                                         `id` int(11) NOT NULL AUTO_INCREMENT,
                                         `group_id` int(11) NOT NULL,
                                         `module` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'name of the module',
                                         PRIMARY KEY (`id`),
                                         KEY `idx_group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `groups_rights_modules` WRITE;
/*!40000 ALTER TABLE `groups_rights_modules` DISABLE KEYS */;

INSERT INTO `groups_rights_modules` (`id`, `group_id`, `module`)
VALUES
    (1,1,'Dashboard'),
    (2,1,'Locale'),
    (3,1,'Settings'),
    (4,1,'Users'),
    (5,1,'Groups'),
    (6,1,'Extensions'),
    (7,1,'Pages'),
    (8,1,'Search'),
    (9,1,'ContentBlocks'),
    (10,1,'Tags'),
    (11,1,'MediaLibrary'),
    (12,1,'Blog');

/*!40000 ALTER TABLE `groups_rights_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table groups_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups_settings`;

CREATE TABLE `groups_settings` (
                                   `group_id` int(11) NOT NULL,
                                   `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'name of the setting',
                                   `value` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'serialized value',
                                   PRIMARY KEY (`group_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `groups_settings` WRITE;
/*!40000 ALTER TABLE `groups_settings` DISABLE KEYS */;

INSERT INTO `groups_settings` (`group_id`, `name`, `value`)
VALUES
    (1,'dashboard_sequence','a:3:{s:8:\"Settings\";a:1:{i:0;s:7:\"Analyse\";}s:5:\"Users\";a:1:{i:0;s:10:\"Statistics\";}s:4:\"Blog\";a:1:{i:0;s:8:\"Comments\";}}');

/*!40000 ALTER TABLE `groups_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table locale
# ------------------------------------------------------------

DROP TABLE IF EXISTS `locale`;

CREATE TABLE `locale` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) NOT NULL,
                          `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `application` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `module` varchar(255) CHARACTER SET utf8 NOT NULL,
                          `type` varchar(110) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lbl',
                          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `value` text COLLATE utf8mb4_unicode_ci,
                          `edited_on` datetime NOT NULL,
                          PRIMARY KEY (`id`),
                          UNIQUE KEY `language` (`language`,`application`(20),`module`(20),`type`,`name`(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `locale` WRITE;
/*!40000 ALTER TABLE `locale` DISABLE KEYS */;

INSERT INTO `locale` (`id`, `user_id`, `language`, `application`, `module`, `type`, `name`, `value`, `edited_on`)
VALUES
    (1,1,'en','Backend','Locale','lbl','Actions','actions','2021-08-26 17:39:35'),
    (2,1,'en','Backend','Locale','lbl','Add','add translation','2021-08-26 17:39:35'),
    (3,1,'en','Backend','Locale','lbl','Copy','copy','2021-08-26 17:39:35'),
    (4,1,'en','Backend','Locale','lbl','Edited','edited','2021-08-26 17:39:35'),
    (5,1,'en','Backend','Locale','lbl','EN','english','2021-08-26 17:39:35'),
    (6,1,'en','Backend','Locale','lbl','Errors','errors','2021-08-26 17:39:35'),
    (7,1,'en','Backend','Locale','lbl','FR','french','2021-08-26 17:39:35'),
    (8,1,'en','Backend','Locale','lbl','Labels','labels','2021-08-26 17:39:35'),
    (9,1,'en','Backend','Locale','lbl','Messages','messages','2021-08-26 17:39:35'),
    (10,1,'en','Backend','Locale','lbl','NL','Dutch','2021-08-26 17:39:35'),
    (11,1,'en','Backend','Locale','lbl','UK','Ukrainian','2021-08-26 17:39:35'),
    (12,1,'en','Backend','Locale','lbl','SV','Swedish','2021-08-26 17:39:35'),
    (13,1,'en','Backend','Locale','lbl','ES','Spanish','2021-08-26 17:39:35'),
    (14,1,'en','Backend','Locale','lbl','RU','Russian','2021-08-26 17:39:35'),
    (15,1,'en','Backend','Locale','lbl','LT','Lithuanian','2021-08-26 17:39:35'),
    (16,1,'en','Backend','Locale','lbl','IT','Italian','2021-08-26 17:39:35'),
    (17,1,'en','Backend','Locale','lbl','HU','Hungarian','2021-08-26 17:39:35'),
    (18,1,'en','Backend','Locale','lbl','EL','Greek','2021-08-26 17:39:35'),
    (19,1,'en','Backend','Locale','lbl','DE','German','2021-08-26 17:39:35'),
    (20,1,'en','Backend','Locale','lbl','ZH','Chinese','2021-08-26 17:39:35'),
    (21,1,'en','Backend','Locale','lbl','Types','types','2021-08-26 17:39:35'),
    (22,1,'en','Backend','Locale','msg','Added','The translation \"%1$s\" was added.','2021-08-26 17:39:35'),
    (23,1,'en','Backend','Locale','msg','ConfirmDelete','Are you sure you want to delete this translation?','2021-08-26 17:39:35'),
    (24,1,'en','Backend','Locale','msg','Deleted','The translation \"%1$s\" was deleted.','2021-08-26 17:39:35'),
    (25,1,'en','Backend','Locale','msg','Edited','The translation \"%1$s\" was saved.','2021-08-26 17:39:35'),
    (26,1,'en','Backend','Locale','msg','EditTranslation','edit translation \"%1$s\"','2021-08-26 17:39:35'),
    (27,1,'en','Backend','Locale','msg','HelpActionValue','Only use alphanumeric characters (no capitals), - and _ for these translations, because they will be used in URLs.','2021-08-26 17:39:35'),
    (28,1,'en','Backend','Locale','msg','HelpAddName','The English reference for the translation','2021-08-26 17:39:35'),
    (29,1,'en','Backend','Locale','msg','HelpAddValue','The translation','2021-08-26 17:39:35'),
    (30,1,'en','Backend','Locale','msg','HelpDateField','eg. 20/06/2011','2021-08-26 17:39:35'),
    (31,1,'en','Backend','Locale','msg','HelpEditName','The English reference for the translation','2021-08-26 17:39:35'),
    (32,1,'en','Backend','Locale','msg','HelpEditValue','The translation','2021-08-26 17:39:35'),
    (33,1,'en','Backend','Locale','msg','HelpImageField','Only jp(e)g, gif or png-files are allowed.','2021-08-26 17:39:35'),
    (34,1,'en','Backend','Locale','msg','HelpName','The english reference for this translation','2021-08-26 17:39:35'),
    (35,1,'en','Backend','Locale','msg','HelpTimeField','eg. 14:35','2021-08-26 17:39:35'),
    (36,1,'en','Backend','Locale','msg','HelpValue','The translation','2021-08-26 17:39:35'),
    (37,1,'en','Backend','Locale','msg','Imported','%1$s translations were imported.','2021-08-26 17:39:35'),
    (38,1,'en','Backend','Locale','msg','NoItems','There are no translations yet. <a href=\"%1$s\">Add the first translation</a>.','2021-08-26 17:39:35'),
    (39,1,'en','Backend','Locale','msg','NoItemsAnalyse','No missing translations were found.','2021-08-26 17:39:35'),
    (40,1,'en','Backend','Locale','msg','NoItemsFilter','There are no translations yet for this filter. <a href=\"%1$s\">Add the first translation</a>.','2021-08-26 17:39:35'),
    (41,1,'en','Backend','Locale','msg','StartSearch','Make a search result.','2021-08-26 17:39:35'),
    (42,1,'en','Backend','Locale','msg','OverwriteConflicts','Overwrite if the translation exists.','2021-08-26 17:39:35'),
    (43,1,'en','Backend','Locale','err','AlreadyExists','This translation already exists.','2021-08-26 17:39:35'),
    (44,1,'en','Backend','Locale','err','FirstLetterMustBeACapitalLetter','The first letter must be a capital letter.','2021-08-26 17:39:35'),
    (45,1,'en','Backend','Locale','err','InvalidActionValue','The action name contains invalid characters.','2021-08-26 17:39:35'),
    (46,1,'en','Backend','Locale','err','InvalidXML','This is an invalid XML-file.','2021-08-26 17:39:35'),
    (47,1,'en','Backend','Locale','err','ModuleHasToBeCore','The module needs to be core for frontend translations.','2021-08-26 17:39:35'),
    (48,1,'en','Backend','Locale','err','NoSelection','No translations were selected.','2021-08-26 17:39:35'),
    (49,1,'en','Backend','Core','err','InvalidCSV','This is an invalid CSV-file.','2021-08-26 17:39:35'),
    (50,1,'en','Frontend','Core','lbl','OpenNavigation','open navigation','2021-08-26 17:39:36'),
    (51,1,'en','Frontend','Core','lbl','CloseNavigation','close navigation','2021-08-26 17:39:36'),
    (52,1,'en','Frontend','Core','err','NumberIsInvalid','Invalid number.','2021-08-26 17:39:36'),
    (53,1,'en','Frontend','Core','lbl','AboutUs','about us','2021-08-26 17:39:36'),
    (54,1,'en','Frontend','Core','lbl','Advertisement','advertisement','2021-08-26 17:39:36'),
    (55,1,'en','Frontend','Core','lbl','Archive','archive','2021-08-26 17:39:36'),
    (56,1,'en','Frontend','Core','lbl','Archives','archives','2021-08-26 17:39:36'),
    (57,1,'en','Frontend','Core','lbl','ArticlesInCategory','articles in category','2021-08-26 17:39:36'),
    (58,1,'en','Frontend','Core','lbl','Avatar','avatar','2021-08-26 17:39:36'),
    (59,1,'en','Frontend','Core','lbl','BirthDate','birth date','2021-08-26 17:39:36'),
    (60,1,'en','Frontend','Core','lbl','Blog','blog','2021-08-26 17:39:36'),
    (61,1,'en','Frontend','Core','lbl','BlogArchive','blog archive','2021-08-26 17:39:36'),
    (62,1,'en','Frontend','Core','lbl','Breadcrumb','breadcrumb','2021-08-26 17:39:36'),
    (63,1,'en','Frontend','Core','lbl','By','by','2021-08-26 17:39:36'),
    (64,1,'en','Frontend','Core','lbl','Categories','categories','2021-08-26 17:39:36'),
    (65,1,'en','Frontend','Core','lbl','Category','category','2021-08-26 17:39:36'),
    (66,1,'en','Frontend','Core','lbl','City','city','2021-08-26 17:39:36'),
    (67,1,'en','Frontend','Core','lbl','Close','close','2021-08-26 17:39:36'),
    (68,1,'en','Frontend','Core','lbl','Comment','comment','2021-08-26 17:39:36'),
    (69,1,'en','Frontend','Core','lbl','CommentedOn','commented on','2021-08-26 17:39:36'),
    (70,1,'en','Frontend','Core','lbl','Comments','comments','2021-08-26 17:39:36'),
    (71,1,'en','Frontend','Core','lbl','Contact','contact','2021-08-26 17:39:36'),
    (72,1,'en','Frontend','Core','lbl','Content','content','2021-08-26 17:39:36'),
    (73,1,'en','Frontend','Core','lbl','Country','country','2021-08-26 17:39:36'),
    (74,1,'en','Frontend','Core','lbl','Date','date','2021-08-26 17:39:36'),
    (75,1,'en','Frontend','Core','lbl','Disclaimer','disclaimer','2021-08-26 17:39:36'),
    (76,1,'en','Frontend','Core','lbl','DisplayName','display name','2021-08-26 17:39:36'),
    (77,1,'en','Frontend','Core','lbl','Email','e-mail','2021-08-26 17:39:36'),
    (78,1,'en','Frontend','Core','lbl','EN','English','2021-08-26 17:39:36'),
    (79,1,'en','Frontend','Core','lbl','EnableJavascript','enable javascript','2021-08-26 17:39:36'),
    (80,1,'en','Frontend','Core','lbl','ES','Spanish','2021-08-26 17:39:36'),
    (81,1,'en','Frontend','Core','lbl','Faq','FAQ','2021-08-26 17:39:36'),
    (82,1,'en','Frontend','Core','lbl','Feedback','feedback','2021-08-26 17:39:36'),
    (83,1,'en','Frontend','Core','lbl','Female','female','2021-08-26 17:39:36'),
    (84,1,'en','Frontend','Core','lbl','FirstName','first name','2021-08-26 17:39:36'),
    (85,1,'en','Frontend','Core','lbl','FooterNavigation','footer navigation','2021-08-26 17:39:36'),
    (86,1,'en','Frontend','Core','lbl','FR','French','2021-08-26 17:39:36'),
    (87,1,'en','Frontend','Core','lbl','Gender','gender','2021-08-26 17:39:36'),
    (88,1,'en','Frontend','Core','lbl','GoTo','go to','2021-08-26 17:39:36'),
    (89,1,'en','Frontend','Core','lbl','GoToPage','go to page','2021-08-26 17:39:36'),
    (90,1,'en','Frontend','Core','lbl','History','history','2021-08-26 17:39:36'),
    (91,1,'en','Frontend','Core','lbl','IAgree','I agree','2021-08-26 17:39:36'),
    (92,1,'en','Frontend','Core','lbl','IDisagree','I disagree','2021-08-26 17:39:36'),
    (93,1,'en','Frontend','Core','lbl','In','in','2021-08-26 17:39:36'),
    (94,1,'en','Frontend','Core','lbl','InTheCategory','in category','2021-08-26 17:39:36'),
    (95,1,'en','Frontend','Core','lbl','ItemsWithTag','items with tag \"%1$s\"','2021-08-26 17:39:36'),
    (96,1,'en','Frontend','Core','lbl','Language','language','2021-08-26 17:39:36'),
    (97,1,'en','Frontend','Core','lbl','LastName','last name','2021-08-26 17:39:36'),
    (98,1,'en','Frontend','Core','lbl','Location','location','2021-08-26 17:39:36'),
    (99,1,'en','Frontend','Core','lbl','Login','login','2021-08-26 17:39:36'),
    (100,1,'en','Frontend','Core','lbl','Logout','logout','2021-08-26 17:39:36'),
    (101,1,'en','Frontend','Core','lbl','LT','Lithuanian','2021-08-26 17:39:36'),
    (102,1,'en','Frontend','Core','lbl','MainNavigation','main navigation','2021-08-26 17:39:36'),
    (103,1,'en','Frontend','Core','lbl','Male','male','2021-08-26 17:39:36'),
    (104,1,'en','Frontend','Core','lbl','Message','message','2021-08-26 17:39:36'),
    (105,1,'en','Frontend','Core','lbl','More','more','2021-08-26 17:39:36'),
    (106,1,'en','Frontend','Core','lbl','MostReadQuestions','Most read questions','2021-08-26 17:39:36'),
    (107,1,'en','Frontend','Core','lbl','Name','name','2021-08-26 17:39:36'),
    (108,1,'en','Frontend','Core','lbl','NewPassword','new password','2021-08-26 17:39:36'),
    (109,1,'en','Frontend','Core','lbl','VerifyNewPassword','verify new password','2021-08-26 17:39:36'),
    (110,1,'en','Frontend','Core','err','PasswordsDontMatch','The passwords differ','2021-08-26 17:39:36'),
    (111,1,'en','Frontend','Core','lbl','Next','next','2021-08-26 17:39:36'),
    (112,1,'en','Frontend','Core','lbl','NextArticle','next article','2021-08-26 17:39:36'),
    (113,1,'en','Frontend','Core','lbl','NextPage','next page','2021-08-26 17:39:36'),
    (114,1,'en','Frontend','Core','lbl','NL','Dutch','2021-08-26 17:39:36'),
    (115,1,'en','Frontend','Core','lbl','PT','Portuguese','2021-08-26 17:39:36'),
    (116,1,'en','Frontend','Core','lbl','UK','Ukrainian','2021-08-26 17:39:36'),
    (117,1,'en','Frontend','Core','lbl','SV','Swedish','2021-08-26 17:39:36'),
    (118,1,'en','Frontend','Core','lbl','RU','Russian','2021-08-26 17:39:36'),
    (119,1,'en','Frontend','Core','lbl','IT','Italian','2021-08-26 17:39:36'),
    (120,1,'en','Frontend','Core','lbl','HU','Hungarian','2021-08-26 17:39:36'),
    (121,1,'en','Frontend','Core','lbl','EL','Greek','2021-08-26 17:39:36'),
    (122,1,'en','Frontend','Core','lbl','ZH','Chinese','2021-08-26 17:39:36'),
    (123,1,'en','Frontend','Core','lbl','DE','German','2021-08-26 17:39:36'),
    (124,1,'en','Frontend','Core','lbl','No','no','2021-08-26 17:39:36'),
    (125,1,'en','Frontend','Core','lbl','OldPassword','old password','2021-08-26 17:39:36'),
    (126,1,'en','Frontend','Core','lbl','On','on','2021-08-26 17:39:36'),
    (127,1,'en','Frontend','Core','lbl','Or','or','2021-08-26 17:39:36'),
    (128,1,'en','Frontend','Core','lbl','Pages','pages','2021-08-26 17:39:36'),
    (129,1,'en','Frontend','Core','lbl','Parent','parent','2021-08-26 17:39:36'),
    (130,1,'en','Frontend','Core','lbl','ParentPage','parent page','2021-08-26 17:39:36'),
    (131,1,'en','Frontend','Core','lbl','Password','password','2021-08-26 17:39:36'),
    (132,1,'en','Frontend','Core','lbl','Previous','previous','2021-08-26 17:39:36'),
    (133,1,'en','Frontend','Core','lbl','PreviousArticle','previous article','2021-08-26 17:39:36'),
    (134,1,'en','Frontend','Core','lbl','PreviousPage','previous page','2021-08-26 17:39:36'),
    (135,1,'en','Frontend','Core','lbl','ProfileSettings','settings','2021-08-26 17:39:36'),
    (136,1,'en','Frontend','Core','lbl','Question','question','2021-08-26 17:39:36'),
    (137,1,'en','Frontend','Core','lbl','Questions','questions','2021-08-26 17:39:36'),
    (138,1,'en','Frontend','Core','lbl','RecentArticles','recent articles','2021-08-26 17:39:36'),
    (139,1,'en','Frontend','Core','lbl','RecentComments','recent comments','2021-08-26 17:39:36'),
    (140,1,'en','Frontend','Core','lbl','Register','register','2021-08-26 17:39:36'),
    (141,1,'en','Frontend','Core','lbl','Related','related','2021-08-26 17:39:36'),
    (142,1,'en','Frontend','Core','lbl','RememberMe','remember me','2021-08-26 17:39:36'),
    (143,1,'en','Frontend','Core','lbl','RequiredField','required field','2021-08-26 17:39:36'),
    (144,1,'en','Frontend','Core','lbl','Save','save','2021-08-26 17:39:36'),
    (145,1,'en','Frontend','Core','lbl','Search','search','2021-08-26 17:39:36'),
    (146,1,'en','Frontend','Core','lbl','SearchAgain','search again','2021-08-26 17:39:36'),
    (147,1,'en','Frontend','Core','lbl','SearchTerm','searchterm','2021-08-26 17:39:36'),
    (148,1,'en','Frontend','Core','lbl','Send','send','2021-08-26 17:39:36'),
    (149,1,'en','Frontend','Core','lbl','SenderInformation','sender information','2021-08-26 17:39:36'),
    (150,1,'en','Frontend','Core','lbl','Sent','sent','2021-08-26 17:39:36'),
    (151,1,'en','Frontend','Core','lbl','SentMailings','sent mailings','2021-08-26 17:39:36'),
    (152,1,'en','Frontend','Core','lbl','SentOn','sent on','2021-08-26 17:39:36'),
    (153,1,'en','Frontend','Core','lbl','Settings','settings','2021-08-26 17:39:36'),
    (154,1,'en','Frontend','Core','lbl','Share','share','2021-08-26 17:39:36'),
    (155,1,'en','Frontend','Core','lbl','ShowDirections','Show directions','2021-08-26 17:39:36'),
    (156,1,'en','Frontend','Core','lbl','ShowPassword','show password','2021-08-26 17:39:36'),
    (157,1,'en','Frontend','Core','lbl','Sitemap','sitemap','2021-08-26 17:39:36'),
    (158,1,'en','Frontend','Core','lbl','SkipToContent','skip to content','2021-08-26 17:39:36'),
    (159,1,'en','Frontend','Core','lbl','Start','startpoint','2021-08-26 17:39:36'),
    (160,1,'en','Frontend','Core','lbl','Subnavigation','subnavigation','2021-08-26 17:39:36'),
    (161,1,'en','Frontend','Core','lbl','Subscribe','subscribe','2021-08-26 17:39:36'),
    (162,1,'en','Frontend','Core','lbl','SubscribeToTheRSSFeed','subscribe to the RSS feed','2021-08-26 17:39:36'),
    (163,1,'en','Frontend','Core','lbl','Tags','tags','2021-08-26 17:39:36'),
    (164,1,'en','Frontend','Core','lbl','The','the','2021-08-26 17:39:36'),
    (165,1,'en','Frontend','Core','lbl','Title','title','2021-08-26 17:39:36'),
    (166,1,'en','Frontend','Core','lbl','ToFaqOverview','to the FAQ overview','2021-08-26 17:39:36'),
    (167,1,'en','Frontend','Core','lbl','ToTagsOverview','to tags overview','2021-08-26 17:39:36'),
    (168,1,'en','Frontend','Core','lbl','Unsubscribe','unsubscribe','2021-08-26 17:39:36'),
    (169,1,'en','Frontend','Core','lbl','ViewLargeMap','Display large map','2021-08-26 17:39:36'),
    (170,1,'en','Frontend','Core','lbl','Website','website','2021-08-26 17:39:36'),
    (171,1,'en','Frontend','Core','lbl','With','with','2021-08-26 17:39:36'),
    (172,1,'en','Frontend','Core','lbl','WrittenOn','written on','2021-08-26 17:39:36'),
    (173,1,'en','Frontend','Core','lbl','Wrote','wrote','2021-08-26 17:39:36'),
    (174,1,'en','Frontend','Core','lbl','Yes','yes','2021-08-26 17:39:36'),
    (175,1,'en','Frontend','Core','lbl','YouAreHere','you are here','2021-08-26 17:39:36'),
    (176,1,'en','Frontend','Core','lbl','YourAvatar','your avatar','2021-08-26 17:39:36'),
    (177,1,'en','Frontend','Core','lbl','YourData','your data','2021-08-26 17:39:36'),
    (178,1,'en','Frontend','Core','lbl','YourEmail','your e-mail address','2021-08-26 17:39:36'),
    (179,1,'en','Frontend','Core','lbl','YourLocationData','your location','2021-08-26 17:39:36'),
    (180,1,'en','Frontend','Core','lbl','YourName','your name','2021-08-26 17:39:36'),
    (181,1,'en','Frontend','Core','lbl','YourQuestion','your question','2021-08-26 17:39:36'),
    (182,1,'en','Frontend','Core','msg','ActivationIsSuccess','Your profile was activated.','2021-08-26 17:39:36'),
    (183,1,'en','Frontend','Core','msg','AlsoInteresting','Also interesting for you','2021-08-26 17:39:36'),
    (184,1,'en','Frontend','Core','msg','AskOwnQuestion','Didn\'t find what you were looking for? Ask your own question!','2021-08-26 17:39:36'),
	(185,1,'en','Frontend','Core','msg','BlogAllComments','All comments on your blog.','2021-08-26 17:39:36'),
	(186,1,'en','Frontend','Core','msg','BlogCommentInModeration','Your comment is awaiting moderation.','2021-08-26 17:39:36'),
	(187,1,'en','Frontend','Core','msg','BlogCommentIsAdded','Your comment was added.','2021-08-26 17:39:36'),
	(188,1,'en','Frontend','Core','msg','BlogCommentIsSpam','Your comment was marked as spam.','2021-08-26 17:39:36'),
	(189,1,'en','Frontend','Core','msg','BlogEmailNotificationsNewComment','%1$s commented on <a href=\"%2$s\">%3$s</a>.','2021-08-26 17:39:36'),
	(190,1,'en','Frontend','Core','msg','BlogEmailNotificationsNewCommentToModerate','%1$s commented on <a href=\"%2$s\">%3$s</a>. <a href=\"%4$s\">Moderate</a> the comment to publish it.','2021-08-26 17:39:36'),
     (191,1,'en','Frontend','Core','msg','BlogNoComments','Be the first to comment','2021-08-26 17:39:36'),
     (192,1,'en','Frontend','Core','msg','BlogNoItems','There are no articles yet.','2021-08-26 17:39:36'),
     (193,1,'en','Frontend','Core','msg','BlogNumberOfComments','%1$s comments','2021-08-26 17:39:36'),
     (194,1,'en','Frontend','Core','msg','BlogOneComment','1 comment already','2021-08-26 17:39:36'),
     (195,1,'en','Frontend','Core','msg','ChangeEmail','change your e-mail address','2021-08-26 17:39:36'),
     (196,1,'en','Frontend','Core','msg','Comment','comment','2021-08-26 17:39:36'),
     (197,1,'en','Frontend','Core','msg','CommentsOn','Comments on %1$s','2021-08-26 17:39:36'),
     (198,1,'en','Frontend','Core','msg','ContactMessageSent','Your e-mail was sent.','2021-08-26 17:39:36'),
     (199,1,'en','Frontend','Core','msg','ContactSubject','E-mail via contact form.','2021-08-26 17:39:36'),
     (200,1,'en','Frontend','Core','msg','CookiesWarning','To improve the user experience on this site we use <a href=\"/disclaimer\">cookies</a>.','2021-08-26 17:39:36'),
     (201,1,'en','Frontend','Core','msg','EN','English','2021-08-26 17:39:36'),
     (202,1,'en','Frontend','Core','msg','EnableJavascript','Having javascript enabled is recommended for using this site.','2021-08-26 17:39:36'),
     (203,1,'en','Frontend','Core','msg','FaqFeedbackSubject','There is feedback on \"%1$s\"','2021-08-26 17:39:36'),
     (204,1,'en','Frontend','Core','msg','FaqNoItems','There are no questions yet.','2021-08-26 17:39:36'),
     (205,1,'en','Frontend','Core','msg','FaqOwnQuestionSubject','A question from %1$s.','2021-08-26 17:39:36'),
     (206,1,'en','Frontend','Core','msg','Feedback','Was this answer helpful?','2021-08-26 17:39:36'),
     (207,1,'en','Frontend','Core','msg','FeedbackSuccess','Your feedback has been sent.','2021-08-26 17:39:36'),
     (208,1,'en','Frontend','Core','msg','ForgotPassword','Forgot your password?','2021-08-26 17:39:36'),
     (209,1,'en','Frontend','Core','msg','ForgotPasswordBody','You just requested to reset your password on <a href=\"%1$s\">Fork CMS</a>. Follow the link below to reset your password.<br /><br /><a href=\"%2$s\">%2$s</a>','2021-08-26 17:39:36'),
     (210,1,'en','Frontend','Core','msg','ForgotPasswordClosure','With kind regards,<br/><br/>The Fork CMS team','2021-08-26 17:39:36'),
     (211,1,'en','Frontend','Core','msg','ForgotPasswordIsSuccess','In less than ten minutes you will receive an e-mail to reset your password.','2021-08-26 17:39:36'),
     (212,1,'en','Frontend','Core','msg','ForgotPasswordSalutation','Dear,','2021-08-26 17:39:36'),
     (213,1,'en','Frontend','Core','msg','ForgotPasswordSubject','Forgot your password?','2021-08-26 17:39:36'),
     (214,1,'en','Frontend','Core','msg','FormBuilderSubject','New submission for form \"%1$s\".','2021-08-26 17:39:36'),
     (215,1,'en','Frontend','Core','msg','FR','French','2021-08-26 17:39:36'),
     (216,1,'en','Frontend','Core','msg','HelpDateField','eg. 20/06/2011','2021-08-26 17:39:36'),
     (217,1,'en','Frontend','Core','msg','HelpDisplayNameChanges','The amount of display name changes is limited to %1$s. You have %2$s change(s) left.','2021-08-26 17:39:36'),
     (218,1,'en','Frontend','Core','msg','HelpImageField','Only jp(e)g, gif or png-files are allowed.','2021-08-26 17:39:36'),
     (219,1,'en','Frontend','Core','msg','HelpTimeField','eg. 14:35','2021-08-26 17:39:36'),
     (220,1,'en','Frontend','Core','msg','HowToImprove','How can we improve this answer?','2021-08-26 17:39:36'),
     (221,1,'en','Frontend','Core','msg','MoreResults','Find more results…','2021-08-26 17:39:36'),
     (222,1,'en','Frontend','Core','msg','NL','Dutch','2021-08-26 17:39:36'),
     (223,1,'en','Frontend','Core','msg','PT','Portuguese','2021-08-26 17:39:36'),
     (224,1,'en','Frontend','Core','msg','UK','Ukrainian','2021-08-26 17:39:36'),
     (225,1,'en','Frontend','Core','msg','SV','Swedish','2021-08-26 17:39:36'),
     (226,1,'en','Frontend','Core','msg','ES','Spanish','2021-08-26 17:39:36'),
     (227,1,'en','Frontend','Core','msg','RU','Russian','2021-08-26 17:39:36'),
     (228,1,'en','Frontend','Core','msg','LT','Lithuanian','2021-08-26 17:39:36'),
     (229,1,'en','Frontend','Core','msg','IT','Italian','2021-08-26 17:39:36'),
     (230,1,'en','Frontend','Core','msg','HU','Hungarian','2021-08-26 17:39:36'),
     (231,1,'en','Frontend','Core','msg','EL','Greek','2021-08-26 17:39:36'),
     (232,1,'en','Frontend','Core','msg','DE','German','2021-08-26 17:39:36'),
     (233,1,'en','Frontend','Core','msg','ZH','Chinese','2021-08-26 17:39:36'),
     (234,1,'en','Frontend','Core','msg','NoQuestionsInCategory','There are no questions in this category.','2021-08-26 17:39:36'),
     (235,1,'en','Frontend','Core','msg','NoSentMailings','So far','2021-08-26 17:39:36'),
     (236,1,'en','Frontend','Core','msg','NotificationSubject','Notification','2021-08-26 17:39:36'),
     (237,1,'en','Frontend','Core','msg','OtherQuestions','Other questions','2021-08-26 17:39:36'),
     (238,1,'en','Frontend','Core','msg','OwnQuestionSuccess','Your question has been sent. We\'ll give you an answer as soon as possible.','2021-08-26 17:39:36'),
	(239,1,'en','Frontend','Core','msg','ProfilesLoggedInAs','You are logged on as <a href=\"%2$s\">%1$s</a>.','2021-08-26 17:39:36'),
	(240,1,'en','Frontend','Core','msg','QuestionsInSameCategory','Other questions in this category','2021-08-26 17:39:36'),
      (241,1,'en','Frontend','Core','msg','RegisterBody','You have just registered on the <a href=\"%1$s\">Fork CMS</a> site. To activate your profile you need to follow the link below.<br /><br /><a href=\"%2$s\">%2$s</a>','2021-08-26 17:39:36'),
      (242,1,'en','Frontend','Core','msg','RegisterClosure','With kind regards,<br/><br/>The Fork CMS team','2021-08-26 17:39:36'),
      (243,1,'en','Frontend','Core','msg','RegisterSalutation','Dear,','2021-08-26 17:39:36'),
      (244,1,'en','Frontend','Core','msg','RegisterSubject','Activate your Fork CMS-profile','2021-08-26 17:39:36'),
      (245,1,'en','Frontend','Core','msg','RelatedQuestions','Also read','2021-08-26 17:39:36'),
      (246,1,'en','Frontend','Core','msg','ResendActivationIsSuccess','In less than ten minutes you will receive an new activation mail. A simple click on the link and you will be able to log in.','2021-08-26 17:39:36'),
      (247,1,'en','Frontend','Core','msg','ResetPasswordIsSuccess','Your password was saved.','2021-08-26 17:39:36'),
      (248,1,'en','Frontend','Core','msg','SearchNoItems','There were no results.','2021-08-26 17:39:36'),
      (249,1,'en','Frontend','Core','msg','SubscribeSuccess','You have successfully subscribed to the newsletter.','2021-08-26 17:39:36'),
      (250,1,'en','Frontend','Core','msg','TagsNoItems','No tags were used.','2021-08-26 17:39:36'),
      (251,1,'en','Frontend','Core','msg','UnsubscribeSuccess','You have successfully unsubscribed from the newsletter.','2021-08-26 17:39:36'),
      (252,1,'en','Frontend','Core','msg','UpdateEmailIsSuccess','Your e-mail was saved.','2021-08-26 17:39:36'),
      (253,1,'en','Frontend','Core','msg','UpdatePasswordIsSuccess','Your password was saved.','2021-08-26 17:39:36'),
      (254,1,'en','Frontend','Core','msg','UpdateSettingsIsSuccess','The settings were saved.','2021-08-26 17:39:36'),
      (255,1,'en','Frontend','Core','msg','WelcomeUserX','Welcome, %1$s','2021-08-26 17:39:36'),
      (256,1,'en','Frontend','Core','msg','WrittenBy','written by %1$s','2021-08-26 17:39:36'),
      (257,1,'en','Frontend','Core','err','AlreadySubscribed','This e-mail address is already subscribed to the newsletter.','2021-08-26 17:39:36'),
      (258,1,'en','Frontend','Core','err','AlreadyUnsubscribed','This e-mail address is already unsubscribed from the newsletter','2021-08-26 17:39:36'),
      (259,1,'en','Frontend','Core','err','AuthorIsRequired','Author is a required field.','2021-08-26 17:39:36'),
      (260,1,'en','Frontend','Core','err','CommentTimeout','Slow down cowboy','2021-08-26 17:39:36'),
      (261,1,'en','Frontend','Core','err','ContactErrorWhileSending','Something went wrong while trying to send','2021-08-26 17:39:36'),
      (262,1,'en','Frontend','Core','err','DateIsInvalid','Invalid date.','2021-08-26 17:39:36'),
      (263,1,'en','Frontend','Core','err','DisplayNameExists','This display name is in use.','2021-08-26 17:39:36'),
      (264,1,'en','Frontend','Core','err','DisplayNameIsRequired','Display name is a required field.','2021-08-26 17:39:36'),
      (265,1,'en','Frontend','Core','err','EmailExists','This e-mailaddress is in use.','2021-08-26 17:39:36'),
      (266,1,'en','Frontend','Core','err','EmailIsInvalid','Please provide a valid e-mail address.','2021-08-26 17:39:36'),
      (267,1,'en','Frontend','Core','err','EmailIsRequired','E-mail is a required field.','2021-08-26 17:39:36'),
      (268,1,'en','Frontend','Core','err','EmailIsUnknown','This e-mailaddress is unknown in our database.','2021-08-26 17:39:36'),
      (269,1,'en','Frontend','Core','err','EmailNotInDatabase','This e-mail address does not exist in the database.','2021-08-26 17:39:36'),
      (270,1,'en','Frontend','Core','err','FeedbackIsRequired','Please provide feedback.','2021-08-26 17:39:36'),
      (271,1,'en','Frontend','Core','err','FeedbackSpam','Your feedback was marked as spam.','2021-08-26 17:39:36'),
      (272,1,'en','Frontend','Core','err','FieldIsRequired','This field is required.','2021-08-26 17:39:36'),
      (273,1,'en','Frontend','Core','err','FileTooBig','maximum filesize: %1$s','2021-08-26 17:39:36'),
      (274,1,'en','Frontend','Core','err','FormError','Something went wrong','2021-08-26 17:39:36'),
      (275,1,'en','Frontend','Core','err','FormTimeout','Slow down cowboy','2021-08-26 17:39:36'),
      (276,1,'en','Frontend','Core','err','InvalidPassword','Invalid password.','2021-08-26 17:39:36'),
      (277,1,'en','Frontend','Core','err','InvalidValue','Invalid value.','2021-08-26 17:39:36'),
      (278,1,'en','Frontend','Core','err','InvalidPrice','Please insert a valid price.','2021-08-26 17:39:36'),
      (279,1,'en','Frontend','Core','err','InvalidURL','This is an invalid URL.','2021-08-26 17:39:36'),
      (280,1,'en','Frontend','Core','err','JPGGIFAndPNGOnly','Only jpg, gif, png','2021-08-26 17:39:36'),
      (281,1,'en','Frontend','Core','err','MessageIsRequired','Message is a required field.','2021-08-26 17:39:36'),
      (282,1,'en','Frontend','Core','err','NameIsRequired','Please provide a name.','2021-08-26 17:39:36'),
      (283,1,'en','Frontend','Core','err','NumericCharactersOnly','Only numeric characters are allowed.','2021-08-26 17:39:36'),
      (284,1,'en','Frontend','Core','err','OwnQuestionSpam','Your question was marked as spam.','2021-08-26 17:39:36'),
      (285,1,'en','Frontend','Core','err','PasswordIsRequired','Password is a required field.','2021-08-26 17:39:36'),
      (286,1,'en','Frontend','Core','err','ProfileIsActive','This profile is already activated.','2021-08-26 17:39:36'),
      (287,1,'en','Frontend','Core','err','ProfilesBlockedLogin','Login failed. This profile is blocked.','2021-08-26 17:39:36'),
      (288,1,'en','Frontend','Core','err','ProfilesDeletedLogin','Login failed. This profile has been deleted.','2021-08-26 17:39:36'),
      (289,1,'en','Frontend','Core','err','ProfilesInactiveLogin','Login failed. This profile is not yet activated. <a href=\"%1$s\">Resend activation e-mail</a>.','2021-08-26 17:39:36'),
      (290,1,'en','Frontend','Core','err','ProfilesInvalidLogin','Login failed. Please check your e-mail and your password.','2021-08-26 17:39:36'),
      (291,1,'en','Frontend','Core','err','QuestionIsRequired','Please provide a question.','2021-08-26 17:39:36'),
      (292,1,'en','Frontend','Core','err','SomethingWentWrong','Something went wrong.','2021-08-26 17:39:36'),
      (293,1,'en','Frontend','Core','err','SubscribeFailed','Subscribing failed','2021-08-26 17:39:36'),
      (294,1,'en','Frontend','Core','err','TermIsRequired','The searchterm is required.','2021-08-26 17:39:36'),
      (295,1,'en','Frontend','Core','err','UnsubscribeFailed','Unsubscribing failed','2021-08-26 17:39:36'),
      (296,1,'en','Frontend','Core','act','Archive','archive','2021-08-26 17:39:36'),
      (297,1,'en','Frontend','Core','act','ArticleCommentsRss','comments-on-rss','2021-08-26 17:39:36'),
      (298,1,'en','Frontend','Core','act','Category','category','2021-08-26 17:39:36'),
      (299,1,'en','Frontend','Core','act','Comment','comment','2021-08-26 17:39:36'),
      (300,1,'en','Frontend','Core','act','Comments','comments','2021-08-26 17:39:36'),
      (301,1,'en','Frontend','Core','act','CommentsRss','comments-rss','2021-08-26 17:39:36'),
      (302,1,'en','Frontend','Core','act','Detail','detail','2021-08-26 17:39:36'),
      (303,1,'en','Frontend','Core','act','Feedback','feedback','2021-08-26 17:39:36'),
      (304,1,'en','Frontend','Core','act','OwnQuestion','ask-your-question','2021-08-26 17:39:36'),
      (305,1,'en','Frontend','Core','act','Preview','preview','2021-08-26 17:39:36'),
      (306,1,'en','Frontend','Core','act','Rss','rss','2021-08-26 17:39:36'),
      (307,1,'en','Frontend','Core','act','Spam','spam','2021-08-26 17:39:36'),
      (308,1,'en','Frontend','Core','act','Subscribe','subscribe','2021-08-26 17:39:36'),
      (309,1,'en','Frontend','Core','act','Success','success','2021-08-26 17:39:36'),
      (310,1,'en','Frontend','Core','act','Unsubscribe','unsubscribe','2021-08-26 17:39:36'),
      (311,1,'en','Frontend','Core','lbl','Loading','loading','2021-08-26 17:39:36'),
      (312,1,'en','Frontend','Core','msg','PrivacyConsentDialogTitle','Your privacy','2021-08-26 17:39:36'),
      (313,1,'en','Frontend','Core','msg','PrivacyConsentDialogBefore','This website uses cookies. You can set your personal preferences below.','2021-08-26 17:39:36'),
      (314,1,'en','Frontend','Core','msg','PrivacyConsentDialogAfter','If you need more information you find it in our <a href=\"/disclaimer\">disclaimer</a>.','2021-08-26 17:39:36'),
      (315,1,'en','Frontend','Core','msg','PrivacyConsentLevelFunctionalTitle','Functional cookies','2021-08-26 17:39:36'),
      (316,1,'en','Frontend','Core','msg','PrivacyConsentLevelFunctionalText','These cookies are needed to let the website function correctly. For instance we store your language preference, &hellip;','2021-08-26 17:39:36'),
      (317,1,'en','Frontend','Core','msg','PrivacyConsentDialogSave','Save my preferences','2021-08-26 17:39:36'),
      (318,1,'en','Backend','Core','lbl','Close','close','2021-08-26 17:39:36'),
      (319,1,'en','Backend','Core','err','NumericCharactersOnly','Only numeric characters are allowed.','2021-08-26 17:39:36'),
      (320,1,'en','Backend','Core','msg','ConfirmDefault','Are you sure you want to perform this actions?','2021-08-26 17:39:36'),
      (321,1,'en','Backend','Core','lbl','ProfileSettings','settings','2021-08-26 17:39:36'),
      (322,1,'en','Backend','Core','lbl','AccountManagement','account management','2021-08-26 17:39:36'),
      (323,1,'en','Backend','Core','lbl','AccountSettings','account settings','2021-08-26 17:39:36'),
      (324,1,'en','Backend','Core','lbl','Activate','activate','2021-08-26 17:39:36'),
      (325,1,'en','Backend','Core','lbl','Active','active','2021-08-26 17:39:36'),
      (326,1,'en','Backend','Core','lbl','Add','add','2021-08-26 17:39:36'),
      (327,1,'en','Backend','Core','lbl','AddBlock','add block','2021-08-26 17:39:36'),
      (328,1,'en','Backend','Core','lbl','EditBlock','edit block','2021-08-26 17:39:36'),
      (329,1,'en','Backend','Core','lbl','AddCategory','add category','2021-08-26 17:39:36'),
      (330,1,'en','Backend','Core','lbl','Address','address','2021-08-26 17:39:36'),
      (331,1,'en','Backend','Core','lbl','EmailAddresses','e-mail addresses','2021-08-26 17:39:36'),
      (332,1,'en','Backend','Core','lbl','AddTemplate','add template','2021-08-26 17:39:36'),
      (333,1,'en','Backend','Core','lbl','Advanced','advanced','2021-08-26 17:39:36'),
      (334,1,'en','Backend','Core','lbl','AllEmailAddresses','all e-mail addresses','2021-08-26 17:39:36'),
      (335,1,'en','Backend','Core','lbl','AllComments','all comments','2021-08-26 17:39:36'),
      (336,1,'en','Backend','Core','lbl','AllowComments','allow comments','2021-08-26 17:39:36'),
      (337,1,'en','Backend','Core','lbl','AllPages','all pages','2021-08-26 17:39:36'),
      (338,1,'en','Backend','Core','lbl','AllQuestions','all questions','2021-08-26 17:39:36'),
      (339,1,'en','Backend','Core','lbl','Amount','amount','2021-08-26 17:39:36'),
      (340,1,'en','Backend','Core','lbl','Analyse','analyse','2021-08-26 17:39:36'),
      (341,1,'en','Backend','Core','lbl','Analysis','analysis','2021-08-26 17:39:36'),
      (342,1,'en','Backend','Core','lbl','Analytics','analytics','2021-08-26 17:39:36'),
      (343,1,'en','Backend','Core','lbl','APIKey','API key','2021-08-26 17:39:36'),
      (344,1,'en','Backend','Core','lbl','APIKeys','API keys','2021-08-26 17:39:36'),
      (345,1,'en','Backend','Core','lbl','APIURL','API URL','2021-08-26 17:39:36'),
      (346,1,'en','Backend','Core','lbl','Application','application','2021-08-26 17:39:36'),
      (347,1,'en','Backend','Core','lbl','Approve','approve','2021-08-26 17:39:36'),
      (348,1,'en','Backend','Core','lbl','Archive','archive','2021-08-26 17:39:36'),
      (349,1,'en','Backend','Core','lbl','Archived','archived','2021-08-26 17:39:36'),
      (350,1,'en','Backend','Core','lbl','Article','article','2021-08-26 17:39:36'),
      (351,1,'en','Backend','Core','lbl','Articles','articles','2021-08-26 17:39:36'),
      (352,1,'en','Backend','Core','lbl','AskOwnQuestion','ask own question','2021-08-26 17:39:36'),
      (353,1,'en','Backend','Core','lbl','At','at','2021-08-26 17:39:36'),
      (354,1,'en','Backend','Core','lbl','Authentication','authentication','2021-08-26 17:39:36'),
      (355,1,'en','Backend','Core','lbl','Author','author','2021-08-26 17:39:36'),
      (356,1,'en','Backend','Core','lbl','Avatar','avatar','2021-08-26 17:39:36'),
      (357,1,'en','Backend','Core','lbl','Average','average','2021-08-26 17:39:36'),
      (358,1,'en','Backend','Core','lbl','Back','back','2021-08-26 17:39:36'),
      (359,1,'en','Backend','Core','lbl','Backend','backend','2021-08-26 17:39:36'),
      (360,1,'en','Backend','Core','lbl','BG','Bulgarian','2021-08-26 17:39:36'),
      (361,1,'en','Backend','Core','lbl','Block','block','2021-08-26 17:39:36'),
      (362,1,'en','Backend','Core','lbl','Blog','blog','2021-08-26 17:39:36'),
      (363,1,'en','Backend','Core','lbl','Bounces','bounces','2021-08-26 17:39:36'),
      (364,1,'en','Backend','Core','lbl','BounceType','bounce type','2021-08-26 17:39:36'),
      (365,1,'en','Backend','Core','lbl','BrowserNotSupported','browser not supported','2021-08-26 17:39:36'),
      (366,1,'en','Backend','Core','lbl','By','by','2021-08-26 17:39:36'),
      (367,1,'en','Backend','Core','lbl','Campaigns','campaigns','2021-08-26 17:39:36'),
      (368,1,'en','Backend','Core','lbl','Cancel','cancel','2021-08-26 17:39:36'),
      (369,1,'en','Backend','Core','lbl','Categories','categories','2021-08-26 17:39:36'),
      (370,1,'en','Backend','Core','lbl','Category','category','2021-08-26 17:39:36'),
      (371,1,'en','Backend','Core','lbl','ChangeEmail','change e-mail','2021-08-26 17:39:36'),
      (372,1,'en','Backend','Core','lbl','ChangePassword','change password','2021-08-26 17:39:36'),
      (373,1,'en','Backend','Core','lbl','ChooseALanguage','choose a language','2021-08-26 17:39:36'),
      (374,1,'en','Backend','Core','lbl','ChooseAModule','choose a module','2021-08-26 17:39:36'),
      (375,1,'en','Backend','Core','lbl','ChooseAnApplication','choose an application','2021-08-26 17:39:36'),
      (376,1,'en','Backend','Core','lbl','ChooseATemplate','choose a template','2021-08-26 17:39:36'),
      (377,1,'en','Backend','Core','lbl','ChooseAType','choose a type','2021-08-26 17:39:36'),
      (378,1,'en','Backend','Core','lbl','ChooseContent','choose content','2021-08-26 17:39:36'),
      (379,1,'en','Backend','Core','lbl','City','city','2021-08-26 17:39:36'),
      (380,1,'en','Backend','Core','lbl','ClientSettings','client settings','2021-08-26 17:39:36'),
      (381,1,'en','Backend','Core','lbl','CN','Chinese','2021-08-26 17:39:36'),
      (382,1,'en','Backend','Core','lbl','Comment','comment','2021-08-26 17:39:36'),
      (383,1,'en','Backend','Core','lbl','Comments','comments','2021-08-26 17:39:36'),
      (384,1,'en','Backend','Core','lbl','ConfirmPassword','confirm password','2021-08-26 17:39:36'),
      (385,1,'en','Backend','Core','lbl','Contact','contact','2021-08-26 17:39:36'),
      (386,1,'en','Backend','Core','lbl','ContactForm','contact form','2021-08-26 17:39:36'),
      (387,1,'en','Backend','Core','lbl','Content','content','2021-08-26 17:39:36'),
      (388,1,'en','Backend','Core','lbl','ContentBlocks','content blocks','2021-08-26 17:39:36'),
      (389,1,'en','Backend','Core','lbl','Copy','copy','2021-08-26 17:39:36'),
      (390,1,'en','Backend','Core','lbl','Core','core','2021-08-26 17:39:36'),
      (391,1,'en','Backend','Core','lbl','Country','country','2021-08-26 17:39:36'),
      (392,1,'en','Backend','Core','lbl','Created','created','2021-08-26 17:39:36'),
      (393,1,'en','Backend','Core','lbl','CreatedOn','created on','2021-08-26 17:39:36'),
      (394,1,'en','Backend','Core','lbl','CS','Czech','2021-08-26 17:39:36'),
      (395,1,'en','Backend','Core','lbl','CSV','CSV','2021-08-26 17:39:36'),
      (396,1,'en','Backend','Core','lbl','CurrentPassword','current password','2021-08-26 17:39:36'),
      (397,1,'en','Backend','Core','lbl','CustomURL','custom URL','2021-08-26 17:39:36'),
      (398,1,'en','Backend','Core','lbl','Dashboard','dashboard','2021-08-26 17:39:36'),
      (399,1,'en','Backend','Core','lbl','Date','date','2021-08-26 17:39:36'),
      (400,1,'en','Backend','Core','lbl','DateAndTime','date and time','2021-08-26 17:39:36'),
      (401,1,'en','Backend','Core','lbl','DateFormat','date format','2021-08-26 17:39:36'),
      (402,1,'en','Backend','Core','lbl','DE','German','2021-08-26 17:39:36'),
      (403,1,'en','Backend','Core','lbl','Dear','dear','2021-08-26 17:39:36'),
      (404,1,'en','Backend','Core','lbl','DebugMode','debug','2021-08-26 17:39:36'),
      (405,1,'en','Backend','Core','lbl','Default','default','2021-08-26 17:39:36'),
      (406,1,'en','Backend','Core','lbl','Delete','delete','2021-08-26 17:39:36'),
      (407,1,'en','Backend','Core','lbl','Description','description','2021-08-26 17:39:36'),
      (408,1,'en','Backend','Core','lbl','Details','details','2021-08-26 17:39:36'),
      (409,1,'en','Backend','Core','lbl','Developer','developer','2021-08-26 17:39:36'),
      (410,1,'en','Backend','Core','lbl','Domains','domains','2021-08-26 17:39:36'),
      (411,1,'en','Backend','Core','lbl','Done','done','2021-08-26 17:39:36'),
      (412,1,'en','Backend','Core','lbl','Draft','draft','2021-08-26 17:39:36'),
      (413,1,'en','Backend','Core','lbl','Drafts','drafts','2021-08-26 17:39:36'),
      (414,1,'en','Backend','Core','lbl','Edit','edit','2021-08-26 17:39:36'),
      (415,1,'en','Backend','Core','lbl','EditedOn','edited on','2021-08-26 17:39:36'),
      (416,1,'en','Backend','Core','lbl','Editor','editor','2021-08-26 17:39:36'),
      (417,1,'en','Backend','Core','lbl','EditProfile','edit profile','2021-08-26 17:39:36'),
      (418,1,'en','Backend','Core','lbl','EditTemplate','edit template','2021-08-26 17:39:36'),
      (419,1,'en','Backend','Core','lbl','Email','e-mail','2021-08-26 17:39:36'),
      (420,1,'en','Backend','Core','lbl','EN','English','2021-08-26 17:39:36'),
      (421,1,'en','Backend','Core','lbl','EnableModeration','enable moderation','2021-08-26 17:39:36'),
      (422,1,'en','Backend','Core','lbl','EndDate','end date','2021-08-26 17:39:36'),
      (423,1,'en','Backend','Core','lbl','Error','error','2021-08-26 17:39:36'),
      (424,1,'en','Backend','Core','lbl','ES','Spanish','2021-08-26 17:39:36'),
      (425,1,'en','Backend','Core','lbl','Example','example','2021-08-26 17:39:36'),
      (426,1,'en','Backend','Core','lbl','Execute','execute','2021-08-26 17:39:36'),
      (427,1,'en','Backend','Core','lbl','ExitPages','exit pages','2021-08-26 17:39:36'),
      (428,1,'en','Backend','Core','lbl','Export','export','2021-08-26 17:39:36'),
      (429,1,'en','Backend','Core','lbl','Extensions','extensions','2021-08-26 17:39:36'),
      (430,1,'en','Backend','Core','lbl','ExtraMetaTags','extra metatags','2021-08-26 17:39:36'),
      (431,1,'en','Backend','Core','lbl','Faq','FAQ','2021-08-26 17:39:36'),
      (432,1,'en','Backend','Core','lbl','Feedback','feedback','2021-08-26 17:39:36'),
      (433,1,'en','Backend','Core','lbl','File','file','2021-08-26 17:39:36'),
      (434,1,'en','Backend','Core','lbl','Filename','filename','2021-08-26 17:39:36'),
      (435,1,'en','Backend','Core','lbl','FilterCommentsForSpam','filter comments for spam','2021-08-26 17:39:36'),
      (436,1,'en','Backend','Core','lbl','Follow','follow','2021-08-26 17:39:36'),
      (437,1,'en','Backend','Core','lbl','For','for','2021-08-26 17:39:36'),
      (438,1,'en','Backend','Core','lbl','ForgotPassword','forgot password','2021-08-26 17:39:36'),
      (439,1,'en','Backend','Core','lbl','FormBuilder','formbuilder','2021-08-26 17:39:36'),
      (440,1,'en','Backend','Core','lbl','FR','French','2021-08-26 17:39:36'),
      (441,1,'en','Backend','Core','lbl','From','from','2021-08-26 17:39:36'),
      (442,1,'en','Backend','Core','lbl','Frontend','frontend','2021-08-26 17:39:36'),
      (443,1,'en','Backend','Core','lbl','General','general','2021-08-26 17:39:36'),
      (444,1,'en','Backend','Core','lbl','GeneralSettings','general settings','2021-08-26 17:39:36'),
      (445,1,'en','Backend','Core','lbl','Generate','generate','2021-08-26 17:39:36'),
      (446,1,'en','Backend','Core','lbl','GoToPage','go to page','2021-08-26 17:39:36'),
      (447,1,'en','Backend','Core','lbl','Group','group','2021-08-26 17:39:36'),
      (448,1,'en','Backend','Core','lbl','GroupMap','general map: all locations','2021-08-26 17:39:36'),
      (449,1,'en','Backend','Core','lbl','Groups','groups','2021-08-26 17:39:36'),
      (450,1,'en','Backend','Core','lbl','Height','height','2021-08-26 17:39:36'),
      (451,1,'en','Backend','Core','lbl','Hidden','hidden','2021-08-26 17:39:36'),
      (452,1,'en','Backend','Core','lbl','Home','home','2021-08-26 17:39:36'),
      (453,1,'en','Backend','Core','lbl','HU','Hungarian','2021-08-26 17:39:36'),
      (454,1,'en','Backend','Core','lbl','Image','image','2021-08-26 17:39:36'),
      (455,1,'en','Backend','Core','lbl','Images','images','2021-08-26 17:39:36'),
      (456,1,'en','Backend','Core','lbl','Import','import','2021-08-26 17:39:36'),
      (457,1,'en','Backend','Core','lbl','ImportNoun','import','2021-08-26 17:39:36'),
      (458,1,'en','Backend','Core','lbl','In','in','2021-08-26 17:39:36'),
      (459,1,'en','Backend','Core','lbl','Index','index','2021-08-26 17:39:36'),
      (460,1,'en','Backend','Core','lbl','IndividualMap','widget: individual map','2021-08-26 17:39:36'),
      (461,1,'en','Backend','Core','lbl','Interface','interface','2021-08-26 17:39:36'),
      (462,1,'en','Backend','Core','lbl','InterfacePreferences','interface preferences','2021-08-26 17:39:36'),
      (463,1,'en','Backend','Core','lbl','IP','IP','2021-08-26 17:39:36'),
      (464,1,'en','Backend','Core','lbl','IT','Italian','2021-08-26 17:39:36'),
      (465,1,'en','Backend','Core','lbl','ItemsPerPage','items per page','2021-08-26 17:39:36'),
      (466,1,'en','Backend','Core','lbl','JA','Japanese','2021-08-26 17:39:36'),
      (467,1,'en','Backend','Core','lbl','Keyword','keyword','2021-08-26 17:39:36'),
      (468,1,'en','Backend','Core','lbl','Keywords','keywords','2021-08-26 17:39:36'),
      (469,1,'en','Backend','Core','lbl','Label','label','2021-08-26 17:39:36'),
      (470,1,'en','Backend','Core','lbl','LandingPages','landing pages','2021-08-26 17:39:36'),
      (471,1,'en','Backend','Core','lbl','Language','language','2021-08-26 17:39:36'),
      (472,1,'en','Backend','Core','lbl','Languages','languages','2021-08-26 17:39:36'),
      (473,1,'en','Backend','Core','lbl','LastEdited','last edited','2021-08-26 17:39:36'),
      (474,1,'en','Backend','Core','lbl','LastEditedOn','last edited on','2021-08-26 17:39:36'),
      (475,1,'en','Backend','Core','lbl','LastFailedLoginAttempt','last failed login attempt','2021-08-26 17:39:36'),
      (476,1,'en','Backend','Core','lbl','LastLogin','last login','2021-08-26 17:39:36'),
      (477,1,'en','Backend','Core','lbl','LastPasswordChange','last password change','2021-08-26 17:39:36'),
      (478,1,'en','Backend','Core','lbl','LastSaved','last saved','2021-08-26 17:39:36'),
      (479,1,'en','Backend','Core','lbl','LatestComments','latest comments','2021-08-26 17:39:36'),
      (480,1,'en','Backend','Core','lbl','Layout','layout','2021-08-26 17:39:36'),
      (481,1,'en','Backend','Core','lbl','LineEnding','line ending','2021-08-26 17:39:36'),
      (482,1,'en','Backend','Core','lbl','Loading','loading','2021-08-26 17:39:36'),
      (483,1,'en','Backend','Core','lbl','Locale','locale','2021-08-26 17:39:36'),
      (484,1,'en','Backend','Core','lbl','Location','location','2021-08-26 17:39:36'),
      (485,1,'en','Backend','Core','lbl','Login','login','2021-08-26 17:39:36'),
      (486,1,'en','Backend','Core','lbl','LoginBox','login box','2021-08-26 17:39:36'),
      (487,1,'en','Backend','Core','lbl','LoginDetails','login details','2021-08-26 17:39:36'),
      (488,1,'en','Backend','Core','lbl','Logout','logout','2021-08-26 17:39:36'),
      (489,1,'en','Backend','Core','lbl','LongDateFormat','long date format','2021-08-26 17:39:36'),
      (490,1,'en','Backend','Core','lbl','LT','Lithuanian','2021-08-26 17:39:36'),
      (491,1,'en','Backend','Core','lbl','Mailmotor','mailmotor','2021-08-26 17:39:36'),
      (492,1,'en','Backend','Core','lbl','MailmotorClicks','clicks','2021-08-26 17:39:36'),
      (493,1,'en','Backend','Core','lbl','MailmotorGroups','groups','2021-08-26 17:39:36'),
      (494,1,'en','Backend','Core','lbl','MailmotorLatestMailing','last sent mailing','2021-08-26 17:39:36'),
      (495,1,'en','Backend','Core','lbl','MailmotorOpened','opened','2021-08-26 17:39:36'),
      (496,1,'en','Backend','Core','lbl','MailmotorSendDate','send date','2021-08-26 17:39:36'),
      (497,1,'en','Backend','Core','lbl','MailmotorSent','sent','2021-08-26 17:39:36'),
      (498,1,'en','Backend','Core','lbl','MailmotorStatistics','statistics','2021-08-26 17:39:36'),
      (499,1,'en','Backend','Core','lbl','MailmotorSubscriptions','subscriptions','2021-08-26 17:39:36'),
      (500,1,'en','Backend','Core','lbl','MailmotorUnsubscriptions','unsubscriptions','2021-08-26 17:39:36'),
      (501,1,'en','Backend','Core','lbl','MainContent','main content','2021-08-26 17:39:36'),
      (502,1,'en','Backend','Core','lbl','MarkAsSpam','mark as spam','2021-08-26 17:39:36'),
      (503,1,'en','Backend','Core','lbl','Marketing','marketing','2021-08-26 17:39:36'),
      (504,1,'en','Backend','Core','lbl','Meta','meta','2021-08-26 17:39:36'),
      (505,1,'en','Backend','Core','lbl','MetaData','metadata','2021-08-26 17:39:36'),
      (506,1,'en','Backend','Core','lbl','MetaInformation','meta information','2021-08-26 17:39:36'),
      (507,1,'en','Backend','Core','lbl','MetaNavigation','meta navigation','2021-08-26 17:39:36'),
      (508,1,'en','Backend','Core','lbl','Moderate','moderate','2021-08-26 17:39:36'),
      (509,1,'en','Backend','Core','lbl','Moderation','moderation','2021-08-26 17:39:36'),
      (510,1,'en','Backend','Core','lbl','Module','module','2021-08-26 17:39:36'),
      (511,1,'en','Backend','Core','lbl','Modules','modules','2021-08-26 17:39:36'),
      (512,1,'en','Backend','Core','lbl','ModuleSettings','module settings','2021-08-26 17:39:36'),
      (513,1,'en','Backend','Core','lbl','More','more','2021-08-26 17:39:36'),
      (514,1,'en','Backend','Core','lbl','MostReadQuestions','most read questions','2021-08-26 17:39:36'),
      (515,1,'en','Backend','Core','lbl','Move','move','2021-08-26 17:39:36'),
      (516,1,'en','Backend','Core','lbl','MoveToModeration','move to moderation','2021-08-26 17:39:36'),
      (517,1,'en','Backend','Core','lbl','MoveToPublished','move to published','2021-08-26 17:39:36'),
      (518,1,'en','Backend','Core','lbl','MoveToSpam','move to spam','2021-08-26 17:39:36'),
      (519,1,'en','Backend','Core','lbl','Name','name','2021-08-26 17:39:36'),
      (520,1,'en','Backend','Core','lbl','Navigation','navigation','2021-08-26 17:39:36'),
      (521,1,'en','Backend','Core','lbl','OpenNavigation','open navigation','2021-08-26 17:39:36'),
      (522,1,'en','Backend','Core','lbl','CloseNavigation','close navigation','2021-08-26 17:39:36'),
      (523,1,'en','Backend','Core','lbl','OpenTreeNavigation','open tree navigation','2021-08-26 17:39:36'),
      (524,1,'en','Backend','Core','lbl','CloseTreeNavigation','close tree navigation','2021-08-26 17:39:36'),
      (525,1,'en','Backend','Core','lbl','NavigationTitle','navigation title','2021-08-26 17:39:36'),
      (526,1,'en','Backend','Core','lbl','Never','never','2021-08-26 17:39:36'),
      (527,1,'en','Backend','Core','lbl','NewPassword','new password','2021-08-26 17:39:36'),
      (528,1,'en','Backend','Core','lbl','News','news','2021-08-26 17:39:36'),
      (529,1,'en','Backend','Core','lbl','Newsletters','mailings','2021-08-26 17:39:36'),
      (530,1,'en','Backend','Core','lbl','Next','next','2021-08-26 17:39:36'),
      (531,1,'en','Backend','Core','lbl','NextPage','next page','2021-08-26 17:39:36'),
      (532,1,'en','Backend','Core','lbl','Nickname','publication name','2021-08-26 17:39:36'),
      (533,1,'en','Backend','Core','lbl','NL','Dutch','2021-08-26 17:39:36'),
      (534,1,'en','Backend','Core','lbl','PT','Portuguese','2021-08-26 17:39:36'),
      (535,1,'en','Backend','Core','lbl','UK','Ukrainian','2021-08-26 17:39:36'),
      (536,1,'en','Backend','Core','lbl','SV','Swedish','2021-08-26 17:39:36'),
      (537,1,'en','Backend','Core','lbl','EL','Greek','2021-08-26 17:39:36'),
      (538,1,'en','Backend','Core','lbl','ZH','Chinese','2021-08-26 17:39:36'),
      (539,1,'en','Backend','Core','lbl','None','none','2021-08-26 17:39:36'),
      (540,1,'en','Backend','Core','lbl','NoPreviousLogin','no previous login','2021-08-26 17:39:36'),
      (541,1,'en','Backend','Core','lbl','NoTheme','no theme','2021-08-26 17:39:36'),
      (542,1,'en','Backend','Core','lbl','Notifications','notifications','2021-08-26 17:39:36'),
      (543,1,'en','Backend','Core','lbl','Number','number','2021-08-26 17:39:36'),
      (544,1,'en','Backend','Core','lbl','NumberFormat','number format','2021-08-26 17:39:36'),
      (545,1,'en','Backend','Core','lbl','NumberOfPositions','number of positions','2021-08-26 17:39:36'),
      (546,1,'en','Backend','Core','lbl','Numbers','numbers','2021-08-26 17:39:36'),
      (547,1,'en','Backend','Core','lbl','OK','OK','2021-08-26 17:39:36'),
      (548,1,'en','Backend','Core','lbl','Or','or','2021-08-26 17:39:36'),
      (549,1,'en','Backend','Core','lbl','Overview','overview','2021-08-26 17:39:36'),
      (550,1,'en','Backend','Core','lbl','Page','page','2021-08-26 17:39:36'),
      (551,1,'en','Backend','Core','lbl','Pages','pages','2021-08-26 17:39:36'),
      (552,1,'en','Backend','Core','lbl','PageTitle','pagetitle','2021-08-26 17:39:36'),
      (553,1,'en','Backend','Core','lbl','Pageviews','pageviews','2021-08-26 17:39:36'),
      (554,1,'en','Backend','Core','lbl','Pagination','pagination','2021-08-26 17:39:36'),
      (555,1,'en','Backend','Core','lbl','Password','password','2021-08-26 17:39:36'),
      (556,1,'en','Backend','Core','lbl','PasswordStrength','password strength','2021-08-26 17:39:36'),
      (557,1,'en','Backend','Core','lbl','PerDay','per day','2021-08-26 17:39:36'),
      (558,1,'en','Backend','Core','lbl','Permissions','permissions','2021-08-26 17:39:36'),
      (559,1,'en','Backend','Core','lbl','Person','person','2021-08-26 17:39:36'),
      (560,1,'en','Backend','Core','lbl','PersonalInformation','personal information','2021-08-26 17:39:36'),
      (561,1,'en','Backend','Core','lbl','Persons','people','2021-08-26 17:39:36'),
      (562,1,'en','Backend','Core','lbl','PerVisit','per visit','2021-08-26 17:39:36'),
      (563,1,'en','Backend','Core','lbl','PingBlogServices','ping blogservices','2021-08-26 17:39:36'),
      (564,1,'en','Backend','Core','lbl','PL','Polish','2021-08-26 17:39:36'),
      (565,1,'en','Backend','Core','lbl','Port','port','2021-08-26 17:39:36'),
      (566,1,'en','Backend','Core','lbl','Position','position','2021-08-26 17:39:36'),
      (567,1,'en','Backend','Core','lbl','Positions','positions','2021-08-26 17:39:36'),
      (568,1,'en','Backend','Core','lbl','Preview','preview','2021-08-26 17:39:36'),
      (569,1,'en','Backend','Core','lbl','Previous','previous','2021-08-26 17:39:36'),
      (570,1,'en','Backend','Core','lbl','PreviousPage','previous page','2021-08-26 17:39:36'),
      (571,1,'en','Backend','Core','lbl','PreviousVersions','previous versions','2021-08-26 17:39:36'),
      (572,1,'en','Backend','Core','lbl','Price','price','2021-08-26 17:39:36'),
      (573,1,'en','Backend','Core','lbl','Profile','profile','2021-08-26 17:39:36'),
      (574,1,'en','Backend','Core','lbl','Profiles','profiles','2021-08-26 17:39:36'),
      (575,1,'en','Backend','Core','lbl','Publish','publish','2021-08-26 17:39:36'),
      (576,1,'en','Backend','Core','lbl','Published','published','2021-08-26 17:39:36'),
      (577,1,'en','Backend','Core','lbl','PublishedArticles','published articles','2021-08-26 17:39:36'),
      (578,1,'en','Backend','Core','lbl','PublishedOn','published on','2021-08-26 17:39:36'),
      (579,1,'en','Backend','Core','lbl','PublishOn','publish on','2021-08-26 17:39:36'),
      (580,1,'en','Backend','Core','lbl','QuantityNo','no','2021-08-26 17:39:36'),
      (581,1,'en','Backend','Core','lbl','Questions','questions','2021-08-26 17:39:36'),
      (582,1,'en','Backend','Core','lbl','RecentArticlesFull','recent articles (full)','2021-08-26 17:39:36'),
      (583,1,'en','Backend','Core','lbl','RecentArticlesList','recent articles (list)','2021-08-26 17:39:36'),
      (584,1,'en','Backend','Core','lbl','RecentComments','recent comments','2021-08-26 17:39:36'),
      (585,1,'en','Backend','Core','lbl','RecentlyEdited','recently edited','2021-08-26 17:39:36'),
      (586,1,'en','Backend','Core','lbl','RecentVisits','recent visits','2021-08-26 17:39:36'),
      (587,1,'en','Backend','Core','lbl','Visits','visits','2021-08-26 17:39:36'),
      (588,1,'en','Backend','Core','lbl','ReferenceCode','reference code','2021-08-26 17:39:36'),
      (589,1,'en','Backend','Core','lbl','Referrer','referrer','2021-08-26 17:39:36'),
      (590,1,'en','Backend','Core','lbl','Register','register','2021-08-26 17:39:36'),
      (591,1,'en','Backend','Core','lbl','Related','related','2021-08-26 17:39:36'),
      (592,1,'en','Backend','Core','lbl','RepeatPassword','repeat password','2021-08-26 17:39:36'),
      (593,1,'en','Backend','Core','lbl','ReplyTo','reply-to','2021-08-26 17:39:36'),
      (594,1,'en','Backend','Core','lbl','RequiredField','required field','2021-08-26 17:39:36'),
      (595,1,'en','Backend','Core','lbl','ResendActivation','resend activation e-mail','2021-08-26 17:39:36'),
      (596,1,'en','Backend','Core','lbl','ResetAndSignIn','reset and sign in','2021-08-26 17:39:36'),
      (597,1,'en','Backend','Core','lbl','ResetPassword','reset password','2021-08-26 17:39:36'),
      (598,1,'en','Backend','Core','lbl','ResetYourPassword','reset your password','2021-08-26 17:39:36'),
      (599,1,'en','Backend','Core','lbl','RO','Romanian','2021-08-26 17:39:36'),
      (600,1,'en','Backend','Core','lbl','RSSFeed','RSS feed','2021-08-26 17:39:36'),
      (601,1,'en','Backend','Core','lbl','RU','Russian','2021-08-26 17:39:36'),
      (602,1,'en','Backend','Core','lbl','Save','save','2021-08-26 17:39:36'),
      (603,1,'en','Backend','Core','lbl','SaveDraft','save draft','2021-08-26 17:39:36'),
      (604,1,'en','Backend','Core','lbl','Scripts','scripts','2021-08-26 17:39:36'),
      (605,1,'en','Backend','Core','lbl','Search','search','2021-08-26 17:39:36'),
      (606,1,'en','Backend','Core','lbl','SearchAgain','search again','2021-08-26 17:39:36'),
      (607,1,'en','Backend','Core','lbl','SearchForm','search form','2021-08-26 17:39:36'),
      (608,1,'en','Backend','Core','lbl','Send','send','2021-08-26 17:39:36'),
      (609,1,'en','Backend','Core','lbl','SendingEmails','sending e-mails','2021-08-26 17:39:36'),
      (610,1,'en','Backend','Core','lbl','SentMailings','sent mailings','2021-08-26 17:39:36'),
      (611,1,'en','Backend','Core','lbl','SentOn','sent on','2021-08-26 17:39:36'),
      (612,1,'en','Backend','Core','lbl','SEO','SEO','2021-08-26 17:39:36'),
      (613,1,'en','Backend','Core','lbl','Server','server','2021-08-26 17:39:36'),
      (614,1,'en','Backend','Core','lbl','Settings','settings','2021-08-26 17:39:36'),
      (615,1,'en','Backend','Core','lbl','ShortDateFormat','short date format','2021-08-26 17:39:36'),
      (616,1,'en','Backend','Core','lbl','SignIn','log in','2021-08-26 17:39:36'),
      (617,1,'en','Backend','Core','lbl','SignOut','sign out','2021-08-26 17:39:36'),
      (618,1,'en','Backend','Core','lbl','Sitemap','sitemap','2021-08-26 17:39:36'),
      (619,1,'en','Backend','Core','lbl','SMTP','SMTP','2021-08-26 17:39:36'),
      (620,1,'en','Backend','Core','lbl','SortAscending','sort ascending','2021-08-26 17:39:36'),
      (621,1,'en','Backend','Core','lbl','SortDescending','sort descending','2021-08-26 17:39:36'),
      (622,1,'en','Backend','Core','lbl','SortedAscending','sorted ascending','2021-08-26 17:39:36'),
      (623,1,'en','Backend','Core','lbl','SortedDescending','sorted descending','2021-08-26 17:39:36'),
      (624,1,'en','Backend','Core','lbl','Source','source','2021-08-26 17:39:36'),
      (625,1,'en','Backend','Core','lbl','Spam','spam','2021-08-26 17:39:36'),
      (626,1,'en','Backend','Core','lbl','SpamFilter','spamfilter','2021-08-26 17:39:36'),
      (627,1,'en','Backend','Core','lbl','SplitCharacter','split character','2021-08-26 17:39:36'),
      (628,1,'en','Backend','Core','lbl','StartDate','start date','2021-08-26 17:39:36'),
      (629,1,'en','Backend','Core','lbl','Statistics','statistics','2021-08-26 17:39:36'),
      (630,1,'en','Backend','Core','lbl','Status','status','2021-08-26 17:39:36'),
      (631,1,'en','Backend','Core','lbl','Street','street','2021-08-26 17:39:36'),
      (632,1,'en','Backend','Core','lbl','Strong','strong','2021-08-26 17:39:36'),
      (633,1,'en','Backend','Core','lbl','Subpages','subpages','2021-08-26 17:39:36'),
      (634,1,'en','Backend','Core','lbl','SubscribeForm','subscribe form','2021-08-26 17:39:36'),
      (635,1,'en','Backend','Core','lbl','Subscriptions','subscriptions','2021-08-26 17:39:36'),
      (636,1,'en','Backend','Core','lbl','Summary','summary','2021-08-26 17:39:36'),
      (637,1,'en','Backend','Core','lbl','Surname','surname','2021-08-26 17:39:36'),
      (638,1,'en','Backend','Core','lbl','Synonym','synonym','2021-08-26 17:39:36'),
      (639,1,'en','Backend','Core','lbl','Synonyms','synonyms','2021-08-26 17:39:36'),
      (640,1,'en','Backend','Core','lbl','TagCloud','tagcloud','2021-08-26 17:39:36'),
      (641,1,'en','Backend','Core','lbl','Tags','tags','2021-08-26 17:39:36'),
      (642,1,'en','Backend','Core','lbl','Template','template','2021-08-26 17:39:36'),
      (643,1,'en','Backend','Core','lbl','Templates','templates','2021-08-26 17:39:36'),
      (644,1,'en','Backend','Core','lbl','Term','term','2021-08-26 17:39:36'),
      (645,1,'en','Backend','Core','lbl','Text','text','2021-08-26 17:39:36'),
      (646,1,'en','Backend','Core','lbl','Themes','themes','2021-08-26 17:39:36'),
      (647,1,'en','Backend','Core','lbl','ThemesSelection','theme selection','2021-08-26 17:39:36'),
      (648,1,'en','Backend','Core','lbl','Till','till','2021-08-26 17:39:36'),
      (649,1,'en','Backend','Core','lbl','TimeFormat','time format','2021-08-26 17:39:36'),
      (650,1,'en','Backend','Core','lbl','Timezone','timezone','2021-08-26 17:39:36'),
      (651,1,'en','Backend','Core','lbl','Title','title','2021-08-26 17:39:36'),
      (652,1,'en','Backend','Core','lbl','Titles','titles','2021-08-26 17:39:36'),
      (653,1,'en','Backend','Core','lbl','To','to','2021-08-26 17:39:36'),
      (654,1,'en','Backend','Core','lbl','Today','today','2021-08-26 17:39:36'),
      (655,1,'en','Backend','Core','lbl','ToStep','to step','2021-08-26 17:39:36'),
      (656,1,'en','Backend','Core','lbl','TR','Turkish','2021-08-26 17:39:36'),
      (657,1,'en','Backend','Core','lbl','TrafficSources','traffic sources','2021-08-26 17:39:36'),
      (658,1,'en','Backend','Core','lbl','Translation','translation','2021-08-26 17:39:36'),
      (659,1,'en','Backend','Core','lbl','Translations','translations','2021-08-26 17:39:36'),
      (660,1,'en','Backend','Core','lbl','Type','type','2021-08-26 17:39:36'),
      (661,1,'en','Backend','Core','lbl','UnsubscribeForm','unsubscribe form','2021-08-26 17:39:36'),
      (662,1,'en','Backend','Core','lbl','Unsubscriptions','unsubscriptions','2021-08-26 17:39:36'),
      (663,1,'en','Backend','Core','lbl','UpdateFilter','update filter','2021-08-26 17:39:36'),
      (664,1,'en','Backend','Core','lbl','URL','URL','2021-08-26 17:39:36'),
      (665,1,'en','Backend','Core','lbl','CanonicalURL','canonical URL','2021-08-26 17:39:36'),
      (666,1,'en','Backend','Core','lbl','UsedIn','used in','2021-08-26 17:39:36'),
      (667,1,'en','Backend','Core','lbl','Userguide','userguide','2021-08-26 17:39:36'),
      (668,1,'en','Backend','Core','lbl','Username','username','2021-08-26 17:39:36'),
      (669,1,'en','Backend','Core','lbl','Users','users','2021-08-26 17:39:36'),
      (670,1,'en','Backend','Core','lbl','UseThisDraft','use this draft','2021-08-26 17:39:36'),
      (671,1,'en','Backend','Core','lbl','UseThisVersion','use this version','2021-08-26 17:39:36'),
      (672,1,'en','Backend','Core','lbl','Value','value','2021-08-26 17:39:36'),
      (673,1,'en','Backend','Core','lbl','Versions','versions','2021-08-26 17:39:36'),
      (674,1,'en','Backend','Core','lbl','View','view','2021-08-26 17:39:36'),
      (675,1,'en','Backend','Core','lbl','ViewReport','view report','2021-08-26 17:39:36'),
      (676,1,'en','Backend','Core','lbl','VisibleOnSite','visible on site','2021-08-26 17:39:36'),
      (677,1,'en','Backend','Core','lbl','Visitors','visitors','2021-08-26 17:39:36'),
      (678,1,'en','Backend','Core','lbl','VisitWebsite','visit website','2021-08-26 17:39:36'),
      (679,1,'en','Backend','Core','lbl','WaitingForModeration','waiting for moderation','2021-08-26 17:39:36'),
      (680,1,'en','Backend','Core','lbl','Weak','weak','2021-08-26 17:39:36'),
      (681,1,'en','Backend','Core','lbl','WebmasterEmail','e-mail webmaster','2021-08-26 17:39:36'),
      (682,1,'en','Backend','Core','lbl','Website','website','2021-08-26 17:39:36'),
      (683,1,'en','Backend','Core','lbl','WebsiteTitle','website title','2021-08-26 17:39:36'),
      (684,1,'en','Backend','Core','lbl','Weight','weight','2021-08-26 17:39:36'),
      (685,1,'en','Backend','Core','lbl','WhichModule','which module','2021-08-26 17:39:36'),
      (686,1,'en','Backend','Core','lbl','WhichWidget','which widget','2021-08-26 17:39:36'),
      (687,1,'en','Backend','Core','lbl','Widget','widget','2021-08-26 17:39:36'),
      (688,1,'en','Backend','Core','lbl','Widgets','widgets','2021-08-26 17:39:36'),
      (689,1,'en','Backend','Core','lbl','Width','width','2021-08-26 17:39:36'),
      (690,1,'en','Backend','Core','lbl','WithSelected','with selected','2021-08-26 17:39:36'),
      (691,1,'en','Backend','Core','lbl','Zip','zip code','2021-08-26 17:39:36'),
      (692,1,'en','Backend','Core','msg','ACT','action','2021-08-26 17:39:36'),
      (693,1,'en','Backend','Core','msg','Added','The item was added.','2021-08-26 17:39:36'),
      (694,1,'en','Backend','Core','msg','AddedCategory','The category \"%1$s\" was added.','2021-08-26 17:39:36'),
      (695,1,'en','Backend','Core','msg','AllAddresses','All addresses sorted by subscription date.','2021-08-26 17:39:36'),
      (696,1,'en','Backend','Core','msg','BG','Bulgarian','2021-08-26 17:39:36'),
      (697,1,'en','Backend','Core','msg','ChangedOrderSuccessfully','Changed order successfully.','2021-08-26 17:39:36'),
      (698,1,'en','Backend','Core','msg','ClickToEdit','Click to edit','2021-08-26 17:39:36'),
      (699,1,'en','Backend','Core','msg','CN','Chinese','2021-08-26 17:39:36'),
      (700,1,'en','Backend','Core','msg','CommentDeleted','The comment was deleted.','2021-08-26 17:39:36'),
      (701,1,'en','Backend','Core','msg','CommentMovedModeration','The comment was moved to moderation.','2021-08-26 17:39:36'),
      (702,1,'en','Backend','Core','msg','CommentMovedPublished','The comment was published.','2021-08-26 17:39:36'),
      (703,1,'en','Backend','Core','msg','CommentMovedSpam','The comment was marked as spam.','2021-08-26 17:39:36'),
      (704,1,'en','Backend','Core','msg','CommentsDeleted','The comments were deleted.','2021-08-26 17:39:36'),
      (705,1,'en','Backend','Core','msg','CommentsMovedModeration','The comments were moved to moderation.','2021-08-26 17:39:36'),
      (706,1,'en','Backend','Core','msg','CommentsMovedPublished','The comments were published.','2021-08-26 17:39:36'),
      (707,1,'en','Backend','Core','msg','CommentsMovedSpam','The comments were marked as spam.','2021-08-26 17:39:36'),
      (708,1,'en','Backend','Core','msg','CommentsToModerate','%1$s comment(s) to moderate.','2021-08-26 17:39:36'),
      (709,1,'en','Backend','Core','msg','ConfigurationError','Some settings aren\'t configured yet:','2021-08-26 17:39:36'),
	(710,1,'en','Backend','Core','msg','ConfirmDelete','Are you sure you want to delete the item \"%1$s\"?','2021-08-26 17:39:36'),
	(711,1,'en','Backend','Core','msg','ConfirmDeleteCategory','Are you sure you want to delete the category \"%1$s\"?','2021-08-26 17:39:36'),
       (712,1,'en','Backend','Core','msg','ConfirmMassDelete','Are your sure you want to delete this/these item(s)?','2021-08-26 17:39:36'),
       (713,1,'en','Backend','Core','msg','ConfirmMassSpam','Are your sure you want to mark this/these item(s) as spam?','2021-08-26 17:39:36'),
       (714,1,'en','Backend','Core','msg','DE','German','2021-08-26 17:39:36'),
       (715,1,'en','Backend','Core','msg','Deleted','The item was deleted.','2021-08-26 17:39:36'),
       (716,1,'en','Backend','Core','msg','DeletedCategory','The category \"%1$s\" was deleted.','2021-08-26 17:39:36'),
       (717,1,'en','Backend','Core','msg','EditCategory','edit category \"%1$s\"','2021-08-26 17:39:36'),
       (718,1,'en','Backend','Core','msg','EditComment','edit comment','2021-08-26 17:39:36'),
       (719,1,'en','Backend','Core','msg','Edited','The item was saved.','2021-08-26 17:39:36'),
       (720,1,'en','Backend','Core','msg','EditedCategory','The category \"%1$s\" was saved.','2021-08-26 17:39:36'),
       (721,1,'en','Backend','Core','msg','EditorImagesWithoutAlt','There are images without an alt-attribute.','2021-08-26 17:39:36'),
       (722,1,'en','Backend','Core','msg','EditorInvalidLinks','There are invalid links.','2021-08-26 17:39:36'),
       (723,1,'en','Backend','Core','msg','EditorSelectInternalPage','Select internal page','2021-08-26 17:39:36'),
       (724,1,'en','Backend','Core','msg','EN','English','2021-08-26 17:39:36'),
       (725,1,'en','Backend','Core','msg','ERR','error','2021-08-26 17:39:36'),
       (726,1,'en','Backend','Core','msg','ES','Spanish','2021-08-26 17:39:36'),
       (727,1,'en','Backend','Core','msg','ForgotPassword','Forgot password?','2021-08-26 17:39:36'),
       (728,1,'en','Backend','Core','msg','FR','French','2021-08-26 17:39:36'),
       (729,1,'en','Backend','Core','msg','HelpAvatar','A square picture produces the best results.','2021-08-26 17:39:36'),
       (730,1,'en','Backend','Core','msg','HelpBlogger','Select the file that you exported from <a href=\"http://blogger.com\">Blogger</a>.','2021-08-26 17:39:36'),
       (731,1,'en','Backend','Core','msg','HelpDrafts','Here you can see your draft. These are temporary versions.','2021-08-26 17:39:36'),
       (732,1,'en','Backend','Core','msg','HelpEmailFrom','E-mails sent from the CMS use these settings.','2021-08-26 17:39:36'),
       (733,1,'en','Backend','Core','msg','HelpEmailReplyTo','Answers on e-mails sent from the CMS will be sent to this e-mailaddress.','2021-08-26 17:39:36'),
       (734,1,'en','Backend','Core','msg','HelpEmailTo','Notifications from the CMS are sent here.','2021-08-26 17:39:36'),
       (735,1,'en','Backend','Core','msg','HelpFileFieldWithMaxFileSize','Only files with the extension %1$s are allowed, maximum file size: %2$s.','2021-08-26 17:39:36'),
       (736,1,'en','Backend','Core','msg','HelpForgotPassword','Below enter your e-mail. You will receive an e-mail containing instructions on how to get a new password.','2021-08-26 17:39:36'),
       (737,1,'en','Backend','Core','msg','HelpImageFieldWithMaxFileSize','Only jp(e)g, gif or png-files are allowed, maximum filesize: %1$s.','2021-08-26 17:39:36'),
       (738,1,'en','Backend','Core','msg','HelpMaxFileSize','maximum filesize: %1$s','2021-08-26 17:39:36'),
       (739,1,'en','Backend','Core','msg','HelpMetaCustom','These custom metatags will be placed in the <code><head></code> section of the page.','2021-08-26 17:39:36'),
       (740,1,'en','Backend','Core','msg','HelpMetaDescription','Briefly summarize the content. This summary is shown in the results of search engines.','2021-08-26 17:39:36'),
       (741,1,'en','Backend','Core','msg','HelpMetaKeywords','Choose a number of wellthought terms that describe the content. From an SEO point of view, these do not longer present an added value though.','2021-08-26 17:39:36'),
       (742,1,'en','Backend','Core','msg','HelpMetaURL','Replace the automaticly generated URL by a custom one. Only alphanumeric lowercase characters and - are allowed','2021-08-26 17:39:36'),
       (743,1,'en','Backend','Core','msg','HelpNickname','The name you want to be published as (e.g. as the author of an article).','2021-08-26 17:39:36'),
       (744,1,'en','Backend','Core','msg','HelpPageTitle','The title in the browser window (<code>&lt;title&gt;</code>).','2021-08-26 17:39:36'),
       (745,1,'en','Backend','Core','msg','HelpResetPassword','Provide your new password.','2021-08-26 17:39:36'),
       (746,1,'en','Backend','Core','msg','HelpRevisions','The last saved versions are kept here. The current version will only be overwritten when you save your changes.','2021-08-26 17:39:36'),
       (747,1,'en','Backend','Core','msg','HelpRSSDescription','Briefly describe what kind of content the RSS feed will contain.','2021-08-26 17:39:36'),
       (748,1,'en','Backend','Core','msg','HelpRSSTitle','Provide a clear title for the RSS feed.','2021-08-26 17:39:36'),
       (749,1,'en','Backend','Core','msg','HelpSMTPServer','Mailserver that should be used for sending e-mails.','2021-08-26 17:39:36'),
       (750,1,'en','Backend','Core','msg','HU','Hungarian','2021-08-26 17:39:36'),
       (751,1,'en','Backend','Core','msg','Imported','The data was imported.','2021-08-26 17:39:36'),
       (752,1,'en','Backend','Core','msg','IT','Italian','2021-08-26 17:39:36'),
       (753,1,'en','Backend','Core','msg','LBL','label','2021-08-26 17:39:36'),
       (754,1,'en','Backend','Core','msg','LoginFormForgotPasswordSuccess','<strong>Mail sent.</strong> Please check your inbox!','2021-08-26 17:39:36'),
       (755,1,'en','Backend','Core','msg','LT','Lithuanian','2021-08-26 17:39:36'),
       (756,1,'en','Backend','Core','msg','MSG','message','2021-08-26 17:39:36'),
       (757,1,'en','Backend','Core','msg','NL','Dutch','2021-08-26 17:39:36'),
       (758,1,'en','Backend','Core','msg','PT','Portuguese','2021-08-26 17:39:36'),
       (759,1,'en','Backend','Core','msg','UK','Ukrainian','2021-08-26 17:39:36'),
       (760,1,'en','Backend','Core','msg','SV','Swedish','2021-08-26 17:39:36'),
       (761,1,'en','Backend','Core','msg','EL','Greek','2021-08-26 17:39:36'),
       (762,1,'en','Backend','Core','msg','ZH','Chinese','2021-08-26 17:39:36'),
       (763,1,'en','Backend','Core','msg','NoAkismetKey','If you want to enable the spam-protection you should <a href=\"%1$s\">configure</a> an Akismet-key.','2021-08-26 17:39:36'),
       (764,1,'en','Backend','Core','msg','NoComments','There are no comments in this category yet.','2021-08-26 17:39:36'),
       (765,1,'en','Backend','Core','msg','NoEmailaddresses','No email addresses.','2021-08-26 17:39:36'),
       (766,1,'en','Backend','Core','msg','NoFeedback','There is no feedback yet.','2021-08-26 17:39:36'),
       (767,1,'en','Backend','Core','msg','NoItems','There are no items yet.','2021-08-26 17:39:36'),
       (768,1,'en','Backend','Core','msg','NoKeywords','There are no keywords yet.','2021-08-26 17:39:36'),
       (769,1,'en','Backend','Core','msg','NoPublishedComments','There are no published comments.','2021-08-26 17:39:36'),
       (770,1,'en','Backend','Core','msg','NoReferrers','There are no referrers yet.','2021-08-26 17:39:36'),
       (771,1,'en','Backend','Core','msg','NoRevisions','There are no previous versions yet.','2021-08-26 17:39:36'),
       (772,1,'en','Backend','Core','msg','NoSentMailings','No mailings have been sent yet.','2021-08-26 17:39:36'),
       (773,1,'en','Backend','Core','msg','NoSubscriptions','No one subscribed to the mailinglist yet.','2021-08-26 17:39:36'),
       (774,1,'en','Backend','Core','msg','NoTags','You didn\'t add tags yet.','2021-08-26 17:39:36'),
	(775,1,'en','Backend','Core','msg','NoUnsubscriptions','No one unsubscribed from from the mailinglist yet.','2021-08-26 17:39:36'),
	(776,1,'en','Backend','Core','msg','NoUsage','Not yet used.','2021-08-26 17:39:36'),
	(777,1,'en','Backend','Core','msg','NowEditing','now editing','2021-08-26 17:39:36'),
	(778,1,'en','Backend','Core','msg','PasswordResetSuccess','Your password has been changed.','2021-08-26 17:39:36'),
	(779,1,'en','Backend','Core','msg','PL','Polish','2021-08-26 17:39:36'),
	(780,1,'en','Backend','Core','msg','Redirecting','You are being redirected.','2021-08-26 17:39:36'),
	(781,1,'en','Backend','Core','msg','ResetYourPasswordMailContent','Reset your password by clicking the link below. If you didn\'t ask for this, you can ignore this message.','2021-08-26 17:39:36'),
       (782,1,'en','Backend','Core','msg','ResetYourPasswordMailSubject','Change your password','2021-08-26 17:39:36'),
       (783,1,'en','Backend','Core','msg','RU','Russian','2021-08-26 17:39:36'),
       (784,1,'en','Backend','Core','msg','Saved','The changes were saved.','2021-08-26 17:39:36'),
       (785,1,'en','Backend','Core','msg','SavedAsDraft','\"%1$s\" saved as draft.','2021-08-26 17:39:36'),
       (786,1,'en','Backend','Core','msg','SequenceSaved','Sequence saved','2021-08-26 17:39:36'),
       (787,1,'en','Backend','Core','msg','TR','Turkish','2021-08-26 17:39:36'),
       (788,1,'en','Backend','Core','msg','UsingADraft','You\'re using a draft.','2021-08-26 17:39:36'),
	(789,1,'en','Backend','Core','msg','UsingARevision','You\'re using an older version. Save to overwrite the current version.','2021-08-26 17:39:36'),
       (790,1,'en','Backend','Core','msg','ValuesAreChanged','Changes will be lost.','2021-08-26 17:39:36'),
       (791,1,'en','Backend','Core','msg','SessionTimeoutWarning','Your session will expire, we recommend you to save your changes (temporarily)','2021-08-26 17:39:36'),
       (792,1,'en','Backend','Core','err','ActionNotAllowed','You have insufficient rights for this action.','2021-08-26 17:39:36'),
       (793,1,'en','Backend','Core','err','NotFound','This page was lost at sea.','2021-08-26 17:39:36'),
       (794,1,'en','Backend','Core','err','AddingCategoryFailed','Something went wrong.','2021-08-26 17:39:36'),
       (795,1,'en','Backend','Core','err','AddTagBeforeSubmitting','Add the tag before submitting.','2021-08-26 17:39:36'),
       (796,1,'en','Backend','Core','err','AddTextBeforeSubmitting','Add the text before submitting.','2021-08-26 17:39:36'),
       (797,1,'en','Backend','Core','err','AkismetKey','Akismet API-key is not yet configured. <a href=\"%1$s\">Configure</a>','2021-08-26 17:39:36'),
       (798,1,'en','Backend','Core','err','AlphaNumericCharactersOnly','Only alphanumeric characters are allowed.','2021-08-26 17:39:36'),
       (799,1,'en','Backend','Core','err','AlterSequenceFailed','Alter sequence failed.','2021-08-26 17:39:36'),
       (800,1,'en','Backend','Core','err','AuthorIsRequired','Please provide an author.','2021-08-26 17:39:36'),
       (801,1,'en','Backend','Core','err','BrowserNotSupported','<p>You\'re using an older browser that is not supported by Fork CMS. Use one of the following alternatives:</p><ul><li><a href=\"http://www.firefox.com/\">Firefox</a>: a very good browser with a lot of free extensions.</li><li><a href=\"http://www.apple.com/safari\">Safari</a>: one of the fastest and most advanced browsers. Good for Mac users.</li><li><a href=\"http://www.google.com/chrome\">Chrome</a>: Google\'s browser - also very fast.</li><li><a href=\"http://www.microsoft.com/windows/products/winfamily/ie/default.mspx\">Internet Explorer*</a>: update to the latest version of Internet Explorer.</li></ul>','2021-08-26 17:39:36'),
	(802,1,'en','Backend','Core','err','CookiesNotEnabled','You need to enable cookies in order to use Fork CMS. Activate cookies and refresh this page.','2021-08-26 17:39:36'),
        (803,1,'en','Backend','Core','err','DateIsInvalid','Invalid date.','2021-08-26 17:39:36'),
        (804,1,'en','Backend','Core','err','DateRangeIsInvalid','Invalid date range.','2021-08-26 17:39:36'),
        (805,1,'en','Backend','Core','err','DebugModeIsActive','Debug-mode is active.','2021-08-26 17:39:36'),
        (806,1,'en','Backend','Core','msg','WarningDebugMode','Debug-mode is active.','2021-08-26 17:39:36'),
        (807,1,'en','Backend','Core','err','EmailAlreadyExists','This e-mailaddress is in use.','2021-08-26 17:39:36'),
        (808,1,'en','Backend','Core','err','EmailIsInvalid','Please provide a valid e-mailaddress.','2021-08-26 17:39:36'),
        (809,1,'en','Backend','Core','err','EmailIsRequired','Please provide a valid e-mailaddress.','2021-08-26 17:39:36'),
        (810,1,'en','Backend','Core','err','EmailIsUnknown','This e-mailaddress is not in our database.','2021-08-26 17:39:36'),
        (811,1,'en','Backend','Core','err','EndDateIsInvalid','Invalid end date.','2021-08-26 17:39:36'),
        (812,1,'en','Backend','Core','err','ErrorWhileSendingEmail','Error while sending email.','2021-08-26 17:39:36'),
        (813,1,'en','Backend','Core','err','ExtensionNotAllowed','Invalid file type. (allowed: %1$s)','2021-08-26 17:39:36'),
        (814,1,'en','Backend','Core','err','FieldIsRequired','This field is required.','2021-08-26 17:39:36'),
        (815,1,'en','Backend','Core','err','FileTooBig','maximum filesize: %1$s','2021-08-26 17:39:36'),
        (816,1,'en','Backend','Core','err','ForkAPIKeys','Fork API-keys are not configured.','2021-08-26 17:39:36'),
        (817,1,'en','Backend','Core','err','FormError','Something went wrong','2021-08-26 17:39:36'),
        (818,1,'en','Backend','Core','err','GoogleMapsKey','Google maps API-key is not configured. <a href=\"%1$s\">Configure</a>','2021-08-26 17:39:36'),
        (819,1,'en','Backend','Core','err','InvalidAPIKey','Invalid API key.','2021-08-26 17:39:36'),
        (820,1,'en','Backend','Core','err','InvalidDomain','Invalid domain.','2021-08-26 17:39:36'),
        (821,1,'en','Backend','Core','err','InvalidEmailPasswordCombination','Your e-mail and password combination is incorrect. <a href=\"#\" id=\"forgotPasswordLink\" rel=\"forgotPasswordHolder\" data-toggle=\"modal\" data-target=\"#forgotPasswordHolder\">Did you forget your password?</a>','2021-08-26 17:39:36'),
        (822,1,'en','Backend','Core','err','InvalidInteger','Invalid number.','2021-08-26 17:39:36'),
        (823,1,'en','Backend','Core','err','InvalidName','Invalid name.','2021-08-26 17:39:36'),
        (824,1,'en','Backend','Core','err','InvalidNumber','Invalid number.','2021-08-26 17:39:36'),
        (825,1,'en','Backend','Core','err','InvalidParameters','Invalid parameters.','2021-08-26 17:39:36'),
        (826,1,'en','Backend','Core','err','InvalidURL','Invalid URL.','2021-08-26 17:39:36'),
        (827,1,'en','Backend','Core','err','InvalidValue','Invalid value.','2021-08-26 17:39:36'),
        (828,1,'en','Backend','Core','err','JavascriptNotEnabled','To use Fork CMS, javascript needs to be enabled. Activate javascript and refresh this page.','2021-08-26 17:39:36'),
        (829,1,'en','Backend','Core','err','JPGGIFAndPNGOnly','Only jpg, gif, png','2021-08-26 17:39:36'),
        (830,1,'en','Backend','Core','err','ModuleNotAllowed','You have insufficient rights for this module.','2021-08-26 17:39:36'),
        (831,1,'en','Backend','Core','err','NameIsRequired','Please provide a name.','2021-08-26 17:39:36'),
        (832,1,'en','Backend','Core','err','NicknameIsRequired','Please provide a publication name.','2021-08-26 17:39:36'),
        (833,1,'en','Backend','Core','err','NoActionSelected','No action selected.','2021-08-26 17:39:36'),
        (834,1,'en','Backend','Core','err','NoCommentsSelected','No comments were selected.','2021-08-26 17:39:36'),
        (835,1,'en','Backend','Core','err','NoItemsSelected','No items were selected.','2021-08-26 17:39:36'),
        (836,1,'en','Backend','Core','err','NoModuleLinked','Cannot generate URL. Create a page that has this module linked to it.','2021-08-26 17:39:36'),
        (837,1,'en','Backend','Core','err','NonExisting','This item doesn\'t exist.','2021-08-26 17:39:36'),
	(838,1,'en','Backend','Core','err','NoSelection','No items were selected.','2021-08-26 17:39:36'),
	(839,1,'en','Backend','Core','err','NoTemplatesAvailable','The selected theme does not yet have templates. Please create at least one template first.','2021-08-26 17:39:36'),
	(840,1,'en','Backend','Core','err','PasswordIsRequired','Please provide a password.','2021-08-26 17:39:36'),
	(841,1,'en','Backend','Core','err','PasswordRepeatIsRequired','Please repeat the desired password.','2021-08-26 17:39:36'),
	(842,1,'en','Backend','Core','err','PasswordsDontMatch','The passwords differ','2021-08-26 17:39:36'),
	(843,1,'en','Backend','Core','err','RobotsFileIsNotOK','robots.txt will block search-engines.','2021-08-26 17:39:36'),
	(844,1,'en','Backend','Core','err','RSSTitle','Blog RSS title is not configured. <a href=\"%1$s\">Configure</a>','2021-08-26 17:39:36'),
	(845,1,'en','Backend','Core','err','SettingsForkAPIKeys','The Fork API-keys are not configured.','2021-08-26 17:39:36'),
         (846,1,'en','Backend','Core','err','SomethingWentWrong','Something went wrong.','2021-08-26 17:39:36'),
         (847,1,'en','Backend','Core','err','StartDateIsInvalid','Invalid start date.','2021-08-26 17:39:36'),
         (848,1,'en','Backend','Core','err','SurnameIsRequired','Please provide a last name.','2021-08-26 17:39:36'),
         (849,1,'en','Backend','Core','err','TimeIsInvalid','Invalid time.','2021-08-26 17:39:36'),
         (850,1,'en','Backend','Core','err','TitleIsRequired','Provide a title.','2021-08-26 17:39:36'),
         (851,1,'en','Backend','Core','err','TooManyLoginAttempts','Too many login attempts. Click the forgot password link if you forgot your password.','2021-08-26 17:39:36'),
         (852,1,'en','Backend','Core','err','URLAlreadyExists','This URL already exists.','2021-08-26 17:39:36'),
         (853,1,'en','Backend','Core','err','ValuesDontMatch','The values don\'t match.','2021-08-26 17:39:36'),
	(854,1,'en','Backend','Core','err','XMLFilesOnly','Only XMl files are allowed.','2021-08-26 17:39:36'),
	(855,1,'en','Backend','Core','lbl','MoveUpOnePosition','move up one position','2021-08-26 17:39:36'),
	(856,1,'en','Backend','Core','lbl','MoveDownOnePosition','move down one position','2021-08-26 17:39:36'),
	(857,1,'en','Backend','Core','msg','HelpTextTags','Type text followed by comma or ENTER to add a new tag. Type a few letters and use the down arrow to select an existing tag','2021-08-26 17:39:36'),
	(858,1,'en','Backend','Core','lbl','Show','show','2021-08-26 17:39:36'),
	(859,1,'en','Backend','Core','lbl','Hide','hide','2021-08-26 17:39:36'),
	(860,1,'en','Backend','Core','lbl','EditContent','edit content','2021-08-26 17:39:36'),
	(861,1,'en','Backend','Core','lbl','AllStatistics','all statistics','2021-08-26 17:39:41'),
	(862,1,'en','Backend','Dashboard','lbl','TopKeywords','top keywords','2021-08-26 17:39:41'),
	(863,1,'en','Backend','Dashboard','lbl','TopReferrers','top referrers','2021-08-26 17:39:41'),
	(864,1,'en','Backend','Dashboard','msg','WillBeEnabledOnSave','This widget will be reenabled on save.','2021-08-26 17:39:41'),
	(865,1,'en','Backend','Settings','lbl','AdminIds','admin ids','2021-08-26 17:39:41'),
	(866,1,'en','Backend','Settings','lbl','ApplicationId','application id','2021-08-26 17:39:41'),
	(867,1,'en','Backend','Settings','lbl','ApplicationSecret','app secret','2021-08-26 17:39:41'),
	(868,1,'en','Backend','Settings','lbl','Cookies','cookies','2021-08-26 17:39:41'),
	(869,1,'en','Backend','Settings','lbl','ClearCache','clear cache','2021-08-26 17:39:41'),
	(870,1,'en','Backend','Settings','lbl','LicenseKey','License key','2021-08-26 17:39:41'),
	(871,1,'en','Backend','Settings','lbl','LicenseName','License name','2021-08-26 17:39:41'),
	(872,1,'en','Backend','Settings','lbl','MaximumHeight','maximum height','2021-08-26 17:39:41'),
	(873,1,'en','Backend','Settings','lbl','MaximumWidth','maximum width','2021-08-26 17:39:41'),
	(874,1,'en','Backend','Settings','lbl','SEOSettings','SEO settings','2021-08-26 17:39:41'),
	(875,1,'en','Backend','Settings','lbl','SmtpSecureLayer','security','2021-08-26 17:39:41'),
	(876,1,'en','Backend','Settings','lbl','Twitter','twitter','2021-08-26 17:39:41'),
	(877,1,'en','Backend','Settings','lbl','Tools','tools','2021-08-26 17:39:41'),
	(878,1,'en','Backend','Settings','lbl','Facebook','facebook','2021-08-26 17:39:41'),
	(879,1,'en','Backend','Settings','lbl','CkFinder','ckfinder','2021-08-26 17:39:41'),
	(880,1,'en','Backend','Settings','msg','ConfigurationError','Some settings are not yet configured.','2021-08-26 17:39:41'),
	(881,1,'en','Backend','Settings','msg','HelpAPIKeys','Access codes for webservices.','2021-08-26 17:39:41'),
	(882,1,'en','Backend','Settings','msg','HelpCkfinderMaximumHeight','Configure the maximum height (in pixels) of uploaded images. If an uploaded image is larger, it gets scaled down proportionally. Set to 0 to disable this feature.','2021-08-26 17:39:41'),
	(883,1,'en','Backend','Settings','msg','HelpCkfinderMaximumWidth','Configure the maximum width (in pixels) of uploaded images. If an uploaded image is larger, it gets scaled down proportionally. Set to 0 to disable this feature.','2021-08-26 17:39:41'),
	(884,1,'en','Backend','Settings','msg','HelpCookies','Show the deprecated cookiebar, consider upgrading to the consent dialog.','2021-08-26 17:39:41'),
	(885,1,'en','Backend','Settings','msg','HelpDateFormatLong','Format that\'s used on overview and detail pages.','2021-08-26 17:39:41'),
         (886,1,'en','Backend','Settings','msg','HelpDateFormatShort','This format is mostly used in table overviews.','2021-08-26 17:39:41'),
         (887,1,'en','Backend','Settings','msg','HelpDomains','Enter the domains on which this website can be reached. (Split domains with linebreaks.)','2021-08-26 17:39:41'),
         (888,1,'en','Backend','Settings','msg','HelpEmailWebmaster','Send CMS notifications to this e-mailaddress.','2021-08-26 17:39:41'),
         (889,1,'en','Backend','Settings','msg','HelpFacebookAdminIds','Either Facebook user IDs or a Facebook Platform application ID that administers this website.','2021-08-26 17:39:41'),
         (890,1,'en','Backend','Settings','msg','HelpFacebookApiKey','The API key of your Facebook application.','2021-08-26 17:39:41'),
         (891,1,'en','Backend','Settings','msg','HelpFacebookApplicationId','The id of your Facebook application','2021-08-26 17:39:41'),
         (892,1,'en','Backend','Settings','msg','HelpFacebookApplicationSecret','The secret of your Facebook application.','2021-08-26 17:39:41'),
         (893,1,'en','Backend','Settings','lbl','TwitterSiteName','twitter username','2021-08-26 17:39:41'),
         (894,1,'en','Backend','Settings','msg','HelpLanguages','Select the languages that are accessible for visitors.','2021-08-26 17:39:41'),
         (895,1,'en','Backend','Settings','msg','HelpNumberFormat','This format is used to display numbers on the website.','2021-08-26 17:39:41'),
         (896,1,'en','Backend','Settings','msg','HelpRedirectLanguages','Select the languages that people may automatically be redirected to based upon their browser language.','2021-08-26 17:39:41'),
         (897,1,'en','Backend','Settings','msg','HelpScriptsEndOfBody','Paste code that needs to be loaded at the end of the <code><body></code> tag here.','2021-08-26 17:39:41'),
         (898,1,'en','Backend','Settings','msg','HelpScriptsEndOfBodyLabel','End of <code>&lt;body&gt;</code> script(s)','2021-08-26 17:39:41'),
         (899,1,'en','Backend','Settings','msg','HelpScriptsHead','Paste code that needs to be loaded in the <code>&lt;head&gt;</code> section here.','2021-08-26 17:39:41'),
         (900,1,'en','Backend','Settings','msg','HelpScriptsHeadLabel','<code>&lt;head&gt;</code> script(s)','2021-08-26 17:39:41'),
         (901,1,'en','Backend','Settings','msg','HelpScriptsStartOfBody','Paste code that needs to be loaded right after the opening <code>&lt;body&gt;</code> tag here.','2021-08-26 17:39:41'),
         (902,1,'en','Backend','Settings','msg','HelpScriptsStartOfBodyLabel','Start of <code>&lt;body&gt;</code> script(s)','2021-08-26 17:39:41'),
         (903,1,'en','Backend','Settings','msg','HelpSendingEmails','You can send emails in 2 ways. By using PHP\'s built-in mail method or via SMTP. We advise you to use SMTP','2021-08-26 17:39:41'),
	(904,1,'en','Backend','Settings','msg','HelpSEONoodp','Opt out of the <a href=\"http://www.dmoz.org/\" class=\"targetBlank\">open directory project</a> override.','2021-08-26 17:39:41'),
	(905,1,'en','Backend','Settings','msg','HelpSEONoydir','Opt out of the Yahoo! Directory override.','2021-08-26 17:39:41'),
          (906,1,'en','Backend','Settings','msg','HelpTimeFormat','This format is used to display dates on the website.','2021-08-26 17:39:41'),
          (907,1,'en','Backend','Settings','msg','ClearCache','Remove all cached files. Useful for when problems arise which possibly have something to do with the cache. Remember clearing the cache might cause some errors when a page is reloaded for the first time.','2021-08-26 17:39:41'),
          (908,1,'en','Backend','Settings','msg','ClearingCache','The cache is being cleared, hang on','2021-08-26 17:39:41'),
          (909,1,'en','Backend','Settings','msg','CacheCleared','The cache has been successfully cleared.','2021-08-26 17:39:41'),
          (910,1,'en','Backend','Settings','lbl','LinkedPagesPerLanguage','linked pages per language','2021-08-26 17:39:41'),
          (911,1,'en','Backend','Settings','msg','NoAdminIds','No admin ids yet.','2021-08-26 17:39:41'),
          (912,1,'en','Backend','Settings','msg','SendTestMail','send test email','2021-08-26 17:39:41'),
          (913,1,'en','Backend','Settings','msg','SEONoFollowInComments','add <code>rel=\"nofollow\"</code> on links inside a comment','2021-08-26 17:39:41'),
          (914,1,'en','Backend','Settings','msg','ShowCookieBar','show the cookie bar','2021-08-26 17:39:41'),
          (915,1,'en','Backend','Settings','msg','TestMessage','this is just a test','2021-08-26 17:39:41'),
          (916,1,'en','Backend','Settings','msg','TestWasSent','The test email was sent.','2021-08-26 17:39:41'),
          (917,1,'en','Backend','Settings','err','PortIsRequired','Port is required.','2021-08-26 17:39:41'),
          (918,1,'en','Backend','Settings','err','ServerIsRequired','Server is required.','2021-08-26 17:39:41'),
          (919,1,'en','Backend','Settings','lbl','GoogleTrackingOptions','Google tracking options','2021-08-26 17:39:41'),
          (920,1,'en','Backend','Settings','msg','HelpGoogleTrackingOptions','You should only enable one option.','2021-08-26 17:39:41'),
          (921,1,'en','Backend','Settings','lbl','GoogleAnalyticsTrackingId','Google Analytics Tracking Id','2021-08-26 17:39:41'),
          (922,1,'en','Backend','Settings','msg','HelpGoogleTrackingGoogleAnalyticsTrackingId','You can find the Tracking Id in <a href=\"https://support.google.com/analytics/answer/1008080?hl=en\">Google Analytics</a>, it will look like: UA-000000-2.','2021-08-26 17:39:41'),
          (923,1,'en','Backend','Settings','lbl','GoogleTagManagerContainerId','Google Tag Manager Container Id','2021-08-26 17:39:41'),
          (924,1,'en','Backend','Settings','msg','HelpGoogleTrackingGoogleTagManagerContainerId','You can find the Container Id in <a href=\"https://support.google.com/tagmanager/answer/6103696?hl=en\">Google Tag Manager</a>, it will look like: GTM-XXXXXX.','2021-08-26 17:39:41'),
          (925,1,'en','Backend','Settings','msg','ShowConsentDialog','Show the privacy preferences dialog','2021-08-26 17:39:41'),
          (926,1,'en','Backend','Settings','lbl','PrivacyConsents','Privacy consents','2021-08-26 17:39:41'),
          (927,1,'en','Backend','Settings','lbl','TechnicalName','Technical name','2021-08-26 17:39:41'),
          (928,1,'en','Backend','Settings','msg','HelpPrivacyConsents','GDPR defines how you as a website owner may use cookies. Below you are able to define several levels, so the user can choose from them.','2021-08-26 17:39:41'),
          (929,1,'en','Backend','Settings','msg','NoPrivacyConsentLevels','No levels yet.','2021-08-26 17:39:41'),
          (930,1,'en','Backend','Settings','msg','HelpPrivacyConsentLevels','These are the names of the variable that will be available in JavaScript and Google Data Layer. You can\'t use spaces or other illegal characters.','2021-08-26 17:39:41'),
	(931,1,'en','Backend','Settings','err','InvalidVariableName','<code>%1$s</code> is an invalid variable name.','2021-08-26 17:39:41'),
	(932,1,'en','Frontend','Core','err','RecaptchaInvalid','Captcha code is invalid.','2021-08-26 17:39:41'),
	(933,0,'en','Backend','Users','lbl','Add','add user','2021-08-26 17:39:42'),
	(934,0,'en','Backend','Users','lbl','PreferredEditor','preferred editor','2021-08-26 17:39:42'),
	(935,0,'en','Backend','Users','msg','Added','The user \"%1$s\" was added.','2021-08-26 17:39:42'),
	(936,0,'en','Backend','Users','msg','ConfirmDelete','Are your sure you want to delete the user \"%1$s\"?','2021-08-26 17:39:42'),
           (937,0,'en','Backend','Users','msg','Deleted','The user \"%1$s\" was deleted.','2021-08-26 17:39:42'),
           (938,0,'en','Backend','Users','msg','Edited','The settings for \"%1$s\" were saved.','2021-08-26 17:39:42'),
           (939,0,'en','Backend','Users','msg','EditUser','edit user \"%1$s\"','2021-08-26 17:39:42'),
           (940,0,'en','Backend','Users','msg','HelpActive','Enable CMS access for this account.','2021-08-26 17:39:42'),
           (941,0,'en','Backend','Users','msg','HelpStrongPassword','Strong passwords consist of a combination of capitals','2021-08-26 17:39:42'),
           (942,0,'en','Backend','Users','msg','Restored','The user \"%1$s\" is restored.','2021-08-26 17:39:42'),
           (943,0,'en','Backend','Users','err','CantChangeGodsEmail','You can\'t change the emailaddres of the GOD-user.','2021-08-26 17:39:42'),
	(944,0,'en','Backend','Users','err','CantDeleteGod','You can\'t delete the GOD-user.','2021-08-26 17:39:42'),
           (945,0,'en','Backend','Users','err','EmailWasDeletedBefore','A user with this emailaddress was deleted. <a href=\"%1$s\">Restore this user</a>.','2021-08-26 17:39:42'),
           (946,0,'en','Backend','Users','err','NonExisting','This user doesn\'t exist.','2021-08-26 17:39:42'),
	(947,1,'en','Backend','Groups','lbl','Action','action','2021-08-26 17:39:43'),
	(948,1,'en','Backend','Groups','lbl','AddGroup','add group','2021-08-26 17:39:43'),
	(949,1,'en','Backend','Groups','lbl','Checkbox',' ','2021-08-26 17:39:43'),
	(950,1,'en','Backend','Groups','lbl','DisplayWidgets','widgets to display','2021-08-26 17:39:43'),
	(951,1,'en','Backend','Groups','lbl','NumUsers','number of users','2021-08-26 17:39:43'),
	(952,1,'en','Backend','Groups','lbl','Presets','presets','2021-08-26 17:39:43'),
	(953,1,'en','Backend','Groups','lbl','SetPermissions','set permissions','2021-08-26 17:39:43'),
	(954,1,'en','Backend','Groups','msg','Added','\"%1$s\" has been added.','2021-08-26 17:39:43'),
	(955,1,'en','Backend','Groups','msg','Deleted','\"%1$s\" has been deleted.','2021-08-26 17:39:43'),
            (956,1,'en','Backend','Groups','msg','Edited','changes for \"%1$s\" has been saved.','2021-08-26 17:39:43'),
            (957,1,'en','Backend','Groups','msg','NoUsers','This group does not contain any users.','2021-08-26 17:39:43'),
            (958,1,'en','Backend','Groups','msg','NoWidgets','There are no widgets available.','2021-08-26 17:39:43'),
            (959,1,'en','Backend','Groups','err','GroupAlreadyExists','This group already exists.','2021-08-26 17:39:43'),
            (960,1,'en','Backend','Extensions','msg','AddedTemplate','The template \"%1$s\" was added.','2021-08-26 17:39:44'),
            (961,1,'en','Backend','Extensions','msg','ConfirmDeleteTemplate','Are your sure you want to delete the template \"%1$s\"?','2021-08-26 17:39:44'),
            (962,1,'en','Backend','Extensions','msg','ConfirmModuleInstall','Are you sure you want to install the module \"%1$s\"?','2021-08-26 17:39:44'),
            (963,1,'en','Backend','Extensions','msg','ConfirmModuleInstallDefault','Are you sure you want to install the module?','2021-08-26 17:39:44'),
            (964,1,'en','Backend','Extensions','msg','ConfirmThemeInstall','Are you sure you want to install this theme?','2021-08-26 17:39:44'),
            (965,1,'en','Backend','Extensions','msg','ShowImageForm','Allow the user to upload an image.','2021-08-26 17:39:44'),
            (966,1,'en','Backend','Extensions','msg','DeletedTemplate','The template \"%1$s\" was deleted.','2021-08-26 17:39:44'),
            (967,1,'en','Backend','Extensions','msg','EditedTemplate','The template \"%1$s\" was saved.','2021-08-26 17:39:44'),
            (968,1,'en','Backend','Extensions','msg','HelpInstallableThemes','Click a theme to install it.','2021-08-26 17:39:44'),
            (969,1,'en','Backend','Extensions','msg','HelpOverwrite','<strong>Attention!</strong> Checking this checkbox will cause the content of every page to be reset to the defaults chosen here-above.','2021-08-26 17:39:44'),
            (970,1,'en','Backend','Extensions','msg','HelpPositionsLayoutText','<strong>A visual representation to be used in the pages-module.</strong><ul><li>Add a row: use <strong>[]</strong></li><li>Reflect a position: use <strong>position name</strong></li><li>Reflect a non-editable area: use <strong>/</strong></li></ul><p>If you want a position to display wider or higher in it\'s graphical representation, repeat the position multiple times (both horizontal and vertical, but the shape should form a rectangle)</p>','2021-08-26 17:39:44'),
	(971,1,'en','Backend','Extensions','msg','HelpPositionsLayoutExample','<strong>A template could look like the chart below:</strong><pre>[  /   ,  /   ,  /   ,  /   ,  top ],<br />[  /   ,  /   ,  /   ,  /   ,  /   ],<br />[ left , main , main , main , right],<br />[bottom,bottom,bottom,bottom,bottom]</pre>','2021-08-26 17:39:44'),
	(972,1,'en','Backend','Extensions','msg','HelpTemplateFormat','e.g. [left,main,right],[/,main,/]','2021-08-26 17:39:44'),
	(973,1,'en','Backend','Extensions','msg','HelpTemplateLocation','Put your templates in the <code>Core/Layout/Templates</code> folder of your theme.','2021-08-26 17:39:44'),
	(974,1,'en','Backend','Extensions','msg','HelpThemes','Select the theme you wish to use.','2021-08-26 17:39:44'),
	(975,1,'en','Backend','Extensions','msg','InformationFileCouldNotBeLoaded','A info.xml file is present but it could not be loaded. Verify if the content is valid XML.','2021-08-26 17:39:44'),
	(976,1,'en','Backend','Extensions','msg','InformationFileIsEmpty','A info.xml file is present but its either empty or it does not contain valuable information.','2021-08-26 17:39:44'),
	(977,1,'en','Backend','Extensions','msg','InformationFileIsMissing','There is no information available.','2021-08-26 17:39:44'),
	(978,1,'en','Backend','Extensions','msg','InformationModuleIsNotInstalled','This module is not yet installed.','2021-08-26 17:39:44'),
	(979,1,'en','Backend','Extensions','msg','InformationThemeIsNotInstalled','This theme is not yet installed.','2021-08-26 17:39:44'),
	(980,1,'en','Backend','Extensions','msg','Module','module \"%1$s\"','2021-08-26 17:39:44'),
                (981,1,'en','Backend','Extensions','msg','ModuleInstalled','The module \"%1$s\" was installed.','2021-08-26 17:39:44'),
             (982,1,'en','Backend','Extensions','msg','ModulesNotWritable','We do not have write rights to the modules folders. Check if you have write rights on the modules folders in all applications.','2021-08-26 17:39:44'),
             (983,1,'en','Backend','Extensions','msg','ModulesWarnings','There are some warnings for following module(s)','2021-08-26 17:39:44'),
             (984,1,'en','Backend','Extensions','msg','NoModulesInstalled','No modules installed.','2021-08-26 17:39:44'),
             (985,1,'en','Backend','Extensions','msg','NoThemes','No themes available.','2021-08-26 17:39:44'),
             (986,1,'en','Backend','Extensions','msg','PathToTemplate','Path to template','2021-08-26 17:39:44'),
             (987,1,'en','Backend','Extensions','msg','TemplateInUse','This template is in use.','2021-08-26 17:39:44'),
             (988,1,'en','Backend','Extensions','msg','Theme','theme \"%1$s\"','2021-08-26 17:39:44'),
             (989,1,'en','Backend','Extensions','msg','ThemeInstalled','The theme \"%1$s\" was installed.','2021-08-26 17:39:44'),
             (990,1,'en','Backend','Extensions','msg','ThemesNotWritable','We do not have write rights to the themes folder. Check if you have write rights on the themes folders in the frontend-application.','2021-08-26 17:39:44'),
             (991,1,'en','Backend','Extensions','msg','ZlibIsMissing','Your server is missing the required PHP \"<a href=\"http://www.php.net/manual/en/book.zlib.php\">Zlib</a>\" extension. Fork CMS needs this extension to be able to unpack your uploaded module.<br /><br />      <ul>        <li>Contact your server administrator with the above message.</li>        <li>Or unpack the ZIP archive on your computer and upload the folders manually (most likely via FTP) to your website root.</li>      </ul>    ','2021-08-26 17:39:44'),
             (992,1,'en','Backend','Extensions','lbl','AddPosition','add position','2021-08-26 17:39:44'),
             (993,1,'en','Backend','Extensions','lbl','AddThemeTemplate','add theme template','2021-08-26 17:39:44'),
             (994,1,'en','Backend','Extensions','lbl','Authors','authors','2021-08-26 17:39:44'),
             (995,1,'en','Backend','Extensions','lbl','DeletePosition','delete position','2021-08-26 17:39:44'),
             (996,1,'en','Backend','Extensions','lbl','DeleteBlock','delete block','2021-08-26 17:39:44'),
             (997,1,'en','Backend','Extensions','lbl','EditThemeTemplate','edit theme template','2021-08-26 17:39:44'),
             (998,1,'en','Backend','Extensions','lbl','Events','events (hooks)','2021-08-26 17:39:44'),
             (999,1,'en','Backend','Extensions','lbl','FindModules','find modules','2021-08-26 17:39:44'),
             (1000,1,'en','Backend','Extensions','lbl','FindThemes','find themes','2021-08-26 17:39:44'),
             (1001,1,'en','Backend','Extensions','lbl','Install','install','2021-08-26 17:39:44'),
             (1002,1,'en','Backend','Extensions','lbl','InstallableModules','not installed modules','2021-08-26 17:39:44'),
             (1003,1,'en','Backend','Extensions','lbl','InstallableThemes','not installed themes','2021-08-26 17:39:44'),
             (1004,1,'en','Backend','Extensions','lbl','InstalledModules','installed modules','2021-08-26 17:39:44'),
             (1005,1,'en','Backend','Extensions','lbl','InstalledThemes','installed themes','2021-08-26 17:39:44'),
             (1006,1,'en','Backend','Extensions','lbl','Overwrite','overwrite','2021-08-26 17:39:44'),
             (1007,1,'en','Backend','Extensions','lbl','Theme','theme','2021-08-26 17:39:44'),
             (1008,1,'en','Backend','Extensions','lbl','ThemeTemplates','theme templates','2021-08-26 17:39:44'),
             (1009,1,'en','Backend','Extensions','lbl','SelectedTheme','selected theme','2021-08-26 17:39:44'),
             (1010,1,'en','Backend','Extensions','lbl','UseThisTheme','use this theme','2021-08-26 17:39:44'),
             (1011,1,'en','Backend','Extensions','lbl','UploadModule','upload module','2021-08-26 17:39:44'),
             (1012,1,'en','Backend','Extensions','lbl','UploadTheme','upload theme','2021-08-26 17:39:44'),
             (1013,1,'en','Backend','Extensions','lbl','Version','version','2021-08-26 17:39:44'),
             (1014,1,'en','Backend','Extensions','err','AlreadyInstalled','\"%1$s\" is already installed.','2021-08-26 17:39:44'),
             (1015,1,'en','Backend','Extensions','err','CorruptedFile','The uploaded file is not a valid ZIP file and could not be extracted.','2021-08-26 17:39:44'),
             (1016,1,'en','Backend','Extensions','err','DeleteTemplate','You can\'t delete this template.','2021-08-26 17:39:44'),
	(1017,1,'en','Backend','Extensions','err','DuplicatePositionName','Position %s is duplicated.','2021-08-26 17:39:44'),
	(1018,1,'en','Backend','Extensions','err','FileContentsIsUseless','We could not find a module in the uploaded file. Verify the contents.','2021-08-26 17:39:44'),
	(1019,1,'en','Backend','Extensions','err','FileIsEmpty','The file is empty. Verify the contents.','2021-08-26 17:39:44'),
	(1020,1,'en','Backend','Extensions','err','TemplateFileNotFound','The template file is missing.','2021-08-26 17:39:44'),
	(1021,1,'en','Backend','Extensions','err','IncompatibleTheme','Theme \"%1$s\" contains files that are incompatible with this version of Fork CMS.','2021-08-26 17:39:44'),
	(1022,1,'en','Backend','Extensions','err','InvalidTemplateSyntax','Invalid syntax.','2021-08-26 17:39:44'),
              (1023,1,'en','Backend','Extensions','err','LibraryFileAlreadyExists','The library-file \"%1$s\" already existed by another module. This module may not function properly.','2021-08-26 17:39:44'),
              (1024,1,'en','Backend','Extensions','err','ModuleAlreadyExists','The module \"%1$s\" already exists, you can not upload it again.','2021-08-26 17:39:44'),
              (1025,1,'en','Backend','Extensions','err','NoAlphaNumPositionName','Position %s is not alphanumerical.','2021-08-26 17:39:44'),
              (1026,1,'en','Backend','Extensions','err','NoInformationFile','We could not find an info.xml file for \"%1$s\".','2021-08-26 17:39:44'),
              (1027,1,'en','Backend','Extensions','err','NoInstallerFile','We could not find an installer for the module \"%1$s\".','2021-08-26 17:39:44'),
              (1028,1,'en','Backend','Extensions','err','NonExistingPositionName','Position %s is not defined.','2021-08-26 17:39:44'),
              (1029,1,'en','Backend','Extensions','err','NoThemes','No themes available.','2021-08-26 17:39:44'),
              (1030,1,'en','Backend','Extensions','err','ReservedPositionName','Position %s is reserved.','2021-08-26 17:39:44'),
              (1031,1,'en','Backend','Extensions','err','ThemeAlreadyExists','The theme \"%1$s\" already exists, you can not upload it again.','2021-08-26 17:39:44'),
              (1032,1,'en','Backend','Extensions','err','ThemeNameDoesntMatch','The theme\'s folder name doesn\'t match the theme name in info.xml.','2021-08-26 17:39:44'),
              (1033,1,'en','Backend','Extensions','lbl','DetailModule','Module details','2021-08-26 17:39:44'),
              (1034,1,'en','Backend','Pages','lbl','LinkCssClass','link CSS class','2021-08-26 17:39:45'),
              (1035,1,'en','Backend','Pages','lbl','MoveThisPage','move this page','2021-08-26 17:39:45'),
              (1036,1,'en','Backend','Pages','lbl','MovePageTree','navigation tree','2021-08-26 17:39:45'),
              (1037,1,'en','Backend','Pages','lbl','MovePageReferencePage','reference page','2021-08-26 17:39:45'),
              (1038,1,'en','Backend','Pages','lbl','MovePageType','action with respect to the reference page','2021-08-26 17:39:45'),
              (1039,1,'en','Backend','Pages','lbl','AppendToTree','append to navigation tree','2021-08-26 17:39:45'),
              (1040,1,'en','Backend','Pages','lbl','BeforePage','add before reference page','2021-08-26 17:39:45'),
              (1041,1,'en','Backend','Pages','lbl','InsidePage','add as subpage of the reference page','2021-08-26 17:39:45'),
              (1042,1,'en','Backend','Pages','lbl','AfterPage','add after reference page','2021-08-26 17:39:45'),
              (1043,1,'en','Backend','Pages','lbl','Add','add','2021-08-26 17:39:45'),
              (1044,1,'en','Backend','Pages','lbl','AddPage','add page','2021-08-26 17:39:45'),
              (1045,1,'en','Backend','Pages','lbl','AddSubPage','add sub page','2021-08-26 17:39:45'),
              (1046,1,'en','Backend','Pages','lbl','ToggleAddPageDropdown','add more options for page','2021-08-26 17:39:45'),
              (1047,1,'en','Backend','Pages','lbl','ChangeTemplate','Change template','2021-08-26 17:39:45'),
              (1048,1,'en','Backend','Pages','lbl','ChooseTemplate','choose template','2021-08-26 17:39:45'),
              (1049,1,'en','Backend','Pages','lbl','DeleteBlock','delete block','2021-08-26 17:39:45'),
              (1050,1,'en','Backend','Pages','lbl','EditModuleContent','edit module content','2021-08-26 17:39:45'),
              (1051,1,'en','Backend','Pages','lbl','ExternalLink','external link','2021-08-26 17:39:45'),
              (1052,1,'en','Backend','Pages','lbl','ExtraTypeBlock','module','2021-08-26 17:39:45'),
              (1053,1,'en','Backend','Pages','lbl','ExtraTypeWidget','widget','2021-08-26 17:39:45'),
              (1054,1,'en','Backend','Pages','lbl','Fallback','Unassigned blocks','2021-08-26 17:39:45'),
              (1055,1,'en','Backend','Pages','lbl','Footer','footer navigation','2021-08-26 17:39:45'),
              (1056,1,'en','Backend','Pages','lbl','InternalLink','internal link','2021-08-26 17:39:45'),
              (1057,1,'en','Backend','Pages','lbl','MainNavigation','main navigation','2021-08-26 17:39:45'),
              (1058,1,'en','Backend','Pages','lbl','Meta','meta navigation','2021-08-26 17:39:45'),
              (1059,1,'en','Backend','Pages','lbl','Navigation','navigation','2021-08-26 17:39:45'),
              (1060,1,'en','Backend','Pages','lbl','Redirect','redirect','2021-08-26 17:39:45'),
              (1061,1,'en','Backend','Pages','lbl','Root','single pages','2021-08-26 17:39:45'),
              (1062,1,'en','Backend','Pages','lbl','SentMailings','sent mailings','2021-08-26 17:39:45'),
              (1063,1,'en','Backend','Pages','lbl','ShowImage','show the image','2021-08-26 17:39:45'),
              (1064,1,'en','Backend','Pages','lbl','SubscribeForm','subscribe form','2021-08-26 17:39:45'),
              (1065,1,'en','Backend','Pages','lbl','UnsubscribeForm','unsubscribe form','2021-08-26 17:39:45'),
              (1066,1,'en','Backend','Pages','lbl','UserTemplate','user template','2021-08-26 17:39:45'),
              (1067,1,'en','Backend','Pages','lbl','WhichTemplate','template','2021-08-26 17:39:45'),
              (1068,1,'en','Backend','Pages','msg','Added','The page \"%1$s\" was added.','2021-08-26 17:39:45'),
              (1069,1,'en','Backend','Pages','msg','HelpImage','This image can be used in the template. for example as a header','2021-08-26 17:39:45'),
              (1070,1,'en','Backend','Pages','msg','AllowChildren','This page can have subpages.','2021-08-26 17:39:45'),
              (1071,1,'en','Backend','Pages','msg','AllowDelete','This page can be deleted.','2021-08-26 17:39:45'),
              (1072,1,'en','Backend','Pages','msg','AllowEdit','This page can be edited.','2021-08-26 17:39:45'),
              (1073,1,'en','Backend','Pages','msg','AllowMove','The position of this page can be changed.','2021-08-26 17:39:45'),
              (1074,1,'en','Backend','Pages','msg','AllowImage','This page can have an image.','2021-08-26 17:39:45'),
              (1075,1,'en','Backend','Pages','msg','BlockAttached','The module <strong>%1$s</strong> is attached to this section.','2021-08-26 17:39:45'),
              (1076,1,'en','Backend','Pages','msg','ConfirmDelete','Are your sure you want to delete the page \"%1$s\"?','2021-08-26 17:39:45'),
              (1077,1,'en','Backend','Pages','msg','ConfirmDeleteBlock','Are your sure you want to delete this block?','2021-08-26 17:39:45'),
              (1078,1,'en','Backend','Pages','msg','ContentSaveWarning','<p><strong>Important:</strong> This content will not be updated until the page has been saved.</p>','2021-08-26 17:39:45'),
              (1079,1,'en','Backend','Pages','msg','CopyAdded','Copy added','2021-08-26 17:39:45'),
              (1080,1,'en','Backend','Pages','msg','Deleted','The page \"%1$s\" was deleted.','2021-08-26 17:39:45'),
              (1081,1,'en','Backend','Pages','msg','Edited','The page \"%1$s\" was saved.','2021-08-26 17:39:45'),
              (1082,1,'en','Backend','Pages','msg','FallbackInfo','<p><strong>Not every block could automatically be assigned to a position.</strong></p><p>Blocks that were added to positions that are not available in this template, are shown here. Default blocks from the previous template that are not present in the current template are also displayed here.<br />You can easily drag them to the desired position.</p><p>These blocks will disappear after saving the page or selecting another template.</p>','2021-08-26 17:39:45'),
              (1083,1,'en','Backend','Pages','msg','HelpBlockContent','What kind of content do you want to show here?','2021-08-26 17:39:45'),
              (1084,1,'en','Backend','Pages','msg','HelpExternalRedirect','Use this if you need to redirect a menu-item to an external website.','2021-08-26 17:39:45'),
              (1085,1,'en','Backend','Pages','msg','HelpInternalRedirect','Use this if you need to redirect a menu-item to another page on this website.','2021-08-26 17:39:45'),
              (1086,1,'en','Backend','Pages','msg','HelpMetaNavigation','Extra topnavigation (above/below the menu) on every page.','2021-08-26 17:39:45'),
              (1087,1,'en','Backend','Pages','msg','HelpNavigationTitle','The title that is shown in the menu.','2021-08-26 17:39:45'),
              (1088,1,'en','Backend','Pages','msg','HomeNoBlock','A module can\'t be linked to the homepage.','2021-08-26 17:39:45'),
	(1089,1,'en','Backend','Pages','msg','IsAction','Use this page as a module action.','2021-08-26 17:39:45'),
	(1090,1,'en','Backend','Pages','msg','MetaNavigation','Enable metanavigation for this website.','2021-08-26 17:39:45'),
	(1091,1,'en','Backend','Pages','msg','ModuleBlockAlreadyLinked','A module has already been linked to this page.','2021-08-26 17:39:45'),
	(1092,1,'en','Backend','Pages','msg','PageIsMoved','The page \"%1$s\" was moved.','2021-08-26 17:39:45'),
	(1093,1,'en','Backend','Pages','msg','RemoveFromSearchIndex','Do not show this page or content in the search results.','2021-08-26 17:39:45'),
               (1094,1,'en','Backend','Pages','msg','RichText','Editor','2021-08-26 17:39:45'),
               (1095,1,'en','Backend','Pages','msg','TemplateChangeWarning','<strong>Warning:</strong> Changing the template can cause existing content to be in another place or no longer be shown.','2021-08-26 17:39:45'),
               (1096,1,'en','Backend','Pages','msg','WidgetAttached','The widget <strong>%1$s</strong> is attached to this section.','2021-08-26 17:39:45'),
               (1097,1,'en','Backend','Pages','msg','AddTagsHere','Add tags here.','2021-08-26 17:39:45'),
               (1098,1,'en','Backend','Pages','msg','MovePagesNotPossible','Moving pages on touch devices is not possible. Go to the settings tab when editing a page to move it.','2021-08-26 17:39:45'),
               (1099,1,'en','Backend','Pages','err','CantAdd2Blocks','It isn\'t possible to link 2 (or more) modules to the same page.','2021-08-26 17:39:45'),
	(1100,1,'en','Backend','Pages','err','HomeCantHaveBlocks','You can\'t link a module to the homepage.','2021-08-26 17:39:45'),
               (1101,1,'en','Backend','Pages','msg','AuthRequired','Users need to be logged to view this page.','2021-08-26 17:39:45'),
               (1102,1,'en','Backend','Pages','lbl','CopyThisPage','copy this page','2021-08-26 17:39:45'),
               (1103,1,'en','Backend','Pages','msg','CopiedTitle','%1$s copy','2021-08-26 17:39:45'),
               (1104,1,'en','Backend','Pages','msg','CopySaveWarning','<p><strong>Important:</strong> The copy hasn\'t been saved yet.</p>','2021-08-26 17:39:45'),
	(1105,1,'en','Backend','Core','err','CantBeMoved','Page can\'t be moved.','2021-08-26 17:39:45'),
               (1106,1,'en','Backend','Search','msg','AddedSynonym','The synonym for the searchterm \"%1$s\" was added.','2021-08-26 17:39:47'),
               (1107,1,'en','Backend','Search','msg','ConfirmDeleteSynonym','Are you sure you want to delete the synonyms for the searchterm \"%1$s\"?','2021-08-26 17:39:47'),
               (1108,1,'en','Backend','Search','msg','DeletedSynonym','The synonym for the searchterm \"%1$s\" was deleted.','2021-08-26 17:39:47'),
               (1109,1,'en','Backend','Search','msg','EditedSynonym','The synonym for the searchterm \"%1$s\" was saved.','2021-08-26 17:39:47'),
               (1110,1,'en','Backend','Search','msg','HelpWeight','The default weight is 1. Increase the value to increase the importance of results from a specific module.','2021-08-26 17:39:47'),
               (1111,1,'en','Backend','Search','msg','HelpWeightGeneral','Define the importance of each module in search results here.','2021-08-26 17:39:47'),
               (1112,1,'en','Backend','Search','msg','HelpSitelinksSearchBox','You can find more info in <a href=\"https://developers.google.com/webmasters/richsnippets/sitelinkssearch\">Googles official documentation</a>.','2021-08-26 17:39:47'),
               (1113,1,'en','Backend','Search','msg','IncludeInSearch','Include in search results?','2021-08-26 17:39:47'),
               (1114,1,'en','Backend','Search','msg','NoStatistics','There are no statistics yet.','2021-08-26 17:39:47'),
               (1115,1,'en','Backend','Search','msg','NoSynonyms','There are no synonyms yet. <a href=\"%1$s\">Add the first synonym</a>.','2021-08-26 17:39:47'),
               (1116,1,'en','Backend','Search','msg','NoSynonymsBox','There are no synonyms yet.','2021-08-26 17:39:47'),
               (1117,1,'en','Backend','Search','lbl','AddSynonym','add synonym','2021-08-26 17:39:47'),
               (1118,1,'en','Backend','Search','lbl','DeleteSynonym','delete synonym','2021-08-26 17:39:47'),
               (1119,1,'en','Backend','Search','lbl','EditSynonym','edit synonym','2021-08-26 17:39:47'),
               (1120,1,'en','Backend','Search','lbl','ItemsForAutocomplete','Items in autocomplete (search results: search term suggestions)','2021-08-26 17:39:47'),
               (1121,1,'en','Backend','Search','lbl','ItemsForAutosuggest','Items in autosuggest (search widget: results)','2021-08-26 17:39:47'),
               (1122,1,'en','Backend','Search','lbl','ModuleWeight','module weight','2021-08-26 17:39:47'),
               (1123,1,'en','Backend','Search','lbl','SearchedOn','searched on','2021-08-26 17:39:47'),
               (1124,1,'en','Backend','Search','lbl','UseSitelinksSearchBox','Enable Googles Sitelinks Search Box.','2021-08-26 17:39:47'),
               (1125,1,'en','Backend','Search','err','SynonymIsRequired','Synonyms are required.','2021-08-26 17:39:47'),
               (1126,1,'en','Backend','Search','err','TermExists','Synonyms for this searchterm already exist.','2021-08-26 17:39:47'),
               (1127,1,'en','Backend','Search','err','TermIsRequired','The searchterm is required.','2021-08-26 17:39:47'),
               (1128,1,'en','Backend','Search','err','WeightNotNumeric','The weight must be numeric.','2021-08-26 17:39:47'),
               (1129,1,'en','Backend','ContentBlocks','lbl','Add','add content block','2021-08-26 17:39:47'),
               (1130,1,'en','Backend','ContentBlocks','msg','Added','The content block \"%1$s\" was added.','2021-08-26 17:39:47'),
               (1131,1,'en','Backend','ContentBlocks','msg','ConfirmDelete','Are your sure you want to delete the content block \"%1$s\"?','2021-08-26 17:39:47'),
               (1132,1,'en','Backend','ContentBlocks','msg','Deleted','The content block \"%1$s\" was deleted.','2021-08-26 17:39:47'),
               (1133,1,'en','Backend','ContentBlocks','lbl','Edit','edit content block','2021-08-26 17:39:47'),
               (1134,1,'en','Backend','ContentBlocks','msg','Edited','The content block \"%1$s\" was saved.','2021-08-26 17:39:47'),
               (1135,1,'en','Backend','ContentBlocks','msg','NoTemplate','No template','2021-08-26 17:39:47'),
               (1136,1,'en','Backend','Tags','msg','Deleted','The selected tag(s) was/were deleted.','2021-08-26 17:39:48'),
               (1137,1,'en','Backend','Tags','msg','Edited','The tag \"%1$s\" was saved.','2021-08-26 17:39:48'),
               (1138,1,'en','Backend','Tags','msg','EditTag','edit tag \"%1$s\"','2021-08-26 17:39:48'),
               (1139,1,'en','Backend','Tags','msg','NoItems','There are no tags yet.','2021-08-26 17:39:48'),
               (1140,1,'en','Backend','Tags','err','NonExisting','This tag doesn\'t exist.','2021-08-26 17:39:48'),
	(1141,1,'en','Backend','Tags','err','NoSelection','No tags were selected.','2021-08-26 17:39:48'),
	(1142,1,'en','Backend','Tags','err','TagAlreadyExists','This tag already exists.','2021-08-26 17:39:48'),
	(1143,1,'en','Backend','Core','lbl','AddMediaItems','add media','2021-08-26 17:39:48'),
	(1144,1,'en','Backend','Core','err','MinimumConnectedItems','You need to connect {{ limit }} media items or more.','2021-08-26 17:39:48'),
	(1145,1,'en','Backend','Core','err','MaximumConnectedItems','You need to connect {{ limit }} media items or less.','2021-08-26 17:39:48'),
	(1146,1,'en','Backend','Core','lbl','MoveLeft','move left','2021-08-26 17:39:48'),
	(1147,1,'en','Backend','Core','lbl','MediaFolderRoot','root (no parent folder)','2021-08-26 17:39:48'),
	(1148,1,'en','Backend','Core','lbl','MoveRight','move right','2021-08-26 17:39:48'),
	(1149,1,'en','Backend','Core','lbl','MoveUp','move up','2021-08-26 17:39:48'),
	(1150,1,'en','Backend','Core','lbl','MoveDown','move down','2021-08-26 17:39:48'),
	(1151,1,'en','Backend','Core','lbl','FlipHorizontal','flip horizontal','2021-08-26 17:39:48'),
	(1152,1,'en','Backend','Core','lbl','FlipVertical','flip vertical','2021-08-26 17:39:48'),
	(1153,1,'en','Backend','Core','msg','ChooseTypeForNewGroup','with the following type:','2021-08-26 17:39:48'),
	(1154,1,'en','Backend','Core','msg','IfYouWantToCropAnImageYouShouldCheckThisCheckboxFirst','If you want to crop an image you should check this checkbox first','2021-08-26 17:39:48'),
	(1155,1,'en','Backend','Core','lbl','DropFilesHere','drop files here','2021-08-26 17:39:48'),
	(1156,1,'en','Backend','Core','msg','FolderIsAdded','The new folder is added.','2021-08-26 17:39:48'),
	(1157,1,'en','Backend','Core','lbl','MediaAddFolder','add folder','2021-08-26 17:39:48'),
	(1158,1,'en','Backend','Core','lbl','MediaAddMovie','add movie','2021-08-26 17:39:48'),
	(1159,1,'en','Backend','Core','lbl','EnableCropper','enable cropper','2021-08-26 17:39:48'),
	(1160,1,'en','Backend','Core','msg','TheCropperIsMandatoryBecauseTheImagesNeedToHaveACertainFormat','The cropper is mandatory because the images need to have a certain aspect ratio.','2021-08-26 17:39:48'),
	(1161,1,'en','Backend','Core','lbl','MediaAudio','audio','2021-08-26 17:39:48'),
	(1162,1,'en','Backend','Core','msg','MediaChoseToUpload','Choose the media to upload.','2021-08-26 17:39:48'),
	(1163,1,'en','Backend','Core','lbl','MediaConnect','connect media','2021-08-26 17:39:48'),
	(1164,1,'en','Backend','Core','lbl','MediaConnectNow','connect / upload','2021-08-26 17:39:48'),
	(1165,1,'en','Backend','Core','lbl','MediaDisconnect','disconnect','2021-08-26 17:39:48'),
	(1166,1,'en','Backend','Core','lbl','MediaFiles','files','2021-08-26 17:39:48'),
	(1167,1,'en','Backend','Core','lbl','MediaFolder','folder','2021-08-26 17:39:48'),
	(1168,1,'en','Backend','Core','msg','MediaGroupEdited','The media is changed correctly.<br/><strong>Attention:</strong> these changes will be used after saving.','2021-08-26 17:39:48'),
	(1169,1,'en','Backend','Core','lbl','MediaImages','images','2021-08-26 17:39:48'),
	(1170,1,'en','Backend','Core','lbl','MediaInTheFolder','in the folder','2021-08-26 17:39:48'),
	(1171,1,'en','Backend','Core','lbl','MediaItemDelete','removing media','2021-08-26 17:39:48'),
	(1172,1,'en','Backend','Core','lbl','MediaItemIndex','overview','2021-08-26 17:39:48'),
	(1173,1,'en','Backend','Core','lbl','MediaItemUpload','adding media','2021-08-26 17:39:48'),
	(1174,1,'en','Backend','Core','lbl','MediaLibrary','media','2021-08-26 17:39:48'),
	(1175,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeAll','all file types','2021-08-26 17:39:48'),
	(1176,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeAudio','only audio-files','2021-08-26 17:39:48'),
	(1177,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeFile','only files (.pdf, .doc, .docx, ...)','2021-08-26 17:39:48'),
	(1178,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeImage','only images (.jpg, .png, .gif)','2021-08-26 17:39:48'),
	(1179,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeImageFile','only images and files','2021-08-26 17:39:48'),
	(1180,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeImageMovie','only images and movies','2021-08-26 17:39:48'),
	(1181,1,'en','Backend','Core','lbl','MediaLibraryGroupTypeMovie','only movies (.avi, .mov, .mp4)','2021-08-26 17:39:48'),
	(1182,1,'en','Backend','Core','lbl','MediaLibraryTab','media library','2021-08-26 17:39:48'),
	(1183,1,'en','Backend','Core','lbl','MediaMovieId','movie ID','2021-08-26 17:39:48'),
	(1184,1,'en','Backend','Core','err','MediaMovieIdAlreadyExists','Movie ID is already inserted.','2021-08-26 17:39:48'),
	(1185,1,'en','Backend','Core','msg','MediaMovieIdHelp','This is the unique ID for a movie.','2021-08-26 17:39:48'),
	(1186,1,'en','Backend','Core','msg','MediaMovieIsAdded','Movie ID is added.','2021-08-26 17:39:48'),
	(1187,1,'en','Backend','Core','lbl','MediaMovies','movies','2021-08-26 17:39:48'),
	(1188,1,'en','Backend','Core','lbl','MediaMovieSource','source','2021-08-26 17:39:48'),
	(1189,1,'en','Backend','Core','err','MediaMovieSourceIsRequired','Movie source is a required field.','2021-08-26 17:39:48'),
	(1190,1,'en','Backend','Core','lbl','MediaMovieTitle','movie title','2021-08-26 17:39:48'),
	(1191,1,'en','Backend','Core','err','MediaMovieTitleIsRequired','Movie title is a required field.','2021-08-26 17:39:48'),
	(1192,1,'en','Backend','Core','err','MediaFolderIsRequired','Folder is a required field.','2021-08-26 17:39:48'),
	(1193,1,'en','Backend','Core','err','GroupIdIsRequired','group_id is a required field.','2021-08-26 17:39:48'),
	(1194,1,'en','Backend','Core','err','FolderIdIsRequired','folder_id is a required field.','2021-08-26 17:39:48'),
	(1195,1,'en','Backend','Core','err','MediaMovieIdIsRequired','id is a required field.','2021-08-26 17:39:48'),
	(1196,1,'en','Backend','Core','lbl','ZoomIn','zoom in','2021-08-26 17:39:48'),
	(1197,1,'en','Backend','Core','lbl','MediaConnected','connected media','2021-08-26 17:39:48'),
	(1198,1,'en','Backend','Core','lbl','ZoomOut','zoom out','2021-08-26 17:39:48'),
	(1199,1,'en','Backend','Core','err','ParentNotExists','parent directory not found','2021-08-26 17:39:48'),
	(1200,1,'en','Backend','Core','lbl','Reset','reset','2021-08-26 17:39:48'),
	(1201,1,'en','Backend','Core','err','MovieStorageTypeNotExists','Can\'t create a movie with this type.','2021-08-26 17:39:48'),
               (1202,1,'en','Backend','Core','err','MediaUploadedError','%1$s file(s) could not be uploaded.','2021-08-26 17:39:48'),
               (1203,1,'en','Backend','Core','lbl','MediaMultipleAudio','audio','2021-08-26 17:39:48'),
               (1204,1,'en','Backend','Core','lbl','MediaMultipleFile','files','2021-08-26 17:39:48'),
               (1205,1,'en','Backend','Core','lbl','MediaMultipleImage','images','2021-08-26 17:39:48'),
               (1206,1,'en','Backend','Core','lbl','MediaMultipleMovie','movies','2021-08-26 17:39:48'),
               (1207,1,'en','Backend','Core','lbl','MediaNew','new media','2021-08-26 17:39:48'),
               (1208,1,'en','Backend','Core','msg','MediaNoItemsConnected','You didn\'t have any media connected.','2021-08-26 17:39:48'),
	(1209,1,'en','Backend','Core','msg','MediaNoItemsInFolder','There is no media in this folder.','2021-08-26 17:39:48'),
	(1210,1,'en','Backend','Core','msg','MediaOrAddMediaFolder','or add new folder','2021-08-26 17:39:48'),
	(1211,1,'en','Backend','Core','lbl','MediaStorageType','source','2021-08-26 17:39:48'),
	(1212,1,'en','Backend','Core','msg','MediaUploaded','Uploaded media.','2021-08-26 17:39:48'),
	(1213,1,'en','Backend','Core','msg','MediaUploadedSuccess','%1$s file(s) successful uploaded.','2021-08-26 17:39:48'),
	(1214,1,'en','Backend','Core','msg','MediaUploadedSuccessful','Media \"%1$s\" uploaded successful.','2021-08-26 17:39:48'),
	(1215,1,'en','Backend','Core','msg','MediaUploadThisType','What do you want to add?','2021-08-26 17:39:48'),
                (1216,1,'en','Backend','Core','msg','MediaUploadToThisFolder','Upload to this folder:','2021-08-26 17:39:48'),
                (1217,1,'en','Backend','Core','msg','MediaUploadTypeFiles','Images, files','2021-08-26 17:39:48'),
                (1218,1,'en','Backend','Core','msg','MediaUploadTypeMovies','Online movies (Youtube, Vimeo, ...)','2021-08-26 17:39:48'),
                (1219,1,'en','Backend','Core','msg','MediaYouAreHere','You are here:','2021-08-26 17:39:48'),
                (1220,1,'en','Backend','Core','msg','MediaWhichMovieToAdd','Which movie do you want to add?','2021-08-26 17:39:48'),
                (1221,1,'en','Backend','Core','msg','MediaWillBeConnected','The following media will be connected when pressing \"OK\":','2021-08-26 17:39:48'),
                (1222,1,'en','Backend','Core','lbl','Crop','crop','2021-08-26 17:39:48'),
                (1223,1,'en','Backend','Core','lbl','RotateLeft','rotate left','2021-08-26 17:39:48'),
                (1224,1,'en','Backend','Core','lbl','RotateRight','rotate right','2021-08-26 17:39:48'),
                (1225,1,'en','Backend','Core','lbl','CropImage','crop image','2021-08-26 17:39:48'),
                (1226,1,'en','Backend','MediaLibrary','lbl','Action','action class','2021-08-26 17:39:48'),
                (1227,1,'en','Backend','MediaLibrary','lbl','AddMediaFolder','add folder','2021-08-26 17:39:48'),
                (1228,1,'en','Backend','MediaLibrary','lbl','AddMediaItems','add media','2021-08-26 17:39:48'),
                (1229,1,'en','Backend','MediaLibrary','lbl','AllMedia','all media','2021-08-26 17:39:48'),
                (1230,1,'en','Backend','MediaLibrary','lbl','BackToOverview','back to overview','2021-08-26 17:39:48'),
                (1231,1,'en','Backend','MediaLibrary','msg','CleanedUpMediaItems','Removed %1$s media items.','2021-08-26 17:39:48'),
                (1232,1,'en','Backend','MediaLibrary','msg','ConfirmMediaFolderDelete','Are you sure you want to delete the folder \"%1$s\"?','2021-08-26 17:39:48'),
                (1233,1,'en','Backend','MediaLibrary','msg','ConfirmMediaFolderDeleteAndFiles','Are you sure you want to delete the folder \"%1$s\" and all its files? This files will be completely removed and you can\'t use them anymore.','2021-08-26 17:39:48'),
	(1234,1,'en','Backend','MediaLibrary','msg','ConfirmMediaItemDelete','Are you sure you want to delete this media item \"%1$s\" and all it connections?','2021-08-26 17:39:48'),
	(1235,1,'en','Backend','MediaLibrary','lbl','Delete','delete','2021-08-26 17:39:48'),
                 (1236,1,'en','Backend','MediaLibrary','msg','DeletedMediaItem','The media item \"%1$s\" and all its connections are deleted.','2021-08-26 17:39:48'),
                 (1237,1,'en','Backend','MediaLibrary','msg','DeletedMediaFolder','The folder \"%1$s\" and all its files are deleted.','2021-08-26 17:39:48'),
                 (1238,1,'en','Backend','MediaLibrary','msg','DeletedView','View deleted.','2021-08-26 17:39:48'),
                 (1239,1,'en','Backend','MediaLibrary','msg','EditFile','edit file \"%1$s\"','2021-08-26 17:39:48'),
                 (1240,1,'en','Backend','MediaLibrary','lbl','EditMediaFolder','edit folder','2021-08-26 17:39:48'),
                 (1241,1,'en','Backend','MediaLibrary','lbl','File','file','2021-08-26 17:39:48'),
                 (1242,1,'en','Backend','MediaLibrary','lbl','FileTypes','file types','2021-08-26 17:39:48'),
                 (1243,1,'en','Backend','MediaLibrary','err','FileExtensionNotAllowed','File extension not allowed.','2021-08-26 17:39:48'),
                 (1244,1,'en','Backend','MediaLibrary','msg','FilesFor','in the folder \"%1$s\"','2021-08-26 17:39:48'),
                 (1245,1,'en','Backend','MediaLibrary','lbl','Folders','folders','2021-08-26 17:39:48'),
                 (1246,1,'en','Backend','MediaLibrary','lbl','Image','image','2021-08-26 17:39:48'),
                 (1247,1,'en','Backend','MediaLibrary','err','InvalidWidgetAction','Invalid widget action.','2021-08-26 17:39:48'),
                 (1248,1,'en','Backend','MediaLibrary','err','LabelIsRequired','Title is a required field.','2021-08-26 17:39:48'),
                 (1249,1,'en','Backend','MediaLibrary','lbl','Library','library','2021-08-26 17:39:48'),
                 (1250,1,'en','Backend','MediaLibrary','msg','MediaDeleted','Media deleted.','2021-08-26 17:39:48'),
                 (1251,1,'en','Backend','MediaLibrary','lbl','MediaFolderDelete','Delete folder','2021-08-26 17:39:48'),
                 (1252,1,'en','Backend','MediaLibrary','msg','MediaFolderDeleted','Media folder \"%1$s\" deleted.','2021-08-26 17:39:48'),
                 (1253,1,'en','Backend','MediaLibrary','err','MediaFolderDeleteNotPossible','Delete folder not possible because it\'s the last folder.','2021-08-26 17:39:48'),
	(1254,1,'en','Backend','MediaLibrary','err','MediaFolderDeleteNotPossibleBecauseOfConnectedMediaItems','Delete folder not possible because it contains media that is connected somewhere.','2021-08-26 17:39:48'),
	(1255,1,'en','Backend','MediaLibrary','msg','MediaDisconnected','File disconnected from gallery \"%1$s\".','2021-08-26 17:39:48'),
	(1256,1,'en','Backend','MediaLibrary','err','MediaFolderDoesNotExists','The folder doesn\'t exists.','2021-08-26 17:39:48'),
	(1257,1,'en','Backend','MediaLibrary','err','MediaFolderExists','This folder already exists.','2021-08-26 17:39:48'),
	(1258,1,'en','Backend','MediaLibrary','msg','MediaFolderIsEdited','Folder \"%1$s\" edited.','2021-08-26 17:39:48'),
	(1259,1,'en','Backend','MediaLibrary','msg','MediaFolderMoved','Media folder \"%1$s\" moved.','2021-08-26 17:39:48'),
	(1260,1,'en','Backend','MediaLibrary','msg','MediaItemDeleted','Media item \"%1$s\" deleted.','2021-08-26 17:39:48'),
	(1261,1,'en','Backend','MediaLibrary','lbl','MediaItemEdit','edit item','2021-08-26 17:39:48'),
	(1262,1,'en','Backend','MediaLibrary','msg','MediaItemEdited','The item \"%1$s\" has been saved.','2021-08-26 17:39:48'),
	(1263,1,'en','Backend','MediaLibrary','msg','MediaMoved','Media moved.','2021-08-26 17:39:48'),
	(1264,1,'en','Backend','MediaLibrary','lbl','Mime','filetype','2021-08-26 17:39:48'),
	(1265,1,'en','Backend','MediaLibrary','lbl','Move','move','2021-08-26 17:39:48'),
	(1266,1,'en','Backend','MediaLibrary','lbl','MoveMedia','move media','2021-08-26 17:39:48'),
	(1267,1,'en','Backend','MediaLibrary','msg','MoveMediaFoldersNotPossible','It\'s not possible to move media folders.','2021-08-26 17:39:48'),
	(1268,1,'en','Backend','MediaLibrary','msg','MoveMediaToFolder','Move media to this folder:','2021-08-26 17:39:48'),
	(1269,1,'en','Backend','MediaLibrary','err','NonExistingMediaFolder','Folder does not exist.','2021-08-26 17:39:48'),
	(1270,1,'en','Backend','MediaLibrary','lbl','NumConnected','# connections','2021-08-26 17:39:48'),
	(1271,1,'en','Backend','MediaLibrary','err','PleaseSelectAFolder','Please select a folder when trying to move items.','2021-08-26 17:39:48'),
	(1272,1,'en','Backend','MediaLibrary','lbl','SaveAndEdit','save and edit','2021-08-26 17:39:48'),
	(1273,1,'en','Backend','MediaLibrary','err','YouAreRequiredToConnectMedia','You are required to connect media.','2021-08-26 17:39:48'),
	(1274,1,'en','Backend','MediaLibrary','lbl','Select','select','2021-08-26 17:39:48'),
	(1275,1,'en','Backend','MediaLibrary','lbl','DeleteMedia','delete media','2021-08-26 17:39:48'),
	(1276,1,'en','Backend','MediaLibrary','lbl','ConfirmDeleteMedia','Are you sure you want to delete the media?','2021-08-26 17:39:48'),
	(1277,1,'en','Backend','Blog','lbl','Add','add article','2021-08-26 17:39:50'),
	(1278,1,'en','Backend','Blog','lbl','WordpressFilter','filter','2021-08-26 17:39:50'),
	(1279,1,'en','Backend','Blog','msg','Added','The article \"%1$s\" was added.','2021-08-26 17:39:50'),
	(1280,1,'en','Backend','Blog','msg','ArticlesFor','Articles for \"%1$s\"','2021-08-26 17:39:50'),
	(1281,1,'en','Backend','Blog','msg','CommentOnWithURL','Comment on: <a href=\"%1$s\">%2$s</a>','2021-08-26 17:39:50'),
	(1282,1,'en','Backend','Blog','msg','ConfirmDelete','Are your sure you want to delete the article \"%1$s\"?','2021-08-26 17:39:50'),
	(1283,1,'en','Backend','Blog','msg','DeleteAllSpam','Delete all spam:','2021-08-26 17:39:50'),
	(1284,1,'en','Backend','Blog','msg','Deleted','The selected articles were deleted.','2021-08-26 17:39:50'),
	(1285,1,'en','Backend','Blog','msg','DeletedSpam','All spam-comments were deleted.','2021-08-26 17:39:50'),
	(1286,1,'en','Backend','Blog','msg','EditArticle','edit article \"%1$s\"','2021-08-26 17:39:50'),
	(1287,1,'en','Backend','Blog','msg','EditCommentOn','edit comment on \"%1$s\"','2021-08-26 17:39:50'),
	(1288,1,'en','Backend','Blog','msg','Edited','The article \"%1$s\" was saved.','2021-08-26 17:39:50'),
	(1289,1,'en','Backend','Blog','msg','EditedComment','The comment was saved.','2021-08-26 17:39:50'),
	(1290,1,'en','Backend','Blog','msg','FollowAllCommentsInRSS','Follow all comments in a RSS feed: <a href=\"%1$s\">%1$s</a>.','2021-08-26 17:39:50'),
	(1291,1,'en','Backend','Blog','msg','HelpMeta','Show the meta information for this blogpost in the RSS feed (category)','2021-08-26 17:39:50'),
	(1292,1,'en','Backend','Blog','msg','HelpPingServices','Let various blogservices know when you\'ve posted a new article.','2021-08-26 17:39:50'),
	(1293,1,'en','Backend','Blog','msg','HelpSpamFilter','Enable the built-in spamfilter (Akismet) to help avoid spam comments.','2021-08-26 17:39:50'),
	(1294,1,'en','Backend','Blog','msg','HelpSummary','Write an introduction or summary for long articles. It will be shown on the homepage or the article overview.','2021-08-26 17:39:50'),
	(1295,1,'en','Backend','Blog','msg','HelpWordpress','Hier kan je een export bestand vanuit een wordpress site uploaden.','2021-08-26 17:39:50'),
	(1296,1,'en','Backend','Blog','msg','HelpWordpressFilter','De zoekterm die in bestaande blogposts in een link voor moet komen, alvorens wij de link kunnen omzetten naar een actieve link op de fork blog module.','2021-08-26 17:39:50'),
	(1297,1,'en','Backend','Blog','msg','NoCategoryItems','There are no categories yet. <a href=\"%1$s\">Create the first category</a>.','2021-08-26 17:39:50'),
	(1298,1,'en','Backend','Blog','err','NoFromSelected','No origin selected.','2021-08-26 17:39:50'),
	(1299,1,'en','Backend','Blog','msg','NoItems','There are no articles yet. <a href=\"%1$s\">Write the first article</a>.','2021-08-26 17:39:50'),
	(1300,1,'en','Backend','Blog','msg','NotifyByEmailOnNewComment','Notify by email when there is a new comment.','2021-08-26 17:39:50'),
	(1301,1,'en','Backend','Blog','msg','NotifyByEmailOnNewCommentToModerate','Notify by email when there is a new comment to moderate.','2021-08-26 17:39:50'),
	(1302,1,'en','Backend','Blog','msg','NumItemsInRecentArticlesFull','Number of articles in the recent articles (full) widget','2021-08-26 17:39:50'),
	(1303,1,'en','Backend','Blog','msg','NumItemsInRecentArticlesList','Number of articles in the recent articles (list) widget','2021-08-26 17:39:50'),
	(1304,1,'en','Backend','Blog','msg','ShowImageForm','The user can upload a file.','2021-08-26 17:39:50'),
	(1305,1,'en','Backend','Blog','msg','ShowOnlyItemsInCategory','Show only articles for:','2021-08-26 17:39:50'),
	(1306,1,'en','Backend','Blog','msg','NoSpam','There is no spam yet.','2021-08-26 17:39:50'),
	(1307,1,'en','Backend','Blog','err','DeleteCategoryNotAllowed','It is not allowed to delete the category \"%1$s\".','2021-08-26 17:39:50'),
	(1308,1,'en','Backend','Blog','err','RSSDescription','Blog RSS description is not yet provided. <a href=\"%1$s\">Configure</a>','2021-08-26 17:39:50'),
	(1309,1,'en','Backend','Blog','err','XMLFilesOnly','Only XML files can be uploaded.','2021-08-26 17:39:50'),
	(1310,1,'en','Backend','Core','lbl','RelatedArticles','related articles','2021-08-26 17:39:50'),
	(1311,1,'en','Frontend','Core','lbl','RelatedArticles','related articles','2021-08-26 17:39:50');

/*!40000 ALTER TABLE `locale` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table MediaFolder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MediaFolder`;

CREATE TABLE `MediaFolder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `editedOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `parentMediaFolderId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F8B3AB017897CFE7` (`parentMediaFolderId`),
  CONSTRAINT `FK_F8B3AB017897CFE7` FOREIGN KEY (`parentMediaFolderId`) REFERENCES `MediaFolder` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `MediaFolder` WRITE;
/*!40000 ALTER TABLE `MediaFolder` DISABLE KEYS */;

INSERT INTO `MediaFolder` (`id`, `userId`, `name`, `createdOn`, `editedOn`, `parentMediaFolderId`)
VALUES
	(1,1,'default','2021-08-26 17:39:50','2021-08-26 17:39:50',NULL);

/*!40000 ALTER TABLE `MediaFolder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table MediaGroup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MediaGroup`;

CREATE TABLE `MediaGroup` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:media_group_type)',
  `editedOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `numberOfConnectedItems` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table MediaGroupMediaItem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MediaGroupMediaItem`;

CREATE TABLE `MediaGroupMediaItem` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `createdOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `sequence` int(11) NOT NULL,
  `mediaGroupId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:uuid)',
  `mediaItemId` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`),
  KEY `IDX_BCC51AD86776CC71` (`mediaGroupId`),
  KEY `IDX_BCC51AD827759A6A` (`mediaItemId`),
  CONSTRAINT `FK_BCC51AD827759A6A` FOREIGN KEY (`mediaItemId`) REFERENCES `MediaItem` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_BCC51AD86776CC71` FOREIGN KEY (`mediaGroupId`) REFERENCES `MediaGroup` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table MediaItem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `MediaItem`;

CREATE TABLE `MediaItem` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `userId` int(11) NOT NULL,
  `storageType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '(DC2Type:media_item_storage_type)',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:media_item_type)',
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shardingFolderName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `aspectRatio` decimal(13,2) DEFAULT NULL COMMENT '(DC2Type:media_item_aspect_ratio)',
  `createdOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `editedOn` datetime NOT NULL COMMENT '(DC2Type:datetime)',
  `mediaFolderId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BE26384C1DCBB95` (`mediaFolderId`),
  CONSTRAINT `FK_3BE26384C1DCBB95` FOREIGN KEY (`mediaFolderId`) REFERENCES `MediaFolder` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table meta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta`;

CREATE TABLE `meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords_overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_overwrite` tinyint(1) NOT NULL DEFAULT '0',
  `custom` longtext COLLATE utf8mb4_unicode_ci,
  `data` longtext COLLATE utf8mb4_unicode_ci,
  `seo_follow` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:seo_follow)',
  `seo_index` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:seo_index)',
  PRIMARY KEY (`id`),
  KEY `idx_url` (`url`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `meta` WRITE;
/*!40000 ALTER TABLE `meta` DISABLE KEYS */;

INSERT INTO `meta` (`id`, `keywords`, `keywords_overwrite`, `description`, `description_overwrite`, `title`, `title_overwrite`, `url`, `url_overwrite`, `custom`, `data`, `seo_follow`, `seo_index`)
VALUES
	(1,'Home',0,'Home',0,'Home',0,'home',0,NULL,NULL,NULL,NULL),
	(2,'Sitemap',0,'Sitemap',0,'Sitemap',0,'sitemap',0,NULL,NULL,NULL,NULL),
	(3,'Disclaimer',0,'Disclaimer',0,'Disclaimer',0,'disclaimer',0,NULL,NULL,'nofollow','noindex'),
	(4,'404',0,'404',0,'404',0,'404',0,NULL,NULL,NULL,NULL),
	(5,'Modules',0,'Modules',0,'Modules',0,'modules',0,NULL,NULL,NULL,NULL),
	(6,'Home',0,'Home',0,'Home',0,'home',0,NULL,NULL,NULL,NULL),
	(7,'Blog',0,'Blog',0,'Blog',0,'blog',0,NULL,NULL,NULL,NULL),
	(8,'Tags',0,'Tags',0,'Tags',0,'tags',0,NULL,NULL,NULL,NULL),
	(9,'Search',0,'Search',0,'Search',0,'search',0,NULL,NULL,NULL,NULL),
	(10,'Default',0,'Default',0,'Default',0,'default',0,NULL,NULL,NULL,NULL),
	(11,'Nunc sediam est',0,'Nunc sediam est',0,'Nunc sediam est',0,'nunc-sediam-est',0,NULL,NULL,NULL,NULL),
	(12,'Lorem ipsum',0,'Lorem ipsum',0,'Lorem ipsum',0,'lorem-ipsum',0,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `meta` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'unique module name',
  `installed_on` datetime NOT NULL,
  PRIMARY KEY (`name`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`name`, `installed_on`)
VALUES
	('Core','2021-08-26 17:39:35'),
	('Authentication','2021-08-26 17:39:35'),
	('Dashboard','2021-08-26 17:39:35'),
	('Error','2021-08-26 17:39:35'),
	('Locale','2021-08-26 17:39:35'),
	('Settings','2021-08-26 17:39:41'),
	('Users','2021-08-26 17:39:42'),
	('Groups','2021-08-26 17:39:43'),
	('Extensions','2021-08-26 17:39:44'),
	('Pages','2021-08-26 17:39:45'),
	('Search','2021-08-26 17:39:47'),
	('ContentBlocks','2021-08-26 17:39:47'),
	('Tags','2021-08-26 17:39:48'),
	('MediaLibrary','2021-08-26 17:39:48'),
	('Blog','2021-08-26 17:39:50');

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules_extras
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules_extras`;

CREATE TABLE `modules_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the extra.',
  `module` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'The name of the module this extra belongs to.',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The label for this extra. It will be used for displaying purposes.',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'A serialized value with the optional parameters',
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `sequence` int(11) NOT NULL COMMENT 'The sequence in the backend.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='The possible extras';

LOCK TABLES `modules_extras` WRITE;
/*!40000 ALTER TABLE `modules_extras` DISABLE KEYS */;

INSERT INTO `modules_extras` (`id`, `module`, `type`, `label`, `action`, `data`, `hidden`, `sequence`)
VALUES
	(1,'Search','widget','SearchForm','Form',NULL,0,0),
	(2,'Search','block','Search',NULL,NULL,0,1),
	(3,'Pages','widget','Sitemap','Sitemap',NULL,0,1000),
	(4,'Pages','widget','Subpages','Subpages','a:1:{s:8:\"template\";s:25:\"SubpagesDefault.html.twig\";}',0,1001),
	(5,'Pages','widget','Navigation','PreviousNextNavigation',NULL,0,1002),
	(6,'Blog','block','Blog',NULL,NULL,0,2000),
	(7,'Blog','widget','RecentComments','RecentComments',NULL,0,2001),
	(8,'Blog','widget','Categories','Categories',NULL,0,2002),
	(9,'Blog','widget','Archive','Archive',NULL,0,2003),
	(10,'Blog','widget','RecentArticlesFull','RecentArticlesFull',NULL,0,2004),
	(11,'Blog','widget','RelatedArticles','RelatedArticles',NULL,0,2005),
	(12,'Blog','widget','RecentArticlesList','RecentArticlesList',NULL,0,2006),
	(13,'Tags','block','Tags',NULL,NULL,0,3000),
	(14,'Tags','widget','TagCloud','TagCloud',NULL,0,3001),
	(15,'Tags','widget','Related','Related',NULL,0,3002);

/*!40000 ALTER TABLE `modules_extras` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules_settings`;

CREATE TABLE `modules_settings` (
  `module` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'name of the module',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'name of the setting',
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'serialized value',
  PRIMARY KEY (`module`(25),`name`(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `modules_settings` WRITE;
/*!40000 ALTER TABLE `modules_settings` DISABLE KEYS */;

INSERT INTO `modules_settings` (`module`, `name`, `value`)
VALUES
	('Core','languages','a:1:{i:0;s:2:\"en\";}'),
	('Core','active_languages','a:1:{i:0;s:2:\"en\";}'),
	('Core','redirect_languages','a:1:{i:0;s:2:\"en\";}'),
	('Core','default_language','s:2:\"en\";'),
	('Core','interface_languages','a:1:{i:0;s:2:\"en\";}'),
	('Core','default_interface_language','s:2:\"en\";'),
	('Core','theme','s:6:\"Custom\";'),
	('Core','akismet_key','s:0:\"\";'),
	('Core','google_maps_key','s:0:\"\";'),
	('Core','max_num_revisions','i:20;'),
	('Core','site_domains','a:1:{i:0;s:14:\"localhost:8000\";}'),
	('Core','site_html_head','s:0:\"\";'),
	('Core','site_html_end_of_body','s:0:\"\";'),
	('Core','date_format_short','s:5:\"j.n.Y\";'),
	('Core','date_formats_short','a:24:{i:0;s:5:\"j/n/Y\";i:1;s:5:\"j-n-Y\";i:2;s:5:\"j.n.Y\";i:3;s:5:\"n/j/Y\";i:4;s:5:\"n/j/Y\";i:5;s:5:\"n/j/Y\";i:6;s:5:\"d/m/Y\";i:7;s:5:\"d-m-Y\";i:8;s:5:\"d.m.Y\";i:9;s:5:\"m/d/Y\";i:10;s:5:\"m-d-Y\";i:11;s:5:\"m.d.Y\";i:12;s:5:\"j/n/y\";i:13;s:5:\"j-n-y\";i:14;s:5:\"j.n.y\";i:15;s:5:\"n/j/y\";i:16;s:5:\"n-j-y\";i:17;s:5:\"n.j.y\";i:18;s:5:\"d/m/y\";i:19;s:5:\"d-m-y\";i:20;s:5:\"d.m.y\";i:21;s:5:\"m/d/y\";i:22;s:5:\"m-d-y\";i:23;s:5:\"m.d.y\";}'),
	('Core','date_format_long','s:7:\"l j F Y\";'),
	('Core','date_formats_long','a:14:{i:0;s:5:\"j F Y\";i:1;s:7:\"D j F Y\";i:2;s:7:\"l j F Y\";i:3;s:6:\"j F, Y\";i:4;s:8:\"D j F, Y\";i:5;s:8:\"l j F, Y\";i:6;s:5:\"d F Y\";i:7;s:6:\"d F, Y\";i:8;s:5:\"F j Y\";i:9;s:7:\"D F j Y\";i:10;s:7:\"l F j Y\";i:11;s:6:\"F d, Y\";i:12;s:8:\"D F d, Y\";i:13;s:8:\"l F d, Y\";}'),
	('Core','time_format','s:3:\"H:i\";'),
	('Core','time_formats','a:4:{i:0;s:3:\"H:i\";i:1;s:5:\"H:i:s\";i:2;s:5:\"g:i a\";i:3;s:5:\"g:i A\";}'),
	('Core','number_format','s:11:\"dot_nothing\";'),
	('Core','number_formats','a:6:{s:13:\"comma_nothing\";s:8:\"10000,25\";s:11:\"dot_nothing\";s:8:\"10000.25\";s:9:\"dot_comma\";s:9:\"10,000.25\";s:9:\"comma_dot\";s:9:\"10.000,25\";s:9:\"dot_space\";s:8:\"10000.25\";s:11:\"comma_space\";s:9:\"10 000,25\";}'),
	('Core','mailer_from','a:2:{s:4:\"name\";s:8:\"Fork CMS\";s:5:\"email\";s:18:\"davy@sumocoders.be\";}'),
	('Core','mailer_to','a:2:{s:4:\"name\";s:8:\"Fork CMS\";s:5:\"email\";s:18:\"davy@sumocoders.be\";}'),
	('Core','mailer_reply_to','a:2:{s:4:\"name\";s:8:\"Fork CMS\";s:5:\"email\";s:18:\"davy@sumocoders.be\";}'),
	('Core','smtp_server','s:0:\"\";'),
	('Core','smtp_port','s:0:\"\";'),
	('Core','smtp_username','s:0:\"\";'),
	('Core','smtp_password','s:0:\"\";'),
	('Core','site_title_en','s:10:\"My website\";'),
	('Core','ckfinder_license_name','s:8:\"Fork CMS\";'),
	('Core','ckfinder_license_key','s:34:\"QFKH-MNCN-19A8-32XW-35GK-Q58G-UPMC\";'),
	('Core','show_consent_dialog','b:0;'),
	('Users','date_formats','a:4:{i:0;s:5:\"j/n/Y\";i:1;s:5:\"d/m/Y\";i:2;s:5:\"j F Y\";i:3;s:6:\"F j, Y\";}'),
	('Users','default_group','i:1;'),
	('Users','time_formats','a:4:{i:0;s:3:\"H:i\";i:1;s:5:\"H:i:s\";i:2;s:5:\"g:i a\";i:3;s:5:\"g:i A\";}'),
	('Pages','default_template','i:3;'),
	('Pages','meta_navigation','b:0;'),
	('Search','overview_num_items','i:10;'),
	('Search','validate_search','b:1;'),
	('ContentBlocks','max_num_revisions','i:20;'),
	('MediaLibrary','upload_number_of_sharding_folders','i:15;'),
	('Blog','allow_comments','b:1;'),
	('Blog','max_num_revisions','i:20;'),
	('Blog','moderation','b:1;'),
	('Blog','overview_num_items','i:10;'),
	('Blog','recent_articles_full_num_items','i:3;'),
	('Blog','recent_articles_list_num_items','i:5;'),
	('Blog','requires_akismet','b:1;'),
	('Blog','spamfilter','b:0;'),
	('Blog','rss_meta_en','b:1;'),
	('Blog','rss_title_en','s:3:\"RSS\";'),
	('Blog','rss_description_en','s:0:\"\";');

/*!40000 ALTER TABLE `modules_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table modules_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules_tags`;

CREATE TABLE `modules_tags` (
  `module` varchar(255) CHARACTER SET utf8 NOT NULL,
  `tag_id` int(11) NOT NULL,
  `other_id` int(11) NOT NULL,
  PRIMARY KEY (`module`,`tag_id`,`other_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL COMMENT 'the real page_id',
  `revision_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'which user has created this page?',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'the parent_id for the page ',
  `template_id` int(11) NOT NULL DEFAULT '0' COMMENT 'the template to use',
  `meta_id` int(11) NOT NULL COMMENT 'linked meta information',
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'language of the content',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'root' COMMENT 'page, header, footer, ...',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `navigation_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'title that will be used in the navigation',
  `navigation_title_overwrite` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'should we override the navigation title',
  `hidden` tinyint(1) NOT NULL DEFAULT '1',
  `status` varchar(243) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'is this the active, archive or draft version',
  `publish_on` datetime NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'serialized array that may contain type specific parameters',
  `created_on` datetime NOT NULL,
  `edited_on` datetime NOT NULL,
  `allow_move` tinyint(1) NOT NULL DEFAULT '1',
  `allow_children` tinyint(1) NOT NULL DEFAULT '1',
  `allow_edit` tinyint(1) NOT NULL DEFAULT '1',
  `allow_delete` tinyint(1) NOT NULL DEFAULT '1',
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`revision_id`),
  KEY `idx_id_status_hidden_language` (`id`,`status`,`hidden`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `revision_id`, `user_id`, `parent_id`, `template_id`, `meta_id`, `language`, `type`, `title`, `navigation_title`, `navigation_title_overwrite`, `hidden`, `status`, `publish_on`, `data`, `created_on`, `edited_on`, `allow_move`, `allow_children`, `allow_edit`, `allow_delete`, `sequence`)
VALUES
	(1,1,1,0,4,1,'en','page','Home','Home',0,0,'archive','2021-08-26 17:39:46','a:1:{s:5:\"image\";s:14:\"1629999586.jpg\";}','2021-08-26 17:39:46','2021-08-26 17:39:46',0,1,1,0,1),
	(2,2,1,0,3,2,'en','footer','Sitemap','Sitemap',0,0,'active','2021-08-26 17:39:46','a:1:{s:5:\"image\";s:14:\"1629999586.jpg\";}','2021-08-26 17:39:46','2021-08-26 17:39:46',1,1,1,1,1),
	(3,3,1,0,3,3,'en','footer','Disclaimer','Disclaimer',0,0,'active','2021-08-26 17:39:46','a:1:{s:5:\"image\";s:14:\"1629999586.jpg\";}','2021-08-26 17:39:46','2021-08-26 17:39:46',1,1,1,1,2),
	(404,4,1,0,5,4,'en','root','404','404',0,0,'active','2021-08-26 17:39:46','a:1:{s:5:\"image\";s:14:\"1629999586.jpg\";}','2021-08-26 17:39:46','2021-08-26 17:39:46',0,1,1,0,1),
	(4,5,1,1,3,5,'en','page','Modules','Modules',0,0,'active','2021-08-26 17:39:47','a:1:{s:5:\"image\";s:14:\"1629999587.jpg\";}','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1,1,1,1),
	(1,6,1,0,4,6,'en','page','Home','Home',0,0,'active','2021-08-26 17:39:47','a:1:{s:5:\"image\";s:14:\"1629999587.jpg\";}','2021-08-26 17:39:47','2021-08-26 17:39:47',0,1,1,0,2),
	(405,7,1,4,3,7,'en','page','Blog','Blog',0,0,'active','2021-08-26 17:39:47','a:1:{s:5:\"image\";s:14:\"1629999587.jpg\";}','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1,1,1,1),
	(406,8,1,4,3,8,'en','page','Tags','Tags',0,0,'active','2021-08-26 17:39:47','a:1:{s:5:\"image\";s:14:\"1629999587.jpg\";}','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1,1,1,2),
	(407,9,1,0,3,9,'en','root','Search','Search',0,0,'active','2021-08-26 17:39:47','a:1:{s:5:\"image\";s:14:\"1629999587.jpg\";}','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1,1,1,2);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages_blocks`;

CREATE TABLE `pages_blocks` (
  `revision_id` int(11) NOT NULL COMMENT 'The ID of the page that contains this block.',
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_id` int(11) DEFAULT NULL COMMENT 'The linked extra.',
  `extra_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'rich_text',
  `extra_data` text COLLATE utf8mb4_unicode_ci,
  `html` text COLLATE utf8mb4_unicode_ci COMMENT 'if this block is HTML this field should contain the real HTML.',
  `created_on` datetime NOT NULL,
  `edited_on` datetime NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `sequence` int(11) NOT NULL,
  KEY `idx_rev_status` (`revision_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pages_blocks` WRITE;
/*!40000 ALTER TABLE `pages_blocks` DISABLE KEYS */;

INSERT INTO `pages_blocks` (`revision_id`, `position`, `extra_id`, `extra_type`, `extra_data`, `html`, `created_on`, `edited_on`, `visible`, `sequence`)
VALUES
	(1,'main',NULL,'rich_text',NULL,'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam id magna. Proin euismod vestibulum tortor. Vestibulum eget nisl. Donec interdum quam at nunc. In laoreet orci sit amet sem. In sed metus ac nunc blandit ultricies. Maecenas sed tortor. Sed velit velit, mollis quis, ultricies tincidunt, dictum ac, felis. Integer hendrerit consectetur libero. Duis sem. Mauris tellus justo, sollicitudin at, vehicula eget, auctor vel, odio. Proin mattis. Mauris mollis elit sit amet lectus. Vestibulum in tortor sodales elit sollicitudin gravida. Integer scelerisque sollicitudin velit. Aliquam erat volutpat. Sed ut nisl congue justo pharetra accumsan.</p>','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(1,'top',1,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(2,'main',NULL,'rich_text',NULL,'<p>Take a look at all the pages in our website:</p>','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(2,'main',3,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,1),
	(2,'top',1,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(3,'main',NULL,'rich_text',NULL,'<p><strong>This website is property of [Bedrijfsnaam].</strong></p>\n<p><strong>Contact info:</strong><br />[Bedrijfsnaam]<br /> [Straatnaam] [Nummer]<br /> [Postcode] [Gemeente]</p>\n<p><strong>Adres maatschappelijk zetel:</strong><br />[Maatschappelijke zetel]<br /> [Straatnaam] [Nummer]<br /> [Postcode] [Gemeente]</p>\n<p>Telefoon:<br />E-mail:</p>\n<p>Ondernemingsnummer: BTW BE 0 [BTW-nummer]</p>\n<p>De toezichthoudende autoriteit: (wanneer uw activiteit aan een vergunningsstelsel is onderworpen)</p>\n<p>By accessing and using the website, you have expressly agreed to the following general conditions.</p>\n<h3>Intellectual property rights</h3>\n<p>The contents of this site, including trade marks, logos, drawings, data, product or company names, texts, images, etc. are protected by intellectual property rights and belong to [Bedrijfsnaam] or entitled third parties.</p>\n<h3>Liability limitation</h3>\n<p>The information on the website is general in nature. It is not adapted to personal or specific circumstances and can therefore not be regarded as personal, professional or judicial advice for the user.</p>\n<p>[Bedrijfsnaam] does everything in its power to ensure that the information made available is complete, correct, accurate and updated. However, despite these efforts inaccuracies may occur when providing information. If the information provided contains inaccuracies or if specific information on or via the site is unavailable, [Bedrijfsnaam] shall make the greatest effort to ensure that this is rectified as soon as possible.</p>\n<p>[Bedrijfsnaam] cannot be held responsible for direct or indirect damage caused by the use of the information on this site.&nbsp;  The site manager should be contacted if the user has noticed any inaccuracies in the information provided by the site.</p>\n<p>The contents of the site (including links) may be adjusted, changed or extended at any time without any announcement or advance notice. [Bedrijfsnaam] gives no guarantees for the smooth operation of the website and cannot be held responsible in any way for the poor operation or temporary unavailability of the website or for any type of damage, direct or indirect, which may occur due to the access to or use of the website.</p>\n<p>[Bedrijfsnaam] can in no case be held liable, directly or indirectly, specifically or otherwise, vis-&agrave;-vis anyone for any damage attributable to the use of this site or any other one, in particular as the result of links or hyperlinks including, but not limited to, any loss, work interruption, damage of the user&rsquo;s programs or other data on the computer system, hardware, software or otherwise.</p>\n<p>The website may contain hyperlinks to websites or pages of third parties or refer to these indirectly. The placing of links on these websites or pages shall not imply in any way the implicit approval of the contents thereof.&nbsp;  [Bedrijfsnaam] expressly declares that it has no authority over the contents or over other features of these websites and can in no case be held responsible for the contents or features thereof or for any other type of damage resulting from their use.</p>\n<h3>Applicable legislation and competent courts</h3>\n<p>This site is governed by Belgian law. Only the courts of the district of Ghent are competent to settle any disputes.</p>\n<h3>Privacy policy</h3>\n<p>[Bedrijfsnaam] believes that your privacy is important. While most of the information on this site is available without having to ask the user for personal information,&nbsp; the user may be asked for some personal details.&nbsp;&nbsp; This information will only be used to ensure a better service.&nbsp;&nbsp; (e.g. for our customer database, to keep users informed of our activities, etc.). The user may, free of charge and on request, always prevent the use of his personal details for the purposes of direct marketing. In this regard, the user should contact [Bedrijfsnaam], [Adres bedrijf] or via [Email adres bedrijf]. Your personal details will never been transferred to any third parties (if this should occur, you will be informed).</p>\n<p>In accordance with the law on the processing of personal data of 8 December 1992, the user has the legal right to examine and possibly correct any of his/her personal details. Subject to proof of identity (copy of the user&rsquo;s identity card), you can via a written, dated and signed request to [Bedrijfsnaam], [Adres bedrijf] or via [Email adres bedrijf], receive free of charge a written statement of the user&rsquo;s personal details.&nbsp; If necessary, you may also ask for any incorrect, incomplete or irrelevant data to be adjusted.</p>\n<p>[Bedrijfsnaam] can collect non-personal anonymous or aggregate data such as browser type, IP address or operating system in use or the domain name of the website that led you to and from our website, ensuring optimum effectiveness of our website for all users.</p>\n<h3>The use of cookies</h3>\n<p>During a visit to the site, cookies may be placed on the hard drive of your computer. This is only in order to ensure that our site is geared to the needs of users returning to our website. These tiny files known as cookies are not used to ascertain the surfing habits of the visitor on other websites. Your internet browser enables you to disable these cookies, receive a warning when a cookie has been installed or have the cookies removed from your hard disc.&nbsp; For this purpose, consult the help function of your internet browser.</p>','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(3,'top',1,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(4,'main',NULL,'rich_text',NULL,'<div id=\"error\">\n<p>This page doesn\'t exist or is not accessible at this time.</p>\n<a href=\"/\">Go to home</a>\n<h2>404</h2>\n</div>\n','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(4,'main',3,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,1),
	(4,'top',1,'rich_text',NULL,'','2021-08-26 17:39:46','2021-08-26 17:39:46',1,0),
	(5,'main',4,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(5,'top',1,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(6,'main',NULL,'rich_text',NULL,'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam id magna. Proin euismod vestibulum tortor. Vestibulum eget nisl. Donec interdum quam at nunc. In laoreet orci sit amet sem. In sed metus ac nunc blandit ultricies. Maecenas sed tortor. Sed velit velit, mollis quis, ultricies tincidunt, dictum ac, felis. Integer hendrerit consectetur libero. Duis sem. Mauris tellus justo, sollicitudin at, vehicula eget, auctor vel, odio. Proin mattis. Mauris mollis elit sit amet lectus. Vestibulum in tortor sodales elit sollicitudin gravida. Integer scelerisque sollicitudin velit. Aliquam erat volutpat. Sed ut nisl congue justo pharetra accumsan.</p>','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(6,'main',12,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1),
	(6,'main',7,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,2),
	(6,'top',1,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(7,'main',6,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(7,'main',7,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,1),
	(7,'main',8,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,2),
	(7,'main',9,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,3),
	(7,'main',12,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,4),
	(7,'top',1,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(8,'main',13,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(8,'top',1,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0),
	(9,'main',2,'rich_text',NULL,'','2021-08-26 17:39:47','2021-08-26 17:39:47',1,0);

/*!40000 ALTER TABLE `pages_blocks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table search_index
# ------------------------------------------------------------

DROP TABLE IF EXISTS `search_index`;

CREATE TABLE `search_index` (
  `module` varchar(255) CHARACTER SET utf8 NOT NULL,
  `other_id` int(11) NOT NULL,
  `field` varchar(64) CHARACTER SET utf8 NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) CHARACTER SET utf8 NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module`,`other_id`,`field`,`language`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Search index';

LOCK TABLES `search_index` WRITE;
/*!40000 ALTER TABLE `search_index` DISABLE KEYS */;

INSERT INTO `search_index` (`module`, `other_id`, `field`, `value`, `language`, `active`)
VALUES
	('Pages',2,'title','Take a look at all the pages in our website:  ','en',1),
	('Pages',3,'title','This website is property of [Bedrijfsnaam].\nContact info:[Bedrijfsnaam] [Straatnaam] [Nummer] [Postcode] [Gemeente]\nAdres maatschappelijk zetel:[Maatschappelijke zetel] [Straatnaam] [Nummer] [Postcode] [Gemeente]\nTelefoon:E-mail:\nOndernemingsnummer: BTW BE 0 [BTW-nummer]\nDe toezichthoudende autoriteit: (wanneer uw activiteit aan een vergunningsstelsel is onderworpen)\nBy accessing and using the website, you have expressly agreed to the following general conditions.\nIntellectual property rights\nThe contents of this site, including trade marks, logos, drawings, data, product or company names, texts, images, etc. are protected by intellectual property rights and belong to [Bedrijfsnaam] or entitled third parties.\nLiability limitation\nThe information on the website is general in nature. It is not adapted to personal or specific circumstances and can therefore not be regarded as personal, professional or judicial advice for the user.\n[Bedrijfsnaam] does everything in its power to ensure that the information made available is complete, correct, accurate and updated. However, despite these efforts inaccuracies may occur when providing information. If the information provided contains inaccuracies or if specific information on or via the site is unavailable, [Bedrijfsnaam] shall make the greatest effort to ensure that this is rectified as soon as possible.\n[Bedrijfsnaam] cannot be held responsible for direct or indirect damage caused by the use of the information on this site.&nbsp;  The site manager should be contacted if the user has noticed any inaccuracies in the information provided by the site.\nThe contents of the site (including links) may be adjusted, changed or extended at any time without any announcement or advance notice. [Bedrijfsnaam] gives no guarantees for the smooth operation of the website and cannot be held responsible in any way for the poor operation or temporary unavailability of the website or for any type of damage, direct or indirect, which may occur due to the access to or use of the website.\n[Bedrijfsnaam] can in no case be held liable, directly or indirectly, specifically or otherwise, vis-&agrave;-vis anyone for any damage attributable to the use of this site or any other one, in particular as the result of links or hyperlinks including, but not limited to, any loss, work interruption, damage of the user&rsquo;s programs or other data on the computer system, hardware, software or otherwise.\nThe website may contain hyperlinks to websites or pages of third parties or refer to these indirectly. The placing of links on these websites or pages shall not imply in any way the implicit approval of the contents thereof.&nbsp;  [Bedrijfsnaam] expressly declares that it has no authority over the contents or over other features of these websites and can in no case be held responsible for the contents or features thereof or for any other type of damage resulting from their use.\nApplicable legislation and competent courts\nThis site is governed by Belgian law. Only the courts of the district of Ghent are competent to settle any disputes.\nPrivacy policy\n[Bedrijfsnaam] believes that your privacy is important. While most of the information on this site is available without having to ask the user for personal information,&nbsp; the user may be asked for some personal details.&nbsp;&nbsp; This information will only be used to ensure a better service.&nbsp;&nbsp; (e.g. for our customer database, to keep users informed of our activities, etc.). The user may, free of charge and on request, always prevent the use of his personal details for the purposes of direct marketing. In this regard, the user should contact [Bedrijfsnaam], [Adres bedrijf] or via [Email adres bedrijf]. Your personal details will never been transferred to any third parties (if this should occur, you will be informed).\nIn accordance with the law on the processing of personal data of 8 December 1992, the user has the legal right to examine and possibly correct any of his/her personal details. Subject to proof of identity (copy of the user&rsquo;s identity card), you can via a written, dated and signed request to [Bedrijfsnaam], [Adres bedrijf] or via [Email adres bedrijf], receive free of charge a written statement of the user&rsquo;s personal details.&nbsp; If necessary, you may also ask for any incorrect, incomplete or irrelevant data to be adjusted.\n[Bedrijfsnaam] can collect non-personal anonymous or aggregate data such as browser type, IP address or operating system in use or the domain name of the website that led you to and from our website, ensuring optimum effectiveness of our website for all users.\nThe use of cookies\nDuring a visit to the site, cookies may be placed on the hard drive of your computer. This is only in order to ensure that our site is geared to the needs of users returning to our website. These tiny files known as cookies are not used to ascertain the surfing habits of the visitor on other websites. Your internet browser enables you to disable these cookies, receive a warning when a cookie has been installed or have the cookies removed from your hard disc.&nbsp; For this purpose, consult the help function of your internet browser. ','en',1),
	('Pages',404,'title','\nThis page doesn\'t exist or is not accessible at this time.\nGo to home\n404\n\n  ','en',1),
	('Pages',4,'title',' ','en',1),
	('Pages',1,'title','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam id magna. Proin euismod vestibulum tortor. Vestibulum eget nisl. Donec interdum quam at nunc. In laoreet orci sit amet sem. In sed metus ac nunc blandit ultricies. Maecenas sed tortor. Sed velit velit, mollis quis, ultricies tincidunt, dictum ac, felis. Integer hendrerit consectetur libero. Duis sem. Mauris tellus justo, sollicitudin at, vehicula eget, auctor vel, odio. Proin mattis. Mauris mollis elit sit amet lectus. Vestibulum in tortor sodales elit sollicitudin gravida. Integer scelerisque sollicitudin velit. Aliquam erat volutpat. Sed ut nisl congue justo pharetra accumsan.   ','en',1),
	('Pages',405,'title','     ','en',1),
	('Pages',406,'title',' ','en',1),
	('Pages',407,'title','','en',1),
	('Blog',1,'title','Nunc sediam est','en',1),
	('Blog',1,'text','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\n\n    Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.\n    Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.\n    Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n','en',1),
	('Blog',2,'title','Lorem ipsum','en',1),
	('Blog',2,'text','Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\n\n    Consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.\n    Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit.\n    Esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n','en',1);

/*!40000 ALTER TABLE `search_index` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table search_modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `search_modules`;

CREATE TABLE `search_modules` (
  `module` varchar(255) CHARACTER SET utf8 NOT NULL,
  `searchable` tinyint(1) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `search_modules` WRITE;
/*!40000 ALTER TABLE `search_modules` DISABLE KEYS */;

INSERT INTO `search_modules` (`module`, `searchable`, `weight`)
VALUES
	('Pages',1,1),
	('Blog',1,1);

/*!40000 ALTER TABLE `search_modules` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table search_statistics
# ------------------------------------------------------------

DROP TABLE IF EXISTS `search_statistics`;

CREATE TABLE `search_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` datetime NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `num_results` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table search_synonyms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `search_synonyms`;

CREATE TABLE `search_synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(245) COLLATE utf8mb4_unicode_ci NOT NULL,
  `synonym` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`term`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table themes_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `themes_templates`;

CREATE TABLE `themes_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the template.',
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The name of the theme.',
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The label for the template, will be used for displaying purposes.',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Filename for the template.',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Is this template active (as in: will it be used).',
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'A serialized array with data that is specific for this template (eg.: names for the blocks).',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='The possible templates';

LOCK TABLES `themes_templates` WRITE;
/*!40000 ALTER TABLE `themes_templates` DISABLE KEYS */;

INSERT INTO `themes_templates` (`id`, `theme`, `label`, `path`, `active`, `data`)
VALUES
	(1,'Fork','Default','Core/Layout/Templates/Default.html.twig',1,'a:3:{s:6:\"format\";s:26:\"[/,/,top],[main,main,main]\";s:5:\"image\";b:1;s:5:\"names\";a:2:{i:0;s:4:\"main\";i:1;s:3:\"top\";}}'),
	(2,'Fork','Home','Core/Layout/Templates/Home.html.twig',1,'a:3:{s:6:\"format\";s:26:\"[/,/,top],[main,main,main]\";s:5:\"image\";b:1;s:5:\"names\";a:2:{i:0;s:4:\"main\";i:1;s:3:\"top\";}}'),
	(3,'Custom','Default','Core/Layout/Templates/Default.html.twig',1,'a:3:{s:6:\"format\";s:34:\"[/,/,/,top,/],[/,main,main,main,/]\";s:5:\"names\";a:2:{i:0;s:4:\"main\";i:1;s:3:\"top\";}s:14:\"default_extras\";a:1:{s:3:\"top\";a:1:{i:0;i:1;}}}'),
	(4,'Custom','Home','Core/Layout/Templates/Home.html.twig',1,'a:3:{s:6:\"format\";s:34:\"[/,/,/,top,/],[/,main,main,main,/]\";s:5:\"names\";a:2:{i:0;s:4:\"main\";i:1;s:3:\"top\";}s:14:\"default_extras\";a:1:{s:3:\"top\";a:1:{i:0;i:1;}}}'),
	(5,'Custom','Error','Core/Layout/Templates/Error.html.twig',1,'a:3:{s:6:\"format\";s:34:\"[/,/,/,top,/],[/,main,main,main,/]\";s:5:\"names\";a:2:{i:0;s:4:\"main\";i:1;s:3:\"top\";}s:14:\"default_extras\";a:1:{s:3:\"top\";a:1:{i:0;i:1;}}}');

/*!40000 ALTER TABLE `themes_templates` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'will be case-sensitive',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is this user active?',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is the user deleted?',
  `is_god` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='The backend users';

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `email`, `password`, `active`, `deleted`, `is_god`)
VALUES
	(1,'davy@sumocoders.be','$2y$10$3zTmtYSJq80EuiN6l7A.lOXfeZCNGFsVPyaYaqRdI2k81VBmBQAV2',1,0,1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;

INSERT INTO `users_groups` (`group_id`, `user_id`)
VALUES
	(1,1);

/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_sessions`;

CREATE TABLE `users_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_session_id_secret_key` (`session_id`(100),`secret_key`(100))
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users_sessions` WRITE;
/*!40000 ALTER TABLE `users_sessions` DISABLE KEYS */;

INSERT INTO `users_sessions` (`id`, `user_id`, `session_id`, `secret_key`, `date`)
VALUES
	(1,1,'2rg194d0abnmmd0gi63pt0p4so','365da5c997e9fc8bc29f92badb1be22040994c72','2021-09-01 13:17:03');

/*!40000 ALTER TABLE `users_sessions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_settings`;

CREATE TABLE `users_settings` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'name of the setting',
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'serialized value',
  PRIMARY KEY (`user_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users_settings` WRITE;
/*!40000 ALTER TABLE `users_settings` DISABLE KEYS */;

INSERT INTO `users_settings` (`user_id`, `name`, `value`)
VALUES
	(1,'nickname','s:8:\"Fork CMS\";'),
	(1,'name','s:4:\"Fork\";'),
	(1,'preferred_editor','s:9:\"ck-editor\";'),
	(1,'surname','s:3:\"CMS\";'),
	(1,'interface_language','s:2:\"en\";'),
	(1,'date_format','s:5:\"j F Y\";'),
	(1,'time_format','s:3:\"H:i\";'),
	(1,'datetime_format','s:9:\"j F Y H:i\";'),
	(1,'number_format','s:11:\"dot_nothing\";'),
	(1,'password_key','s:23:\"6127d1df1ff8f9.65747314\";'),
	(1,'password_strength','s:7:\"average\";'),
	(1,'current_password_change','i:1629999583;'),
	(1,'avatar','s:7:\"god.jpg\";'),
	(1,'csv_split_character','s:1:\";\";'),
	(1,'csv_line_ending','s:2:\"\\n\";'),
	(1,'dashboard_sequence','a:3:{s:8:\"Settings\";a:1:{i:0;s:7:\"Analyse\";}s:5:\"Users\";a:1:{i:0;s:10:\"Statistics\";}s:4:\"Blog\";a:1:{i:0;s:8:\"Comments\";}}'),
	(1,'current_login','s:10:\"1630502159\";');

/*!40000 ALTER TABLE `users_settings` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
