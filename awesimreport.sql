-- phpMyAdmin SQL Dump
-- version 3.3.10.4
-- http://www.phpmyadmin.net
--
-- Generation Time: Sep 23, 2011 at 05:11 PM

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `nova_awe_saved_reports`
--

CREATE TABLE IF NOT EXISTS `nova_awe_saved_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `report_name` text NOT NULL,
  `report_date_start` text NOT NULL,
  `report_date_end` text NOT NULL,
  `report_author` int(11) NOT NULL,
  `report_data` text NOT NULL,
  `report_data_html` text NOT NULL,
  `report_template` int(11) NOT NULL,
  `report_status` text NOT NULL,
  `report_date_sent` int(11) NOT NULL,
  `report_saved_date` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;


-- --------------------------------------------------------

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
(9, 'Reporting Officer', 'Reporting Officer', '%repofficer%', 1, 1, 0, 0, 0),
(11, 'Roster', 'Roster', '%roster%', 1, 1, 1315583512, 1, 0),
(15, 'Statistics', 'Statistics', '%stats%', 1, 0, 0, 0, 0),
(17, 'Words from the CO', 'Words from the Big Cheese', '', 1, 1315636782, 1315884244, 5, 1),
(18, 'temp1', 'Temporary Section', 'With some text in it for default.', 1, 1316052320, 0, 0, 1);

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
(17, 1),
(8, 2),
(9, 3),
(11, 4),
(15, 5);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `nova_awe_templates`
--

INSERT INTO `nova_awe_templates` (`template_id`, `template_name`, `template_folder`, `template_author`, `template_author_email`, `template_author_url`, `template_version`, `template_created_date`, `template_description`, `template_imagefolder`) VALUES
(1, 'Industrial', 'industrial', 'CampaignMonitor', 'themoocode@gmail.com', 'http://www.campaignmonitor.com/templates/', '1.0', 'September 10, 2011', 'Basic dark template taken from CampaignMonitor Free template collection and adapted to aweSimReport by mooeypoo.', 'images'),
(2, 'Helvetica', 'helvetica', 'CampaignMonitor (edited by mooeypoo)', 'themoocode@gmail.com', 'http://www.campaignmonitor.com/templates/', '1.0', 'September 10, 2011', 'Simple dark gray template with some blue elements.', 'images'),
(3, 'Clouds', 'clouds', 'CampaignMonitor (edited by mooeypoo)', 'themoocode@gmail.com', 'http://www.campaignmonitor.com/templates/', '1.0', 'September 23, 2011', 'A light color template with cloud background.', 'images');

-- --------------------------------------------------------

--
-- Dumping data for table `nova_menu_items`
--

INSERT INTO `nova_menu_items` (`menu_name`, `menu_group`, `menu_order`, `menu_link`, `menu_link_type`, `menu_need_login`, `menu_use_access`, `menu_access`, `menu_access_level`, `menu_type`, `menu_cat`, `menu_display`, `menu_sim_type`) VALUES
('aweSimReport v2', 0, 3, 'report/awesimreport/generator', 'onsite', 'y', 'y', 'report/activity', 0, 'adminsub', 'report', 'y', 1),
('Report Archive', 0, 5, 'sim/awesimreport', 'onsite', 'none', 'n', '', 0, 'sub', 'sim', 'y', 1);

-- --------------------------------------------------------

--
-- Dumping data for table `nova_settings`
--

INSERT INTO `nova_settings` (`setting_key`, `setting_value`, `setting_label`, `setting_user_created`) VALUES
('awe_txtSimStart', '10:00pm', 'aweSimReport - Sim Start', 'y'),
('awe_txtSimEnd', '11:00pm', 'aweSimReport - Sim End', 'y'),
('awe_txtTemplateFooter', 'Thank you for participating in USS Awesime game. For more information, please see our website..', 'aweSimReport - Template Footer', 'y'),
('awe_txtReportingOfficer', '0', 'aweSimReport - Reporting Officer (Alternative Title)', 'y'),
('awe_txtDateFormat', '24%y%m.%j', 'aweSimReport - Date Format', 'y'),
('awe_txtEmailSubject', 'Sim Report for USS Awesome', 'aweSimReport - Email Subject', 'y'),
('awe_txtEmailRecipients', 'email@address.com, email2@someaddress.com', 'aweSimReport - Recipients', 'y'),
('awe_chkPresenceTags', 'checked', 'aweSimReport - Display Presence Tags', 'y'),
('awe_txtPresenceTag_Present', 'P', 'aweSimReport - Presence Tags (Present)', 'y'),
('awe_txtPresenceTag_Unexcused', 'U', 'aweSimReport - Presence Tags (Unexcused absence)', 'y'),
('awe_txtPresenceTag_Excused', 'E', 'aweSimReport - Presence Tags (Excused absence)', 'y'),
('awe_chkShowRankImagesRoster', 'checked', 'aweSimReport - Display Rank Images', 'y'),
('awe_txtReportDuration', '14', 'aweSimReport - Report Duration', 'y'),
('awe_txtStatOccurences', '15', 'aweSimReport - Number of Occurences on the Statistics Graph', 'y'),
('awe_chkShowRankImagesCOC', 'checked', 'aweSimReport - Show Rank Images for COC', 'y'),
('awe_txtReportTitle', 'Sim Report for USS Awesome', 'aweSimReport - Report Title', 'y'),
('awe_ActiveTemplate', '3', 'aweSimReport - Active Template', 'y');
