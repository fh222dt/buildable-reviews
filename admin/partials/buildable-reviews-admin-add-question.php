<?php

/**
 * View for adding new question to review
 */
 require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
 $sql = new BR_SQL_Quieries();

 if(isset($_GET['question-id'])) {
     $question_id = $_GET['question-id'];
     $question = $sql->get_question($question_id);
     $question = $question[0];
     $db_options = $sql->get_question_options($question_id);
     $saved_options;
     foreach ($db_options as $option) {
         $saved_options[] = $option['option_id'];

     }

 }
 else {
     $question = null;
     $saved_options = [];
     $saved = null;
 }

 ?><div class="wrap">
		<h2>Add question</h2>

		<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
			<table class="form-table">
				<tbody>
                    <tr valign="top">
						<th scope="row"><?php _e( 'Name', 'textdomain' ); ?></th>
						<td>
							<input type="text" name="question-name" placeholder="<?php _e( 'Enter a name...' , 'textdomain' ); ?> " value="<?php echo $question['question_name']; ?>" required  />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Description', 'textdomain' ); ?></th>
						<td>
							<textarea id="desciption" name="desciption" type="text" maxlength="350" cols="100" placeholder="<?php _e( 'Enter a description...' , 'textdomain' ); ?>" required ><?php echo $question['question_desc']; ?>
							</textarea>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Type', 'textdomain' ); ?></th>
						<td>
							<select name="type" id="type">
                                <?php
                                    $types = $sql->get_all_question_types();
                                    foreach($types as $type) {
                                        echo '<option value="'.$type['question_type_id'].'"';
                                        selected($question['question_type_name'], $type['question_type_name']);
                                        echo '>'.$type['question_type_name'].'</option>';
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
                                    for ($i=0; $i<count($options); $i++) {
                                        $option = $options[$i];
                                         if ($saved_options && in_array($option['option_id'], $saved_options)) {
                                             $saved = $option['option_id'];
                                         }
                                        echo '<option value="'.$option['option_id'].'"';
                                        selected($saved, $option['option_id'] );
                                        echo  '>'.$option['option_name'].'</option>';
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
            <?php
                if(isset($_GET['question-id'])) {
                    ?>
                    <input type="hidden" name="action" value="br_update_question" />
                    <input type="hidden" name="question-id" value="<?php echo $question_id; ?>" />
        			<input class="button button-primary" value="<?php _e( 'Update question', 'textdomain' ); ?>" type="submit" />
                    <?php
                }
                else {
                    ?>
                    <input type="hidden" name="action" value="br_add_new_question" />
        			<input class="button button-primary" value="<?php _e( 'Add new question', 'textdomain' ); ?>" type="submit" />
                    <?php
                }
             ?>

		</form>
	</div>