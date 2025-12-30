# Divi Slider Accessibility Fix

A WordPress/Divi accessibility enhancement that fixes WCAG compliance issues with slider navigation controls.

## Overview

This lightweight script converts Divi slider navigation arrows from improper link usage to proper button semantics with appropriate ARIA labels, ensuring WCAG 2.1 compliance for web accessibility.

## The Problem

Divi's sliders use anchor tags (`<a>`) for Previous/Next navigation controls, which violates WCAG guidelines:
- Links used as buttons fail accessibility audits
- Missing or improper ARIA labels
- Keyboard navigation issues
- Screen reader confusion

## The Solution

This script automatically:
- Adds `role="button"` to navigation controls
- Implements proper ARIA labels
- Ensures keyboard accessibility with `tabindex="0"`
- Removes problematic `href` attributes
- Works with WordPress jQuery noConflict mode
- Handles Divi's dynamic content loading

## Features

- **Security First**: Proper WordPress jQuery scoping and error handling
- **Performance Optimized**: Minimal DOM operations, efficient selectors
- **WordPress Compatible**: Follows WordPress coding standards
- **Divi Optimized**: Accounts for Divi's dynamic initialization timing
- **Clean Code**: Well-documented, maintainable, production-ready

## Installation

### Option 1: Add to functions.php (Recommended)

Add this code to your child theme's `functions.php`:

```php
/**
 * Enqueue Divi Slider Accessibility Script
 */
function crftd_enqueue_slider_accessibility() {
    if (is_admin()) return;
    
    wp_add_inline_script('divi-custom-script', '
        // Paste the JavaScript code here
    ', 'after');
}
add_action('wp_enqueue_scripts', 'crftd_enqueue_slider_accessibility', 20);
```

### Option 2: Direct Script Implementation

Add the script to your theme or use Divi's **Theme Options > Integration** section:

```html
<script>
// Paste the JavaScript code here
</script>
```

### Option 3: Custom Plugin

Create a simple plugin for portability across themes (see `plugin-version.php` in this repo).

## Usage

Once installed, the script automatically:
1. Waits for page load and Divi initialization
2. Detects slider navigation arrows
3. Applies proper ARIA attributes and button roles
4. Logs success to browser console

No configuration needed!

## Debugging

To debug timing and element detection, add `?debug=slider` to your URL:

```
https://yoursite.com/page-with-slider/?debug=slider
```

Check browser console for detailed timing information.

## Browser Compatibility

- All modern browsers (Chrome, Firefox, Safari, Edge)
- Internet Explorer 11+ (with polyfills)
- Mobile browsers (iOS Safari, Chrome Mobile)

## WordPress Requirements

- **WordPress**: 5.0+
- **Divi Theme**: 4.0+
- **jQuery**: Included with WordPress
- **PHP**: 7.0+ (for functions.php implementation)

## WCAG Compliance

This script helps achieve:
- **WCAG 2.1 Level A**: Proper semantic markup
- **WCAG 2.1 Level AA**: Keyboard accessibility, ARIA labels
- **Section 508**: Assistive technology compatibility

## Testing

Validated with:
- WAVE (Web Accessibility Evaluation Tool)
- axe DevTools
- Lighthouse Accessibility Audit
- NVDA Screen Reader
- JAWS Screen Reader
- VoiceOver (macOS/iOS)

## Changelog

### Version 1.0.0
- Initial release
- WordPress jQuery noConflict compatibility
- Dynamic content loading handling
- ARIA label implementation
- Button role assignment
- Debug mode

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch (`git checkout -b feature/improvement`)
3. Commit your changes (`git commit -am 'Add improvement'`)
4. Push to the branch (`git push origin feature/improvement`)
5. Open a Pull Request

## Support

For issues, questions, or feature requests:
- **Issues**: [GitHub Issues](https://github.com/bcrabs/divi-slider-accessibility/issues)
- **Website**: [https://crftd.dev](https://crftd.dev)
- **Plugins**: [https://reallysimpleplugins.com](https://reallysimpleplugins.com)

## Credits

**Developed by CRFTD**
- Website: [https://crftd.dev](https://crftd.dev)
- Plugin Store: [https://reallysimpleplugins.com](https://reallysimpleplugins.com)

## License

GPL-2.0-or-later

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

## Related Projects

Check out our other WordPress accessibility and development tools at [Really Simple Plugins](https://reallysimpleplugins.com).

---

**[ReallySimplePlugins.com](https://reallysimpleplugins.com) by [CRFTD](https://crftd.dev)**
