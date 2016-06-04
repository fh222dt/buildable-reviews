<?php
class BR_widgets extends WP_Widget {

    function __construct() {
        parent::__construct(
            'BR_widget',
            __('Review Top List', 'BR_widget_domain'),
            array('description' => __('Toplistor från dina byggbara recensioner', 'BR_widget_domain'),)
        );
    }

    public function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $title = apply_filters('widget_title', $instance['title']);
        $top_list = $instance['list'];

        echo $args['before_widget'];

        if(!empty($title)) {
            echo $args['before_title']. $title . $args['after_title'];
        }
        if($top_list == 'Latest') {
            echo $this->newest_reviews();
        }

        if($top_list == 'Highest') {
            echo $this->highest_score_objects();
        }

        echo $args['after_widget'];
    }

    public function form($instance) {

        if(isset($instance['title'])) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'wpb_widget_domain' );
        }
        if(isset($instance['list'])) {
            $top_list = $instance['list'];
        }

    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('text'); ?>">Lista:
        <select class='widefat' id="<?php echo $this->get_field_id('list'); ?>"
                name="<?php echo $this->get_field_name('list'); ?>" type="text">
          <option value='Latest'<?php echo ($top_list=='Latest')?'selected':''; ?>>
            Latest
          </option>
          <option value='Highest'<?php echo ($top_list=='Highest')?'selected':''; ?>>
            Highest
          </option>
        </select>
      </label>
     </p>
    <?php

    }


    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['list'] = $new_instance['list'];

        return $instance;


    }
    /**
     * Get top 5 rated objects, if less than 5 error is displayed
     * @return string
     */
    public function highest_score_objects() {
        global $wpdb;
        $sql = 'SELECT DISTINCT(posts_id) FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW.
                ' WHERE status_id = 1';
        $unique_objects = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(count($unique_objects) >= 5) {
            foreach ($unique_objects as &$object) {
                $score = Buildable_reviews_admin::get_total_score_of_object($object['posts_id']);
                $object['score'] = $score;
            }

            usort($unique_objects, function($item1, $item2) {
                return $item2['score'] <=> $item1['score'];
            });

            $output = '';
            for ($i=0; $i < 5; $i++) {
                $title = '<a href="'. get_permalink($unique_objects[$i]['posts_id']) .'">'. get_the_title($unique_objects[$i]['posts_id']) .'</a>';

                $output.= '<div>'. $title .'<span class="score-icons" data-score='. $unique_objects[$i]['score'] .'></span></div>';
            }
        }

        else {
            $output = '<p>Det finns inte tillräckligt många recensioner ännu</p>';
        }

        return $output;
    }

    /**
     * Get top 5 newest reviews
     * @return string
     */
    public function newest_reviews() {
        global $wpdb;
        $sql = 'SELECT posts_id FROM ' .$wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW.
                ' WHERE status_id = 1 ORDER BY created_at DESC LIMIT 5';
        $newest_objects = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(count($newest_objects) >= 1) {
            $output = '';
            for ($i=0; $i < 5; $i++) {
                $title = '<a href="'. get_permalink($newest_objects[$i]['posts_id']) .'">'. get_the_title($newest_objects[$i]['posts_id']) .'</a>';

                $output.= '<div>'. $title .'</div>';
            }

        }

        else {
            $output = '<p>Det finns inga recensioner ännu</p>';
        }

        return $output;

    }

}