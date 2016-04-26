<?php

/**
 * View for adding new question to review
 */
 ?><div class="wrap">
		<h2>Add question</h2>

		<form method="post">
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
                                    $types = ['Select','Checkbox', 'Textfield', 'Scale 1-5', 'Radio']; //TODO: get all types from db can also be a förmån
                                    foreach($types as $type) {
                                        echo '<option value="'.$type.'">'.$type.'</option>';
                                    }
                                 ?>
							</select>
						<label><?php _e( 'What type of question do you want to add?', 'textdomain' );?></label>
						</td>
					</tr>
				<tr valign="top">
						<th scope="row"><?php _e( 'Answer options', 'textdomain' ); ?></th>
						<td>
                            <select name="options" multiple='multiple'>
                                <?php
                                    $options = ['1','ganska bra', 'förmån']; //get all options from db can also be a förmån
                                    foreach($options as $option) {
                                        echo '<option value="'.$option.'">'.$option.'</option>';
                                    }
                                 ?>
							</select>
							<label><?php _e( 'Select all the answer-options you like to add', 'textdomain' ); ?></label>
                            <a href='#'>Add new option? (not working yet)</a>
						</td>
				</tr>
				</tbody>
			</table>
			<input class="button button-primary" value="<?php _e( 'Add new question', 'textdomain' ); ?>" type="submit" />
		</form>
	</div>