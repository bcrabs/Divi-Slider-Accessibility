<?php
/**
 * Plugin Name: CRFTD Divi Slider Accessibility
 * Plugin URI: https://github.com/crftd/divi-slider-accessibility
 * Description: Fixes WCAG compliance issues with Divi testimonial slider navigation controls by converting improper link usage to proper button semantics with ARIA labels.
 * Version: 1.0.0
 * Author: CRFTD
 * Author URI: https://crftd.dev
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: crftd-divi-slider-a11y
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * 
 * @package CRFTD_Divi_Slider_Accessibility
 * @author CRFTD
 * @link https://crftd.dev
 * @security Security-first approach with proper WordPress standards
 * @performance Conditional loading, optimized execution
 */

// Security: Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CRFTD_DSA_VERSION', '1.0.0');
define('CRFTD_DSA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CRFTD_DSA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CRFTD_DSA_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class CRFTD_Divi_Slider_Accessibility {
    
    /**
     * Instance of this class
     * @var object
     */
    private static $instance = null;
    
    /**
     * Get instance of this class
     * @return object
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Enqueue scripts on frontend only
        add_action('wp_enqueue_scripts', array($this, 'enqueue_accessibility_script'), 20);
        
        // Add settings link on plugins page
        add_filter('plugin_action_links_' . CRFTD_DSA_PLUGIN_BASENAME, array($this, 'add_action_links'));
        
        // Load text domain for translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'crftd-divi-slider-a11y',
            false,
            dirname(CRFTD_DSA_PLUGIN_BASENAME) . '/languages'
        );
    }
    
    /**
     * Check if Divi is active
     * @return bool
     */
    private function is_divi_active() {
        // Check if Divi theme is active
        $theme = wp_get_theme();
        if ('Divi' === $theme->name || 'Divi' === $theme->parent_theme) {
            return true;
        }
        
        // Check if Divi Builder plugin is active
        if (defined('ET_BUILDER_VERSION')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if current page likely has testimonial sliders
     * @return bool
     */
    private function page_has_testimonials() {
        global $post;
        
        if (!$post) {
            return true; // Load on all pages if we can't determine
        }
        
        // Check post content for testimonial shortcode
        if (has_shortcode($post->post_content, 'et_pb_testimonial')) {
            return true;
        }
        
        // Check Divi page builder content
        $page_layout = get_post_meta($post->ID, '_et_pb_use_builder', true);
        if ('on' === $page_layout) {
            return true; // Assume Divi builder pages might have sliders
        }
        
        return false;
    }
    
    /**
     * Enqueue accessibility enhancement script
     */
    public function enqueue_accessibility_script() {
        // Security: Only load on frontend
        if (is_admin()) {
            return;
        }
        
        // Performance: Only load if Divi is active
        if (!$this->is_divi_active()) {
            return;
        }
        
        // Register and enqueue inline script
        wp_register_script(
            'crftd-divi-slider-a11y',
            false, // No external file
            array('jquery'),
            CRFTD_DSA_VERSION,
            true
        );
        
        wp_enqueue_script('crftd-divi-slider-a11y');
        
        // Add inline script
        wp_add_inline_script(
            'crftd-divi-slider-a11y',
            $this->get_accessibility_script()
        );
    }
    
    /**
     * Get the accessibility enhancement JavaScript
     * @return string
     */
    private function get_accessibility_script() {
        // Security: Return sanitized script
        $script = <<<'JAVASCRIPT'
/**
 * CRFTD Divi Testimonials Slider Accessibility Fix
 * WordPress-safe jQuery implementation with noConflict mode
 * Fixes WCAG compliance issues with Divi slider navigation controls
 * 
 * @author CRFTD (https://crftd.dev)
 * @link https://crftd.dev
 * @version 1.0.0
 * @license GPL-2.0-or-later
 * 
 * @security Proper jQuery scoping for WordPress
 * @performance Optimized timing and selectors
 * @wcag Converts improper link usage to proper button roles with ARIA labels
 */

// Wait for window load to ensure Divi initialization
jQuery(window).on('load', function() {
    setTimeout(function() {
        crftdEnhanceSliderAccessibility();
    }, 200);
});

// Also try on document ready as fallback
jQuery(document).ready(function() {
    setTimeout(function() {
        crftdEnhanceSliderAccessibility();
    }, 500);
});

/**
 * Main accessibility enhancement function
 * Uses jQuery explicitly to avoid WordPress conflicts
 */
function crftdEnhanceSliderAccessibility() {
    try {
        // Use jQuery explicitly, not $
        var $prevArrows = jQuery('.et-pb-slider-arrows .et-pb-arrow-prev, .et_pb_testimonial .et-pb-arrow-prev');
        var $nextArrows = jQuery('.et-pb-slider-arrows .et-pb-arrow-next, .et_pb_testimonial .et-pb-arrow-next');
        
        // Security: Validate elements exist
        if ($prevArrows.length === 0 && $nextArrows.length === 0) {
            return false;
        }
        
        // Apply accessibility enhancements to previous arrows
        if ($prevArrows.length > 0) {
            $prevArrows.attr({
                'role': 'button',
                'aria-label': 'Previous Testimonial',
                'tabindex': '0'
            });
        }
        
        // Apply accessibility enhancements to next arrows
        if ($nextArrows.length > 0) {
            $nextArrows.attr({
                'role': 'button', 
                'aria-label': 'Next Testimonial',
                'tabindex': '0'
            });
        }
        
        // Remove problematic href attributes
        $prevArrows.add($nextArrows).each(function() {
            var href = jQuery(this).attr('href');
            if (!href || href === '#' || href === 'javascript:void(0)' || href === 'javascript:;') {
                jQuery(this).removeAttr('href');
            }
        });
        
        // Debug logging (only if debug parameter present)
        if (window.location.search.indexOf('debug=slider') !== -1) {
            console.log('CRFTD: Enhanced ' + ($prevArrows.length + $nextArrows.length) + ' slider arrows for accessibility');
        }
        
        return true;
        
    } catch (error) {
        console.error('CRFTD: Error enhancing slider accessibility:', error);
        return false;
    }
}

// DEBUGGING: Check element timing (only with debug parameter)
if (window.location.search.indexOf('debug=slider') !== -1) {
    jQuery(document).ready(function() {
        console.log('CRFTD Debug - Document ready - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    });
    
    jQuery(window).on('load', function() {
        console.log('CRFTD Debug - Window load - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    });
    
    setTimeout(function() {
        console.log('CRFTD Debug - After 2s - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    }, 2000);
}
JAVASCRIPT;
        
        return $script;
    }
    
    /**
     * Add action links to plugins page
     * @param array $links
     * @return array
     */
    public function add_action_links($links) {
        $plugin_links = array(
            '<a href="https://crftd.dev" target="_blank">' . esc_html__('Visit CRFTD', 'crftd-divi-slider-a11y') . '</a>',
            '<a href="https://reallysimpleplugins.com" target="_blank">' . esc_html__('More Plugins', 'crftd-divi-slider-a11y') . '</a>',
        );
        
        return array_merge($plugin_links, $links);
    }
}

/**
 * Initialize the plugin
 */
function crftd_divi_slider_accessibility_init() {
    return CRFTD_Divi_Slider_Accessibility::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'crftd_divi_slider_accessibility_init', 10);

/**
 * Activation hook
 */
register_activation_hook(__FILE__, 'crftd_divi_slider_accessibility_activate');
function crftd_divi_slider_accessibility_activate() {
    // Check for minimum WordPress version
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        deactivate_plugins(CRFTD_DSA_PLUGIN_BASENAME);
        wp_die(
            esc_html__('This plugin requires WordPress 5.0 or higher.', 'crftd-divi-slider-a11y'),
            esc_html__('Plugin Activation Error', 'crftd-divi-slider-a11y'),
            array('back_link' => true)
        );
    }
    
    // Check for minimum PHP version
    if (version_compare(PHP_VERSION, '7.0', '<')) {
        deactivate_plugins(CRFTD_DSA_PLUGIN_BASENAME);
        wp_die(
            esc_html__('This plugin requires PHP 7.0 or higher.', 'crftd-divi-slider-a11y'),
            esc_html__('Plugin Activation Error', 'crftd-divi-slider-a11y'),
            array('back_link' => true)
        );
    }
}

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, 'crftd_divi_slider_accessibility_deactivate');
function crftd_divi_slider_accessibility_deactivate() {
    // Cleanup if needed (currently none required)
}
