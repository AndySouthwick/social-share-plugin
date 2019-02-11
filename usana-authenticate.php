<?php
/**
 * combine wordpess and associated login form
 * @author Andrew Southwick
 */

add_shortcode('combined_login_form','combined_login_form_fun');

function combined_login_form_fun()
{
	ob_start();
    if(!isset($_SESSION['id'])){
	?>
<script>
	function onSubmit(token) {
		console.log('submitted');
		jQuery(document).ready(function ($) {

		var $form = $("form#associate_login_form");

		$form.find('.fa-spinner').css("display","inline-block");
		$form.find(".btn-blue").attr("disabled", true);

		$.ajax({
				type:"POST",
				url: usanaAjax.ajaxurl,
				dataType: 'json',
				data: {
						action: 'authenticate_associates',
						value: $form.serialize()
				},
				success:function(data){
						$form.find('.fa-spinner').css("display","none");
						$form.find(".btn-blue").attr("disabled", false);
						console.log(data);

						if (data.url) {
								window.location.href = data.url;
						}

						if (data.error) {
								$('#al_response').empty().append(data.error);
						}

				}
		});

		});
	}

	function validate(event) {
		event.preventDefault();

		if (!jQuery("form#associate_login_form").valid()) {
			console.log('Form not valid');
		} else {
			grecaptcha.execute();
		}
	}

	function onload() {
		var element = document.getElementById('associate_login_form_submit');
		element.onclick = validate;
	}
</script>

	<div id="associate_login_form_wrap">
		<h3><?php _e('Login to Ask The Scientists with your Associate ID and USANA password to share this content or to submit questions', 'usana'); ?></h3>
		<form id="associate_login_form" method="POST">
			<div>
			    <input type="text" name="usid" id="usid" value="" placeholder="<?php _e('Associate ID', 'usana'); ?>" required/>
				<input type="password" name="uspw" id="password" value="" placeholder="<?php _e('Password', 'usana'); ?>" required/>
			</div>
			<div class="login-remember">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever">
			<label for="rememberme"><?php _e('Remember Me', 'usana'); ?></label>
			<div id='recaptcha' class="g-recaptcha"
          data-sitekey="6Ld2kCgUAAAAADsdZmo9DVVoXuN0ZZeHSEHptPHp"
          data-callback="onSubmit"
          data-size="invisible"></div>
			<button type="submit" class="btn btn-blue" id="associate_login_form_submit"><?php _e('Submit', 'usana'); ?><i class="fa fa-spin fa-spinner"></i></button>
			<script>onload()</script>
			</div>

		</form>
		<div id="al_response"></div>
	</div>
	<?php
	}else{
		_e('You are currently logged in. To submit a question  <a href="/submit-a-question">click here</a> ', 'usana');
	}
	return ob_get_clean();

}

/*---------------------------------------------
  Authentication from RESET NATION Challenge
----------------------------------------------*/
/**
 * Function to authenticate with ajax
 * @since Dec 15th 2015
 * @author Trevor
 */

function ajax_authenticate_associates(){

    //process form data

    $formArray = array();

    parse_str($_POST['value'], $formArray);

	global $wpdb;

	//-- sanitize variables --//
	$you_associate = $formArray['you_associate'];
	$uid = $formArray['usid'];
	$email = $formArray['email'];
	$pass = $formArray['uspw'];
	$rememberme = $formArray['rememberme'];
	$uid_sanitized = filter_var($uid, FILTER_SANITIZE_NUMBER_INT);

	$results = array();

	//set cookie for associate customer tracking to usana.com see usana social sharing plugin


	if( $uid && $pass ){

			$cookie_value = $uid;
        	setcookie('uid', $cookie_value, time() + (86400 * 30), "/", "askthescientists.com", false);
			$mycookie = $_COOKIE['uid'];


			//timestamp login of uid for tracking data and snsert into db

		global $wpdb;
		$wpdb->insert('uid_tracking', array(
			'uid' => $uid,
			'timestamp' => current_time( 'mysql', 1 )
		));



		/* cURL used to grab token for authentication
		------------------------------------------------*/
		$headers = array(
			'Authorization: Basic ZGVjb3J0LWludGVyYWN0aXZlOi1VUk5rTitxN35wPU5VNGt9dkZXMjRnclpzY3JX',
			'Cache-Control: no-cache',
			'Content-Type: application/x-www-form-urlencoded'

		);

		//$url = 'https://esb.usana.com/core/mvc/auth/'.$uid.'?password='.$pass;
		$url = 'https://esb.usana.com/core/mvc/auth/'.$uid;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,'password='.$pass);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$json = curl_exec($ch);

		if (curl_errno($ch)) {
			$results["result"] = "failed";
			$results["error"] = "Error1: ".curl_error($ch);
		} else {
			curl_close($ch);
			if(!empty($json)){
					$decoded = json_decode($json, true);
				//print_r($decoded);
				if(!empty($decoded['token'])){ //-- Successfully grabbed token --//

					$token = $decoded['token'];
					$headers = array(
					'Authorization: Basic ZGVjb3J0LWludGVyYWN0aXZlOi1VUk5rTitxN35wPU5VNGt9dkZXMjRnclpzY3JX',
					'Cache-Control: no-cache',
					'Content-Type: application/json',
					'securetoken: '.$token.''
				);


					$tokenurl = 'https://esb.usana.com/core/mvc/customer/Plain';

					$ch2 = curl_init();
					curl_setopt($ch2, CURLOPT_URL, $tokenurl);
					curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch2, CURLOPT_TIMEOUT, 60);
					curl_setopt($ch2, CURLOPT_FRESH_CONNECT, TRUE);
					curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
					//$response = curl_exec($ch2);
					$json2 = curl_exec($ch2);

					if (curl_errno($ch2)) {
						$results["result"] = "failed";
						$results["error"] = "Error2: ".curl_error($ch2);
					} else {
						curl_close($ch2);
						if(!empty($json2)){
						$decoded2 = json_decode($json2, true);
						//echo "\n\n".$response;
						if($decoded2['id'] == $uid || $decoded2['id'] == $uid_sanitized){
							//$results["error"] = $decoded2['id']. '  '.$decoded2['mainPhone']. '  '.$decoded2['firstName']. '  ' .$decoded2['lastName']. '  '.$decoded2['emailAddress1'];

							$_SESSION['id'] = $decoded2['id'];
							$_SESSION['firstName'] = $decoded2['firstName'];
							$_SESSION['lastName'] = $decoded2['lastName'];
							$_SESSION['mainPhone'] = $decoded2['mainPhone'];
							$_SESSION['emailAddress1'] = $decoded2['emailAddress1'];


								$ask_home_url = $_SERVER['HTTP_REFERER'];
								$results["url"] = $ask_home_url;



						}else{
							$results["result"] = "failed";
							$results["error"] = "1 Response not equal to entered user id ... ".$decoded2['id'];
						}
					}}
				}else{
					$results["result"] = "failed";
					$results["error"] = "Empty Token";
				}
			}else{
				$results["result"] = "failed";
				$results["error"] = "Incorrect username or password";
			}
		}
	}else{

		//-- set url redirect to Associated Register page --//
		//$results["error"] = get_permalink(icl_object_id(851, 'page', true));

		$results["result"] = "failed";
	}

	//-- Send back a url to bypass verification --//
	if($results["result"] == "failed"){
		//$results["url"] = get_permalink(icl_object_id(65, 'page', true)).'?setCreds=1&sessionID=156a235e47s8&mid='.$uid_sanitized.'&cid='.$uid_sanitized;
		//$results["cleanid"] = $uid_sanitized;
	}

    wp_send_json($results);


}
add_action( 'wp_ajax_nopriv_authenticate_associates', 'ajax_authenticate_associates');
add_action( 'wp_ajax_authenticate_associates', 'ajax_authenticate_associates');
/*-- end ajax authentication --*/

?>
