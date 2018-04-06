
DROP TABLE IF EXISTS `observers`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `comment_sources`;

CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(255) DEFAULT NULL,
    `user_hash` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `comment_sources` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `comment_hash` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `comment_source_id` int(11) unsigned NOT NULL,
    `body` text NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `comments_comment_sources_fk` (`comment_source_id`),
    KEY `commens_users_fk` (`user_id`),
    CONSTRAINT `commens_users_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `comments_comment_sources_fk` FOREIGN KEY (`comment_source_id`) REFERENCES `comment_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `observers` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `observer_key` varchar(55) NOT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;