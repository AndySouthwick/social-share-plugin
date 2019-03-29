<?php
/*
Plugin Name: Usana Social Sharing
Plugin URI: http://andrewsouthwick.com
Description: usana associate tracking to pass information to usana.com 1 shortcode for sharing, [sociallinks] the other for a button linking to associates page [abuybutton]
Author: Andrew Soutwhick, Maidul
Author URI: http://andrewsouthwick.com
License: GPL3
*/
/*
URL Params (Wordpress Plugin)
Copyright (C) 2017 Andrew Southwick
*/
//Add custom query vars
add_action( 'init', 'uss_script_enqueuer' );
add_action( 'get_footer', 'social_shares' );
//add_shortcode( 'echomyid', 'get_current_associate_id' );
add_action( 'wp_ajax_nopriv_get_current_associate_id', 'get_current_associate_id' );
add_action( 'wp_ajax_get_current_associate_id', 'get_current_associate_id' );

add_shortcode( 'abuybutton', 'shortcode_associate_id' );

        function add_query_vars_filter( $vars ){
          if (empty($vars)) {
      		// Prevent accessing empty string as array.
      			$vars = array(); 
      		}
        $vars[] = "associate";
        return $vars;
        }
     function social_shares(){        
              echo'  <div class="social-side-bar">
                        <!-- Trigger/Open The Modal -->
                        <div id="myBtn">
                          <a id="fb" data-share="facebook" data-title="" data-description="" target="_blank"><i class="fa fa-facebook fa-2x spacer" aria-hidden="true"></i></a>
                          <a id="tw" data-share="twitter" data-title=""  data-hashtags="" target="_blank"> <i class="fa fa-twitter fa-2x spacer" aria-hidden="true"></i></a>
                          <div id="cl" class="copyTheLink"><i class="fa fa-link fa-2x spacer fa-flip-horizontal" aria-hidden="true"></i></div>
                        </div>
                        </div>

                        <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close"><i class="fa fa-times" aria-hidden="true"></i></span>
     
    <h3>ADD ASSOCIATE ID TO SHARE</h3>
    <form class="usana-share-form" method="POST">
    <div>
    <label>USANA ID</label>
      <input type="text" name="associate_id" id="associate_id" required/>
      </div>
      <button type="submit" id="share-form-btn">SUBMIT</button>
    </form>

    <div id="popup-share-links">
      <a id="fb" data-share="facebook" data-title="" data-description="" target="_blank"><i class="fa fa-facebook fa-2x spacer" aria-hidden="true"></i></a>
      <a id="tw" data-share="twitter" data-title=""  data-hashtags="" target="_blank"> <i class="fa fa-twitter fa-2x spacer" aria-hidden="true"></i></a>
      <div id="cl" class="copyTheLink"><i class="fa fa-link fa-2x spacer fa-flip-horizontal" aria-hidden="true"></i></div>

      </div>
     
  </div>

</div>'; 
ob_start();
?>
<?php
  return ob_get_clean();
}

add_action( 'wp_enqueue_scripts', 'enqueue_load_fa' );
function enqueue_load_fa() {
wp_enqueue_style( 'load-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}

//enque js and styles
function uss_script_enqueuer() {
    wp_enqueue_script( 'js-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', false );
    wp_enqueue_script( "magnific-popup", 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery') );
    wp_enqueue_script( "clipboard", '//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js', array('jquery') );
    wp_enqueue_script( "tracking_script", plugin_dir_url( __FILE__ ) .'/usana_tracking.js', array('jquery') );
    wp_localize_script( 'tracking_script', 'usanatracking', array('ajax_url' => admin_url( 'admin-ajax.php' )));

     wp_register_style( "modal_styles", plugin_dir_url( __FILE__ ) .'usana_tracking.css' );
     wp_register_style( "magnific-popup", 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css' );

    wp_enqueue_style( 'magnific-popup' );
     wp_enqueue_style( 'modal_styles' );
}
//after customer of associate clicks link get the associate params set cookie
function get_params_set_cookie(){
            if(!empty($_GET['id']) && isset($_GET['id'])){
          $cookie_value = $_GET['id'];
            setcookie('attribute', $cookie_value, time() + (86400 * 30), "/", "askthescientists.com/" );
            $ilovecookies = $_COOKIE['attribute'];
            if (!isset($_COOKIE['attribute'])){
             header("Refresh:0");

        }
    }
}
//create shortcode button to send associate customer to their page
function shortcode_associate_id(){

  return '<a class="shop-link" href="https://shop.usana.com/shop/cart/Landing?distributorId=my_associates_id" target="blank"><i class="fa fa-shopping-cart" aria-hidden="true"></i>'.__(' SHOP HERE', 'usana').'</a>';

}
//some changes stuff


// updated URLS for new shopping cart
function biomega_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/122.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'biomega', 'biomega_shortcode' );

function vita_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/103.010104&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'vita', 'vita_shortcode' );

function wholebio_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/240.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';
    
    }
    add_shortcode( 'wholebio', 'wholebio_shortcode' );

function visionex_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/134.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'visionex', 'visionex_shortcode' );

function vitamind_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/109.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'vitamind', 'vitamind_shortcode' );

function hepasil_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/135.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'hepasil', 'hepasil_shortcode' );

function coreminerals_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/102.010104&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'coreminerals', 'coreminerals_shortcode' );

function magnecald_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/120.010102&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'magnecald', 'magnecald_shortcode' );

function phytoestrin_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/129.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'phytoestrin', 'phytoestrin_shortcode' );

function palmettoplus_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/128.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'palmettoplus', 'palmettoplus_shortcode' );

function proflavanol_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/110.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'proflavanol', 'proflavanol_shortcode' );

function digestiveenzyme_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/111.010102&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'digestiveenzyme', 'digestiveenzyme_shortcode' );

function procosa_shortcode(){

  return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/131.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'procosa', 'procosa_shortcode' );

function probiotic_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/108.010102&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'probiotic', 'probiotic_shortcode' );

function proglucamune_shortcode(){

    return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/146.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'proglucamune', 'proglucamune_shortcode' );

function chewablecalicum_shortcode(){
      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/121.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'chewablecalicum', 'chewablecalicum_shortcode' );

function ginkops_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/126.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'ginkops', 'ginkops_shortcode' );

function coquinone_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/123.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'coquinone', 'coquinone_shortcode' );

function boosterc_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/143.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'boosterc', 'boosterc_shortcode' );

function purerest_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/141.010102&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'purerest', 'purerest_shortcode' );

function biomegajr_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/144.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'biomegajr', 'biomegajr_shortcode' );

function bodyrox_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/104.010102&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'bodyrox', 'bodyrox_shortcode' );

function usanimals_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/105.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'usanimals', 'usanimals_shortcode' );

function healthpak_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/100.010104&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'healthpak', 'healthpak_shortcode' );

function cellsentials_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/101.010104&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'cellsentials', 'cellsentials_shortcode' );

function prenatalcellsentials_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/151.010189&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'prenatalcellsentials', 'prenatalcellsentials_shortcode' );

function rev3energydrink_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/138.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'rev3energydrink', 'rev3energydrink_shortcode' );

function rev3energysurge_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/139.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'rev3energysurge', 'rev3energysurge_shortcode' );

function rev3energysurge28_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/139.010119&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'rev3energysurge28', 'rev3energysurge28_shortcode' );

function smartshakeplant_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/206.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'smartshakeplant', 'smartshakeplant_shortcode' );

function smartshakesoy_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/205.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'smartshakesoy', 'smartshakesoy_shortcode' );

function smartshakewhey_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/207.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'smartshakewhey', 'smartshakewhey_shortcode' );

function fibergy_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/226.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'fibergy', 'fibergy_shortcode' );

function chocolatenutrimeal_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/211.010103&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'chocolatenutrimeal', 'chocolatenutrimeal_shortcode' );

function celavivedry_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/353.010101&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivedry', 'celavivedry_shortcode' );

function celaviveoily_shortcode(){

       return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/353.010110&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celaviveoily', 'celaviveoily_shortcode' );

function celavivemilkcleanser_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/332.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivemilkcleanser', 'celavivemilkcleanser_shortcode' );

function celavivefoamcleanser_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/333.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivefoamcleanser', 'celavivefoamcleanser_shortcode' );

function celavivedaycream_shortcode(){

     return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/335.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivedaycream', 'celavivedaycream_shortcode' );

function celavivedaylotion_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/334.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivedaylotion', 'celavivedaylotion_shortcode' );

function celavivenightcream_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/337.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivenightcream', 'celavivenightcream_shortcode' );

function celavivenightgel_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/336.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivenightgel', 'celavivenightgel_shortcode' );

function celaviveserum_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/339.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celaviveserum', 'celaviveserum_shortcode' );

function celavivetoner_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/331.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivetoner', 'celavivetoner_shortcode' );

function celaviveeye_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/340.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celaviveeye', 'celaviveeye_shortcode' );

function celavivemask_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/342.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivemask', 'celavivemask_shortcode' );

function celavivemakeup_shortcode(){

      return '<a class="shop-link" href="https://www.usana.com/ux/cart/#!/en-US/so/PHX-URL/my_associates_id/330.010100&shopperSource=ATS" target="blank">'.__('SHOP HERE', 'usana').'</a>';

}
add_shortcode( 'celavivemakeup', 'celavivemakeup_shortcode' );
