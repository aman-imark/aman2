<?php
/**
* Plugin Name: Admin Post Campsite
* Description: This plugin enables the admin to post campsites.
* Version: 1.0
* Author: Gill
**/

function addcamp_admin() {
    include('add_camp.php');
}

function allcamps_admin()
{
    include('all_camps.php');
}

function updatecamp_admin()
{
    include('update_camp.php');
}

function editcamp_admin()
{
    include('edit_camp.php');
}

function addcamp_admin_actions() {
    add_menu_page ("Post Camps Admin", "Camps", 1, "allcampsbyadmin", "allcamps_admin");
    add_submenu_page( 'allcampsbyadmin', 'All Camps', 'All Camps', 1, 'allcampsbyadmin', "allcamps_admin");
    add_submenu_page( 'allcampsbyadmin', 'Add New', 'Add New', 1, 'addcampbyadmin', "addcamp_admin");
    add_submenu_page( 'allcampsbyadmin', 'Update Camp', NULL, 1, 'updatecampbyadmin', "updatecamp_admin");
    add_submenu_page( 'allcampsbyadmin', 'Edit Camp', NULL, 1, 'editcampbyadmin', "editcamp_admin");
    //add_pages_page("Edit Camp Amdin", NULL, 1, "editcampsbyadmin", "editcamps_admin");
    
}
 
add_action('admin_menu', 'addcamp_admin_actions');

