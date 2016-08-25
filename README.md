# Sidebar Shortcodes

Create sidebar shortcodes which you can fill with widget content. To use this functionality, include the sidebar-shortcodes-class.php and add shortcodes by using the function **add_sidebar_shortcode( $shortcode )**.
 
 
###Example
 
 ``` php
 <?php
 
 require_once( 'sidebar-shortcodes-class.php');
 add_sidebar_shortcode( 'this-is-my-shortcode' ).
 
 ?>
 ```
 
 This will create  a sidebar in *WP Admin > Design > Widgets* where you can add your widgets.

Just add the shortcode to your page, post or custom post type content. The shortcode looks like this:
``` html
[this-is-my-shortcode]
```

#### Attention!

Please be aware that you do not using existing shortcodes. The best way is to use unique shortcodes.
