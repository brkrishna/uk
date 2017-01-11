<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Content controller
 */
class Content extends Admin_Controller
{
    protected $permissionCreate = 'Site_content.Content.Create';
    protected $permissionDelete = 'Site_content.Content.Delete';
    protected $permissionEdit   = 'Site_content.Content.Edit';
    protected $permissionView   = 'Site_content.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('site_content/site_content_model');
        $this->lang->load('site_content');
        
            Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce.js'));
            Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce_init.js'));
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'content/_sub_nav');

        Assets::add_module_js('site_content', 'site_content.js');
    }

    /**
     * Display a list of Site Content data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked)) {

                // If any of the deletions fail, set the result to false, so
                // failure message is set if any of the attempts fail, not just
                // the last attempt

                $result = true;
                foreach ($checked as $pid) {
                    $deleted = $this->site_content_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('site_content_delete_success'), 'success');
                } else {
                    Template::set_message(lang('site_content_delete_failure') . $this->site_content_model->error, 'error');
                }
            }
        }
        $pagerUriSegment = 5;
        $pagerBaseUrl = site_url(SITE_AREA . '/content/site_content/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->site_content_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->site_content_model->limit($limit, $offset);
        
        $records = $this->site_content_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('site_content_manage'));

        Template::render();
    }
    
    /**
     * Create a Site Content object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_site_content()) {
                log_activity($this->auth->user_id(), lang('site_content_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'site_content');
                Template::set_message(lang('site_content_create_success'), 'success');

                redirect(SITE_AREA . '/content/site_content');
            }

            // Not validation error
            if ( ! empty($this->site_content_model->error)) {
                Template::set_message(lang('site_content_create_failure') . $this->site_content_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('site_content_action_create'));

        Template::render();
    }
    /**
     * Allows editing of Site Content data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('site_content_invalid_id'), 'error');

            redirect(SITE_AREA . '/content/site_content');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_site_content('update', $id)) {
                log_activity($this->auth->user_id(), lang('site_content_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'site_content');
                Template::set_message(lang('site_content_edit_success'), 'success');
                redirect(SITE_AREA . '/content/site_content');
            }

            // Not validation error
            if ( ! empty($this->site_content_model->error)) {
                Template::set_message(lang('site_content_edit_failure') . $this->site_content_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->site_content_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('site_content_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'site_content');
                Template::set_message(lang('site_content_delete_success'), 'success');

                redirect(SITE_AREA . '/content/site_content');
            }

            Template::set_message(lang('site_content_delete_failure') . $this->site_content_model->error, 'error');
        }
        
        Template::set('site_content', $this->site_content_model->find($id));

        Template::set('toolbar_title', lang('site_content_edit_heading'));
        Template::render();
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_site_content($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->site_content_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
		
		$data = array();
				//$data = $this->party_documents_model->prep_data($this->input->post());
				if (isset($_FILES['attach']) && is_array($_FILES['attach']) && $_FILES['attach']['error'] != 4)
				{
					// make sure we only pass in the fields we want
					$file_path = $this->config->item('upload_dir');
					$config['upload_path']		= $file_path;
					$config['allowed_types']	= 'pdf|jpeg|gif|docx|doc|xlsx|xls';
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('attach'))
					{
						return array('error'=>$this->upload->display_errors());
					}else{
						$data['attach'] = serialize($this->upload->data());			
					}		
				}
        //$data = $this->site_content_model->prep_data($this->input->post());
		
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        $data['type'] = $this->input->post('type');
		$data['volume'] = $this->input->post('volume');
		$data['title'] = $this->input->post('title');
		$data['tags'] = $this->input->post('tags');
		$data['notes'] = $this->input->post('notes');
		$data['pub_dt']	= $this->input->post('pub_dt') ? $this->input->post('pub_dt') : '0000-00-00';
		$data['start_dt']	= $this->input->post('start_dt') ? $this->input->post('start_dt') : '0000-00-00';
		$data['end_dt']	= $this->input->post('end_dt') ? $this->input->post('end_dt') : '0000-00-00';

        $return = false;
        if ($type == 'insert') {
            $id = $this->site_content_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->site_content_model->update($id, $data);
        }

        return $return;
    }
	
	public function remove_attachment()
	{
		$id = $this->uri->segment(5);
		$success = false;
		// Handle a single-user purge
		if (!empty($id) && is_numeric($id))
		{
			$site_contents = $this->site_content_model->find($id);
			if (isset($site_contents) && isset($site_contents->attach))
			{
				$this->delete_attachments( $site_contents->attach );
				$data = array('attachment'=>'');
				$success = $this->site_content_model->update($id, $data);
			}
		}
		if (!$success)
		{
			Template::set_message('Attachment removal failed.', 'error');
		}
		else
		{
			Template::set_message('Attachment removed.', 'success');
		}
		$this->edit();
	}
	/**
	 * Deletes Attachments or dies trying to. ( Chuck Norris would just chop them off I'm sure )
	 *
	 * @param $attachment Serialized data for attachment
	 */
	private function delete_attachments( $attachment )
	{
		$attachment = unserialize( $attachment );
		$file_dir = $this->config->item('upload_dir');
		if (file_exists( $file_dir . '/' . $attachment['file_name']) )
		{
			$deleted = unlink( $file_dir . '/' .$attachment['file_name']);
			if ( $deleted == false )
			{
				$err = sprintf('Problem deleting attachment file: "%s"', $attachment['file_name']);
				Template::set_message($err, 'error');
				log_message('error', $err);
			}
			unset ( $deleted );
		}
	} 
}