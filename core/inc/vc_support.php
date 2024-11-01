<?php

if(!function_exists('wpf_portfolio_map')) {
   function wpf_portfolio_map() {
      vc_map(array(
         'name' => 'Portfolio List',
         'base' => 'wpf-portfolio',
         "class" => "",
         'params' => apply_filters( 'wpf_vc_params', array(
             array(
                 'type' => 'dropdown',
                 'holder' => 'div',
                 'param_name' => 'hover',
                 'description' => 'Hover Style',
                 'value' => array(
                     'Standard'      => 'standard',
                     'Slide Up'      => 'slide-up',
                     'White Overlay' => 'white-overlay'
                 )
             ),
             array(
               'type' => 'textfield',
               'holder' => 'div',
               'heading' => 'Show',
               'param_name' => 'show',
               'description' => 'Limit Number of displayed items'
            ),
             array(
               'type' => 'textfield',
               'holder' => 'div',
               'heading' => 'Category',
               'param_name' => 'category',
               'description' => 'Show items from selected category'
            ),
             array(
               'type' => 'dropdown',
               'holder' => 'div',
               'param_name' => 'order',
               'description' => 'Order',
               'value' => array(
                  'Title' => 'title',
                  'Date' => 'date',  
                  'Author' => 'author',
                  'Random' => 'random',    
               ), 
               'description' => 'Select Number of Columns'
            ),
             array(
               'type' => 'dropdown',
               'holder' => 'div',
               'param_name' => 'organize',
               'description' => 'Organize',
               'value' => array(
                  'Ascending' => 'ASC',
                  'Descending' => 'DESC',  
               ), 
               'description' => 'Organize results'
            ),
             array(
               'type' => 'dropdown',
               'holder' => 'div',
               'param_name' => 'columns',
               'description' => 'Columns',
               'value' => array(
                  '1' => '1',
                  '2' => '2',  
                  '3' => '3',
                  '4' => '4',    
                  '6' => '6'
               ), 
               'description' => 'Select Number of Columns'
            ),
         )
      )) );
   }

   add_action('vc_before_init', 'wpf_portfolio_map');
}