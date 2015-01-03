meisa
=====
MeisaMenuBundle - The Ultimate Menu Bundle
========================================================

About the bundle
----------------
MeisaMenuBundle is a bundle that helps the user to create multiple frontend menus in an easy way.
Just three steps required after installing the bundle to get a cool menu in your website .
1. Configure the routes (Developer role) .
2. Setup the menu
3. Use the created menu in your theme.
--------------------------------------------
- This bundle serves the the end user , UI developer and also the application developer .
- you can easily configure and manage your frontend menus .
- This bundle depends on SonataAdminBundle .

Installation
------------
1. Install the bundle:
	 1. Add the following line to your composer.json  "mohammedeisa/menu_bundle": "2.0.*@dev"
     2. Update the composer .
     3. Enable the bundle in AppKernel.php by addming this line  to $bundles "new Meisa\MenuBundle\MeisaMenuBundle()" .
2. Configure the bundle:
	1. In config.yml, import this resource which contains the bundle configurations  .
    - { resource: @MeisaMenuBundle/Resources/config/menu_definition.yml }
2. Add "- 'MeisaMenuBundle:Form:tabssoft_link_field.html.twig'" to the
    twig form resources like the following .
    twig:
        debug:            "%kernel.debug%"
        strict_variables: "%kernel.debug%"

        form:
            resources:
                - 'MeisaMenuBundle:Form:tabssoft_link_field.html.twig'

    This template is a helper in your application.I will explain it's benefits 		later in **Meisa link helper** .
3. import the bundle routes by adding the following ti yor routing.yml
    meisa_menu:
        resource: "@MeisaMenuBundle/Controller/"
        type:     annotation
        prefix:   /
4. Add the admin sidebar menu items for the bundle in sonata_admin.yml {- meisa.menu.config , - meisa.menu.name} like the following
    sonata_admin:
        dashboard:
            groups:
                sonata.admin.group.meisa:
                              label: Main
                              icon:  '<i class="fa fa-play-circle"></i>'
                              items:
                                  - meisa.menu.config
                                  - meisa.menu.name
That's it !!!

## How to Use Meisa Menu ?
- the bundle registers two items in sonata sidebar (Menu config , Menu)
At first you have to register all routes you need to use later in your frontend menus.

1. Click on config and create new routes configurations.
    -route configuration process.
    1. Select a route and it's type then save .
    2. If you select the type as "show" , you will be asked to configure each route parameter.
2. Go to menu and create new menu you will see all your configurations published at the button.
  1. Select the needed links for your menu and save it.
  2. After saving you will see the menu name at the top .
3. You can use this in any frontend template to publish your menu using
"**show_menu**" filter 

- Example usage
	- {{ "header_menu"|show_menu|raw }}

Meisa link helper
-----------------
This is a helper form field type "meisa_link". you can use it in any form in your sonata admin classes.
  Example usage:
  		- $formMapper  ->add('link', 'meisa_link', array());



This link field is a text field with a button. when you click on this button , a modal will appear with your configured links.
select the link you want and it will be set in the text field .
this field type can be used in any bundle .
