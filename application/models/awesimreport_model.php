<?php
/*
|---------------------------------------------------------------
| AWESIMREPORT MODEL
|---------------------------------------------------------------
|
| File: models/awesimreport_model.php
| System Version: 1.0
|
| Model used to access the awesimreport table
|
*/

class Awesimreport_model extends Model {

	function Awesimreport_model()
	{
		parent::Model();
		
		/* load the db utility library */
		$this->load->dbutil();
	}
	
	/*
	|---------------------------------------------------------------
	| RETRIEVE METHODS
	|---------------------------------------------------------------
	*/

	function get_section_details($sect_id = 0) {
		$query = $this->db->get_where('awe_sections', array('section_id' => $sect_id));
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	
	function get_section_default($sect_id = 0) {
		$query = $this->db->get_where('awe_sections', array('section_id' => $sect_id));
		if ($query->num_rows() > 0) {
			$row = $query->row();	
			return $row->section_default;
		}
		return FALSE;
	}
	
	function get_all_sections() {
		$query = $this->db->get('awe_sections');
		return $query;
	}
	function get_all_userdefined_sections() {
		$query = $this->db->get_where('awe_sections', array('section_userdefined' => 1));
		return $query;
	}

	function get_section_order() {
		$this->db->order_by('section_order', 'ASC');
		$query = $this->db->get('awe_section_order');
		return $query;
	}
	
	function get_saved_report_details($id = 0) {
		$query = $this->db->get_where('awe_saved_reports', array('report_id' => $id));
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row;
		}
		return FALSE;
	}
	
	function check_section_active($sec_id = 0) {
		$query = $this->db->get_where('awe_section_order', array('section_id' => $sec_id));
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	
	function get_section_by_name($secname = '') {
		$query = $this->db->get_where('awe_sections', array('section_name' => $secname));
		
		if ($query->num_rows() > 0) {
			$row = $query->row();	
			return $row->section_id;
		}
		
		return FALSE;
	}
	

	function get_all_templates() {
		$query = $this->db->get('awe_templates');
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}

	function get_template_details($id = 0) {
		$query = $this->db->get_where('awe_templates', array('template_id' => $id));
		if ($query->num_rows() > 0) {
			$row = $query->row();
			return $row;
		}
		return FALSE;
	}

	

	function count_archive() {
		//don't show saved reports that aren't published
		$this->db->where('report_status !=', 'saved');
		$query = $this->db->get('awe_saved_reports');
		return $this->db->count_all_results();
	}

	function get_saved_reports($status ='active') {
		$query = $this->db->get_where('awe_saved_reports', array('report_status' => $status));
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	
	function get_template_content($id = 0) {
		$this->load->helper('file');
		$template = $this->get_template_details($id);
		if ($template !== FALSE) {
			$tpath = APPPATH.'assets/aweSimReportTemplates/'.$template->template_folder;
			$tmpl['header'] = read_file($tpath.'/header.php');
			$tmpl['section_title'] = read_file($tpath.'/section_title.php');
			$tmpl['section_content'] = read_file($tpath.'/section_content.php');
			$tmpl['footer'] = read_file($tpath.'/footer.php');

			$timgpath = base_url().'application/assets/aweSimReportTemplates/'.$template->template_folder.'/'.$template->template_imagefolder;

			foreach ($tmpl as $key => $val) {
		 		$tmpl[$key] = str_replace('%%images%%', $timgpath, $tmpl[$key]);
			}
			return $tmpl;
		} else {
			return FALSE;
		}
	}


	function get_archived_reports($status = 'all',$limmin = 1,$recnum = 10) {
	     /*
		 param1  => 'y','n'
		 param2  => number 
		 param3  => number
	     */
	     $this->db->from('awe_saved_reports');
		if ($status=='all') { //return all archived results
			$this->db->where('report_status !=', 'saved');
		} else {
			$this->db->where('report_status', $status);
		}
		$this->db->limit($recnum,$limmin);
		$query = $this->db->get();
		
		return $query;
	}
     
	 function get_template($id = '', $return = '') {
	     $query = $this->db->get_where('awreport_templates', array('tem_id' => $id));
	     
	     $row = ($query->num_rows() > 0) ? $query->row() : FALSE;
	     
	     if (!empty($return) && $row !== FALSE) {
		 if (!is_array($return)) {
		     return $row->$return;
		 } else {
		     $array = array();
		     foreach ($return as $r) {
			 $array[$r] = $row->$r;
		     }
		     return $array;
		 }
	     }
	     return $row;
	 }


	
	
	/*
	|---------------------------------------------------------------
	| BUILD TEMPLATE METHODS
	|---------------------------------------------------------------
	*/
	function template_replace_tag($content='', $tag_name ='', $tag_title ='') {
		return str_replace($tag_name, $tag_title, $content);
	}
	
	function template_make_roster_html($charsArray,$useAttendance='',$attTags = '',$useRanks='') {
		$output = '';
		//HEADER
		$output  = '<table cellspacing="0" cellpadding="0" width="100%" class="roster">';
		$output .= '<thead>';
		$output .= '<tr class="tblheader">';
		if ($useRanks == 'checked') {
			$output .= '<th align="center">Rank</th>';
		}
		$output .= '<th colspan=2 align="center">Name</th>';
		if ($useAttendance =='checked') {
			$output .= '<th align="center">Presence</th>';
		}
		$output .= '<th align="center">Log Count</th>';
		$output .= '</tr>';

		//TABLE CONTENT:
		$total_logcount = 0;
		
		
		foreach ($charsArray as $dept) {
			if ((isset($dept['chars'])) && (count($dept['chars'])>0)) {	
				$output .= '<tr><td colspan="5"align="center" class="dept"><span style="margin-top:3px; margin-bottom: 3px; font-weight:bold;">'.strtoupper($dept['deptname']).'</span></td></tr>';
				foreach ($dept['chars'] as $char) {
					$output .= '<tr>';
					if ($useRanks == 'checked') {
						$output .= '<td width="70">'.img($char['rank_img']).'</td>';
					}
					$output .= '<td>'; //charname 
					$output .= '<span class="charname">'.$char['char_name'].'</span><br />';
					$output .= '<span class="userlinks" style="font-size: 80%">'.anchor('personnel/user/'. $char['id'], 'User Account').' | ';
					$output .= anchor('personnel/character/'. $char['charid'], 'Character Bio').'</span>';
					$output .= '</td>';
					$output .= '<td>'; //char position
					$output .= '<span style="font-size: 80%">('.$char['position'].')</span>';
					$output .= '</td>';
					if ($useAttendance =='checked') {
						$output .= '<td align="center">'; //presence
						if (($char['attendance'] == 'loa') || ($char['attendance'] == 'eloa')) {
							$output .= '<span class="loa"><strong>'.$char['attendance'].'</strong></span>';
						}
						$output .= '<span class="attendance">'.$char['attendance'].'</span>';
						$output .= '</td>';
					}
					$output .= '<td align="center">'; //logcount
					$output .= '<span class="count">'.$char['totalcount'].'</span>';
					$output .= '</td>'; 
					
					$total_logcount = $total_logcount + $char['totalcount'];
					
					$output .= '</tr>';
				} //end foreach user
			} //end if dept has users
		} //end foreach $charsArray

		$output .= '<tr><td colspan=6 align="right" class="totalcount"> Total Logcount: '.$total_logcount.'</td></tr>';
		$output .= '</table>';

		return $output;
	} 
	/*
	|---------------------------------------------------------------
	| CREATE METHODS
	|---------------------------------------------------------------
	*/

	function add_new_section($data = '') {
		$query = $this->db->insert('awe_sections', $data);
		
		$this->dbutil->optimize_table('awe_sections');
		
		return $this->db->affected_rows();
	}
	
	function add_saved_report($data = '') {
		$query = $this->db->insert('awe_saved_reports', $data);
		
		$this->dbutil->optimize_table('awe_saved_reports');
		
		return $this->db->affected_rows();
	}
	
	function create_section_order_entry($data = '')
	{
		$query = $this->db->insert('awe_section_order', $data);
		
		/* optimize the table */
		$this->dbutil->optimize_table('awe_section_order');
		
		return $query;
	}

	
	/*
	|---------------------------------------------------------------
	| UPDATE METHODS
	|---------------------------------------------------------------
	*/

	function update_section($id = '', $data = '') {
		$this->db->where('section_id', $id);
		$query = $this->db->update('awe_sections', $data);
		
		$this->dbutil->optimize_table('awe_sections');
		
		return $query;
	}


	function update_saved_report($id = '', $data = '') {
		$this->db->where('report_id', $id);
		$query = $this->db->update('awe_saved_reports', $data);
		
		$this->dbutil->optimize_table('awe_saved_reports');
		return $this->db->affected_rows();
	}
	
	function renumber_sections_order() {
		$this->db->order_by('section_order', 'ASC');
		$sec_order = $this->db->get('awe_section_order');
		$counter = 0;
		$secorder = Array();
		if ($sec_order->num_rows() > 0) {
			foreach ($sec_order->result() as $sec_row) {
				$counter++;
				$secorder[$counter] = $sec_row->section_id;
			}
			$this->db->empty_table('awe_section_order');
			$insertcounter = 0;
			$endquery='';
			foreach ($secorder as $order => $secid) {
				$insert_array = array(
					'section_id' => $secid,
					'section_order' => $order
				);
				$endquery.=$this->db->insert('awe_section_order',$insert_array);
				$endquery.='<br>';
				$insertcounter++;
			}
		}
		return $counter.'|'.$insertcounter;
	}
	
	/*
	|---------------------------------------------------------------
	| DELETE METHODS
	|---------------------------------------------------------------
	*/
	
	
	function delete_section_order($id = '') {
		$query = $this->db->delete('awe_section_order', array('section_id' => $id));
		
		$this->dbutil->optimize_table('awe_section_order');
		
		return $query;
	}
	
	function delete_section_permanently($id = '') {
		$query = $this->db->delete('awe_sections', array('section_id' => $id));
		
		$this->dbutil->optimize_table('awe_sections');
		
		return $this->db->affected_rows();
	}

	function empty_active_sections_order() {
		$this->db->empty_table('awe_section_order');
	}

	function delete_saved_report($id = '') {
		$query = $this->db->delete('awe_saved_reports', array('report_id' => $id));
		
		$this->dbutil->optimize_table('awe_saved_reports');
		
		return $query;
	}

	/*
	|---------------------------------------------------------------
	| GENERATOR METHODS
	|---------------------------------------------------------------
	*/
	function count_user_log_post($id, $startDate ='',$endDate ='',$type='all') {
	//	return $startDate.' to '.$endDate;
	
		$countposts = 0;
		$countlogs = 0;
		$count = 0;
		
		    $this->db->from('posts');
		    $this->db->where('post_status', 'activated');
		    
		    $this->db->where('post_date >=', $startDate);
		    $this->db->where('post_date <=', $endDate);
		    
		    $string = "(post_authors_users LIKE '%,$id' OR post_authors_users LIKE '$id,%' OR post_authors_users LIKE '%,$id,%' OR post_authors_users = $id)";
			
		    $this->db->where("($string)", NULL);
			
		    $countposts = $this->db->count_all_results();

		    $this->db->from('personallogs');
		    $this->db->where('log_status', 'activated');
		    
		    $this->db->where('log_date >=', $startDate);
		    $this->db->where('log_date <=', $endDate);
		    
		    $this->db->where('log_author_user', $id);
			
		    $countlogs = $this->db->count_all_results();

		if ($type=='posts') {
			return ($countposts);
		} elseif ($type=='logs') {
			return ($countlogs);
		} else {
			return ($countposts) + ($countlogs);
		}

	}	

	
	
}
?>