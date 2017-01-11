<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Site_content controller
 */
class Site_content extends Front_Controller
{
    protected $permissionCreate = 'Site_content.Site_content.Create';
    protected $permissionDelete = 'Site_content.Site_content.Delete';
    protected $permissionEdit   = 'Site_content.Site_content.Edit';
    protected $permissionView   = 'Site_content.Site_content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('site_content/site_content_model');
        $this->lang->load('site_content');
        
            Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce.js'));
            Assets::add_js(Template::theme_url('js/editors/tiny_mce/tiny_mce_init.js'));
            Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
            Assets::add_js('jquery-ui-1.8.13.min.js');
        

        Assets::add_module_js('site_content', 'site_content.js');
    }

    /**
     * Display a list of Site Content data.
     *
     * @return void
     */
    public function index($offset = 0)
    {
        
        $pagerUriSegment = 3;
        $pagerBaseUrl = site_url('site_content/index') . '/';
        
        $limit  = $this->settings_lib->item('site.list_limit') ?: 15;

        $this->load->library('pagination');
        $pager['base_url']    = $pagerBaseUrl;
        $pager['total_rows']  = $this->site_content_model->count_all();
        $pager['per_page']    = $limit;
        $pager['uri_segment'] = $pagerUriSegment;

        $this->pagination->initialize($pager);
        $this->site_content_model->limit($limit, $offset);
        

        // Don't display soft-deleted records
        $this->site_content_model->where($this->site_content_model->get_deleted_field(), 0);
        $records = $this->site_content_model->find_all();

        Template::set('records', $records);
        

        Template::render();
    }
    
}