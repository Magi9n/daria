# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a WordPress-based Learning Management System (LMS) website for "daria.mate4kids.com". The site uses the Wellnez theme with a child theme approach and focuses on educational content delivery through the Tutor LMS plugin.

**Tech Stack:**
- WordPress (latest version)
- PHP 7.2.24+ (recommended 7.4+)
- MySQL 5.5.5+ (recommended 8.0+)
- Apache with mod_rewrite
- Tutor LMS for course management
- Elementor Pro for page building
- WooCommerce for e-commerce functionality

## Core Architecture

### Theme Structure
- **Active Theme:** `wellnez-child` (Child theme of Wellnez)
- **Parent Theme:** `wellnez` - A complete WordPress theme by Vecurosoft
- **Child Theme Location:** `wp-content/themes/wellnez-child/`
- **Parent Theme Location:** `wp-content/themes/wellnez/`

The Wellnez theme follows a modular architecture:
- `/inc/` - Core theme functionality and setup
- `/templates/` - Template parts for different content types  
- `/framework/` - Theme options and meta configurations
- `/hooks/` - Custom WordPress hooks and functions

### Key Plugins Architecture
- **Tutor LMS** - Primary LMS functionality with course management, certificates, and progress tracking
- **Tutor Pro** - Extended LMS features and additional modules
- **Elementor Pro** - Advanced page builder for custom layouts
- **Advanced Custom Fields (ACF)** - Custom field management
- **WooCommerce** - E-commerce integration for course sales
- **MercadoPago & PayPal** - Payment gateways for Latin American market

### Database Configuration
- Database: `admin_80944`
- User: `admin_80944` 
- Host: `localhost`
- Table Prefix: `wp_`
- Charset: `utf8mb4`

## Development Commands

### Local Development Setup
```bash
# Navigate to the WordPress directory
cd D:\Documents\Downloads\daria.mate4kids.com\daria

# Check WordPress installation status
php wp-admin/install.php

# Enable WordPress debugging (modify wp-config.php)
# Set WP_DEBUG to true for development
```

### Theme Development
```bash
# Navigate to child theme directory
cd wp-content/themes/wellnez-child

# Edit child theme styles
notepad style.css

# Edit child theme functions
notepad functions.php

# View parent theme structure
dir ..\wellnez

# Check theme files for errors
php -l functions.php
```

### Plugin Development & Management
```bash
# Navigate to plugins directory
cd wp-content/plugins

# List all active plugins
dir

# Check Tutor LMS version and structure
cd tutor
php -l tutor.php

# Navigate to Elementor customizations
cd ..\elementor
```

### File Permissions & Security
```bash
# Set proper file permissions (on Unix-like systems)
# Files: 644, Directories: 755, wp-config.php: 600

# Check .htaccess configuration
type .htaccess

# Verify hotlinking protection for Tutor LMS content
# Current domain restriction: dariaalexa.com
```

### Database Operations
```bash
# Export database backup
mysqldump -u admin_80944 -p admin_80944 > backup_$(date +%Y%m%d).sql

# Import database
mysql -u admin_80944 -p admin_80944 < backup_file.sql

# Access MySQL console
mysql -u admin_80944 -p
```

### Content Management
```bash
# Navigate to uploads directory
cd wp-content/uploads

# Check Tutor LMS course materials
cd 2024  # or current year

# View language files
cd ..\languages
```

## Development Workflow

### Working with Child Theme
1. Always modify `wellnez-child/style.css` for custom styles
2. Add custom functions to `wellnez-child/functions.php`
3. Override parent theme templates by copying to child theme directory
4. Test changes on staging before production

### Tutor LMS Customization
1. Use Tutor hooks and filters for functionality changes
2. Custom course templates go in `tutor/templates/` directory
3. Certificate customizations through Tutor Pro certificate builder
4. Payment integration primarily through WooCommerce

### Elementor Development
1. Create custom widgets in child theme
2. Use Tutor LMS Elementor Addons for course-specific elements
3. Maintain responsive design across all devices

### Security Considerations
- Hotlinking protection configured for media files
- Regular security updates for WordPress core and plugins
- Database credentials stored in wp-config.php
- File upload restrictions in place

## File Structure Priority

**Critical Files to Monitor:**
- `wp-config.php` - Database and security configuration
- `wp-content/themes/wellnez-child/` - All custom theme modifications
- `wp-content/plugins/tutor/` - LMS core functionality
- `wp-content/plugins/elementor-pro/` - Page builder customizations
- `.htaccess` - Server configuration and security rules

**Backup Priority:**
1. Database (complete site content)
2. wp-content/uploads (media files and course materials)
3. wp-content/themes/wellnez-child (custom code)
4. wp-config.php (configuration)

## Tutor LMS Specific Architecture

The site is built around educational content delivery with these key components:

- **Course Management**: Tutor LMS handles course creation, lessons, quizzes, and assignments
- **User Progress**: Student enrollment, progress tracking, and certificate generation
- **E-commerce Integration**: WooCommerce provides course purchase functionality
- **Content Protection**: Hotlinking prevention for course materials and videos
- **Multilingual Support**: Spanish (es_ES and es_MX) language files present

## Environment Notes

- **Server**: LiteSpeed web server configuration present
- **Domain**: dariaalexa.com (configured in hotlink protection)
- **Platform**: Windows development environment
- **Primary Language**: Spanish (multiple regional variants supported)
- **Payment Methods**: MercadoPago and PayPal (Latin American focus)