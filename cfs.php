<?php
/*
Plugin Name: Custom Field Suite
Description: Visually add custom fields to your WordPress edit pages.
Version: 2.6.4
Author: Matt Gibbs
Text Domain: cfs
Domain Path: /languages/
*/
// Comments Block: This block is the plugin header required by WordPress, detailing the plugin's name, description, version, author, and text domain used for translation.
// 注释块：此块是 WordPress 所需的插件标头，详细说明了插件的名称、描述、版本、作者和用于翻译的文本域。

// This code is a PHP script for a WordPress plugin called "Custom Field Suite" (CFS). This plugin is designed to allow users to add custom fields to their WordPress edit pages easily.
// 此代码是名为“Custom Field Suite”（CFS）的 WordPress 插件的 PHP 脚本。该插件旨在允许用户轻松地将自定义字段添加到他们的 WordPress 编辑页面。

// Class Declaration: This line starts the definition of the Custom_Field_Suite class, which encapsulates all the functionality of the plugin.
// 类声明：这一行开始定义 Custom_Field_Suite 类，该类封装了插件的所有功能。
class Custom_Field_Suite
{

    // Properties: The class properties include public objects that will be used throughout the plugin ($api, $form, $fields, $field_group) and a private static property ($instance) for implementing the singleton pattern.
    // 属性：类属性包括将在整个插件中使用的公共对象（$api、$form、$fields、$field_group）和用于实现单例模式的私有静态属性（$instance）。
    public $api;
    public $form;
    public $fields;
    public $field_group;
    private static $instance;

    // Constructor: When an instance of Custom_Field_Suite is created, this constructor function sets up the plugin by defining constants and initializing components.
    // 构造函数：创建 Custom_Field_Suite 实例时，该构造函数通过定义常量和初始化组件来设置插件。
    function __construct() {

        // setup variables
        define( 'CFS_VERSION', '2.6.4' );
        define( 'CFS_DIR', dirname( __FILE__ ) );
        define( 'CFS_URL', plugins_url( '', __FILE__ ) );

        // get the gears turning
        include( CFS_DIR . '/includes/init.php' );
    }


    // Singleton Method: This method ensures that only one instance of Custom_Field_Suite exists, following the singleton pattern.
    // 单例方法：该方法遵循单例模式，确保 Custom_Field_Suite 只存在一个实例。
    /**
     * Singleton
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    // API Methods: These functions are public methods of the class, providing the core functionality of the plugin such as retrieving, saving, and manipulating custom fields.
    // API 方法：这些函数是类的公共方法，提供插件的核心功能，如检索、保存和操作自定义字段。
    /**
     * Public API methods
     */
    function get( $field_name = false, $post_id = false, $options = [] ) {
        return CFS()->api->get( $field_name, $post_id, $options );
    }


    function get_field_info( $field_name = false, $post_id = false ) {
        return CFS()->api->get_field_info( $field_name, $post_id );
    }


    function get_reverse_related( $post_id, $options = [] ) {
        return CFS()->api->get_reverse_related( $post_id, $options );
    }


    function save( $field_data = [], $post_data = [], $options = [] ) {
        return CFS()->api->save_fields( $field_data, $post_data, $options );
    }


    function find_fields( $params = [] ) {
        return CFS()->api->find_input_fields( $params );
    }


    function form( $params = [] ) {
        ob_start();
        CFS()->form->render( $params );
        return ob_get_clean();
    }


    // Render Field HTML: This method includes the necessary HTML template for a field in the WordPress admin.
    // 渲染字段 HTML：此方法包含 WordPress 管理中字段所需的 HTML 模板。
    /**
     * Render a field's admin settings HTML
     */
    function field_html( $field ) {
        include( CFS_DIR . '/templates/field_html.php' );
    }


    // Create Field: This method is responsible for creating a field using specified parameters.
    // 创建字段：该方法负责使用指定参数创建字段。
    /**
     * Trigger the field type "html" method
     */
    function create_field( $field ) {
        $defaults = [
            'type'          => 'text',
            'input_name'    => '',
            'input_class'   => '',
            'options'       => [],
            'value'         => '',
        ];

        $field = (object) array_merge( $defaults, (array) $field );
        CFS()->fields[ $field->type ]->html( $field );
    }
}

// CFS Function: A global function defined to return an instance of the Custom_Field_Suite class, making it accessible throughout WordPress.
// CFS 函数：全局函数，用于返回自定义字段套件类的实例，使整个 WordPress 都能访问该类。
function CFS() {
    return Custom_Field_Suite::instance();
}

// Instantiate CFS: Finally, this line creates an instance of the Custom_Field_Suite class and stores it in the $cfs variable, effectively initializing the plugin when the file is included in a WordPress installation.
// 实例化 CFS：最后，这一行创建 Custom_Field_Suite 类的一个实例，并将其存储在 $cfs 变量中，当文件包含在 WordPress 安装中时，可以有效地初始化插件。
$cfs = CFS();
