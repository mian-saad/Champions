<?php
/**
 * @package register
 */
namespace Incl;

final class Init {

  /**
   * Store all the classes inside an array
   * @return array full lists of classes
   */
  public static function get_services(){

    return [
      AdminPages\Admin::class,
      AdminPages\Settings::class,
      Base\Enqueue::class,
      Base\Shortcode::class,
      Base\Fetch::class
    ];

  }

  /**
   * Loop through the classes, initialize them and
   * call the register method if it exists
   * @return
   */
  public static function register_services() {


    foreach (self::get_services() as $class) {
      $service = self::instantiate( $class );
      if (method_exists($service, 'register')) {
        $service -> register();
      }
    }
  }

  /**
   * Initialize the class
   * @param $class, Class from services array
   * @return $class, New instance of class
   */
  private static function instantiate($class){
    $service  = new $class();
    return $service;
  }

}


//
// if (!class_exists('registerService')) {
//
//   class registerService {
//
//     public pluginName;
//
//
//
//     function register_assets(){
//       add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
//       add_action( 'admin_menu', array( $this, 'add_admin_pages'));
//
//     }
//
//
//
//     //This is going to add an admin plugin settings section in left wordpress admin panel.
//     function add_admin_pages(){
//       add_menu_page( 'Register', 'Arena', 'manage_options', 'register_plugin', array($this, 'admin_index'), 'dashicons-buddicons-buddypress-logo', 110);
//     }
//
//     function admin_index(){
//       require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
//     }
//
//     function enqueue(){
//       wp_enqueue_style( 'pluginstyle', plugins_url( '/assets/style.css', __FILE__ ));
//       wp_enqueue_script( 'pluginscript', plugins_url( '/assets/script.js', __FILE__ ));
//     }
//
//     function activate(){
//       Activate::activate();
//     }
//
//     function deactivate(){
//       Deactivate::deactivate();
//     }
//
//   }
// }
//
// $registerService = new registerService;
// $registerService -> register_assets();
//
// //Activates Plugin
// //require_once plugin_dir_path( __FILE__ ) . 'includes/activate.php';
// register_activation_hook( __file__, array($registerService, 'activate') );
//
// //Activates Plugin
// //require_once plugin_dir_path( __FILE__ ) . 'includes/deactivate.php';
// register_deactivation_hook( __file__, array($registerService, 'deactivate') );
