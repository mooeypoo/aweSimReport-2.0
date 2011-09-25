<?php
/*
|---------------------------------------------------------------
| AJAX CONTROLLER
|---------------------------------------------------------------
|
| File: controllers/ajax.php
| System Version: 1.0
|
| Controller that handles the AJAX work of the system
|
*/

require_once APPPATH . 'controllers/base/ajax_base.php';

class Ajax extends Ajax_base {
	
	function Ajax()
	{
		parent::Ajax_base();
	}
	
	/** ====================== **/	
	/** aweSimReport FUNCTIONS **/
	/** ====================== **/	
	
	/*=== SECTIONS ===*/

	function awe_add_section() {
		$head = "Add New Section";
		/* data being sent to the facebox */
		$data['header'] = $head;
		$data['text'] = "Use the form below to add a section to the aweSimReport. You will be able to arrange the sections after you save.";
		
		//$editID
		
		/* input parameters */
		$data['inputs'] = array(
			'secName' => array(
				'name' => 'secName',
				'class' => 'hud'),
			'secTitle' => array(
				'name' => 'secTitle',
				'class' => 'hud'),
			'secDefaultContent' => array(
				'name' => 'secDefaultContent',
				'class' => 'hud'),
			'submit' => array(
				'type' => 'submit',
				'class' => 'hud_button',
				'name' => 'submit',
				'value' => 'submit',
				'content' => 'Add Section')
		);
		/* figure out the skin */
		$skin = $this->session->userdata('skin_admin');
				
		/* figure out where the view should come from */
		$ajax = ajax_location('awe_add_section', $skin, 'admin');
				
		/* write the data to the template */
		$this->template->write_view('content', $ajax, $data);
				
		/* render the template */
		$this->template->render();

	} /* end awe_add_section */
		
	function awe_preview_report() {
		if (IS_AJAX) {
	
			$repID = $this->input->post('repID', TRUE);
			$templID = $this->input->post('templID', TRUE);
			$dateSent = $this->input->post('dateSent', TRUE);
	
			$data['params']['repID'] = $repID;
			$data['params']['templID'] = $templID;

			$settings_array = array(
				'awe_txtDateFormat',
			);
			$aweSettings = $this->settings->get_settings($settings_array);

			$data['report']['dateSent'] = strftime($aweSettings['awe_txtDateFormat'],$dateSent);
			
			$head = "Archived Report (ID# ".$repID.')';
			/* data being sent to the facebox */
			$data['header'] = $head;

			/* figure out the skin */
			$skin = $this->session->userdata('skin_admin');
					
			/* figure out where the view should come from */
			$ajax = ajax_location('awe_preview_report', $skin, 'admin');
					
			/* write the data to the template */
			$this->template->write_view('content', $ajax, $data);
					
			/* render the template */
			$this->template->render();
		}
	}
	function awe_preview_report_output() {
		
		$this->load->model('awesimreport_model', 'awe');
		$this->load->model('characters_model', 'char');
		$this->load->model('positions_model', 'pos');
		$this->load->model('depts_model', 'dept');
		$this->load->model('personallogs_model', 'logs');
		$this->load->model('posts_model', 'posts');
		$this->load->model('ranks_model', 'ranks');
		$this->load->model('news_model', 'news');
		$this->load->model('users_model', 'user');

		// grab the settings 
		$settings_array = array(
			'sim_name','awe_txtSimStart','awe_txtSimEnd','awe_txtDateFormat','awe_txtEmailSubject','awe_txtReportTitle',
			'awe_txtEmailRecipients','awe_chkPresenceTags','awe_txtReportDuration',
			'awe_txtPresenceTag_Present','awe_txtPresenceTag_Unexcused','awe_txtPresenceTag_Excused',
			'awe_chkShowRankImagesRoster','awe_chkShowRankImagesCOC','awe_ActiveTemplate','awe_txtTemplateFooter',
		);
		$aweSettings = $this->settings->get_settings($settings_array);

		//get current template
		$tmplID = $aweSettings['awe_ActiveTemplate'];		

		//get all variables
		$tDateStart = $this->input->post('txtReportDateStart', TRUE);
		$tDateEnd = $this->input->post('txtReportDateEnd', TRUE);
		$arrRosterUsers = $this->input->post('chkRosterShowUsers', TRUE);
		$arrRosterAttendance = $this->input->post('rAttendance', TRUE);
		$arrSections = $this->input->post('sections', TRUE);

		//see if this is a 'load file' request, and replace the vars
		if ((($this->uri->segment(3)) > 0)) {
			//structure: ajax/awe_preview_report_output/reportID/templateID
			$reportID = $this->uri->segment(3);
			//check if report exists in db:
			$rep = $this->awe->get_saved_report_details($reportID);
			if ($rep !== FALSE) {
				//report exists. Load it:
				$repData = unserialize($rep->report_data);
				
				$tDateStart = date('n/j/Y',$rep->report_date_start);
				$tDateEnd =  date('n/j/Y',$rep->report_date_end);
				$arrRosterUsers = $repData['ShowUsers'];
				$arrRosterAttendance = $repData['UserAttendance'];
				$arrSections = $repData['CustomSections'];
				
			} else {
				print 'ERROR: The report you\'re looking for has not been found. Check your link and try again.';
			}
			
			$templateID = $this->uri->segment(4);
			if ($this->awe->get_template_content($templateID) !== FALSE) {
				$tmplID = $templateID;		
			}

		}
		
		
		//LOAD THE TEMPLATE
		$template = $this->awe->get_template_content($tmplID); 
		if ($template === FALSE) {
			print "The requested template cannot be found. Please make sure you picked a correct template from the 'templates' section.<br />";
		}

		//prepare output array:
		$tOutput = array();

		//get section order:
		$sectionQuery = $this->awe->get_section_order();
		$c =1;
		if ($sectionQuery->num_rows() > 0) {
			foreach ($sectionQuery->result() as $sID) {
				$sec = $this->awe->get_section_details($sID->section_id);
				if ($sec->num_rows() > 0) {
					//get section info:
					$section = $sec->row();
					$tOutput[$c]['title']  = $section->section_title;
					switch (strtolower($section->section_name)) {
						case 'chain of command':
							
							$coc = $this->char->get_coc();
							$defaultRankset = $this->ranks->get_rank_default();
							$rank_ext = $this->ranks->get_rankcat($defaultRankset, 'rankcat_location', 'rankcat_extension');
							if ($coc->num_rows() > 0) {
								$cocHtml = '<table cellspacing="0" cellpadding="0" class="coctable">';
								foreach ($coc->result() as $item) {
									$cocHtml .= '<tr>';
									if ($item->crew_type == 'active' && empty($item->user)) {
										// skip 
									} else {
					
										if ($aweSettings['awe_chkShowRankImagesCOC']=='checked') {
											
											$charinf = $this->char->get_character($item->charid);
											//get rank image:
											$rankdata = $this->ranks->get_rank($charinf->rank, array('rank_name', 'rank_image'));
										/*	$rank = $this->ranks->get_rankcat($defaultRankset);
											/* build the rank image array 
											$rank_img = array(
												'src' => rank_location($defaultRankset, $rankdata['rank_image'],$rank->rankcat_extension),
												'alt' => $rankdata['rank_name'],
												'class' => 'image');
*/
											$img_rank = array(
											/*	'src' => rank_location($this->rank, $item->rank_image, $rank_ext),*/
												'src' => rank_location($defaultRankset, $item->rank_image, $rank_ext),
												'alt' => $item->rank_name,
												'class' => 'image',
												'border' => 0,
											);
									
											$cocHtml .= '<td width="80" class="coc_rank">'.img($img_rank).'</td>';
										}
															
										$coc_id = $item->charid;
										$coc_name = $this->char->get_character_name($item->charid, TRUE);
										$coc_position = $item->pos_name;

										$coc_item['id'] = $item->charid;
										$coc_item['coc_name'] = $this->char->get_character_name($item->charid, TRUE);
										$coc_item['coc_position'] = $item->pos_name;
							
										$cocHtml .= '<td class="coc_char">';
										$cocHtml .= '<strong>'.anchor('personnel/character/'.$item->charid, $coc_name).'</strong><br />';
										$cocHtml .= '<span style="size: 90%;">('.$coc_position.')</span>';
										$cocHtml .= '</td>';
									}
									$cocHtml .= '</tr>';
								} //foreach coc item
								$cocHtml .= '</table>';
							} //end if coc has records
							$html = $cocHtml;
							break;
					/*	case 'report date':
							$html = '<span class="reportDate">Dates: '.date($aweSettings['awe_txtDateFormat'],$tDateStart).' to '.date($aweSettings['awe_txtDateFormat'],$tDateEnd).'</span>';
							break;*/
						case 'reporting officer':
							$uid = $this->session->userdata('userid');
							$charid = $this->user->get_main_character($uid);
							$curr_char = $this->char->get_character($charid);
							$posts = $this->pos->get_position($curr_char->position_1);
							$positions = ($posts !== FALSE) ? $posts->pos_name : '';
							if ((int)($curr_char->position_2)>0) {
								$positions .= " & ".$this->pos->get_position($curr_char->position_2, 'pos_name');
							}
							$html  = '<span class="reportingOfficer">';
							$html .= $this->char->get_character_name($charid, TRUE).'<br />';
							$html .= $positions.'<br />';
							$html .= $aweSettings['sim_name'];
							$html .= '</span>';
							break;
						case 'roster':
							$arrRosterAttendanceTags = '';
							//go over the 'checked users' checkboxes:
							if ($aweSettings['awe_chkPresenceTags'] == 'checked') {
								$arrRosterAttendanceTags['P'] = $aweSettings['awe_txtPresenceTag_Present'];
								$arrRosterAttendanceTags['U'] = $aweSettings['awe_txtPresenceTag_Unexcused'];
								$arrRosterAttendanceTags['E'] = $aweSettings['awe_txtPresenceTag_Excused'];
							}
							$depts = $this->dept->get_all_depts('asc', '');
							if ($depts->num_rows() > 0) {
								foreach ($depts->result() as $d) {
									$characters[$d->dept_id]['deptname'] = $d->dept_name;
		
									$subdepts = $this->dept->get_sub_depts($d->dept_id);
									if ($subdepts->num_rows() >0) {
										foreach ($subdepts->result() as $subd) {
											$characters[$d->dept_id]['subdept'][$subd->dept_id]['deptname'] = $subd->dept_name;
										}
									} 
								}
							}
							
							if (count($arrRosterUsers) > 0) {
								foreach ($arrRosterUsers as $uid => $val) {
									$charid = $this->user->get_main_character($uid);
									
									$charinf = $this->char->get_character($charid);

									//get rank image:
									$rankdata = $this->ranks->get_rank($charinf->rank, array('rank_name', 'rank_image'));
									$defaultRankset = $this->ranks->get_rank_default();
									$rank = $this->ranks->get_rankcat($defaultRankset);
									/* build the rank image array */
									$rank_img = array(
										'src' => rank_location($defaultRankset, $rankdata['rank_image'],$rank->rankcat_extension),
										'alt' => $rankdata['rank_name'],
										'class' => 'image');
									

									$posts = $this->pos->get_position($charinf->position_1);
									$positions = ($posts !== FALSE) ? $posts->pos_name : '';
									if ((int)($charinf->position_2)>0) {
										$positions .= " & ".$this->pos->get_position($charinf->position_2, 'pos_name');
									}
									$currdept = $this->dept->get_dept($posts->pos_dept);
									$cdept = $currdept->dept_id; 
									$parentDep= $currdept->dept_parent; 
									
									
									$u = $this->user->get_user($uid);
									$loa = $u->loa;
									if ((int)($parentDep) > 0) { //there is a 'parent' to the dept
										$characters[$parentDep]['subdept'][$cdept]['chars'][$uid] = array(
											'id' => $uid,
											'name' => $u->name,
											'email' => $u->email,
											'char_name' => $this->char->get_character_name($charid, TRUE),
											'position' => $positions,
											'rank_img' => $rank_img,
											'charid' => $charid,
											'attendance' => $arrRosterAttendance[$uid],
											'logcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd),'logs'),
											'postcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd),'posts'),
											'totalcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd)),
										); 
//										$departments[$charid] = $cdept;
									} else { //the dept is the parent
										$characters[$cdept]['chars'][$uid] = array(
											'id' => $uid,
											'name' => $u->name,
											'email' => $u->email,
											'char_name' => $this->char->get_character_name($charid, TRUE),
											'position' => $positions,
											'rank_img' => $rank_img,
											'charid' => $charid,
											'attendance' => $arrRosterAttendance[$uid],
											'logcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd),'logs'),
											'postcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd),'posts'),
											'totalcount' => $this->awe->count_user_log_post($uid, strtotime($tDateStart), strtotime($tDateEnd)),
										); 
//										$departments[$charid] = $cdept;
									}
								} //end foreach users
							}
							
							
							$html ='';
							$html = '<span class="reportDate">Dates: '.strftime($aweSettings['awe_txtDateFormat'],$tDateStart).' to '.strftime($aweSettings['awe_txtDateFormat'],$tDateEnd).'</span>';
							$html .= $this->awe->template_make_roster_html($characters,$aweSettings['awe_chkPresenceTags'],$arrRosterAttendanceTags,$aweSettings['awe_chkShowRankImagesRoster']);
							break;
						case 'statistics':
							$html ='';
							break;
						default: //freetext
							$html = nl2br($arrSections[$section->section_id]);
							break;
					}
					$tOutput[$c]['html']  = $html;
				} //end if sec->num_rows >0
				$c++; //counter
			} //end foreach section
		}//end if sectionquery
		
		
		//print out the html result:
		echo $this->awe->template_replace_tag($template['header'],'%%reporttitle%%',$aweSettings['awe_txtReportTitle']);
		
		//print out sections:
		foreach ($tOutput as $sec) {
			if (!empty($sec['html'])) {
				echo $this->awe->template_replace_tag($template['section_title'],'%%section_title%%',$sec['title']);
				echo $this->awe->template_replace_tag($template['section_content'],'%%section_content%%',$sec['html']);
			}
		}
		$credits = '<div style="font-size: 80%;">Report generated by <a href="https://github.com/mooeypoo/aweSimReport-2.0" target="_blank">aweSimReport Generator.</a></div>';
		echo  $this->awe->template_replace_tag($template['section_content'],'%%section_content%%',$credits);
	
		echo $this->awe->template_replace_tag($template['footer'],'%%footer%%',$aweSettings['awe_txtTemplateFooter']);

	}

	function awe_edit_section() {
		/* load the resources */
		$this->load->model('awesimreport_model', 'awe');

		$head = "Edit Section";
		/* data being sent to the facebox */
		$data['header'] = $head;

		$secID = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : 0;
/*		$data['secID'] = $secID;*/

		//check if exists:
		$section = $this->awe->get_section_details($secID);
		if ($section->num_rows() > 0) {
			$row = $section->row();
			$data['secID'] = $row->section_id;
			//check if it's a system section:
			if (($row->section_userdefined)==1) { //custom userdefined section
				/* input parameters */
				$data['text'] = "Use the form below to edit the section.";
				$data['inputs'] = array(
					'secName' => array(
						'name' => 'secName',
						'value' => $row->section_name,
						'readonly' => 'readonly',
						'class' => 'hud'),
					'secTitle' => array(
						'name' => 'secTitle',
						'value' => $row->section_title,
						'class' => 'hud'),
					'secDefaultContent' => array(
						'name' => 'secDefaultContent',
						'value' => $row->section_default,
						'class' => 'hud'),
					'submit' => array(
						'type' => 'submit',
						'class' => 'hud_button',
						'name' => 'submit',
						'value' => 'submit',
						'content' => 'Edit Section')
				);
				$view_location = 'awe_edit_section';
			} else { //system section
				/* input parameters */
				$data['text'] = "This is a system section, and cannot be deleted. You can use this form to edit the title given to this section in the report itself. To edit specific properties, please go to awSimReport Settings.";
				$data['inputs'] = array(
					'secName' => array(
						'name' => 'secName',
						'value' => $row->section_name,
						'readonly' => 'readonly',
						'class' => 'hud'),
					'secTitle' => array(
						'name' => 'secTitle',
						'value' => $row->section_title,
						'class' => 'hud'),
					'submit' => array(
						'type' => 'submit',
						'class' => 'hud_button',
						'name' => 'submit',
						'value' => 'submit',
						'content' => 'Edit Section')
				);
				$view_location = 'awe_edit_system_section';
			}
		}
		/* figure out the skin */
		$skin = $this->session->userdata('skin_admin');
				
		/* figure out where the view should come from */
		$ajax = ajax_location($view_location, $skin, 'admin');
				
		/* write the data to the template */
		$this->template->write_view('content', $ajax, $data);
				
		/* render the template */
		$this->template->render();

	} /* end awe_add_section */
		
	
	function awe_add_active_section() { 
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');
			
			$sections = $this->awe->get_section_order();
			
			if ($sections->num_rows() > 0) {
				$last = $sections->last_row();
				$order = $last->section_order + 1;
			}
			$sec_id = $this->input->post('secid', TRUE);
			
			$insert_array = array(
				'section_id' => $sec_id,
				'section_order' => (isset($order)) ? $order : 0,
			);
				
			$insert = $this->awe->create_section_order_entry($insert_array);
			
			if ($insert > 0) {
				$message = "Section added to report.";
	
				$flash['status'] = 'success';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			} else {
				$message = "There was a problem adding the section to the report. Please try again.";
	
				$flash['status'] = 'error';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			}
			
			echo $output;
		}
	}
	
	function awe_remove_active_section() { 
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');

			$sec_id = $this->input->post('secid', TRUE);
			
			$delete = $this->awe->delete_section_order($sec_id);
			
			//renumber:
			$this->awe->renumber_sections_order();
			
			if ($delete > 0) {
				$message = "Section removed.";
	
				$flash['status'] = 'success';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			} else {
				$message = "There was a problem removing this section. Please try again.";
	
				$flash['status'] = 'error';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			}
			
			echo $output;
		}
	}
	function awe_save_active_sections() {
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');
			
			$sections = $this->input->post('sec', TRUE);
			
			$empty = $this->awe->empty_active_sections_order();
			
			$i = 0;
			$count = 0;
			
			foreach ($sections as $s) {
				$insert_array = array(
					'section_id' => $s,
					'section_order' => $i
				);
				
				$insert = $this->awe->create_section_order_entry($insert_array);
				$count+= $insert;
				
				++$i;
			}
			
			if ($count > 0) {
				$message = "Active section order updated."; 
				
				$flash['status'] = 'success';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			} else {
				$message = "An error has occured while trying to update the sections. Please try again.";
	
				$flash['status'] = 'error';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			}
			
			echo $output;
		}
	} /* end awe_save_active_sections */
	
	function awe_delete_section_permanently() { 
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');

			$sec_id = $this->input->post('secid', TRUE);
			
			$delete = $this->awe->delete_section_permanently($sec_id);
			
			//renumber:
			$rearrange=$this->awe->renumber_sections_order();
			
			if ($delete > 0) {
				$message = "Section removed.";
	
				$flash['status'] = 'success';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			} else {
				$message = "There was a problem removing this section. Please try again.";
	
				$flash['status'] = 'error';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			}
			
			echo $output;
		}
	}

	/*=== SETTINGS ===*/

	function awe_settings_save() {
		if (IS_AJAX) {
			$upArr['awe_txtSimStart'] = array(
				'setting_value' => $this->input->post('txtSimStart')
			);
			$upArr['awe_txtSimEnd'] = array(
				'setting_value' => $this->input->post('txtSimEnd')
			);
			$upArr['awe_txtDateFormat'] = array(
				'setting_value' => $this->input->post('txtDateFormat')
			);
			$upArr['awe_txtReportDuration'] = array(
				'setting_value' => $this->input->post('txtReportDuration')
			);
			$upArr['awe_txtEmailSubject'] = array(
				'setting_value' => $this->input->post('txtEmailSubject')
			);
			$upArr['awe_txtReportTitle'] = array(
				'setting_value' => $this->input->post('txtReportTitle')
			);
			$upArr['awe_txtTemplateFooter'] = array(
				'setting_value' => $this->input->post('txtTemplateFooter')
			);
			$upArr['awe_txtEmailRecipients'] = array(
				'setting_value' => $this->input->post('txtEmailRecipients')
			);
			$upArr['awe_chkPresenceTags'] = array(
				'setting_value' => $this->input->post('chkPresenceTags')
			);
			$upArr['awe_txtPresenceTag_Present'] = array(
				'setting_value' => $this->input->post('txtPresenceTag_Present')
			);
			$upArr['awe_txtPresenceTag_Unexcused'] = array(
				'setting_value' => $this->input->post('txtPresenceTag_Unexcused')
			);
			$upArr['awe_txtPresenceTag_Excused'] = array(
				'setting_value' => $this->input->post('txtPresenceTag_Excused')
			);
			$upArr['awe_chkShowRankImagesRoster'] = array(
				'setting_value' => $this->input->post('chkShowRankImagesRoster')
			);
			$upArr['awe_chkShowRankImagesCOC'] = array(
				'setting_value' => $this->input->post('chkShowRankImagesCOC')
			);

			if (isset($upErr)) { unset($upErr); }
			foreach ($upArr as $key => $value) {
				/*echo $key." => ".$value."<br />";*/
				$update = $this->settings->update_setting($key , $value);
				if ($update > 0) {
					//print $key."\n";
//					echo "success";
				} else {
					//echo $key.":ERROR";
					$upErr[$key]= $value;
				} 
			} 
			if (isset($upErr)) {
				echo "Error: An error has occured while trying to update settings.<br />";
				echo "Error Details:<br />";
				echo "===<br />";
				foreach ($upArr as $key => $value) {
					echo $key." => ".$value."<br />";
				}
				echo "===<br />";
				echo "If this error persists, please consider submitting a bug report with the above details.";
			} else {
				echo 'success';
			}
		} 
	} /* end awe_settings_save */
	
	/*= TEMLPATES =*/
	function awe_switch_templates() {
		if (IS_AJAX) {
			$id = $this->input->post('tmplid');
			$upArr = array('setting_value' => $id);
			$update = $this->settings->update_setting('awe_ActiveTemplate', $upArr);
			
			if ($update > 0) {
				$message = "Template updated successfully.";
	
				$flash['status'] = 'success';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			} else {
				$message = "There was a problem updating the active template. Please try again.";
	
				$flash['status'] = 'error';
				$flash['message'] = text_output($message);
					
				$output = $this->load->view('_base/admin/pages/flash', $flash, TRUE);
			}
			
			echo $output;
		}
	}
	
	
	/*= GENERATOR =*/

	function awe_save_report() {
		/* load the resources */
		$this->load->model('awesimreport_model', 'awe');

		$head = "Save Report";
		/* data being sent to the facebox */
		$data['header'] = $head;

		$reportID = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : 0;

		//check if exists:
		$section = $this->awe->get_saved_report_details($reportID);
		if ($section == FALSE) { //new report to save:
				/* input parameters */
			$data['text'] = "Choose a name for your saved report.";
			$data['inputs'] = array(
				'txtReportID' => array(
					'name' => 'secReportID',
					'value' => '0',
					'readonly' => 'readonly',
					'class' => 'hud'),
				'txtSavedName' => array(
					'name' => 'txtSavedName',
				/*	'value' => $row->report_name,*/
					'class' => 'hud'),
				'submit' => array(
					'type' => 'submit',
					'class' => 'hud_button',
					'name' => 'submit',
					'value' => 'submit',
					'content' => 'Edit Section')
			);
			$data['formAction']='newReport';
		} else {
				/* input parameters */
			$data['text'] = "You are working on an existing report.<br />You can choose a new name for this version, but saving will <strong>override the previos version</strong>!";
			$data['inputs'] = array(
				'txtReportID' => array(
					'name' => 'secReportID',
					'value' => $row->report_id,
					'readonly' => 'readonly',
					'class' => 'hud'),
				'txtSavedName' => array(
					'name' => 'txtSavedName',
					'value' => $row->report_name,
					'class' => 'hud'),
				'submit' => array(
					'type' => 'submit',
					'class' => 'hud_button',
					'name' => 'submit',
					'value' => 'submit',
					'content' => 'Edit Section')
			);
			$data['formAction']='saveReport';
		}
		
		/* figure out the skin */
		$skin = $this->session->userdata('skin_admin');
				
		/* figure out where the view should come from */
		$ajax = ajax_location('awe_save_report', $skin, 'admin');
				
		/* write the data to the template */
		$this->template->write_view('content', $ajax, $data);
				
		/* render the template */
		$this->template->render();

	} /* end awe_save_report */

	
	function awe_tooltip_saved_report() {
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');
			//custom sections:
			$reportID = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : 0;
/*			$id = (int)($this->input->post('id')); */
			$report = $this->awe->get_saved_report_details($reportID);
			if ($report !== FALSE) {
				print '<strong>'.$report->report_id.'</strong>';
			} else {
				print 'ERROR. Please refresh the page.';
			}
			
		}
		
	} //end awe_tooltip_saved_report
	
	
	/* ARCHIVE */
	function awe_publish_archive() {
		if (IS_AJAX) {
			/* load the resources */
			$this->load->model('awesimreport_model', 'awe');

			$nID = $this->input->post('tid');
			$action = $this->input->post('act');
			
			if ($action=='unpublish') { $stat = 'hidden'; }
			else { $stat = 'published'; }
			
			$update_array = array('report_status' => $stat);
			$update = $this->awe->update_saved_report($nID,$update_array);
			
			if ($update>0) { echo 'success'; }
			else { echo 'fail'; }
		}
	}
	
	/** ========================== **/	
	/**       END aweSimReport FUNCTIONS          **/
	/** ========================== **/	

	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */