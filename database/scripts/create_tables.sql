--
-- Table structure for table `tbl_user_master`
--

CREATE TABLE `tbl_user_master` (
      `user_id` int(11) NOT NULL AUTO_INCREMENT,
      `email_id` varchar(128) NOT NULL COMMENT 'Registered email id',
      `remitter_id` int(11) NOT NULL COMMENT 'FK into tbl_remitter_master',
      `password` varchar(255) NOT NULL,
      `first_name` varchar(64) NOT NULL,
      `last_name` varchar(64) NOT NULL,
      `status` tinyint(4) NOT NULL COMMENT '0=Inactive, 1=Active, 2=Suspended, 3=Deleted',
      `created_on_date` datetime NOT NULL,
      `status_change_date` datetime NOT NULL,
      PRIMARY KEY (user_id),
      FOREIGN KEY (remitter_id) REFERENCES tbl_remitter_master(remitter_id),
      UNIQUE(email_id)  
);

--
-- Table structure for table `tbl_remitter_master`
--

CREATE TABLE `tbl_remitter_master` (
      `remitter_id` int(11) NOT NULL COMMENT 'Random 6-digit id',
      `remitter_name` varchar(64) NOT NULL,
      `service_type` smallint(6) NOT NULL,
      `country_iso3_code` varchar(6) NOT NULL,
      `master_password` varchar(16) NOT NULL,
      `api_key` varchar(128) NOT NULL,
      `status` tinyint(4) NOT NULL COMMENT '0=Inactive, 1=Active, 2=Suspended, 3=Deleted'
      `created_on_date` datetime NOT NULL,
      `status_change_date` datetime NOT NULL,
      PRIMARY KEY (`remitter_id`)
);

--
-- Table structure for table `tbl_remitter_partners`
--

CREATE TABLE `tbl_remitter_partners` (
      `remitter_id` int(11) NOT NULL,
      `partner_remitter_id` int(11) NOT NULL,
      `created_on_date` datetime NOT NULL,
      `status` tinyint(4) NOT NULL COMMENT '0=Inactive, 1=Active',
      `status_change_date` datetime NOT NULL
);
