-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: thedb.smarterthanthat.com
-- Generation Time: Sep 16, 2011 at 01:55 PM
-- Server version: 5.1.53
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `dbnova`
--

-- --------------------------------------------------------

--
-- Table structure for table `nova_awe_saved_reports`
--

CREATE TABLE IF NOT EXISTS `nova_awe_saved_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_name` text NOT NULL,
  `report_date_start` int(11) NOT NULL,
  `report_date_end` int(11) NOT NULL,
  `report_author` int(11) NOT NULL,
  `report_data` text NOT NULL,
  `report_status` text NOT NULL,
  `report_saved_date` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Table structure for table `nova_awe_sections`
--

CREATE TABLE IF NOT EXISTS `nova_awe_sections` (
  `section_id` int(11) NOT NULL AUTO_INCREMENT,
  `section_name` text NOT NULL,
  `section_title` text NOT NULL,
  `section_default` text NOT NULL,
  `section_added_user` int(11) NOT NULL,
  `section_added_date` int(11) NOT NULL,
  `section_last_edit_date` int(11) NOT NULL,
  `section_last_edit_user` int(11) NOT NULL,
  `section_userdefined` int(11) NOT NULL,
  PRIMARY KEY (`section_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `nova_awe_sections`
--

INSERT INTO `nova_awe_sections` (`section_id`, `section_name`, `section_title`, `section_default`, `section_added_user`, `section_added_date`, `section_last_edit_date`, `section_last_edit_user`, `section_userdefined`) VALUES
(8, 'Chain of Command', 'Chain of Command', '%coc%', 1, 1, 0, 0, 0),
(5, 'Report Date', 'Report Date', '%reporttime%', 1, 1, 1315574867, 1, 0),
(9, 'Reporting Officer', 'Reporting Officer', '%repofficer%', 1, 1, 0, 0, 0),
(11, 'Roster', 'Roster', '%roster%', 1, 1, 1315583512, 1, 0),
(15, 'Statistics', 'Statistics', '%stats%', 1, 0, 0, 0, 0),
(16, 'Sim Time', 'Sim Time and Duration', '%simtime%', 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `nova_awe_section_order`
--

CREATE TABLE IF NOT EXISTS `nova_awe_section_order` (
  `section_id` int(11) NOT NULL,
  `section_order` int(11) NOT NULL,
  PRIMARY KEY (`section_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nova_awe_section_order`
--

INSERT INTO `nova_awe_section_order` (`section_id`, `section_order`) VALUES
(9, 1),
(11, 2),
(8, 3),
(17, 4),
(15, 5),
(18, 6);

-- --------------------------------------------------------

--
-- Table structure for table `nova_awe_templates`
--

CREATE TABLE IF NOT EXISTS `nova_awe_templates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` text NOT NULL,
  `template_folder` text NOT NULL,
  `template_author` text NOT NULL,
  `template_author_email` text NOT NULL,
  `template_author_url` text NOT NULL,
  `template_version` text NOT NULL,
  `template_created_date` text NOT NULL,
  `template_description` text NOT NULL,
  `template_imagefolder` text NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `nova_awe_templates`
--

INSERT INTO `nova_awe_templates` (`template_id`, `template_name`, `template_folder`, `template_author`, `template_author_email`, `template_author_url`, `template_version`, `template_created_date`, `template_description`, `template_imagefolder`) VALUES
(1, 'Industrial', 'industrial', 'CampaignMonitor', 'themoocode@gmail.com', 'http://www.campaignmonitor.com/templates/', '1.0', 'September 10, 2011', 'Basic dark template taken from CampaignMonitor Free template collection and adapted to aweSimReport by mooeypoo.', 'images'),
(2, 'Helvetica', 'helvetica', 'CampaignMonitor (edited by mooeypoo)', 'themoocode@gmail.com', 'http://www.campaignmonitor.com/templates/', '1.0', 'September 10, 2011', 'Simple dark gray template with some blue elements.', 'images');

-- --------------------------------------------------------

--
-- Table structure for table `nova_settings`
--

CREATE TABLE IF NOT EXISTS `nova_settings` (
  `setting_id` int(5) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) DEFAULT '',
  `setting_value` text,
  `setting_label` varchar(255) DEFAULT '',
  `setting_user_created` enum('y','n') DEFAULT 'y',
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `nova_settings`
--

INSERT INTO `nova_settings` (`setting_key`, `setting_value`, `setting_label`, `setting_user_created`) VALUES
('awe_txtSimStart', '10:00pm', 'awSimReport - Sim Start', 'y'),
('awe_txtSimEnd', '11:00pm', 'awSimReport - Sim End', 'y'),
('awe_txtReportingOfficer', '0', 'awSimReport - Reporting Officer (Alternative Title)', 'y'),
('awe_txtDateFormat', '24%y%m.%j', 'awSimReport - Date Format', 'y'),
('awe_txtEmailSubject', 'Sim Report for %simname%', 'awSimReport - Email Subject', 'y'),
('awe_txtEmailRecipients', 'person@example.com, people@exampletwo.com', 'awSimReport - Recipients', 'y'),
('awe_chkPresenceTags', 'checked', 'awSimReport - Display Presence Tags', 'y'),
('awe_txtPresenceTag_Present', 'P', 'awSimReport - Presence Tags (Present)', 'y'),
('awe_txtPresenceTag_Unexcused', 'U', 'awSimReport - Presence Tags (Unexcused absence)', 'y'),
('awe_txtPresenceTag_Excused', 'E', 'awSimReport - Presence Tags (Excused absence)', 'y'),
('awe_chkShowRankImagesRoster', 'checked', 'awSimReport - Display Rank Images', 'y'),
('awe_txtReportDuration', '14', 'awSimReport - Report Duration', 'y'),
('awe_chkShowRankImagesCOC', '', 'aweSimReport - Show Rank Images for COC', 'y'),
('awe_txtReportTitle', 'Sim Report for USS Awesome', 'awSimReport - Report Title', 'y'),
('awe_txtTemplateFooter', 'Thank you for participating in USS Awesime game. For more information, please see our website.', 'awSimReport - Template Footer', 'y'),
('awe_ActiveTemplate', '1', 'awSimReport - Active Template', 'y');
