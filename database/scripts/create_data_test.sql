--
-- Create data for table `tbl_remitter_master`
--

INSERT INTO `tbl_remitter_master` VALUES (594629,'Bangla Bank Ltd.',2,'BGD','0pwzrfpgjX','6ad65576-f29a-4f8e-9846-505e94b1ceea',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (760613,'Banco Pico Mesa',2,'MEX','h40LVQJGju','df05d6ad-6a92-432c-96c9-95b8acda89a2',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (922196,'Rhyloo Money Pay',2,'PHL','xSTCSIze48','fa56801c-40dc-4c26-9e9e-4815dd2c4222',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (506863,'Yozu Bank Corp.',2,'CHN','EpqWvMIjKY','be9b5d06-b0c9-47e8-ba50-66cde4382e9b',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (642247,'Rupaisa Lenders P. Ltd.',2,'IND','5vQCxJG7VC','121e4e51-9408-4c7d-b1f3-c9d0ca97acc2',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (505113,'Zoomia Dinero SL',3,'ESP','gQJP6G1Z3c','aa8263b5-4437-4136-8a49-128b0d2e20be',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (164831,'Tanyx Express SEL',1,'ITA','0WWYKHsFhD','b579b5c5-771f-4a50-8d2c-668b17350dea',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (475246,'Quadel Wires S.A.R.L.',1,'FRA','4tj9bO15Md','691365c7-6e94-4709-9c21-759c01ba83cf',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (516712,'Agible Wire Transfers Inc.',1,'USA','d2SPrOm8EQ','7e7a19ac-1d92-4d9b-9c4d-75ffde9c3bbb',1,'0000-00-00 00:00:00');
INSERT INTO `tbl_remitter_master` VALUES (443444,'Eiboo Money Txfr P. Ltd.',1,'GBR','uMOS2Ikbgb','3786ea26-014c-4d87-9b79-76b16d80c634',1,'0000-00-00 00:00:00');


--
-- Create data for table `remitters`
--

INSERT INTO `remitters` VALUES (null,594629,'Bangla Bank Ltd.','0pwzrfpgjX','6ad65576-f29a-4f8e-9846-505e94b1ceea','BGD',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,760613,'Banco Pico Mesa','h40LVQJGju','df05d6ad-6a92-432c-96c9-95b8acda89a2','MEX',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,922196,'Rhyloo Money Pay','xSTCSIze48','fa56801c-40dc-4c26-9e9e-4815dd2c4222','PHL',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,506863,'Yozu Bank Corp.','EpqWvMIjKY','be9b5d06-b0c9-47e8-ba50-66cde4382e9b','CHN',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,642247,'Rupaisa Lenders P. Ltd.','5vQCxJG7VC','121e4e51-9408-4c7d-b1f3-c9d0ca97acc2','IND',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,505113,'Zoomia Dinero SL','gQJP6G1Z3c','aa8263b5-4437-4136-8a49-128b0d2e20be','ESP',3,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,164831,'Tanyx Express SEL','0WWYKHsFhD','b579b5c5-771f-4a50-8d2c-668b17350dea','ITA',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,475246,'Quadel Wires S.A.R.L.','4tj9bO15Md','691365c7-6e94-4709-9c21-759c01ba83cf','FRA',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,516712,'Agible Wire Transfers Inc.','d2SPrOm8EQ','7e7a19ac-1d92-4d9b-9c4d-75ffde9c3bbb','USA',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');
INSERT INTO `remitters` VALUES (null,443444,'Eiboo Money Txfr P. Ltd.','uMOS2Ikbgb','3786ea26-014c-4d87-9b79-76b16d80c634','GBR',2,1,'0000-00-00 00:00:00','000-00-00 00:00:00');

--
-- Create data for table `users`
--

USE LARAVEL SEEDING SINCE BCRYPT IS REQUIRED

--
-- Create data for table `tbl_remitter_partners`
--

INSERT INTO `tbl_remitter_partners` VALUES (516712,505113,'2015-11-20 17:32:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (516712,642247,'2015-11-20 17:33:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (516712,506863,'2015-11-20 17:34:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (516712,922196,'2015-11-20 17:35:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (516712,760613,'2015-11-20 17:36:00',0,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (516712,594629,'2015-11-20 17:37:00',0,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (443444,642247,'2015-11-20 17:38:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (443444,594629,'2015-11-20 17:39:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (475246,506863,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (164831,506863,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (505113,760613,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (164831,922196,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (642247,516712,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (642247,443444,'2015-11-20 17:40:00',1,'2015-11-20 17:40:00');
INSERT INTO `tbl_remitter_partners` VALUES (164831,505113,'2015-12-06 03:47:43',1,'2015-12-06 03:47:43');
INSERT INTO `tbl_remitter_partners` VALUES (505113,516712,'2015-12-06 05:05:59',1,'2015-12-06 05:05:59');
INSERT INTO `tbl_remitter_partners` VALUES (506863,516712,'2015-12-06 05:06:19',1,'2015-12-06 05:06:19');
INSERT INTO `tbl_remitter_partners` VALUES (922196,516712,'2015-12-06 05:06:33',1,'2015-12-06 05:06:33');
INSERT INTO `tbl_remitter_partners` VALUES (443444,506863,'2015-12-16 12:56:07',1,'2015-12-16 12:56:07');
INSERT INTO `tbl_remitter_partners` VALUES (506863,443444,'2015-12-16 12:57:18',1,'2015-12-16 12:57:18');
