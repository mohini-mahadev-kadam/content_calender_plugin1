<?php

function my_content_calender_menu_page() {
    add_menu_page(
      'Content-calender', // The title of the page
      'Content-calender', // The menu item text
      'manage_options', // The capability required to access the page
      'my-content-calender', // The unique ID of the page
      'my_content_calender_page_callback', // The callback function that generates the page content
      'dashicons-bell' // The icon for the menu item
    );
  }
  add_action('admin_menu', 'my_content_calender_menu_page');
  


  function my_content_calender_page_callback() {

        echo '<div class="wrap">';
        echo '<h1>Content-Calender</h1>';

       
        if (isset($_POST['submit'])) {

            $postDetails = array('occation' => $_POST['occation'], 'publishedDate' => $_POST['date'], 'post_title' => $_POST['post_title'], 'author' => $_POST['writer'], 'reviewer' => $_POST['reviewer']);
            $occations = get_option('occation_content', array());      
            
            // Unserialize the retrieved string into an array of objects
            $unserialized_occations = unserialize($occations);
            
            // adding new post details 
            $unserialized_occations[] = $postDetails;

            // Serialize the object
            $serialized_occations = serialize($unserialized_occations);
            
            // Store the serialized object in the WordPress database
            update_option('occation_content', $serialized_occations);

            // Display a success message
            echo '<div class="updated"><p>New occation post details added!</p></div>';

        }


        if (isset($_POST['update'])) {

          $postDetails = array('occation' => $_POST['occation'], 'publishedDate' => $_POST['date'], 'post_title' => $_POST['post_title'], 'author' => $_POST['writer'], 'reviewer' => $_POST['reviewer']);
          $occations = get_option('occation_content', array());      
          
          // Unserialize the retrieved string into an array of objects
          $unserialized_occations = unserialize($occations);
         
          // adding new post details 
          $unserialized_occations[] = $postDetails;

          // Serialize the object
          $serialized_occations = serialize($unserialized_occations);
          
          // Store the serialized object in the WordPress database
          update_option('occation_content', $serialized_occations);

          // Display a success message
          echo '<div class="updated"><p>Occation updated!</p></div>';

      }

        // Check if an offer has been deleted
        if (isset($_POST['delete'])) {

            // Remove the offer from the list of offers
            $occation_index = $_POST['occation_index'];
            
            $serialized_occations = get_option('occation_content', array());

            $unserialized_occations = unserialize($serialized_occations);

            unset($unserialized_occations[$occation_index]);

            // Serialize the object
            $serialized_occations = serialize($unserialized_occations);
            // Store the serialized object in the WordPress database
            update_option('occation_content', $serialized_occations);


            // Display a success message
            echo '<div class="updated"><p>Occation post details deleted!</p></div>';
        }

        if (isset($_POST['edit'])) {

            // Remove the offer from the list of offers
            $occation_index = $_POST['occation_index'];
            
            $serialized_occations = get_option('occation_content', array());

            $unserialized_occations = unserialize($serialized_occations);

            $occation_to_edit = $unserialized_occations[$occation_index];
            
            echo '<h2>Update Occation</h2>';
            echo '<form method="post">';
    
            echo '<label for="occation">Occation:</label>';
            echo '<input type="text" id="occation" name="occation" value="'.$occation_to_edit['occation'].'">';
            
            echo '<label for="date">Date:</label>';
            echo '<input type="date" id="date" name="date" value="'.esc_html($occation_to_edit['publishedDate']).'">';
            
            echo '<label for="post_title">Post Title:</label>';
            echo '<input type="text" id="post_title" name="post_title" value="'.esc_html($occation_to_edit['post_title']).'">';
            
            echo '<label for="writer">Writer:</label>';
            echo '<input type="text" id="writer" name="writer" value="'.esc_html($occation_to_edit['author']).'">';
            
            echo '<label for="reviewer">Reviewer:</label>';
            echo '<input type="text" id="reviewer" name="reviewer" value="'.esc_html($occation_to_edit['reviewer']).'">';
            
            echo '<input type="submit" name="update" value="Update" class="button button-primary">';
            echo '</form>';
    

            unset($unserialized_occations[$occation_index]);

            // Serialize the object
            $serialized_occations = serialize($unserialized_occations);
            // Store the serialized object in the WordPress database
            update_option('occation_content', $serialized_occations);


            // Display a success message
            echo '<div class="updated"><p>Occation post details deleted!</p></div>';
        }



        // Display the form for adding a new offer
        echo '<h2>Add New Occation</h2>';
        echo '<form method="post">';

        echo '<label for="occation">Occation:</label>';
        echo '<input type="text" id="occation" name="occation">';
        
        echo '<label for="date">Date:</label>';
        echo '<input type="date" id="date" name="date">';

        echo '<label for="post_title">Post Title:</label>';
        echo '<input type="text" id="post_title" name="post_title">';
        
        $writerList = '<label for="writer">Writer:</label>

        <select id="writer" name="writer">';

        $users = get_users();
        $userlist='';
        
        foreach ( $users as $user ) {
           $userlist .= '<option value="' . $user->display_name . '">' . $user->display_name . '</option>';
        }

        $writerList .= $userlist;
        $writerList .= '</select>';

        echo $writerList;

        $reviewerList ='<select id="reviewer" name="reviewer">';
        $reviewerList .= $userlist;
        $reviewerList .= '</select>';

        echo '<label for="reviwer">Reviwer:</label>';
        echo $reviewerList;
        //echo '<input type="text" id="reviwer" name="reviwer">';
        
        echo '<input type="submit" name="submit" value="Add" class="button button-primary">';
        echo '</form>';

       


        // Display the list of offers
        $serialized_occations = get_option('occation_content');
        echo '<h2>Scheduled occations</h2>';
        echo '<table class="widefat">';
        echo '<thead>
                <tr>
                    <th>Occation</th>
                    <th>Date</th>
                    <th>Post Title</th>
                    <th>Writer</th>
                    <th>Reviewer</th>
                    <th>Action</th>
                </tr>
              </thead>';
        echo '<tbody>';
        
        $unserialized_occations = unserialize($serialized_occations);

     

        foreach ($unserialized_occations as $index => $occation) {

            echo '<tr>';
            echo '<td>' . esc_html($occation['occation']) . '</td>';
            echo '<td>' . esc_html($occation['publishedDate']) . '</td>';
            echo '<td>' . esc_html($occation['post_title']) . '</td>';
            echo '<td>' . esc_html($occation['author']) . '</td>';
            echo '<td>' . esc_html($occation['reviewer']) . '</td>';
            echo '<td>';
            echo '<form method="post">';
            
            echo '<input type="hidden" name="occation_index" value="' . esc_attr($index) . '">';

            echo '<input type="submit" name="edit" value="Edit" class="button button-secondary">';
            echo '<input type="submit" name="delete" value="Delete" class="button button-secondary">';
            
            echo '</form>';
            echo '</td>';
            echo '</tr>';
      

        }



        echo '</tbody>';
        echo '</table>';
        echo '</div>';

        // // Enqueue the CSS styles for the page
        // wp_enqueue_style('content-calender-admin-page', get_stylesheet_directory_uri() . '/custom-page-styles.css');
        
    
  }


  function my_content_calender_admin_page_enqueue_styles1() {
    wp_enqueue_style( 'content-calender-styles', plugins_url( 'content-calender-styles.css', __FILE__ ) );
  }
  add_action( 'wp_enqueue_scripts', 'my_content_calender_admin_page_enqueue_styles1' );
  
  
  
  function my_content_calender_admin_enqueue_scripts() {
    wp_enqueue_script( 'content-calender-script', plugin_dir_url( __FILE__ ) . 'content-calender-script.js', array( 'jquery' ), '1.0', true );
  
  }
  add_action( 'wp_enqueue_scripts', 'my_content_calender_admin_enqueue_scripts' );  
  


  ?>