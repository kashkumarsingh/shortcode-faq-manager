=== Shortcode FAQ Manager ===
Contributors: Kashkumar Singh
Tags: faq, shortcode, accordion, FAQ manager
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The **Shortcode FAQ Manager** is a powerful plugin that allows you to create and display FAQs using a simple shortcode. It supports accordion functionality, tabbed navigation, and is fully responsive. 

== Features ==

- Easily create FAQs with custom categories
- Accordion functionality for easy reading
- Tabbed navigation for category filtering
- Fully responsive design for mobile and desktop
- Built-in AJAX support for dynamic content loading

== Installation ==

1. **Upload the Plugin:**
   - Download the latest version of the Shortcode FAQ Manager plugin.
   - Go to your WordPress admin area, navigate to **Plugins > Add New**.
   - Click **Upload Plugin**, choose the downloaded zip file, and click **Install Now**.

2. **Activate the Plugin:**
   - After installation, click the **Activate Plugin** link.

3. **Create Custom Post Types:**
   - Ensure you have created custom post types for FAQs and associated taxonomies (e.g., FAQ Categories) as needed.

4. **Using the Shortcode:**
   - To display FAQs, use the `[faqs]` shortcode in your post or page editor.

== Usage ==

### Shortcode Attributes

- **count**: (integer) Number of FAQs to display. Use `-1` to show all FAQs.
- **category**: (string) Slug of the category to filter FAQs. Use an empty string to show all categories.

**Example:**
```plaintext
[faqs count="5" category="your-category-slug"]
