
-- SUMMARY --

Corolla is a stylized, re-colorable, multi-column theme which uses HTML5
and includes many advanced theme settings to allow you to customize 
the theme to your requirements.


-- REQUIREMENTS --

Corolla 7.x-2.x is a sub-theme of Adaptivetheme, you must download and install
this theme first. You do not need to enable it.

http://drupal.org/project/adaptivetheme (take the latest 7.x-2.x version).


-- INSTALLATION --

Install as usual, see http://drupal.org/node/70151 for further information.


-- CONFIGURATION --

* Theme settings and layout: Corolla includes lots of theme settings including
  the ability to change the layout and support for mobile devices. Visit the
  theme settings page admin/appearance/settings/corolla to configure your settings.
  You can change the layout, font, font size, heading styles and much more.
  In due course we will write documentation pages about each block of settings.
  
  For additional help please view the online documentation:
  http://adaptivethemes.com/documentation/layout-settings-guide

* Color options: Enable the theme at admin/appearance, and visit
  admin/appearance/settings/corolla to modify the color scheme if desired.

* Menus: The corolla theme does not support the hard coded menu defaults. To setup
  your main navigation menu, place the block containing the menu you want in the
  "Menu Bar" region.


-- SUPERFISH DROP MENUS --

corolla supports the Superfish module which is a dynamic menu module and enables 
drop menus and other styles. First configure the Superfish menu to use the "none" 
style and then place the menu in the "Menu Bar" region. Placing a Superfish menu
into the "Header" region will also enable a styled drop menu, one of my "easter egg"
features :)

Superfish installation instructions and downloads can be found on the Superfish 
menu project page:

http://drupal.org/project/superfish


-- CREATING A SUBTHEME --

1. Create a new theme directory and .info file with the following inside it:

    name = Name of your theme.
    description = Description of your theme.
    core = 7.x

    base theme = corolla

    stylesheets[all][] = color/colors.css
    stylesheets[all][] = custom.css

    regions[sidebar_first]     = Sidebar first
    regions[sidebar_second]    = Sidebar second
    regions[highlighted]       = Highlighted
    regions[content]           = Main content
    regions[content_aside]     = Content cottom panel
    regions[menu_bar]          = Menu Bar
    regions[header]            = Header panel
    regions[three_33_first]    = Top panel first
    regions[three_33_second]   = Top panel second
    regions[three_33_third]    = Top panel third
    regions[secondary_content] = Features panel
    regions[tertiary_content]  = Bottom panel
    regions[four_first]        = Footer panel first
    regions[four_second]       = Footer panel second
    regions[four_third]        = Footer panel third
    regions[four_fourth]       = Footer panel fourth
    regions[footer]            = Footer
    regions[help]              = Help
    regions[page_top]          = Page top
    regions[page_bottom]       = Page bottom

    features[] = logo
    features[] = favicon
    features[] = name
    features[] = slogan
    features[] = node_user_picture
    features[] = comment_user_picture
    features[] = comment_user_verification

2. Create a custom.css file which will contain any styles you want to change or
   add. You can place this in the root or your theme or in a sub-directory, just
   make sure the path matches the info file entry.

3. Copy the entire color directory from Corolla into your subtheme.


-- CONTACT --

Issues and problems please post to:

http://drupal.org/project/issues/corolla

Current maintainer:
* Jeff Burnz (Jeff Burnz) - http://drupal.org/user/61393

Origignator and previous maintainer:
* Jarek Foksa (Jarek Foksa) - http://drupal.org/user/479726
