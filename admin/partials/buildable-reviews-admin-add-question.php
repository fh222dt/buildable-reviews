<?php

/**
 * View for adding new question to review
 */
 require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
 $sql = new BR_SQL_Quieries();

 ?><div class="wrap">
		<h2>Add question</h2>

		<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
			<table class="form-table">
				<tbody>
                    <tr valign="top">
						<th scope="row"><?php _e( 'Name', 'textdomain' ); ?></th>
						<td>
							<input type="text" name="question-name" placeholder="<?php _e( 'Enter a name...' , 'textdomain' ); ?>" required />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'textdomain' ); ?></th>
						<td>
							<textarea id="desciption" name="desciption" type="text" maxlength="350" cols="100" placeholder="<?php _e( 'Enter a description...' , 'textdomain' ); ?>" required ></textarea>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Type', 'textdomain' ); ?></th>
						<td>
							<select name="type" id="type">
                                <?php
                                    $types = $sql->get_all_question_types();
                                    foreach($types as $type) {
                                        echo '<option value="'.$type['question_type_id'].'">'.$type['question_type_name'].'</option>';
                                    }
                                 ?>
							</select>
						<label><?php _e( 'What type of question do you want to add?', 'textdomain' );?></label>
						</td>
					</tr>
				<tr valign="top">
						<th scope="row"><?php _e( 'Answer options', 'textdomain' ); ?></th>
						<td>
                            <select name="options[]" multiple='multiple'>
                                <?php
                                    $options = $sql->get_all_answer_options();
                                    foreach($options as $option) {
                                        echo '<option value="'.$option['option_id'].'">'.$option['option_name'].'</option>';
                                    }
                                 ?>
                             </select>
                             <label><?php _e( 'Select all the answer-options you like to add', 'textdomain' ); ?></label>
                             <a href='#'>Add new option? (not working yet)</a>
                        </td>
                <tr valign="top">
                        <th scope="row"><?php _e( 'Required to answer', 'textdomain' ); ?></th>
                        <td>

                            <select name="required">
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                            <label><?php _e( 'Is this question required to answer?', 'textdomain' );?></label>


						</td>
				</tr>
				</tbody>
			</table>
            <input type="hidden" name="action" value="br_add_new_question" />
			<input class="button button-primary" value="<?php _e( 'Add new question', 'textdomain' ); ?>" type="submit" />
		</form>
	</div>