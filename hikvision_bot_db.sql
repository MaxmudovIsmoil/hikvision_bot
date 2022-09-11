/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100424
 Source Host           : localhost:3306
 Source Schema         : hikvision_bot_db

 Target Server Type    : MySQL
 Target Server Version : 100424
 File Encoding         : 65001

 Date: 08/09/2022 16:29:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_tasks_table', 1);
INSERT INTO `migrations` VALUES (3, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (5, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (6, '2022_06_27_144012_create_user_tasks_table', 1);
INSERT INTO `migrations` VALUES (7, '2022_08_16_141902_create_task_dones_table', 1);
INSERT INTO `migrations` VALUES (9, '2014_10_12_000000_create_users_table', 2);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for task_dones
-- ----------------------------
DROP TABLE IF EXISTS `task_dones`;
CREATE TABLE `task_dones`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` int NOT NULL COMMENT '1 done; 0 failed',
  `confirmation_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of task_dones
-- ----------------------------

-- ----------------------------
-- Table structure for tasks
-- ----------------------------
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remind_time` time(0) NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tasks
-- ----------------------------
INSERT INTO `tasks` VALUES (1, 'Ишга келиш', '08:00:00', '2022-08-20 11:42:56', '2022-08-26 12:23:17');
INSERT INTO `tasks` VALUES (2, 'Насиялар билан алока', '10:00:00', '2022-08-20 11:51:22', '2022-08-26 12:23:28');
INSERT INTO `tasks` VALUES (4, 'Иш столи озодалиги', '08:30:00', '2022-08-26 12:23:54', '2022-08-26 12:23:54');
INSERT INTO `tasks` VALUES (5, 'Камере ветрина тулдириш', '11:00:00', '2022-08-26 12:24:02', '2022-08-26 12:24:02');
INSERT INTO `tasks` VALUES (6, 'Вилоятлардан янги 1 та контрагент топиш', '11:30:00', '2022-08-26 12:24:31', '2022-08-26 12:24:31');
INSERT INTO `tasks` VALUES (7, 'Китоб укиш 15-30 минут', '12:00:00', '2022-08-26 12:24:43', '2022-08-26 12:24:43');
INSERT INTO `tasks` VALUES (8, 'Йечилган насияларни телеграмдан юбориш', '14:00:00', '2022-08-26 12:24:53', '2022-08-26 12:24:53');
INSERT INTO `tasks` VALUES (9, 'Эрталабки фото отчёт', '08:45:00', '2022-08-26 12:25:02', '2022-08-26 12:25:02');
INSERT INTO `tasks` VALUES (10, 'Кечги видео отчёт', '15:00:00', '2022-08-26 12:25:09', '2022-08-26 12:25:09');
INSERT INTO `tasks` VALUES (11, 'Котта канал силкасини 10 одамга жунатиш', '15:30:00', '2022-08-26 12:25:26', '2022-08-26 12:25:26');
INSERT INTO `tasks` VALUES (12, 'Камколган таварла хакида малумот бериш', '16:00:00', '2022-08-26 12:25:37', '2022-08-26 12:25:37');
INSERT INTO `tasks` VALUES (13, 'test22', '20:40:00', '2022-09-06 13:38:04', '2022-09-06 13:38:25');

-- ----------------------------
-- Table structure for user_tasks
-- ----------------------------
DROP TABLE IF EXISTS `user_tasks`;
CREATE TABLE `user_tasks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `task_id` bigint UNSIGNED NOT NULL,
  `remind_time` time(0) NOT NULL,
  `start_day` date NOT NULL,
  `end_day` date NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`, `task_id`) USING BTREE,
  INDEX `task_id`(`task_id`) USING BTREE,
  CONSTRAINT `user_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `user_tasks_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_tasks
-- ----------------------------
INSERT INTO `user_tasks` VALUES (1, 1, 1, '28:03:44', '2022-08-01', '2022-08-02', 'test', NULL, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` int NOT NULL,
  `job_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule` enum('ADMIN','USER') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Administration', 'admin', '$2y$10$Rvg6J1/ufa1wHD3bLWcUFO/WrPyaCu5FFYACEE9mrsYf1Ds4qvOjm', '+998332087090', 1, 'Admin', '1', 'ADMIN', 'admin@gmail.com', NULL, NULL, '2022-08-20 10:35:33', '2022-08-20 10:35:33', NULL);
INSERT INTO `users` VALUES (2, 'Усманова Клара', '', '$2y$10$2VkAO1TcJqcAm826kFxAL.Cue6SDUr5/vWKwWBFpIcGx/QzmMwzne', '+998903546770', 1, 'sotuvchi', '1', 'USER', 'УсмановаКлара@gmail.uz', NULL, NULL, '2022-08-20 10:45:20', '2022-08-23 07:21:33', NULL);
INSERT INTO `users` VALUES (4, 'Susanna Tevosova', 'test4', '$2y$10$u8/972y928LKN0UlN4J7au7ZKiJ8Wg81WrnbRoFyA9mlVDc.llWe6', '+998903711290', 1, 'mutahasiss', '1', 'USER', 'test1@gmail.uz', NULL, NULL, '2022-08-20 10:50:02', '2022-08-23 07:21:49', NULL);
INSERT INTO `users` VALUES (6, 'Рахимова Рушана', 'test6', '$2y$10$49hcEtrLms8.2iwJ.FXHeOOC/s06PYSe7VjoXtAwNqeO.9O40bu1S', '+998781500116', 1, 'muhandis', '0', 'USER', 'test1660992682@gmail.uz', NULL, NULL, '2022-08-20 10:51:22', '2022-08-23 07:21:24', NULL);
INSERT INTO `users` VALUES (8, 'Убайдуллаев Акбар', 'test3', '$2y$10$IdQI7gBgNddZaUnAI1EdqOoIoGxgohszyubXymK.pc4fvfz8xr2fy', '+998903546770', 1, 'mutahasiss', '1', 'USER', 'test1660992712@gmail.uz', NULL, NULL, '2022-08-20 10:51:52', '2022-08-23 07:22:28', NULL);
INSERT INTO `users` VALUES (11, 'Aliyev Sardor', 'test10', '$2y$10$F2sSEueCMgKypMxko9fR5ex1LFa5B0sCSc42SYFT2P/pV8bCYZ7R2', '+998903711290', 1, 'mutahasis', '1', 'USER', 'test1661516345@gmail.uz', NULL, NULL, '2022-08-26 12:19:05', '2022-08-26 12:19:05', NULL);
INSERT INTO `users` VALUES (14, 'Ergashev Odiljon', 'test1661516442', '$2y$10$3bbeTE57H9AY03MyMHCbuOfb/TgJaiQvIdGeqysPBmL4dQse2Npa2', '+998903711213', 1, 'mutahasiss yordamchisi', '1', 'USER', 'test1661516442@gmail.uz', NULL, NULL, '2022-08-26 12:20:42', '2022-08-26 12:20:42', NULL);
INSERT INTO `users` VALUES (15, 'Qurbonov Qahramon', 'test1661516473', '$2y$10$BdiYfuEieY94/v9LODwwrenpbAqixh/Rx2SYGKuGnILlVt30UdPAy', '+998903711290', 1, 'mutahasiss', '1', 'USER', 'test1661516473@gmail.uz', NULL, NULL, '2022-08-26 12:21:13', '2022-08-26 12:26:54', NULL);
INSERT INTO `users` VALUES (16, 'Tohirov Shohruh', 'test1661516487', '$2y$10$byxZD4WXaH.rj.vdY..6cecMVwxQfnOsy7qg4HEwHiylcQp1Tin3y', '+998903546770', 1, 'muhandis yordamchisi', '1', 'USER', 'test1661516487@gmail.uz', NULL, NULL, '2022-08-26 12:21:27', '2022-08-26 12:27:36', NULL);

SET FOREIGN_KEY_CHECKS = 1;
