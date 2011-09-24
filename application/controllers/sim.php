<?php
/*
|---------------------------------------------------------------
| SIM CONTROLLER
|---------------------------------------------------------------
|
| File: controllers/sim.php
| System Version: 1.0
|
| Controller that handles the SIM part of the system.
|
*/

require_once APPPATH . 'controllers/base/sim_base.php';

class Sim extends Sim_base {
	
	function Sim()
	{
		parent::Sim_base();
	}
	
	/** ====================== **/	
	/** aweSimReport FUNCTIONS **/
	/** ====================== **/	

	function awesimreport() {
		/* load resources */
		$this->load->model('awesimreport_model', 'awe');
		$this->load->model('characters_model', 'char');
		$this->load->model('positions_model', 'pos');
		$this->load->model('depts_model', 'dept');
		$this->load->model('users_model', 'user');

		$archnum = $this->awe->count_archive();
		$pages = ceil((int)($archnum)/10);
		$pg = '1';
		$min=0;
		$data['archnum'] = $archnum;

		if ($this->uri->segment(4, 0, TRUE) > 1) {
		    $pg = $this->uri->segment(4, 0, TRUE);
		}

		if ($pg > $pages) { $pg = $pages; }
		$min = ($pg * 10) - 10;	
				
		$data['pg'] = $pg;
		$data['pages'] = $pages;
			
		$data['images']['icons']['loading'] = array(
		    'src' => img_location('loading-circle.gif', $this->skin, 'admin'),
		    'alt' => '',
		    'class' => 'image'
		);
				
		//go over archive list:
		$archive = $this->awe->get_archived_reports('published',$min);

		if ($archive->num_rows() > 0) {
			foreach ($archive->result() as $item) {
				$curruserid = $item->report_author;
				$currchar = $this->char->get_character($curruserid);
				$curruser = $this->user->get_user($curruserid);
			
				//get the current user (reporting officer):
				$posts = $this->pos->get_position($currchar->position_1);
				$positions = ($posts !== FALSE) ? $posts->pos_name : '';

				if ((int)($currchar->position_2)>0) {
					$positions .= " & ".$this->pos->get_position($currchar->position_2, 'pos_name');
				}

				if ((empty($item->report_date_start)) || (empty($item->report_date_end))) {
					$dStart = '';
					$dEnd = '';
				} else {
					$dStart = date($aweSettings['awe_txtDateFormat'],$item->report_date_start);
					$dEnd = date($aweSettings['awe_txtDateFormat'],$item->report_date_end);
				}

				$data['archive'][$item->report_id] = array(
					'id' => $item->report_id,
					'date_start' => $dStart,
					'date_end' => $dEnd,
					'repofficer' => $this->char->get_character_name($curruserid, TRUE),
					'repofficer_userid' => $item->arch_repofficer_userid,
					'repofficer_charid' => $curruser->main_char,
					'repofficer_position' => $positions,
					'display' => $item->report_status,
					'template' => $item->report_template,
					'date_sent' => $item->report_date_sent,
					'date_sent_visual' => date($aweSettings['awe_txtDateFormat'],$item->report_date_sent)
				);
					
				$pubcheck = '';
				if ($item->report_status=='published') { 
					$pubcheck = 'checked';
				}
				$data['chkPublish'][$item->report_id] = array(
					'name' => 'chkPublish', 
					'id' => 'chkPublish',
					'checked' => $pubcheck,
					'value' => $item->report_id);
	
			} //end foreach
		} else {
			$data['archive'] ='';
		} //end if archive exists
				

		/** SETUP VIEW **/
		$data['header'] = 'aweSimReport: Public Archive';
		/* view locations */
		$currpage = 'sim_awesimreport';
		$currpage_js = 'sim_awesimreport_js';

		$view_loc = view_location($currpage, $this->skin, 'main');
		$js_loc = js_location($currpage_js, $this->skin, 'main'); 

		/* produce write the header */
		$this->template->write('title', $data['header']);
		
		/* produce view */
		$this->template->write_view('javascript', $js_loc, $js_data); 
		$this->template->write_view('content', $view_loc, $data);
		
		/* render the template */
		$this->template->render();
	}

	/** ========================== **/	
	/** END aweSimReport FUNCTIONS **/
	/** ========================== **/	
	
}

/* End of file sim.php */
/* Location: ./application/controllers/sim.php */