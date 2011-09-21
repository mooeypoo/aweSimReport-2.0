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

	

	function get_saved_reports($status ='active') {
		$query = $this->db->get_where('awe_saved_reports', array('report_status' => $status));
		if ($query->num_rows() > 0) {
			return $query;
		} else {
			return false;
		}
	}
	
	function get_template_content($id = 0){
		$this->load->helper('file');
		$template = $this->get_template_details($id);
		if ($template !== FALSE) {
			$tpath = APPPATH.'assets/aweSimReportTemplates/'.$template->template_folder;
			$tmpl['header'] = read_file($tpath.'/header.php');
			$tmpl['section_title'] = read_file($tpath.'/section_title.php');
			$tmpl['section_content'] = read_file($tpath.'/section_content.php');
			$tmpl['footer'] = read_file($tpath.'/footer.php');

			$timgpath = base_url().'application/assets/aweSimReportTemplates/'.$template->template_folder.'/'.$template->template_imagefolder;

			foreach (tmpl as $key => $val) {
		 		$tmpl[$key] = str_replace('%%images%%',$timgpath,$tmpl[$val]);
			}
			return $tmpl;
		} else {
			return FALSE;
		}
	}
	
	/*
	|---------------------------------------------------------------
	| BUILD TEMPLATE METHODS
	|---------------------------------------------------------------
	*/
/*	function create_coc_html($coc ='', $rank_ext ='',$displayRankImages ='') {
//		$rank_ext = $this->ranks->get_rankcat($this->rank, 'rankcat_location', 'rankcat_extension');
		if ($coc->num_rows() > 0) {
			$cocHtml = '<table cellspacing="0" cellpadding="0">';
			foreach ($coc->result() as $item) {
				$cocHtml .= '<tr>';
				if ($item->crew_type == 'active' && empty($item->user)) {
					// skip 
				} else {
/*					if ($displayRankImages=='checked') {
						$img_rank = array(
							'src' => rank_location($this->rank, $item->rank_image, $rank_ext),
							'alt' => $item->rank_name,
							'class' => 'image',
							'border' => 0,
						);
						$cocHtml .= '<td width="80">'.img($img_rank).'</td>';
					}
										
					$coc_id = $item->charid;
					/*$coc_name = $this->char->get_character_name($item->charid, TRUE);
					$coc_position = $item->pos_name;
		
					$cocHtml .= '<td>';
				/*	$cocHtml .= '<strong>'.anchor('personnel/character/'.$item->charid, 'BLA'.$coc_name).'</strong><br />';
					$cocHtml .= '<span style="size: 90%;">('.$coc_position.')</span>';
					$cocHtml .= '</td>';
				}
				$cocHtml .= '</tr>';
			} //foreach coc item
			$cocHtml .= '</table>';
		} //end if coc has records
		
	}
	*/
	
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
	
	function collect_user_logcount($id = 0, $type = 'all') {
		$count = 0;
		//mission posts:
		$this->db->from('posts');
		$this->db->where('post_status', 'activated');
		
		$this->db->where('post_date >=', $startDate);
		$this->db->where('post_date <=', $endDate);
			
		$string = "(post_authors_users LIKE '%,".$id."' OR post_authors_users LIKE '$id,%' OR post_authors_users LIKE '%,".$id.",%' OR post_authors_users = ".$id.")";
		
		$this->db->where("($string)", NULL);
		
		$count_posts = $this->db->count_all_results();
		
		//personal logs
		$this->db->from('personallogs');
		$this->db->where('log_status', 'activated');
			
		$this->db->where('log_date >=', $startDate);
		$this->db->where('log_date <=', $endDate);
			
		$this->db->where('log_author_user', $id);
				
		$count_logs = $this->db->count_all_results();
		
		if ($type == 'logs') {
			$count = (int)($count_logs);
		} elseif ($type == 'posts') {
			$count = (int)($count_posts);
		} else {
			$count = (int)($count_posts) + (int)($count_logs);
		}
		return $count;
	}
	
	
}
?>