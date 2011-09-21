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
	
	/*== AWESIMREPORT FUNCTIONS ==*/

	
	
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
		$head = "Preview Report";
		/* data being sent to the facebox */
		$data['header'] = $head;

//		$data['inputsdata'] = $this->uri->segment(3);
//		$data['inputsdata'] = $this->input->post('inputsdata');

		$tDateStart = $this->input->post('txtReportDateStart', TRUE);
		$tDateEnd = $this->input->post('txtReportDateEnd', TRUE);
		$arrRosterUsers = $this->input->post('chkRosterShowUsers', TRUE);
		$arrRosterAttendance = $this->input->post('rAttendance', TRUE);
		$arrSections = $this->input->post('sections', TRUE);

		$varlist = 'tDateStart='.$tDateStart.'&tDateEnd='.$tDateEnd;

		//prepare the variable list for the iframe:
		$varRosterUsers = '';
		foreach ($arrRosterUsers as $key => $val) {
			$varRosterUsers .= 'arrRosterUsers['.$key.']='.$val.'&';
		}

		$varRosterAttendance = '';
		foreach ($arrRosterAttendance as $key => $val) {
			$varRosterAttendance .= 'arrRosterAttendance['.$key.']='.$val.'&';
		}

		$varSections = '';
		foreach ($arrSections as $key => $val) {
			$varSections .= 'arrSections['.$key.']='.nl2br(str_replace(' ','+',$val)).'&';
		}

		$varlist = $varRosterUsers.$varRosterAttendance.$varSections.'tDateStart='.$tDateStart.'&tDateEnd='.$tDateEnd;

		$data['varlist'] = $varlist;
		/* figure out the skin */
		$skin = $this->session->userdata('skin_admin');
				
		/* figure out where the view should come from */
		$ajax = ajax_location('awe_preview_report', $skin, 'admin');
				
		/* write the data to the template */
		$this->template->write_view('content', $ajax, $data);
				
		/* render the template */
		$this->template->render();

	}
	function awe_preview_report_output() {
//		$this->load->helper('file');
		
		$this->load->model('awesimreport_model', 'awe');
		$this->load->model('characters_model', 'char');
		$this->load->model('positions_model', 'pos');
		$this->load->model('depts_model', 'dept');
		$this->load->model('personallogs_model', 'logs');
		$this->load->model('posts_model', 'posts');
		$this->load->model('ranks_model', 'ranks');
		$this->load->model('news_model', 'news');
		$this->load->model('users_model', 'user');

		//get all variables
		$tDateStart = $this->input->post('tDateStart', TRUE);
		$tDateEnd = $this->input->post('tDateEnd', TRUE);
		$arrRosterUsers = $this->input->post('arrRosterUsers', TRUE);
		$arrRosterAttendance = $this->input->post('arrRosterAttendance', TRUE);
		$arrSections = $this->input->post('arrSections', TRUE);
		
		// grab the settings 
		$settings_array = array(
			'awe_txtSimStart','awe_txtSimEnd','awe_txtDateFormat','awe_txtEmailSubject','awe_txtReportTitle',
			'awe_txtEmailRecipients','awe_chkPresenceTags','awe_txtReportDuration',
			'awe_txtPresenceTag_Present','awe_txtPresenceTag_Unexcused','awe_txtPresenceTag_Excused',
			'awe_chkShowRankImagesRoster','awe_chkShowRankImagesCOC','awe_ActiveTemplate',
		);
		$aweSettings = $this->settings->get_settings($settings_array);
		
		//get current template
		$tmplID = $aweSettings['awe_ActiveTemplate'];		
		$template = $this->awe->get_template_content($tmplID); 
		
/*		print_r($_POST); */
		
		echo $template['header'];
		echo $template['section_title'];
		echo $template['section_contents'];
		echo $template['footer'];
		
		
		//prepare output array:
		$tOutput = array();

		//get section order:
/*		$sectionQuery = $this->awe->get_section_order();
		$c =1;
		if ($sectionQuery->num_rows() > 0) {
			foreach ($sectionQuery->result() as $sID) {
				$sec = $this->awe->get_section_details($sID);
				if ($sec->num_rows() > 0) {
					//get section info:
					$section = $sec->row();
					switch (strtolower($section->section_name)) {
						case 'chain of command':
							
							$coc = $this->char->get_coc();
							$rank_ext = $this->ranks->get_rankcat($this->rank, 'rankcat_location', 'rankcat_extension');

		$rank_ext = $this->ranks->get_rankcat($this->rank, 'rankcat_location', 'rankcat_extension');
		if ($coc->num_rows() > 0) {
			$cocHtml = '<table cellspacing="0" cellpadding="0">';
			foreach ($coc->result() as $item) {
				$cocHtml .= '<tr>';
				if ($item->crew_type == 'active' && empty($item->user)) {
					// skip 
				} else {

					if ($aweSettings['awe_chkShowRankImagesCOC']=='checked') {
						$img_rank = array(
							'src' => rank_location($this->rank, $item->rank_image, $rank_ext),
							'alt' => $item->rank_name,
							'class' => 'image',
							'border' => 0,
						);
						$cocHtml .= '<td width="80">'.img($img_rank).'</td>';
					}
										
					$coc_id = $item->charid;
					$coc_name = $this->char->get_character_name($item->charid, TRUE);
					$coc_position = $item->pos_name;
		
					$cocHtml .= '<td>';
					$cocHtml .= '<strong>'.anchor('personnel/character/'.$item->charid, $coc_name).'</strong><br />';
					$cocHtml .= '<span style="size: 90%;">('.$coc_position.')</span>';
					$cocHtml .= '</td>';
				}
				$cocHtml .= '</tr>';
			} //foreach coc item
			$cocHtml .= '</table>';
		} //end if coc has records

 						
						
							$tOutput[$c]  = str_replace('%%section_title%%',$section->section_title,$template['section_title']);
							$tOutput[$c] .= str_replace('%%section_content%%',$cocHtml,$template['section_content']);
							break;
						case 'report date':
							break;
						case 'reporting officer':
							break;
						case 'roster':
							break;
						case 'statistics':
							break;
						case 'sim time':
							break;
						default: //freetext
							break;
					}
				} //end if sec->num_rows >0
				$c++; //counter
			} //end foreach section
		}//end if sectionquery

*/

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
/*
			if ((isset($upErr)) || (count($upErr) > 0)) { 
			} else {
				echo "success";
			} */
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

/*
	function awe_report_save() {
		if (IS_AJAX) {
			//custom sections:
			$sections = $this->input->post('sections');
			$dateStart = $this->input->post('txtReportStart');
			$dateEnd = $this->input->post('txtReportEnd');
			$showUsers = $this->input->post('chkRosterShowUsers');
			
			$dataArray = array(
					   'sections' => $sections,
					   'showUsers' => $showUsers,
					   );
			
			//build insert array:
			$update_array = array(
				'report_date_start' => $dateStart,
				'report_date_end' => $dateEnd,
				'report_author' => $this->session->userdata('userid'),
				'report_data' => serialize($dataArray),
				'report_sent' => 0,
				'report_saved_date' => time()
			);

										
			/* insert the record 
			$insert = $this->awe->save_report($update_array);
										
			if ($insert > 0) {
				$message = "Report saved successfully.";
			} else {
				$message = 'Err 102: There was a problem editing the requested section. Please try again later.';
			}
			echo $message;
			
		}
	}*/
	
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
	
	/*== END AWESIMREPORT FUNCS ==*/
	
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */