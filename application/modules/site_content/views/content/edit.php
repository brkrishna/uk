<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('site_content_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($site_content->id) ? $site_content->id : '';

?>
<div class='admin-box'>
    <h3>Site Content</h3>
    <?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <?php // Change the values in this array to populate your dropdown as required
                $options = array(
                    'Magazine'=>'Magazine',
                    'GO'=>'GO',
                    'Spl Edition'=>'Spl Edition',
                    'Misc'=>'Misc'
                );
                echo form_dropdown(array('name' => 'type', 'required' => 'required'), $options, set_value('type', isset($site_content->type) ? $site_content->type : ''), lang('site_content_field_type') . lang('bf_form_label_required'));
            ?>

            <div class="control-group<?php echo form_error('volume') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_volume'), 'volume', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='volume' type='text' name='volume' maxlength='255' value="<?php echo set_value('volume', isset($site_content->volume) ? $site_content->volume : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('volume'); ?></span>
                </div>
            </div>

            <?php if(isset($site_content) && isset($site_content->attach) && !empty($site_content->attach)) : $attachment = unserialize($site_content->attach); ?>    
                <!-- Current Document Display -->
                <div class="control-group">
                    <?php echo form_label(lang('site_content_field_attach'), 'attach', array('class' => 'control-label')); ?>
                    <div class="controls">
                        <ul class="thumbnails">
                            <li class="span6">
                                <a class="lightbox" href="<?php echo base_url() . '/uploads/' . $attachment['file_name'] ?>" target="_blank" >
                                    <?php echo $attachment['file_name']; ?>
                                </a>
                                <p><?php echo anchor(SITE_AREA.'/content/site_content/remove_attachment/'.$site_content->id,'Remove', 'class="btn btn-small btn-danger"'); ?></p>
                            </li>
                        </ul>
                        <input id='doc_file' type='hidden' name='doc_file' value=<?php echo($site_content->attach); ?>/>
                    </div>
                </div>            
            <?php else: ?>
                <div class="control-group<?php echo form_error('attach') ? ' error' : ''; ?>">
                    <?php echo form_label(lang('site_content_field_attach'), 'attach', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='attach' type='file' name='attach'  value="<?php echo set_value('attach', isset($site_content->attach) ? $site_content->attach : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('attach'); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <div class="control-group<?php echo form_error('title') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_title') . lang('bf_form_label_required'), 'title', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='title' type='text' required='required' name='title' maxlength='255' value="<?php echo set_value('title', isset($site_content->title) ? $site_content->title : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('title'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('tags') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_tags') . lang('bf_form_label_required'), 'tags', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'tags', 'id' => 'tags', 'rows' => '5', 'cols' => '80', 'value' => set_value('tags', isset($site_content->tags) ? $site_content->tags : ''), 'required' => 'required')); ?>
                    <span class='help-inline'><?php echo form_error('tags'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('notes') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_notes'), 'notes', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'notes', 'id' => 'notes', 'rows' => '5', 'cols' => '80', 'value' => set_value('notes', isset($site_content->notes) ? $site_content->notes : ''))); ?>
                    <span class='help-inline'><?php echo form_error('notes'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('pub_dt') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_pub_dt') . lang('bf_form_label_required'), 'pub_dt', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='pub_dt' type='text' required='required' name='pub_dt'  value="<?php echo set_value('pub_dt', isset($site_content->pub_dt) ? $site_content->pub_dt : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('pub_dt'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('start_dt') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_start_dt') . lang('bf_form_label_required'), 'start_dt', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='start_dt' type='text' required='required' name='start_dt'  value="<?php echo set_value('start_dt', isset($site_content->start_dt) ? $site_content->start_dt : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('start_dt'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('end_dt') ? ' error' : ''; ?>">
                <?php echo form_label(lang('site_content_field_end_dt') . lang('bf_form_label_required'), 'end_dt', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='end_dt' type='text' required='required' name='end_dt'  value="<?php echo set_value('end_dt', isset($site_content->end_dt) ? $site_content->end_dt : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('end_dt'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('site_content_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/content/site_content', lang('site_content_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Site_Content.Content.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('site_content_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('site_content_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>