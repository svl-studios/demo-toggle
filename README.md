# SVL Demo Toggle

This plugin adds a toggle to the website to display an array of images representing different demo sites.

The array of demo blocks is set using shortcodes.  For simplicity, these can be set via the WPBakery Page Builder modules.  Or, if you're savvy, manually using the format shown below.

Once the shortcodes have been added, the data will automataically be added to the flyout toggle on the page.  Simply put, set your shortcodes and the plugin does the rest!

## Container Shortcode
**[svl_demos theme="Theme Name" purchase_link="https://www.url-to-purchase-site.com" display_toggle="yes"]**

### Parameters
* theme
*- The name of your theme.*
* purchase_link
*- URL to site where the theme may be purchased.*
* display_toggle
*- Enables/disables the on screen toggle switch (default: yes)*

## Block Shortcode 
*Placed inside the Container shortcode (see example below).*

**[svl_demo demo_title="My Demo" demo_url="https://www.url-to-demo-site.com" source="external_link" custom_demo_image="https://www.url-to-demo-image.com"]**

### Parameters
* demo_title
*- The name of the demo site.*
* demo_url
*- URL to the demo site.*
* link_target (options: _blank / _self)
*- Open in same window or new window/tab (default: _blank).*
* source  (options: media_library / external_link)
*- Source of the demo image (default: media_library).*
* demo_image (used when 'source' is set to 'media_library')
*- Image ID from the image uploaded to the media library.*
* custom_demo_image (used when 'source' is set to 'external_link')
*- Full URL to location of demo image.*
* builder_used (options: wpbakery / elementor)
*- Builder used to create the demo (default: wpbakery)*
* new (options: yes / no)
*- Sets the 'new' ribbon over the demo image.*
* coming_soon (options: yes / no)
*- Sets the 'coming soon' ribbon over the demo image.*
```
[svl_demos theme="Qixi" purchase_link="https://themeforest.net/user/svlstudios/portfolio" display_toggle="yes"]
[svl_demo demo_title="Coffee Shop" demo_url="https://qixi.svlstudios.com/coffee-shop" source="external_link" custom_demo_image="https://qixi.svlstudios.com/wp-content/uploads/2021/05/qixi-coffee-shop-demo.png" builder_used="wpbakery" new="yes"]
[/svl_demos]
```

## Notes
* Demo site block images, ideally, should be 350x475 pixels.
* Colors are currently set via CSS.  I hope to make these options in the container shortcode in the future.
* If you make changes to the JavaScript or CSS, don't forget to minify them or modify the enqueue functions to not use the minfied files.
* This code will pass WordPress Coding Standards.  All output is escaped/sanitized.  If you discovere something I missed, please feel free to fork the project and submit a pull request.
* This plugin uses Roboto Google Font (with Arial fallback) and Font Awesome 4.0+.  It is assumed these resources are already loaded.