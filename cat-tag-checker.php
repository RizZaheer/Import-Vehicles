<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Cat_Tag_Checker_Public {

    public function __construct() {
        add_filter( 'the_content', array( &$this, 'ctc_append_cat_img'), 10, 2 ); //Add filters to change the default content hook
        add_filter( 'the_title', array( &$this, 'ctc_extend_title_with_date'), 10, 2 ); //Add filters to change the default title hook
    }

public function ctc_append_cat_img( $content ){ //Check if post has thumbnail, otherwise show default cat image

        global $post;
        $post_id = $post->ID; //Getting current POST ID

        if( get_post_type( $post_id ) == 'post' ){ //Loop through Post type POST
            
            $tag_terms = wp_get_post_terms( $post_id, 'post_tag' ); //Get all the terms on the basis of POST ID
            $cat_terms = array_column($tag_terms, 'name'); //Get all the terms on the basis of names
            if( in_array( 'cat', $cat_terms ) ){ //Check cat in the selected terms

                if( !has_post_thumbnail( $post_id ) ){ //Check if there is no Featured Image
                    return '<div><img src="'.plugin_dir_url( __FILE__ ).'/images/cat.gif"></div>'.$content;
                }
            }


        }

        return $content; //Return the Output
    }

public function ctc_extend_title_with_date( $title, $id ){ //Change the POST title format on the basis of Tag

         if( get_post_type( $id ) == 'post' && is_single() ){

            $tag_terms = wp_get_post_terms( $id, 'post_tag' );
            $cat_terms = array_column( $tag_terms, 'name');

            if( in_array( 'cat', $cat_terms ) ){

                $post_date = get_the_date( 'F d, Y', $id ); //Format date
                return $post_date.' - '.$title; //Return - splitied date formated and title

            }
        }

        return $title;
    }

}

new Cat_Tag_Checker_Public();//calling class object
