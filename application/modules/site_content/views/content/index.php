<?php

$num_columns	= 12;
$can_delete	= $this->auth->has_permission('Site_Content.Content.Delete');
$can_edit		= $this->auth->has_permission('Site_Content.Content.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class='admin-box'>
	<h3>
		<?php echo lang('site_content_area_title'); ?>
	</h3>
	<?php echo form_open($this->uri->uri_string()); ?>
		<table class='table table-striped'>
			<thead>
				<tr>
					<?php if ($can_delete && $has_records) : ?>
					<th class='column-check'><input class='check-all' type='checkbox' /></th>
					<?php endif;?>
					
					<th><?php echo lang('site_content_field_type'); ?></th>
					<th><?php echo lang('site_content_field_volume'); ?></th>
					<th><?php echo lang('site_content_field_attach'); ?></th>
					<th><?php echo lang('site_content_field_title'); ?></th>
					<th><?php echo lang('site_content_field_tags'); ?></th>
					<th><?php echo lang('site_content_field_notes'); ?></th>
					<th><?php echo lang('site_content_field_pub_dt'); ?></th>
					<th><?php echo lang('site_content_field_start_dt'); ?></th>
					<th><?php echo lang('site_content_field_end_dt'); ?></th>
					<th><?php echo lang('site_content_column_deleted'); ?></th>
					<th><?php echo lang('site_content_column_created'); ?></th>
					<th><?php echo lang('site_content_column_modified'); ?></th>
				</tr>
			</thead>
			<?php if ($has_records) : ?>
			<tfoot>
				<?php if ($can_delete) : ?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'>
						<?php echo lang('bf_with_selected'); ?>
						<input type='submit' name='delete' id='delete-me' class='btn btn-danger' value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('site_content_delete_confirm'))); ?>')" />
					</td>
				</tr>
				<?php endif; ?>
			</tfoot>
			<?php endif; ?>
			<tbody>
				<?php
				if ($has_records) :
					foreach ($records as $record) :
				?>
				<tr>
					<?php if ($can_delete) : ?>
					<td class='column-check'><input type='checkbox' name='checked[]' value='<?php echo $record->id; ?>' /></td>
					<?php endif;?>
					
				<?php if ($can_edit) : ?>
					<td><?php echo anchor(SITE_AREA . '/content/site_content/edit/' . $record->id, '<span class="icon-pencil"></span> ' .  $record->type); ?></td>
				<?php else : ?>
					<td><?php
					$options = array(
						'Magazine'=>'Magazine',
                    	'GO'=>'GO',
                    	'Spl Edition'=>'Spl Edition',
                    	'Misc'=>'Misc'
					);
					echo form_dropdown(array('name' => 'type', 'required' => 'required'), $options, set_value('type', isset($record->type) ? $record->type : ''), 'Type' . lang('bf_form_label_required'));					
					?></td>
				<?php endif; ?>
					<td><?php e($record->volume); ?></td>
					<td>
						<?php if(isset($record) && isset($record->attach) && !empty($record->attach)) :
							$attachment = unserialize($record->attach);
						?> 
							<a href="<?php echo base_url() . 'uploads/' . $attachment['file_name'] ?>" target="_blank" >
								<?php echo $attachment['file_name']; ?>
							</a>
						<?php endif; ?>
					</td>
					<td><?php e($record->title); ?></td>
					<td><?php e($record->tags); ?></td>
					<td><?php e($record->notes); ?></td>
					<td><?php e($record->pub_dt); ?></td>
					<td><?php e($record->start_dt); ?></td>
					<td><?php e($record->end_dt); ?></td>
					<td><?php echo $record->deleted > 0 ? lang('site_content_true') : lang('site_content_false'); ?></td>
					<td><?php e($record->created_on); ?></td>
					<td><?php e($record->modified_on); ?></td>
				</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan='<?php echo $num_columns; ?>'><?php echo lang('site_content_records_empty'); ?></td>
				</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
    echo form_close();
    
    echo $this->pagination->create_links();
    ?>
</div>