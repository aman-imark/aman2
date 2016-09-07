=== Reviews Ultimate ===
Contributors: Rustaurius, EtoileWebDesign
Tags: reviews, WooCommerce reviews, simple reviews, product reviews, rich reviews, testimonial, in-depth reviews, review categories
Requires at least: 4.0.0
Tested up to: 4.5
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

Let visitors submit reviews and display them via shortcode or widget. Replace WooCommerce reviews and ratings. Require login, email confirmation, etc.

== Description == 

<a href='http://www.etoilewebdesign.com/ultimate-reviews-demo/'>Reviews Demo</a>

[youtube https://www.youtube.com/watch?v=IxM1mizhzek]

A simple plugin that lets visitors submit reviews for different products, events or anything else. Ultimate Reviews is easy to set up and includes two smart review shortcodes, one that displays your reviews and one that displays a review form that your visitors can use to submit reviews. Many straight-forward options are included that let you adjust review rating and scoring, require login and/or email confirmation, add review categories (such as value, appearance, etc.), and change how reviews are displayed.

Ultimate Reviews is now integrated with WooCommerce. Available in the premium version are options to replace the default WooCommerce reviews tab as well as the ratings area on your WooCommerce product page with with the reviews and ratings from this plugin. This will allow you to better manage your reviews and offer your visitors more customized reviews and ratings in your WooCommerce shop.

It’s never been easier to add review functionality to your site. From simple reviews, with one overall rating score, to in-depth reviews, with multiple review categories and sub-ratings, you can customize Ultimate Reviews to perfectly suit your needs. All of this with multiple layout, styling and review ordering options.

= Key Features =

* Accept and manage user reviews
* Allow users to upload an image with their review
* Allow in-depth reviews by creating review sub-categories, such as value, appearance, quality, etc.
* Display reviews for one or all products
* Multiple rating systems, including points and percentage
* Set the maximum score and use either a text input, dropdown selection or number of stars for score input
* Flexible styling using the Custom CSS option
* Options to display the date and author of the review
* Restrict reviewable products to a predefined list, and drag-and-drop to re-arrange the list
* Review pagination
* Integration with <a href=“https://wordpress.org/plugins/ultimate-product-catalogue/“>Ultimate Product Catalog</a> plugin, allowing you to restrict reviews to only products in your catalog

= Premium Features =

The premium version includes lots of useful features such as:

* Let visitors vote reviews up or down if they find them helpful
* Require reviewers to be logged in, with available login options including WordPress, <a href=“https://wordpress.org/plugins/front-end-only-users/“>Front-End Only Users</a> plugin, Facebook and Twitter
* Replace “Reviews” tab on WooCommerce product page with reviews from Ultimate Reviews 
* Replace “Ratings” area on WooCommerce product page with ratings from Ultimate Reviews 
* Multiple layout options for your reviews, including thumbnail (excerpt with read more link) and expandable layouts, in addition to the standard layout
* Require Admin Approval of reviews before they're displayed
* Require confirmation of a review submitter's email address
* Add a captcha to your review submission form to help prevent spam
* Group your reviews by product for better organization
* Display summary statistics above product groupings
* Dozens of options to customize the labels, fonts, colors, etc. of the plugin

= Shortcodes =

* [submit-review]: displays a form that allows visitors to submit a review of a product that you or they specify
* [ultimate-reviews]: displays all reviews or all reviews of a specified product
* Premium: [ultimate-review-search]: displays all reviews with a search box that lets users search for keywords in reviews (such as "Great product", "Customer service", etc.)

== Installation ==

1. Upload the `ultimate-reviews` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place "[submit-review]" shortcode on the page where you want your review form to display
4. Place "[ultimate-reviews]" shortcode on page(s) where you want to display reviews
5. Both shortcodes accept the "product_name" attribute, which lets you limit the review form or displayed reviews to one product


== Frequently Asked Questions ==

= How do I let visitors submit reviews for a product? =

You can add the shortcode [submit-review]  to your page. If you only want visitors to be able to submit a review for one particular product, add the attribute "product_name" and set it equal to the name of the product that you would like them to be able to review.

= How do I display reviews that have been submitted? =

* The shortcode [ultimate-reviews] lets you display all reviews that have been created. If you only want to display reviews for one particular product, add the attribute "product_name" and set it equal to the name of the product whose reviews you'd like to display.

= Can I redirect visitors to a thank you page after they submit a review? =

* Yes, you'd just need to add the "redirect_page" attribute to your shortcode. It would look something like: [submit-review redirect_page='http://www.example.com']

= How do I manage reviews or submit my own? =

* In the WordPress admin area, click on the "Reviews" menu item in the navgiation bar. You can add, edit and delete reviews from there.

= How do I replace WooCommerce reviews with those from Ultimate Reviews? =

* You need to set to "Yes" the "Replace WooCommerce Reviews" option, which is found in the "Premium" area of the Settings page.

= What are in-depth reviews? =

* In-depth reviews allow you to divide your reviews into multiple sections, called "Review Categories" in the plugin. Each category has its own score input as well as the ability to turn on an explanation, which allows reviewers to elaborate on their score.

= Can I restrict what visitors are allowed to review (e.g. only specific products)? =

* Yes, by setting the "Restrict Product Names" option to "Yes" and/or the "Product Name Input Type" option to "Dropdown," you can then use the "Products List" section to define the products (or anything else) that you'd like visitors to be able to review.

= How do I integrate the Ulimate Product Catalog plugin? =

* You need to set the "UPCP Integration" to "Yes" and then set the "Restrict Product Names" option to "Yes" and/or the "Product Name Input Type" option to "Dropdown." You can then also set the "Autocomplete Product Names" option to "Yes" and the name field in your review form will be automatically completed with product names from your product catalog as the user types.

= Is it possible to re-order my reviews? =

* Currently you can choose between ascending or descending ordering by either Submitted Date, Rating or Review Title. You can also group your reviews by product with the ability to order them (ascending or descending) by product name.

= How do I customize the styling and layout of my reviews? =

* You can customize the plugin by adding code to the "Custom CSS" box on the settings page. For example to change the font of the review title, you might want to add something like:

.ewd-urp-review-title { font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif; }

In the premium version you can choose between Standard, Thumbnail and Expandable review layouts as well as use the "Styling" area in the settings, which has a bulit-in color picker for color fields and more!

= What are summary statistics? =

* When you choose to group your reviews by product, you can enable either full or limited summary statistics. These display at the top of the product grouping and show the average score as well as a breakdown (bar graphic and numbers) of how many ratings each score has.


== Screenshots ==

1. The stars review style
2. The color bar review style
3. The simple bar review style
4. The hearts review style
5. The thumbnail review format
6. The expandable review format
7. The submit review form
8. WooCommerce review integration
9. WooCommerce rating and review integration on the WooCommerce product page
10. The reviews tab for the "Ultimate Product Catalog Plugin"
11. The admin area showing the review title and the product being reviewed
12. The basic review options page
13. The premium review options page
14. The ordering review options page
15. The labelling review options page
16. The styling review options page

== Changelog ==
= 1.2.2 =
- Fixed an error where pagination would mess up the product_name attribute if it was set

= 1.2.1 =
- Added in a filter for the author name on individual post pages

= 1.2.0 =
- Added in filtering options so visitors can filter by product name or review score
- Added in a premium shortcode, ultimate-review-search, which lets visitors put in a search term to help find specific reviews which will be helpful to them
- Added in a number of features that will be needed to allow comments on reviews
- Added in an import feature for WooCommerce reviews
- Added in two new styling options to help customize the plugin to your theme
- Made a number of minor styling improvements

= 1.1.3 =
- Fixed a problem where reviews entered through the admin panel couldn't set "Email Confirmed" to "Yes"

= 1.1.2 =
- Added in two missing font classes

= 1.1.1 =
- Fixed an error that was happening when there was no character limit entered
- Fixed an warning that was coming up after an old version of one file was mistakenly included in the update

= 1.1.0 =
- Added a bunch of requested and new features! We've done quite a bit of testing, but there might be a couple of bugs left, so please check to make sure that everything is working correctly if you're using the plugin on a production site
- Added a new "Review Karma" preimum option, that lets visitors vote up or down for reviews, to show which ones they found most helpful
- Added a "Review Image" option to let reviewers leave an image with their review
- Added options to use stars instead of text or dropdown for review scores
- Added options to customize the "review submitted" and "requires admin approval" messages, an optional character limit for reviews
- Products in the "Product List" can now be sorted by dragging and dropping instead of needing to delete them to rearrange
- Added weighted ratings (only limited testing of this feature though)
- Added a "requires admin approval" message, if that option is turned on
- Switched to descending order for ratings in the dropdown format
- Made a few minor CSS changes

= 1.0.8 =
- Removed decimal reviews from the full summary listing
- Fixed a repeated option

= 1.0.7 =
- Minor re-arrangement of the options page

= 1.0.6 =
- Fixed a null variable error message

= 1.0.5 =
- Added the ability to specify the product_name attribute via an URL parameter

= 1.0.4 =
- Added an attribute, redirect_page, which lets you specify the page that users should be sent to after submitting a review
- Added an option to not display the submit-review form on email confirmation

= 1.0.3 =
- Small fix for users who didn't have the "stars" style displaying correctly on their sites

= 1.0.2 =
- Added styling options for the color bar display style as well as the summary statistics bars
- Added in a "Email Confirmed" dropdown for reviews, so that admins can mark reviews as confirmed
- Put a maximum width on the dropdown score inputs
- Added in the missing Facebook and Twitter login images
- Fixed an error where the product name dropdown input ended up in the wrong div

= 1.0.1 =
- Fixed a fatal error caused by a function being declared twice

= 1.0.0 =
- HUGE update with many new features, so please back up before upgrading if you're using the plugin on a live site!
- Added option to require login before being able to post a review, either through WordPress, FEUP, Facebook or Twitter
- Replace “Reviews” tab on WooCommerce product page with reviews from Ultimate Reviews 
- Replace “Ratings” area on WooCommerce product page with ratings from Ultimate Reviews 
- Multiple layout options for your reviews, including thumbnail (excerpt with read more link) and expandable layouts, in addition to the standard layout
- Require Admin Approval of reviews before they're displayed
- Add a captcha to your review submission form to help prevent spam
- Display summary statistics above product groupings
- Added pagination and an option to choose where pagination controls are displayed
- Option to change the maximum review score, or display scores as a percentage
- Added widgets for recent, popular or selected reviews
- Option to switch the review input to a select element instead of a text element
- Added in Google aggregate reviews microdata for WooCommerce
- Added admin notifications of draft reviews
- WPML support
- Added each review's score in the product table
- Added ability to filter by reviewed product name

= 0.16 =
- Added in a closing div tag that could be missing depending on the options selected

= 0.15 =
- Minor CSS update

= 0.14 =
- Added an option to link to the individual review's single post page
- The name of the product being reviewed will now be displayed if all reviews are being displayed
- Fixed a bug where it was impossible to delete products from the product list

= 0.13 =
- The product_name attribute is no longer necessary. All reviews will be returned if it's not included

= 0.12 =
- Included a missing function to allow editing of the "Overall Score" field when "In-Depth" is set to "No"

= 0.11 =
- Added new labelling options
- Added new styling options
- Cleaned up a lot of the 'submit-review' page
- Fixed an error where the in-depth portion of reviews couldn't be saved when being modified in the back-end

= 0.10 =
- Fixed an error with the settings link on the plugins page

= 0.9 =
- Fixed the error where product names weren't displaying in the back-end table

= 0.8 =
- Fixed a bunch of small display, spacing, missing content, etc. errors

= 0.7 =
- Added a bunch of ordering options, so reviews can be shown by order they were submitted, the reviews' ratings, or their titles

= 0.6 =
- Added a bunch of new styling options, to make reviews more visually appealing
- Added restricted product names and autocomplete options, to make it easier to quality control reviews
- Added UPCP product names integration
- Fixed the display for indepth reviews

= 0.5 =
- Fixed a missing closing div tag error

= 0.4 =
- Updated the readme file to include new features
- Added screenshots of the plugin
- Fixed a whole bunch of small bugs

= 0.3 =
- Added a whole bunch of new options

= 0.2 =
- Styling update

= 0.1 =
- Initial beta version. Please make comments/suggestions in the "Support" forum.

== Upgrade Notice ==