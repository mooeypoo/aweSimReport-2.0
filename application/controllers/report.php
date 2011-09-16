<?php
/*
|---------------------------------------------------------------
| ADMIN - REPORT CONTROLLER
|---------------------------------------------------------------
|
| File: controllers/report.php
| System Version: 1.0
|
| Controller that handles the REPORT section of the admin system.
|
*/

require_once APPPATH . 'controllers/base/report_base.php';

class Report extends Report_base {

	function Report() {
		parent::Report_base();
	}
	
	/** your methods here **/
	function awesimreport() {
		/* load resources */
		$this->load->model('awesimreport_model', 'awe');

		$this->load->model('characters_model', 'char');
		$this->load->model('positions_model', 'pos');
		$this->load->model('depts_model', 'dept');
		$this->load->model('personallogs_model', 'logs');
		$this->load->model('posts_model', 'posts');
		$this->load->model('ranks_model', 'ranks');
		$this->load->model('news_model', 'news');

		$data['images']['loading'] = array(
			'src' => img_location('loading-bar.gif', $this->skin, 'admin'),
			'alt' => 'Loading',
			'class' => 'image'
		);
		
		/** SET UP MENU **/
		$data['images']['menu'] = array(
			'generator' => array(
				'src' => asset_location('images/awesimreport', 'awe_menu_generator.png'),
				'class' => 'image inline_img_left',
				'height' => 48,
				'alt' => 'Generator',
				'title' => 'Generator'),
			'archive' => array(
				'src' => asset_location('images/awesimreport', 'awe_menu_archive.png'),
				'class' => 'image inline_img_left',
				'height' => 48,
				'alt' => 'Archive'),
			'sections' => array(
				'src' => asset_location('images/awesimreport', 'awe_menu_sections.png'),
				'class' => 'image inline_img_left',
				'height' => 48,
				'alt' => 'Sections'),
			'settings' => array(
				'src' => asset_location('images/awesimreport', 'awe_menu_settings.png'),
				'class' => 'image inline_img_left',
				'height' => 48,
				'alt' => 'Settings'),
			'templates' => array(
				'src' => asset_location('images/awesimreport', 'awe_menu_templates.png'),
				'class' => 'image inline_img_left',
				'height' => 48,
				'alt' => 'Templates'),
		);

		/* grab the settings */
		$settings_array = array(
			'awe_txtSimStart',
			'awe_txtSimEnd',
			'awe_txtDateFormat',
			'awe_txtEmailSubject',
			'awe_txtReportTitle',
			'awe_txtEmailRecipients',
			'awe_chkPresenceTags',
			'awe_txtReportDuration',
			'awe_txtPresenceTag_Present',
			'awe_txtPresenceTag_Unexcused',
			'awe_txtPresenceTag_Excused',
			'awe_chkShowRankImagesRoster',
			'awe_chkShowRankImagesCOC',
			'awe_ActiveTemplate',
		);
		$aweSettings = $this->settings->get_settings($settings_array);
		
		switch ($this->uri->segment(3)) {
			default:
			case "generator":
				/* set the variables */
				$id = $this->uri->segment(4, FALSE, TRUE);
				$data['debug']['id'] = $id;
				$data['reportid'] = $id;
				//DEAL WITH POST REQUESTS:
				if (isset($_POST['submit'])) {
					$action = strtolower($this->input->post('submit', TRUE));
					switch ($action) {
						case 'save report':
							$dateStart = $this->input->post('txtReportDateStart');
							$dateEnd = $this->input->post('txtReportDateEnd');
							$chkShowUsers = $this->input->post('chkRosterShowUsers', TRUE);
							$customSections = $this->input->post('sections', TRUE);
							
							$dataArray = array(
									   'CustomSections' => $customSections,
									   'ShowUsers' => $chkShowUsers
									   );
							
								/* build the insert array */
								$insert_array = array(
									'report_date_start' => strtotime($dateStart),
									'report_date_end' => strtotime($dateEnd),
									'report_author' => $this->session->userdata('userid'),
									'report_data' => serialize($dataArray),
									'report_status' => 'saved',
									'report_saved_date' => now()
								);
								
								/* do the insert */
								$insert = $this->awe->add_saved_report($insert_array);
								
								/* grab the insert id */
								$insert_id = $this->db->insert_id();
								
								/* optimize the table */
								$this->sys->optimize_table('awe_saved_reports');
								
								if ($insert > 0) {
									$message = 'Report saved successfully. Please wait to be redirected to your saved report...';
	
									$flash['status'] = 'success';
									$flash['message'] = text_output($message);
								} else {
									$message = '201: An error occured while saving this report. Please try again later.';
	
									$flash['status'] = 'error';
									$flash['message'] = text_output($message);
								}
								/* add a quick redirect */
								$this->template->add_redirect('report/awesimreport/generator/'. $insert_id);
								
							break; //save
						case 'update report':
							$dateStart = $this->input->post('txtReportDateStart');
							$dateEnd = $this->input->post('txtReportDateEnd');
							$chkShowUsers = $this->input->post('chkRosterShowUsers', TRUE);
							$customSections = $this->input->post('sections', TRUE);
							
							$dataArray = array(
									   'CustomSections' => $customSections,
									   'ShowUsers' => $chkShowUsers
									   );
							
							/* if there is an ID, it is a previously saved report */
							$update_array = array(
								'report_date_start' => strtotime($dateStart),
								'report_date_end' => strtotime($dateEnd),
								'report_author' => $this->session->userdata('userid'),
								'report_data' => serialize($dataArray),
								'report_status' => 'saved',
								'report_saved_date' => now()
							);
								
							/* do the update */
							$update = $this->awe->update_saved_report($id, $update_array);
								
							if ($update > 0) {
								$message = 'Report updated successfully.';

								$flash['status'] = 'success';
								$flash['message'] = text_output($message);
							} else {
								$message = 'An error occured while trying to update this report. Please try again later.';
								$flash['status'] = 'error';
								$flash['message'] = text_output($message);
							}
							break; //update
						case 'generate report':
							break; //generate
						case 'delete saved report':
							//get info:
							$reportRow = $this->awe->get_saved_report_details($id);
							if ($reportRow !== FALSE) {
								if ($reportRow->report_status == 'saved') {
									//exists, and not a published report.
									//go ahead an delete:
									$delete = $this->awe->delete_saved_report($id);
									
									if ($delete > 0) {
										$message = 'Report deleted successfully.';
	
										$flash['status'] = 'success';
										$flash['message'] = text_output($message);
									} else {
										$message = '102: There was an error deleting this report. Try again later.';
	
										$flash['status'] = 'error';
										$flash['message'] = text_output($message);
									}
									
									
/*								} else {								{
									//this is a published report
									$message = 'Cannot delete published report.';
									$flash['status'] = 'еrror';
									$flash['message'] = text_output($message);	
*/
								} 
					
								/* add an automatic redirect */
								$this->template->add_redirect('report/awesimreport');
							} else {
								//report doesn't exist
								$message = 'There was a problem deleting this report. It appears the ID doesn\'t exist anymore! Please try again later.';
								$flash['status'] = 'еrror';
								$flash['message'] = text_output($message);
							}
							
							break; //delete
						default:
							$flash['status'] = 'error';
							$flash['message'] = lang_output('error_generic', '');
					}
					/* write everything to the template */
					$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
					
				} //end if 'isset submit'

				$chkShowUsers= array();
				$customSections= array();
				$inputVal['txtReportDateStart'] = '';
				$inputVal['txtReportDateStart'] = '';
				if ($id !== FALSE) { /* if there is an ID, it is a previously saved report */
					//load the report:
					$report = $this->awe->get_saved_report_details($id);
					if ($report !== FALSE) { //it exists!
						$repdata = unserialize($report->report_data);
						$chkShowUsers = $repdata['ShowUsers'];
						$customSections = $repdata['CustomSections'];
						$inputVal['txtReportDateStart'] = (($report->report_date_start > 0) ? date('n/j/Y',$report->report_date_start) : '');
						$inputVal['txtReportDateEnd'] = (($report->report_date_end > 0) ? date('n/j/Y',$report->report_date_end) : '');
					}
				}
				$data['debug']['action'] = $action;
				
				//set up inputs:
				$data['inputs'] = array(
					'formAttributes' => array(
						'name' => 'frmGenerate',
						'id' => 'frmGenerate'),
					'txtReportDateStart' => array(
						'style' => 'width:150px;',
						'name' => 'txtReportDateStart',
						'id' => 'txtReportDateStart',
						'value' => $inputVal['txtReportDateStart']),
					'txtReportDateEnd' => array(
						'style' => 'width:150px;',
						'name' => 'txtReportDateEnd',
						'id' => 'txtReportDateEnd',
						'value' => $inputVal['txtReportDateEnd']),
					'butGenerate' => array(
						'type' => 'submit',
						'class' => 'button-main',
						'name' => 'submit',
						'value' => 'generate',
						'id' => 'submitGenerate',
						'content' => ucwords('Generate Report')),
					'preview' => array(
						'type' => 'submit',
						'class' => 'button-sec',
						'name' => 'submit',
						'value' => 'preview',
						'content' => ucwords('Preview Report')),
					'save' => array(
						'type' => 'submit',
						'class' => 'button-sec',
						'name' => 'submit',
						'value' => 'save',
						'content' => ucwords('Save Report')),
					'update' => array(
						'type' => 'submit',
						'class' => 'button-sec',
						'name' => 'submit',
						'value' => 'update',
						'content' => ucwords('Update Report')),
					'delete' => array(
						'type' => 'submit',
						'class' => 'button-sec',
						'name' => 'submit',
						'value' => 'delete',
						'id' => 'submitDelete',
						'content' => ucwords('Delete Saved Report'))
				);
				/** ROSTER! **/
				$secID = $this->awe->get_section_by_name('Roster');
				$isActive = $this->awe->check_section_active($secID);

				if ($isActive > 0) { //roster exists. 
					//get settings:
					$data['roster']['Enabled'] = (int)($isActive);
					$data['roster']['UseTags'] = $aweSettings['awe_chkPresenceTags'];
					$data['roster']['Present'] = $aweSettings['awe_txtPresenceTag_Present'];
					$data['roster']['Unexcused'] = $aweSettings['awe_txtPresenceTag_Unexcused'];
					$data['roster']['Excused'] = $aweSettings['awe_txtPresenceTag_Excused'];
					$data['roster']['ShowRankImages'] = $aweSettings['awe_chkShowRankImagesRoster'];
					
					if ($aweSettings['awe_chkShowRankImagesRoster'] == 'checked') {
						$sRosterImages = true;
					}
					$rank = $this->ranks->get_rankcat($this->rank);
					
					/* build the blank image array */
					$blank_img = array(
						'src' => rank_location($this->rank, 'blank', $rank->rankcat_extension),
						'alt' => '',
						'class' => 'image');

					$data['debug']['chkShowUsers']=$chkShowUsers;
					$depts = $this->dept->get_all_depts('asc', '');
					if ($depts->num_rows() > 0) {
						foreach ($depts->result() as $d) {
							$data['characters'][$d->dept_id]['deptname'] = $d->dept_name;

							$subdepts = $this->dept->get_sub_depts($d->dept_id);
							if ($subdepts->num_rows() >0) {
								foreach ($subdepts->result() as $subd) {
									$data['characters'][$d->dept_id]['subdept'][$subd->dept_id]['deptname'] = $subd->dept_name;
								}
							} 
						}
					}

					$users = $this->user->get_users();
					if ($users->num_rows() > 0) {
						/* set the posting requirement threshold */
						foreach ($users->result() as $p) {
							if (empty($id)) {
								$chkVal = 'checked';
							} else {
								if (empty($chkShowUsers[$p->userid])) {
									$chkVal = '';
									
								} else {
									$chkVal = $chkShowUsers[$p->userid];
								}
							}
//							$chkValue = ((empty($chkShowUsers[$p->userid])) ? $chkShowUsers[$p->userid] : 'checked');
							$data['chkRosterShowUsers'][$p->userid] = array(
								'name' => 'chkRosterShowUsers['.$p->userid.']',
								'id' => 'chkRosterShowUsers['.$p->userid.']',
								'value' => 'checked',
								'checked' => $chkVal, 
							);
							$charinf = $this->char->get_character($p->main_char);
							$posts = $this->pos->get_position($charinf->position_1);
							$positions = ($posts !== FALSE) ? $posts->pos_name : '';
							if ((int)($charinf->position_2)>0) {
								$positions .= " & ".$this->pos->get_position($charinf->position_2, 'pos_name');
							}
							$currdept = $this->dept->get_dept($posts->pos_dept);
							$cdept = $currdept->dept_id; //$this->dept->get_dept($posts->pos_dept, 'dept_id');
							$parentDep= $currdept->dept_parent; //$this->dept->get_dept($posts->pos_dept, 'dept_parent');
													

							//get rank image:
							$rankdata = $this->ranks->get_rank($charinf->rank, array('rank_name', 'rank_image'));
							/* build the rank image array */
							$rank_img = array(
								'src' => rank_location($this->rank, $rankdata['rank_image'],$rank->rankcat_extension),
								'alt' => $rankdata['rank_name'],
								'class' => 'image');
							
							
							if ((int)($parentDep) > 0) { //there is a 'parent' to the dept
								$data['characters'][$parentDep]['subdept'][$cdept]['chars'][$p->userid] = array(
									'id' => $p->userid,
									'name' => $p->name,
									'email' => $p->email,
									'char_name' => $this->char->get_character_name($p->main_char, TRUE),
									'position' => $positions,
									'rank_img' => $rank_img,
									'charid' => $p->main_char,
									'loa' => ($p->loa != 'active') ? '['. strtoupper($p->loa) .']' : ''
								); 
								$data['department'][$p->main_char] = $cdept;
							} else { //the dept is the parent
								$data['characters'][$cdept]['chars'][$p->userid] = array(
									'id' => $p->userid,
									'name' => $p->name,
									'email' => $p->email,
									'char_name' => $this->char->get_character_name($p->main_char, TRUE),
									'position' => $positions,
									'rank_img' => $rank_img,
									'charid' => $p->main_char,
									'loa' => ($p->loa != 'active') ? '['. strtoupper($p->loa) .']' : ''
								); 
								$data['department'][$p->main_char] = $cdept;
							}
							if ($p->loa == 'active') {
								$data['radAttendance'][$p->main_char] = array(
									'U' => array(
										'name' => 'rAttendance['.$p->main_char.']',
										'id' => 'rAttendance['.$p->main_char.']',
										'value' => 'U'),
									'E' => array(
										'name' => 'rAttendance['.$p->main_char.']',
										'id' => 'rAttendance['.$p->main_char.']',
										'value' => 'E'),
									'P' => array(
										'name' => 'rAttendance['.$p->main_char.']',
										'id' => 'rAttendance['.$p->main_char.']',
										'value' => 'P')
								);
							} else {
								$data['radAttendance'][$p->main_char] = array(
									'LOA' => array(
										'name' => 'rAttendance['.$p->main_char.']',
										'id' => 'rAttendance['.$p->main_char.']',
										'value' => 'LOA'),
									'ELOA' => array(
										'name' => 'rAttendance['.$p->main_char.']',
										'id' => 'rAttendance['.$p->main_char.']',
										'value' => 'ELOA'));
							}
						}
					} 
					/* sort the keys */
					ksort($data['characters']);
				} //end if -- roster exists
				
				/* CUSTOM SECTIONS */
				//check the active sections:
				$cSections = $this->awe->get_all_userdefined_sections();
				if ($cSections->num_rows() > 0) {
					foreach ($cSections->result() as $sec) {
						if ($id !== FALSE) { /* if there is an ID, it is a previously saved report */
							$secVal = $customSections[$sec->section_id];
						} else {
							$secVal = $this->awe->get_section_default($sec->section_id);
						}
/*						$secVal = (empty($customSections[$sec->section_id]) ? $customSections[$sec->section_id] : $this->awe->get_section_default($sec->section_id));*/
						if ($this->awe->check_section_active($sec->section_id) > 0) {
							$data['sections'][$sec->section_id]['title'] = $sec->section_title;
							$data['sections'][$sec->section_id]['input'] = array(
								'name' => 'sections['.$sec->section_id.']',
								'secname' => 'sections['.$sec->section_id.']',
								'secID' => $sec->section_id,
								'id' => 'sections',
								'rows' => 5,
								'value' => $secVal
/*								'value' => $this->awe->get_section_default($sec->section_id)*/
							);
						} //end if active section
					} //end foreach custom section
				} //end if custom sections
				
				//display saved reports:
				$savedReports = $this->awe->get_saved_reports('saved');
				$sReports = array();
				if ($savedReports->num_rows() > 0) {
					foreach ($savedReports->result() as $row) {
						$sReports[$row->report_id] = array(
										   'id' => $row->report_id,
										   'dateStart' => $row->report_date_start,
										   'dateEnd' => $row->report_date_end
										  );
					}
				}
				$data['savedReports'] = $sReports;
				
				/** SETUP VIEW **/
				$data['header'] = 'aweSimReport: Generator';
				/* view locations */
				$currpage = 'reports_awesimreport_generator';
				$currpage_js = 'report_awesimreport_generator_js';
				break; /* GENERATOR */
			case "sections":
				//DEAL WITH POST REQUESTS:
				if (isset($_POST['submit'])) {
					switch ($this->uri->segment(4)) {
						case 'add':
							$secName = trim($this->input->post('secName', TRUE));
							$secTitle = trim($this->input->post('secTitle', TRUE));
							$secDefaultContent = trim($this->input->post('secDefaultContent', TRUE));
							//make sure both aren't empty, just in case:
							if ((empty($secName))) {
								/* set the content of the message */
								$message = '101: There was a problem adding this section. Please try again later.';
								
								$flash['status'] = 'error';
								$flash['message'] = text_output($message);
								
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							} else { //not empty
								//check that section name doesn't exist:
								$check = $this->awe->get_section_by_name($secName);
								
								if ($check === FALSE) { //it doesn't exist. GOOD! ADD!
									$secName = str_replace('&', '&amp;', $secName);
									$secTitle = str_replace('&', '&amp;', $secTitle);
									$secDefaultContent = str_replace('&', '&amp;', $secDefaultContent);

									//if (empty($secTitle)) { $secTitle = $secName; }
									
									$insert_array = array(
										'section_name' => $secName,
										'section_title' => $secTitle,
										'section_default' => $secDefaultContent,
										'section_added_user' => $this->session->userdata('userid'),
										'section_added_date' => time(),
										'section_userdefined' => 1
									);
									
									/* insert the record */
									$insert = $this->awe->add_new_section($insert_array);
									
									if ($insert > 0)
									{
										$message = "Section added successfully.";

										$flash['status'] = 'success';
										$flash['message'] = text_output($message);
									}
									else
									{
										$message = '102: There was a problem adding the requested section. Please try again later.';

										$flash['status'] = 'error';
										$flash['message'] = text_output($message);
									}
								}
								else
								{
									$message = sprintf(
										lang('flash_duplicate_key'),
										lang('labels_site') .' '. lang('labels_message')
									);

									$flash['status'] = 'error';
									$flash['message'] = text_output($message);
								}
								
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							}
							break;
						case 'edit':
							$secID =(int)($this->input->post('secID', TRUE));
							$secName = trim($this->input->post('secName', TRUE));
							$secTitle = trim($this->input->post('secTitle', TRUE));
							$secDefaultContent = trim($this->input->post('secDefaultContent', TRUE));
							//make sure both aren't empty, just in case:
							if ((empty($secName))) {
								/* set the content of the message */
								$message = 'Err 101: There was a problem adding this section. Please try again later.';
/*								$message .= '<br>secDefaultContent: '.$secDefaultContent;
								$message .= '<br>secName: '.$secName;*/
								
								$flash['status'] = 'error';
								$flash['message'] = text_output($message);
								
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							} else { //not empty
								//check that ID is valid:
								$section = $this->awe->get_section_details($secID);
								if ($section != false) {
									if ($section->num_rows() > 0) {
										$row = $section->row();
										$secName = str_replace('&', '&amp;', $secName);
										$secTitle = str_replace('&', '&amp;', $secTitle);
										$secDefaultContent = str_replace('&', '&amp;', $secDefaultContent);

										if (empty($secTitle)) { $secTitle = $secName; }
										
										$update_array = array(
											'section_name' => $secName,
											'section_title' => $secTitle,
											'section_default' => $secDefaultContent,
											'section_last_edit_user' => $this->session->userdata('userid'),
											'section_last_edit_date' => time(),
											'section_userdefined' => 1
										);
										
										/* insert the record */
										$insert = $this->awe->update_section($secID,$update_array);
										
										if ($insert > 0)
										{
											$message = "Section edited successfully.";

											$flash['status'] = 'success';
											$flash['message'] = text_output($message);
										}
										else
										{
											$message = 'Err 102: There was a problem editing the requested section. Please try again later.';

											$flash['status'] = 'error';
											$flash['message'] = text_output($message);
										}
									}
									else
									{
										$message = 'Error: This section seems to not exist anymore. If this persists, please submit a bug report.';
										$message .= '<br>[DEBUG] SecID: '.$secID;

										$flash['status'] = 'error';
										$flash['message'] = text_output($message);
									}
								} else {
									$message = 'Error: This section seems to not exist anymore. If this persists, please submit a bug report.';
									$message .= '<br>[DEBUG] SecID: '.$secID;

									$flash['status'] = 'error';
									$flash['message'] = text_output($message);
								}
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							}
							break;
						case 'sysedit':
							$secID =(int)($this->input->post('secID', TRUE));
							$secName = trim($this->input->post('secName', TRUE));
							$secTitle = trim($this->input->post('secTitle', TRUE));
							//make sure both aren't empty, just in case:
							if ((empty($secName))) {
								/* set the content of the message */
								$message = 'Err 201: There was a problem editing this section. Please try again later.';
								
								$flash['status'] = 'error';
								$flash['message'] = text_output($message);
								
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							} else { //not empty
								//check that ID is valid:
								$section = $this->awe->get_section_details($secID);
								if ($section != false) {
									if ($section->num_rows() > 0) {
										$row = $section->row();
										$secName = str_replace('&', '&amp;', $secName);
										$secDefaultContent = str_replace('&', '&amp;', $secDefaultContent);

										if (empty($secTitle)) { $secTitle = $secName; }
										
										$update_array = array(
											'section_title' => $secTitle,
											'section_last_edit_user' => $this->session->userdata('userid'),
											'section_last_edit_date' => time(),
										);
										
										/* insert the record */
										$insert = $this->awe->update_section($secID,$update_array);
										
										if ($insert > 0)
										{
											$message = "Section edited successfully.";

											$flash['status'] = 'success';
											$flash['message'] = text_output($message);
										}
										else
										{
											$message = 'Err 202: There was a problem editing the requested section. Please try again later.';

											$flash['status'] = 'error';
											$flash['message'] = text_output($message);
										}
									}
									else
									{
										$message = 'Error: There was an error reading the fields information. If this persists, please submit a bug report.';
										$message .= '<br>[DEBUG] SecID: '.$secID;

										$flash['status'] = 'error';
										$flash['message'] = text_output($message);
									}
								} else {
									$message = 'Error: There was an error reading the fields information. If this persists, please submit a bug report.';
									$message .= '<br>[DEBUG] SecID: '.$secID;

									$flash['status'] = 'error';
									$flash['message'] = text_output($message);
								}
								/* write everything to the template */
								$this->template->write_view('flash_message', '_base/admin/pages/flash', $flash);
							}
							break;
					}
				}
				
				//get sections:
				$allsections = $this->awe->get_all_sections();
				if ($allsections->num_rows() > 0) {
					foreach ($allsections->result() as $row) {
						$chkactive = $this->awe->check_section_active($row->section_id);
						$data['sections'][$chkactive][$row->section_id] = array(
							'id' => $row->section_id,
							'userdefined' => $row->section_userdefined,
							'name' => $row->section_name,
							'title' => $row->section_title,
							'default' => $row->section_default
						);
					}
				}

				//get section order:
				$data['reorganize'] = $this->awe->renumber_sections_order();
				$sectionorder = $this->awe->get_section_order();
				if ($sectionorder->num_rows() > 0) {
					foreach ($sectionorder->result() as $row) {
						//make sure these are active sections:
						if (($this->awe->check_section_active($row->section_id) > 0) && ($this->awe->get_section_details($row->section_id)>0)) {
							$data['sections']['order'][$row->section_order] = $row->section_id;
						}
//						$data['sections']['order'][$row->section_order] = 1;
					}
				}

				$data['inputs'] = array(
					'txtAddSection' => array(
						'style' => 'width:100px;',
						'name' => 'txtAddSection',
						'id' => 'txtAddSection',
						'value' => ''),
					'txtDefaultContent' => array(
						'rows' => 5,
						'cols' => 15,
						'name' => 'txtDefaultContent',
						'id' => 'txtDefaultContent',
						'value' => ''),
					'addSection' => array(
						'type' => 'submit',
						'class' => 'button-main',
						'name' => 'submit',
						'value' => 'addSection',
						'id' => 'addSection',
						'content' => ucwords('Add Section')),
					'saveSections' => array(
						'type' => 'button',
						'class' => 'button-main',
						'name' => 'saveSections',
						'id' => 'saveSections',
						'content' => ucwords('Save Order'))
				);
				/** SETUP VIEW **/
				$data['header'] = 'aweSimReport: Custom Sections';
				/* view locations */
				$currpage = 'reports_awesimreport_sections';
				$currpage_js = 'report_awesimreport_sections_js';
				break; /* SECTIONS */
			case "settings":
				$data['inputs'] = array(
					'txtSimStart' => array(
						'style' => 'width:100px;',
						'name' => 'txtSimStart',
						'id' => 'txtSimStart',
						'value' => $aweSettings['awe_txtSimStart']),
					'txtSimEnd' => array(
						'style' => 'width:100px;',
						'name' => 'txtSimEnd',
						'id' => 'txtSimEnd',
						'value' => $aweSettings['awe_txtSimEnd']),
					'txtDateFormat' => array(
						'style' => 'width:250px;',
						'name' => 'txtDateFormat',
						'id' => 'txtDateFormat',
						'value' => $aweSettings['awe_txtDateFormat']),
					'txtReportDuration' => array(
						'style' => 'width:70px;',
						'name' => 'txtReportDuration',
						'id' => 'txtReportDuration',
						'value' => $aweSettings['awe_txtReportDuration']),
					'txtEmailSubject' => array(
						'style' => 'width:400px;',
						'name' => 'txtEmailSubject',
						'id' => 'txtEmailSubject',
						'value' => $aweSettings['awe_txtEmailSubject']),
					'txtReportTitle' => array(
						'style' => 'width:400px;',
						'name' => 'txtReportTitle',
						'id' => 'txtReportTitle',
						'value' => $aweSettings['awe_txtReportTitle']),
					'txtEmailRecipients' => array(
						'style' => 'width:400px;',
						'name' => 'txtEmailRecipients',
						'id' => 'txtEmailRecipients',
						'value' => $aweSettings['awe_txtEmailRecipients']),
					'chkPresenceTags' => array(
						'name' => 'chkPresenceTags',
						'id' => 'chkPresenceTags',
						'value' => 'checked',
						'checked' => $aweSettings['awe_chkPresenceTags']),
					'txtPresenceTag_Present' => array(
						'style' => 'width:100px;',
						'name' => 'txtPresenceTag_Present',
						'id' => 'txtPresenceTag_Present',
						'value' => $aweSettings['awe_txtPresenceTag_Present']),
					'txtPresenceTag_Unexcused' => array(
						'style' => 'width:100px;',
						'name' => 'txtPresenceTag_Unexcused',
						'id' => 'txtPresenceTag_Unexcused',
						'value' => $aweSettings['awe_txtPresenceTag_Unexcused']),
					'txtPresenceTag_Excused' => array(
						'style' => 'width:100px;',
						'name' => 'txtPresenceTag_Excused',
						'id' => 'txtPresenceTag_Excused',
						'value' => $aweSettings['awe_txtPresenceTag_Excused']),
					'chkShowRankImagesRoster' => array(
						'name' => 'chkShowRankImagesRoster',
						'id' => 'chkShowRankImagesRoster',
						'value' => 'checked',
						'checked' => $aweSettings['awe_chkShowRankImagesRoster']), //TAKE FROM DB
					'chkShowRankImagesCOC' => array(
						'name' => 'chkShowRankImagesCOC',
						'id' => 'chkShowRankImagesCOC',
						'value' => 'checked',
						'checked' => $aweSettings['awe_chkShowRankImagesCOC']), //TAKE FROM DB
					'saveSettings' => array(
						'type' => 'button',
						'class' => 'button-main',
						'name' => 'saveSettings',
						'id' => 'saveSettings',
						'content' => ucwords('Save Settings')),
					'saveSettings2' => array(
						'type' => 'button',
						'class' => 'button-main',
						'name' => 'saveSettings',
						'id' => 'saveSettings',
						'content' => ucwords('Save Settings'))
				);

				/** SETUP VIEW **/
				$data['header'] = 'aweSimReport: Settings';
				/* view locations */
				$currpage = 'reports_awesimreport_settings';
				$currpage_js = 'report_awesimreport_settings_js';
				break; /* SETTINGS */
			case "templates":
				/* get all templates */
				$templatelist = $this->awe->get_all_templates();
				$templates = array();
				if ($templatelist->num_rows() > 0) {
					foreach ($templatelist->result() as $row) {
						$isActive = 'no';
						if ($row->template_id == $aweSettings['awe_ActiveTemplate']) {
							$isActive = 'yes';
						}
/*						$templatefolder = 'aweSimReportTemplates/'.$row->template_folder; */
						$templates[$row->template_id] = array(
							'id' => $row->template_id,
							'name' => $row->template_name,
							'author' => $row->template_author,
							'author_email' => $row->template_author_email,
							'author_url' => $row->template_author_url,
							'version' => $row->template_version,
							'created_date' => $row->template_created_date,
							'description' => $row->template_description,
							'imagefolder' => $row->template_imagefolder,
							'active' => $isActive,
							'thumbnail' => array(
								'src' => asset_location('aweSimReportTemplates/'.$row->template_folder, 'thumbnail.png'),
								'class' => 'image',
								'alt' => $row->template_name,
								'title' => $row->template_name,
								'id' => 'templ_img'
							),
						);
					}
				}
				$data['templates']=$templates;
				
			
				/** SETUP VIEW **/
				$data['header'] = 'aweSimReport: Templates';
				/* view locations */
				$currpage = 'reports_awesimreport_templates';
				$currpage_js = 'report_awesimreport_templates_js';
				break; /* TEMPLATES */
			case "archive":
				/** SETUP VIEW **/
				$data['header'] = 'aweSimReport: Archive';
				/* view locations */
				$currpage = 'reports_awesimreport_archive';
				$currpage_js = 'report_awesimreport_archive_js';
				break; /* ARCHIVE */
		} /* END SWITCH URI SEGMENT */

		/** ============ **/
		/** DISPLAY VIEW **/
		/** ============ **/


		$view_loc = view_location($currpage, $this->skin, 'admin');
		$js_loc = js_location($currpage_js, $this->skin, 'admin'); 

		/* produce write the header */
		$this->template->write('title', $data['header']);
		
		/* produce view */
		$this->template->write_view('javascript', $js_loc, $js_data); 
		$this->template->write_view('content', $view_loc, $data);
		
		/* render the template */
		$this->template->render();
		
	} /** end aweSimReport function **/
}