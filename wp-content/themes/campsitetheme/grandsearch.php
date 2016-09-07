<?php
/*
  Template Name: Grand_Search
 */

//Function starts from here

//Function ends here
//Array starts from here
$campTypes = array('car' => "Car Camping", 'hike' => "Hike In", 'rvs' => "RVs Only");
$ratings = array('all' => "Overall Rating", 'scene' => "Scenic Beauty", 'location' => "Location", 'friendly' => "Family Friendly", 'privacy' => "Privacy", 'clean' => "Cleanliness", 'bug' => "Bug Factor");
//Array ends here
//Code starts from here
get_header();
$user = wp_get_current_user();
if ($_GET) {
    $search = get_camps_by_name_count_fast($_GET);
    $total = count($search);
    if (!empty($search) && !empty($_GET['rating'])) {
        foreach ($search as $key => $value) {
            $reviewsOfSearchedCamp = get_reviews_by_id($value['ID']);
            $totalReviewsOfSearchedCamp = count($reviewsOfSearchedCamp);
            $searchedCampOverAllRating = get_all_rating($totalReviewsOfSearchedCamp, $value['ID'], $_GET['rating']);
            $search[$key][$_GET['rating']] = $searchedCampOverAllRating;
        }
        $search = array_orderby($search, $_GET['rating'], SORT_DESC);
    }
}
//Code ends here
?>
<!-- Style starts from here-->
<style>
    table td[class*="col-"], table th[class*="col-"]{
        text-indent: 8px;
        line-height: 3;
        font-size: 12px;
        color: #777;
    }
    input{
        outline: 0 none !important;
    }
    .search-filters
    {
        background:#f3f3f3; padding:20px;
        border-radius:5px;
    }
    .search-filters h4
    {
        margin-top:0px;
        font-weight:bold;
        color:#000;
    }

    .content-section {
        padding-bottom: 50px;
    }
    .content-section-header {

        margin-bottom: 25px;
    }
    .content-section-header h1 {
        background-color: #ff7444;
        color: #fff;
        font-size: 24px;
        margin: 0;
        padding: 10px 15px;
    }
    .list-unstyled, .list-inline, .change-country-list, .campgrounds-list, .region-list, .review-type-list, .reviews-list {
        list-style: outside none none;
        padding-left: 0;
    }
    .campgrounds-list-item {
        min-height: 70px;
        padding-bottom: 10px;
        position: relative;
    }
    .campground-list-title-container {
        line-height: 12px;
        color:#216462;
    }
    .campground-list-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.42857;
        color:#216462;
    }
    .campground-list-title {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.42857;
    }
    .ratings-container {
        margin-bottom: 5px;
    }
    .campgrounds-list .ratings-stars {
        margin-right: 0;
    }
    .ratings-stars {
        display: inline-block;
        line-height: 18px;
        margin-right: 12px;
        vertical-align: top;
    }
    .link-blended
    {
        color:#333;
    }
    .page-numbers.current{
        background-color: #23527c;
        color: white !important;
        border: 1px solid #23527c;
    }
    .page-numbers.current:hover{
        background-color: #23527c;
        border: 1px solid #23527c;
    }
</style>
<!-- Style ends here-->
<!-- Div starts from here-->
<div class="container">
    <div class="clear40"></div>
    <section class="search-header col-sm-8 col-sm-offset-2 ">
        <h4 class="search-hdr">Search with any terms you're looking for. Campground Names, Locations, Cities, and more.</h4>
        <div class="row">
            <form method="get" id="midsearch" action="<?php echo get_site_url() ?>/grandsearch" class="form-horizontal">        
                <div class="col-sm-12">
                    <div class="input-group">
                        <span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" value="<?php echo $_GET['query'] ?>" id="query2" name="query" class="form-control ui-autocomplete-input" autocomplete="off" placeholder="Search City, Campground, etc...">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-secondary btn-search btn_blue" style="margin-top:0; height:34px;">GO</button>
                        </span>

                    </div>
                </div>
                <div class="clear10"></div>
                <div class="col-sm-12 search-options">
                    <div class="col-sm-4 padding0">
                        <select name="regionId" class="form-control">
                            <option value="">- Any State/Providence -</option>
                            <optgroup label="USA">
<?php
$selectedState = $_GET['regionId'];
if (!empty(get_states())):
    foreach (get_states() as $state) :
        ?>
                                        <option value="<?php echo $state->state_code ?>" <?php
                                        if ($selectedState == $state->state_code) {
                                            echo("selected");
                                        }
                                        ?>><?php echo $state->state_name ?></option>
                                                <?php
                                            endforeach;
                                        endif;
                                        ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="clear10 visible-xs"></div>
                    <div class="col-sm-4 padding0">   
                        <select name="ftype" class="form-control">
                            <option value="">- Any Type -</option>
                            <?php
                            $selectedCampType = $_GET['ftype'];
                            foreach ($campTypes as $key => $value) :
                                ?>
                                <option value="<?php echo $key ?>" <?php
                                if ($selectedCampType == $key) {
                                    echo("selected");
                                }
                                ?>><?php echo $value ?></option>
                                        <?php
                                    endforeach;
                                    ?>
                        </select>
                    </div>
                    <div class="clear10 visible-xs"></div>
                    <div class="col-sm-4 padding0">   
                        <select name="rating" class="form-control">
                            <option value="">- Sort By -</option>
                            <?php
                            $selectedCampRating = $_GET['rating'];
                            foreach ($ratings as $key => $value) :
                                ?>
                                <option value="<?php echo $key ?>" <?php
                                        if ($selectedCampRating == $key) {
                                            echo("selected");
                                        }
                                        ?>><?php echo $value ?></option>
    <?php
endforeach;
?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<div class="clear20"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg" style="text-align: center;">
    <div id="detailmap" style=" width: 67%; height: 300px; border-radius:5px; border:2px solid white; margin: auto;"></div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 middle_bg">
    <div class="col-sm-12 container white_box_login" style="padding-right:30px; padding-left:30px;margin:0 auto; float:none;">
        <div class="col-sm-4">
            <section class="search-filters ">
                <h4>Optional Search Filters</h4>
                <table width="100%" cellpadding="2" cellspacing="2">
                    <tbody><tr>
                            <th width="28" class="col-yes">Yes</th>
                            <th width="17" class="col-no">No</th>
                            <th width="112" class="col-label"></th>
                        </tr>
                        <tr>
                            <td align="center" class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_pets]" id="cg_pets_yes" class="filter" <?php if ($_GET['filter']['cg_pets'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center" class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_pets]" id="cg_pets_no" class="filter" <?php if ($_GET['filter']['cg_pets'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">Pets Allowed</td>
                        </tr>
                        <tr>
                            <td align="center" class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_play]" id="cg_play_yes" class="filter" <?php if ($_GET['filter']['cg_play'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center" class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_play]" id="cg_play_no" class="filter" <?php if ($_GET['filter']['cg_play'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">Playground Access</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_wifi]" id="cg_wifi_yes" class="filter" <?php if ($_GET['filter']['cg_wifi'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_wifi]" id="cg_wifi_no" class="filter" <?php if ($_GET['filter']['cg_wifi'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">WiFi Access</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_showers]" id="cg_showers_yes" class="filter" <?php if ($_GET['filter']['cg_showers'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_showers]" id="cg_showers_no" class="filter" <?php if ($_GET['filter']['cg_showers'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">Showers</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_flush]" id="cg_flush_yes" class="filter" <?php if ($_GET['filter']['cg_flush'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_flush]" id="cg_flush_no" class="filter" <?php if ($_GET['filter']['cg_flush'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">Flush Toilets</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_vault]" id="cg_vault_yes" class="filter" <?php if ($_GET['filter']['cg_vault'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_vault]"  id="cg_vault_no" class="filter" <?php if ($_GET['filter']['cg_vault'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label">Vault Toilets</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_tent]" id="cg_tent_yes" class="filter" <?php if ($_GET['filter']['cg_tent'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_tent]"  id="cg_tent_no" class="filter" <?php if ($_GET['filter']['cg_tent'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label" style="font-size: 11px;">Tent Pad</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_picnic]" id="cg_picnic_yes" class="filter" <?php if ($_GET['filter']['cg_picnic'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_picnic]"  id="cg_picnic_no" class="filter" <?php if ($_GET['filter']['cg_picnic'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label" style="font-size: 11px;">Picnic Table</td>
                        </tr>
                        <tr>
                            <td align="center"  class="col-yes">
                                <input type="checkbox" value="yes" name="filter[cg_fire]" id="cg_fire_yes" class="filter" <?php if ($_GET['filter']['cg_fire'] === 'yes') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="center"  class="col-no">
                                <input type="checkbox" value="no" name="filter[cg_fire]"  id="cg_fire_no" class="filter" <?php if ($_GET['filter']['cg_fire'] === 'no') echo 'checked="checked"'; ?>>
                            </td>
                            <td align="left" class="col-label" style="font-size: 11px;">Fire Ring</td>
                        </tr>
                    </tbody></table>
                <button type="submit" id="submit_button" class="btn btn-secondary btn-search btn_blue">Update Search</button>
            </section>
        </div>
        <div class="col-sm-8">
            <section class="content-section search-results">
                <div class="top-pagination"></div>
                <header class="content-section-header">
                    <h1><?php echo $total ?> Results</h1>
                </header>
                <?php
                if (!empty($search)) {
                    echo '<div id="camps">';
                    //print_r($search); @die;
                    foreach ($search as $value) {
                        $reviewsOfSearchedCamp = get_reviews_by_id($value['ID']);
                        $totalReviewsOfSearchedCamp = count($reviewsOfSearchedCamp);
                        $searchedCampOverAllRating = get_overall_rating($totalReviewsOfSearchedCamp, $value['ID']);
                        $camp_path = get_site_url() . '/camp?id=' . $value['ID'];
                        ?>
                        <div class="z">
                            <ol class="campgrounds-list">
                                <li class="campgrounds-list-item">
                                    <a href="<?php echo $camp_path ?>" class="campground-list-title"><?php echo $value['camp_name'] ?></a> - <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['city_name'] ?>" class="link-blended"><?php echo $value['city_name'] ?></a>, <a href="<?php echo get_site_url() ?>/grandsearch?query=<?php echo $value['state_name'] ?>" class="link-blended"><?php echo $value['state_name'] ?></a>
                                    <p class="ratings-container">
                                        <span class="campground-badge-stat pull-left star-dec" itemprop="ratingValue" style="font-size:14px; font-weight:bold;"><?php echo $searchedCampOverAllRating; ?>/10</span>
                                        <span class="ratings-total-reviews" style="margin-left: 10px; line-height: 2;"><?php echo $totalReviewsOfSearchedCamp; ?> Reviews</span>
                                    </p>
                                </li>
                            </ol>
                        </div>
                        <?php
                    }
                    echo '</div>';
                } else {
                    echo '<div>';
                    echo '<strong>Message</strong>: ';
                    echo 'No campgrounds exist.<br/>';
                    echo '</div>';
                }
                ?>
                <div id="pagingControls"></div>		
            </section>
        </div>
        <div class="clear40"></div>
    </div>
</div>
<div class="clear"></div>
<!-- Div ends here-->
<?php
get_footer();
?>
<!--start of ahmad's grandsearch   -->
<script type="text/javascript">
//    var txt_filter;
//    jQuery("#query2").change(function () {
//        $('#query2').val('');
//        $('#query2').attr("placeholder", "Type a City");
//        $('#query2').prop("disabled", false);
//        txt_filter = $('#query2').val();
//        var testingarray1 = [];
//        var number_of_names;
//        var i;
//        jQuery.ajax({
//            type: "POST",
//            url: "<?php echo get_site_url() ?>/g_search",
//            data: {selected: txt_filter},
//            success: function (data) {
//                var arr = JSON.parse(data);
//                number_of_names = arr.length;
//                // ahmad's code end
//                for (i = 0; i < number_of_names; i++) {
//                    testingarray1[i] = arr[i].city_name;
//                }
//                jQuery("#select_city").autocomplete({
//                    source: testingarray1
//                });
//            },
//            error: function (data) {
//                // Stuff
//            }
//        });
//    });
</script>
<!-- end of ahmad's grandsearch  -->
<script>
    $(function () {
        $('#submit_button').click(function () {
            var qp = {}, qs = location.search.substring(1), re = /([^&=]+)=([^&]*)/g, m;
            while (m = re.exec(qs)) {
                if (decodeURIComponent(m[1]) == "query" || decodeURIComponent(m[1]) == "regionId" || decodeURIComponent(m[1]) == "ftype" || decodeURIComponent(m[1]) == "rating")
                {
                    qp[decodeURIComponent(m[1])] = decodeURIComponent(m[2]).replace(/\+/g, ' ');
                }
            }
            var pre;
            $('input.filter').each(function () {
                var name = this.name;
                if (this.checked && this.value == "yes") {
                    qp[name] = this.value;
                } else if (this.checked && this.value == "no") {
                    qp[name] = this.value;
                }
                else if (!this.checked && !pre) {
                    qp[name] = '';
                }
                pre = this.checked;
            });
            location.search = $.param(qp);
        });

    });
    $("#cg_pets_yes").click(function () {
        if (document.getElementById("cg_pets_yes").checked)
        {
            document.getElementById("cg_pets_yes").checked = true;
            document.getElementById("cg_pets_no").checked = false;

        }
        else
        {
            document.getElementById("cg_pets_yes").checked = false;
        }
    });
    $("#cg_pets_no").click(function () {
        if (document.getElementById("cg_pets_no").checked)
        {
            document.getElementById("cg_pets_no").checked = true;
            document.getElementById("cg_pets_yes").checked = false;

        }
        else
        {
            document.getElementById("cg_pets_no").checked = false;
        }
    });
    $("#cg_play_yes").click(function () {
        if (document.getElementById("cg_play_yes").checked)
        {
            document.getElementById("cg_play_yes").checked = true;
            document.getElementById("cg_play_no").checked = false;

        }
        else
        {
            document.getElementById("cg_play_yes").checked = false;
        }
    });
    $("#cg_play_no").click(function () {
        if (document.getElementById("cg_play_no").checked)
        {
            document.getElementById("cg_play_no").checked = true;
            document.getElementById("cg_play_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_play_no").checked = false;
        }
    });
    $("#cg_wifi_yes").click(function () {
        if (document.getElementById("cg_wifi_yes").checked)
        {
            document.getElementById("cg_wifi_yes").checked = true;
            document.getElementById("cg_wifi_no").checked = false;
        }
        else
        {
            document.getElementById("cg_wifi_yes").checked = false;
        }
    });
    $("#cg_wifi_no").click(function () {
        if (document.getElementById("cg_wifi_no").checked)
        {
            document.getElementById("cg_wifi_no").checked = true;
            document.getElementById("cg_wifi_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_wifi_no").checked = false;
        }
    });
    $("#cg_showers_yes").click(function () {
        if (document.getElementById("cg_showers_yes").checked)
        {
            document.getElementById("cg_showers_yes").checked = true;
            document.getElementById("cg_showers_no").checked = false;
        }
        else
        {
            document.getElementById("cg_showers_yes").checked = false;
        }
    });
    $("#cg_showers_no").click(function () {
        if (document.getElementById("cg_showers_no").checked)
        {
            document.getElementById("cg_showers_no").checked = true;
            document.getElementById("cg_showers_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_showers_no").checked = false;
        }
    });
    $("#cg_flush_yes").click(function () {
        if (document.getElementById("cg_flush_yes").checked)
        {
            document.getElementById("cg_flush_yes").checked = true;
            document.getElementById("cg_flush_no").checked = false;
        }
        else
        {
            document.getElementById("cg_flush_yes").checked = false;
        }
    });
    $("#cg_flush_no").click(function () {
        if (document.getElementById("cg_flush_no").checked)
        {
            document.getElementById("cg_flush_no").checked = true;
            document.getElementById("cg_flush_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_flush_no").checked = false;
        }
    });
    $("#cg_vault_yes").click(function () {
        if (document.getElementById("cg_vault_yes").checked)
        {
            document.getElementById("cg_vault_yes").checked = true;
            document.getElementById("cg_vault_no").checked = false;
        }
        else
        {
            document.getElementById("cg_vault_yes").checked = false;
        }
    });
    $("#cg_vault_no").click(function () {
        if (document.getElementById("cg_vault_no").checked)
        {
            document.getElementById("cg_vault_no").checked = true;
            document.getElementById("cg_vault_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_vault_no").checked = false;
        }
        });
        $("#cg_tent_yes").click(function () {
        if (document.getElementById("cg_tent_yes").checked)
        {
            document.getElementById("cg_tent_yes").checked = true;
            document.getElementById("cg_tent_no").checked = false;
        }
        else
        {
            document.getElementById("cg_tent_yes").checked = false;
        }
    });
    $("#cg_tent_no").click(function () {
        if (document.getElementById("cg_tent_no").checked)
        {
            document.getElementById("cg_tent_no").checked = true;
            document.getElementById("cg_tent_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_tent_no").checked = false;
        }
        });
        $("#cg_picnic_yes").click(function () {
        if (document.getElementById("cg_picnic_yes").checked)
        {
            document.getElementById("cg_picnic_yes").checked = true;
            document.getElementById("cg_picnic_no").checked = false;
        }
        else
        {
            document.getElementById("cg_picnic_yes").checked = false;
        }
    });
    $("#cg_picnic_no").click(function () {
        if (document.getElementById("cg_picnic_no").checked)
        {
            document.getElementById("cg_picnic_no").checked = true;
            document.getElementById("cg_picnic_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_picnic_no").checked = false;
        }
     });   
        $("#cg_fire_yes").click(function () {
        if (document.getElementById("cg_fire_yes").checked)
        {
            document.getElementById("cg_fire_yes").checked = true;
            document.getElementById("cg_fire_no").checked = false;
        }
        else
        {
            document.getElementById("cg_fire_yes").checked = false;
        }
    });
    $("#cg_fire_no").click(function () {
        if (document.getElementById("cg_fire_no").checked)
        {
            document.getElementById("cg_fire_no").checked = true;
            document.getElementById("cg_fire_yes").checked = false;
        }
        else
        {
            document.getElementById("cg_fire_no").checked = false;
        }
        
    });
</script>
<script type="text/javascript">
    var pager = new Imtech.Pager();
    $(document).ready(function () {
        pager.paragraphsPerPage = 20; // set amount elements per page
        pager.pagingContainer = $('#camps'); // set of main container
        pager.paragraphs = $('div.z', pager.pagingContainer); // set of required containers
        pager.showPage(1);
    });
</script>
<script>
<?php
if ($search == NULL) {
    $search[0]['camp_latitude'] = 39.56841;
    $search[0]['camp_longitude'] = -101.80533;
}
?>
    var myOptions = {
        zoom: 5,
        center: new google.maps.LatLng(<?php echo $search[0]['camp_latitude'] ?>,<?php echo $search[0]['camp_longitude'] ?>),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("detailmap"), myOptions);
    var infowindow = new google.maps.InfoWindow();
    var i = 0;
<?php
if ($search != NULL) {
    foreach ($search as $value) {
        $reviewsOfSearchedCamp = get_reviews_by_id($value['ID']);
        $totalReviewsOfSearchedCamp = count($reviewsOfSearchedCamp);
        $searchedCampOverAllRating = get_overall_rating($totalReviewsOfSearchedCamp, $value['ID']);
        $cc_id = $value['ID'];
        ?>
            var lat = <?php echo $value['camp_latitude']; ?>;
            var long = <?php echo $value['camp_longitude']; ?>;
            var myLatlng = new google.maps.LatLng(lat, long);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: "<?php echo $searchedCampOverAllRating; ?> <?php //echo $value['state_name'];   ?> "
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
        <?php $c_name = $value['camp_name'];
        $url = get_site_url() . "/camp?id=$cc_id";
        ?>
                    infowindow.setContent("<?php echo "<a href=$url>" . $c_name . "</a>&nbsp;<span style='color: #ff7444;'>" . $searchedCampOverAllRating . "/10</span>"; ?>");
                    infowindow.open(map, marker);
                }
            })(marker, i));
            i++;
        <?php
    }
}
?>
</script>