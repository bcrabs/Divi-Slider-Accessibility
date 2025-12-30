/**
 * CRFTD Divi Slider Accessibility Fix
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

// SOLUTION 1: Proper WordPress jQuery syntax (RECOMMENDED)
jQuery(window).on('load', function() {
    setTimeout(function() {
        crftdEnhanceSliderAccessibility();
    }, 200);
});

// SOLUTION 2: Alternative with explicit jQuery scoping
(function($) {
    $(window).on('load', function() {
        setTimeout(function() {
            crftdEnhanceSliderAccessibility();
        }, 200);
    });
})(jQuery);

/**
 * Main accessibility enhancement function
 * Uses jQuery explicitly to avoid conflicts
 */
function crftdEnhanceSliderAccessibility() {
    try {
        // Use jQuery explicitly, not $
        var $prevArrows = jQuery('.et-pb-slider-arrows .et-pb-arrow-prev, .et_pb_testimonial .et-pb-arrow-prev');
        var $nextArrows = jQuery('.et-pb-slider-arrows .et-pb-arrow-next, .et_pb_testimonial .et-pb-arrow-next');
        
        // Security: Validate elements exist
        if ($prevArrows.length === 0 || $nextArrows.length === 0) {
            console.log('CRFTD: Testimonial slider arrows not found yet');
            return false;
        }
        
        // Apply accessibility enhancements
        $prevArrows.attr({
            'role': 'button',
            'aria-label': 'Previous Testimonial',
            'tabindex': '0'
        });
        
        $nextArrows.attr({
            'role': 'button', 
            'aria-label': 'Next Testimonial',
            'tabindex': '0'
        });
        
        // Remove problematic href attributes
        $prevArrows.add($nextArrows).each(function() {
            var href = jQuery(this).attr('href');
            if (!href || href === '#' || href === 'javascript:void(0)') {
                jQuery(this).removeAttr('href');
            }
        });
        
        console.log('CRFTD: Enhanced ' + ($prevArrows.length + $nextArrows.length) + ' slider arrows');
        return true;
        
    } catch (error) {
        console.error('CRFTD: Error enhancing slider accessibility:', error);
        return false;
    }
}

// DEBUGGING: WordPress-safe debug code
if (window.location.search.indexOf('debug=slider') !== -1) {
    jQuery(document).ready(function() {
        console.log('Document ready - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    });
    
    jQuery(window).on('load', function() {
        console.log('Window load - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    });
    
    setTimeout(function() {
        console.log('After 2s - arrows found:', jQuery('.et-pb-arrow-prev, .et-pb-arrow-next').length);
    }, 2000);
}
