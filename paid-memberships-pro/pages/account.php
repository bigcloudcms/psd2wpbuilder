
<?php 
	//echo pmpro_shortcode_account('');

	global $wpdb, $current_user, $pmpro_msg, $pmpro_msgt, $show_paypal_link;
	global $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth, $ExpirationYear;

	/**
	 * Filter to set if PMPro uses email or text as the type for email field inputs.
	 * 
	 * @since 1.8.4.5
	 *
	 * @param bool $use_email_type, true to use email type, false to use text type
	 */
	$pmpro_email_field_type = apply_filters('pmpro_email_field_type', true);
	
	$gateway = pmpro_getOption("gateway");

	//set to true via filter to have Stripe use the minimal billing fields
	$pmpro_stripe_lite = apply_filters("pmpro_stripe_lite", !pmpro_getOption("stripe_billingaddress")); //default is oposite of the stripe_billingaddress setting

	$level = $current_user->membership_level;
	if($level)
	{
	?>
		<p><?php printf(__("Logged in as <strong>%s</strong>.", "pmpro"), $current_user->user_login);?> <small><a href="<?php echo wp_logout_url(get_bloginfo("url") . "/membership-checkout/?level=" . $level->id);?>"><?php _e("logout", "pmpro");?></a></small></p>
		<ul>
			<li><strong><?php _e("Level", "pmpro");?>:</strong> <?php echo $level->name?></li>
		<?php if($level->billing_amount > 0) { ?>
			<li><strong><?php _e("Membership Fee", "pmpro");?>:</strong>
				<?php
					$level = $current_user->membership_level;
					if($current_user->membership_level->cycle_number > 1) {
						printf(__('%s every %d %s.', 'pmpro'), pmpro_formatPrice($level->billing_amount), $level->cycle_number, pmpro_translate_billing_period($level->cycle_period, $level->cycle_number));
					} elseif($current_user->membership_level->cycle_number == 1) {
						printf(__('%s per %s.', 'pmpro'), pmpro_formatPrice($level->billing_amount), pmpro_translate_billing_period($level->cycle_period));
					} else {
						echo pmpro_formatPrice($current_user->membership_level->billing_amount);
					}
				?>
			</li>
		<?php } ?>

		<?php if($level->billing_limit) { ?>
			<li><strong><?php _e("Duration", "pmpro");?>:</strong> <?php echo $level->billing_limit.' '.sornot($level->cycle_period,$level->billing_limit)?></li>
		<?php } ?>
		</ul>
	<?php
	}
?>

<?php if(pmpro_isLevelRecurring($level)) { ?>
	<?php if($show_paypal_link) { ?>

		<p><?php  _e('Your payment subscription is managed by PayPal. Please <a href="http://www.paypal.com">login to PayPal here</a> to update your billing information.', 'pmpro');?></p>

	<?php } else { ?>

		<form id="pmpro_form" class="pmpro_form" action="<?php echo pmpro_url("billing", "", "https")?>" method="post">

			<input type="hidden" name="level" value="<?php echo esc_attr($level->id);?>" />
			<?php if($pmpro_msg)
				{
			?>
				<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
			<?php
				}
			?>

			<?php if(empty($pmpro_stripe_lite) || $gateway != "stripe") { ?>
			<table id="pmpro_billing_address_fields" class="pmpro_checkout" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th><?php _e('Billing Address', 'pmpro');?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div>
							<label for="bfirstname"><?php _e('First Name', 'pmpro');?></label>
							<input id="bfirstname" name="bfirstname" type="text" class="input" size="20" value="<?php echo esc_attr($bfirstname);?>" />
						</div>
						<div>
							<label for="blastname"><?php _e('Last Name', 'pmpro');?></label>
							<input id="blastname" name="blastname" type="text" class="input" size="20" value="<?php echo esc_attr($blastname);?>" />
						</div>
						<div>
							<label for="baddress1"><?php _e('Address 1', 'pmpro');?></label>
							<input id="baddress1" name="baddress1" type="text" class="input" size="20" value="<?php echo esc_attr($baddress1);?>" />
						</div>
						<div>
							<label for="baddress2"><?php _e('Address 2', 'pmpro');?></label>
							<input id="baddress2" name="baddress2" type="text" class="input" size="20" value="<?php echo esc_attr($baddress2);?>" /> <small class="lite">(<?php _e('optional', 'pmpro');?>)</small>
						</div>

						<?php
							$longform_address = apply_filters("pmpro_longform_address", false);
							if($longform_address)
							{
							?>
								<div>
									<label for="bcity"><?php _e('City', 'pmpro');?>City</label>
									<input id="bcity" name="bcity" type="text" class="input" size="30" value="<?php echo esc_attr($bcity)?>" />
								</div>
								<div>
									<label for="bstate"><?php _e('State', 'pmpro');?>State</label>
									<input id="bstate" name="bstate" type="text" class="input" size="30" value="<?php echo esc_attr($bstate)?>" />
								</div>
								<div>
									<label for="bzipcode"><?php _e('Postal Code', 'pmpro');?></label>
									<input id="bzipcode" name="bzipcode" type="text" class="input" size="30" value="<?php echo esc_attr($bzipcode)?>" />
								</div>
							<?php
							}
							else
							{
							?>
								<div>
									<label for="bcity_state_zip"><?php _e('City, State Zip', 'pmpro');?></label>
									<input id="bcity" name="bcity" type="text" class="input" size="14" value="<?php echo esc_attr($bcity)?>" />,
									<?php
										$state_dropdowns = apply_filters("pmpro_state_dropdowns", false);
										if($state_dropdowns === true || $state_dropdowns == "names")
										{
											global $pmpro_states;
										?>
										<select name="bstate">
											<option value="">--</option>
											<?php
												foreach($pmpro_states as $ab => $st)
												{
											?>
												<option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $st;?></option>
											<?php } ?>
										</select>
										<?php
										}
										elseif($state_dropdowns == "abbreviations")
										{
											global $pmpro_states_abbreviations;
										?>
											<select name="bstate">
												<option value="">--</option>
												<?php
													foreach($pmpro_states_abbreviations as $ab)
													{
												?>
													<option value="<?php echo esc_attr($ab);?>" <?php if($ab == $bstate) { ?>selected="selected"<?php } ?>><?php echo $ab;?></option>
												<?php } ?>
											</select>
										<?php
										}
										else
										{
										?>
										<input id="bstate" name="bstate" type="text" class="input" size="2" value="<?php echo esc_attr($bstate)?>" />
										<?php
										}
									?>
									<input id="bzipcode" name="bzipcode" type="text" class="input" size="5" value="<?php echo esc_attr($bzipcode)?>" />
								</div>
							<?php
							}
						?>

						<?php
							$show_country = apply_filters("pmpro_international_addresses", false);
							if($show_country)
							{
						?>
						<div>
							<label for="bcountry"><?php _e('Country', 'pmpro');?></label>
							<select name="bcountry">
								<?php
									global $pmpro_countries, $pmpro_default_country;
									foreach($pmpro_countries as $abbr => $country)
									{
										if(!$bcountry)
											$bcountry = $pmpro_default_country;
									?>
									<option value="<?php echo $abbr?>" <?php if($abbr == $bcountry) { ?>selected="selected"<?php } ?>><?php echo $country?></option>
									<?php
									}
								?>
							</select>
						</div>
						<?php
							}
							else
							{
							?>
								<input type="hidden" id="bcountry" name="bcountry" value="US" />
							<?php
							}
						?>
						<div>
							<label for="bphone"><?php _e('Phone', 'pmpro');?></label>
							<input id="bphone" name="bphone" type="text" class="input" size="20" value="<?php echo esc_attr($bphone)?>" />
						</div>
						<?php if($current_user->ID) { ?>
						<?php
							if(!$bemail && $current_user->user_email)
								$bemail = $current_user->user_email;
							if(!$bconfirmemail && $current_user->user_email)
								$bconfirmemail = $current_user->user_email;
						?>
						<div>
							<label for="bemail"><?php _e('E-mail Address', 'pmpro');?></label>
							<input id="bemail" name="bemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="input" size="20" value="<?php echo esc_attr($bemail)?>" />
						</div>
						<div>
							<label for="bconfirmemail"><?php _e('Confirm E-mail', 'pmpro');?></label>
							<input id="bconfirmemail" name="bconfirmemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="input" size="20" value="<?php echo esc_attr($bconfirmemail)?>" />

						</div>
						<?php } ?>
					</td>
				</tr>
			</tbody>
			</table>
			<?php } ?>

			<?php
				$pmpro_accepted_credit_cards = pmpro_getOption("accepted_credit_cards");
				$pmpro_accepted_credit_cards = explode(",", $pmpro_accepted_credit_cards);
				$pmpro_accepted_credit_cards_string = pmpro_implodeToEnglish($pmpro_accepted_credit_cards);
			?>

			<table id="pmpro_payment_information_fields" class="pmpro_checkout top1em" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th>
						<span class="pmpro_thead-name"><?php _e('Credit Card Information', 'pmpro');?></span>
						<span class="pmpro_thead-msg"><?php printf(__('We accept %s', 'pmpro'), $pmpro_accepted_credit_cards_string);?></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr valign="top">
					<td>
						<?php
							$sslseal = pmpro_getOption("sslseal");
							if($sslseal)
							{
							?>
								<div class="pmpro_sslseal"><?php echo stripslashes($sslseal)?></div>
							<?php
							}
						?>
						<?php if(empty($pmpro_stripe_lite) || $gateway != "stripe") { ?>
						<div>
							<label for="CardType"><?php _e('Card Type', 'pmpro');?></label>
							<select id="CardType" <?php if($gateway != "stripe") { ?>name="CardType"<?php } ?>>
								<?php foreach($pmpro_accepted_credit_cards as $cc) { ?>
									<option value="<?php echo $cc?>" <?php if($CardType == $cc) { ?>selected="selected"<?php } ?>><?php echo $cc?></option>
								<?php } ?>
							</select>
						</div>
						<?php } ?>

						<div>
							<label for="AccountNumber"><?php _e('Card Number', 'pmpro');?></label>
							<input id="AccountNumber" <?php if($gateway != "stripe" && $gateway != "braintree") { ?>name="AccountNumber"<?php } ?> class="input <?php echo pmpro_getClassForField("AccountNumber");?>" type="text" size="25" value="<?php echo esc_attr($AccountNumber)?>" <?php if($gateway == "braintree") { ?>data-encrypted-name="number"<?php } ?> autocomplete="off" />
						</div>

						<div>
							<label for="ExpirationMonth"><?php _e('Expiration Date', 'pmpro');?></label>
							<select id="ExpirationMonth" <?php if($gateway != "stripe") { ?>name="ExpirationMonth"<?php } ?>>
								<option value="01" <?php if($ExpirationMonth == "01") { ?>selected="selected"<?php } ?>>01</option>
								<option value="02" <?php if($ExpirationMonth == "02") { ?>selected="selected"<?php } ?>>02</option>
								<option value="03" <?php if($ExpirationMonth == "03") { ?>selected="selected"<?php } ?>>03</option>
								<option value="04" <?php if($ExpirationMonth == "04") { ?>selected="selected"<?php } ?>>04</option>
								<option value="05" <?php if($ExpirationMonth == "05") { ?>selected="selected"<?php } ?>>05</option>
								<option value="06" <?php if($ExpirationMonth == "06") { ?>selected="selected"<?php } ?>>06</option>
								<option value="07" <?php if($ExpirationMonth == "07") { ?>selected="selected"<?php } ?>>07</option>
								<option value="08" <?php if($ExpirationMonth == "08") { ?>selected="selected"<?php } ?>>08</option>
								<option value="09" <?php if($ExpirationMonth == "09") { ?>selected="selected"<?php } ?>>09</option>
								<option value="10" <?php if($ExpirationMonth == "10") { ?>selected="selected"<?php } ?>>10</option>
								<option value="11" <?php if($ExpirationMonth == "11") { ?>selected="selected"<?php } ?>>11</option>
								<option value="12" <?php if($ExpirationMonth == "12") { ?>selected="selected"<?php } ?>>12</option>
							</select>/<select id="ExpirationYear" <?php if($gateway != "stripe") { ?>name="ExpirationYear"<?php } ?>>
								<?php
									for($i = date("Y"); $i < date("Y") + 10; $i++)
									{
								?>
									<option value="<?php echo $i?>" <?php if($ExpirationYear == $i) { ?>selected="selected"<?php } ?>><?php echo $i?></option>
								<?php
									}
								?>
							</select>
						</div>

						<?php
							$pmpro_show_cvv = apply_filters("pmpro_show_cvv", true);
							if($pmpro_show_cvv)
							{
						?>
						<div>
							<label for="CVV"><?php _ex('CVV', 'Credit card security code, CVV/CCV/CVV2', 'pmpro');?></label>
							<input class="input" id="CVV" <?php if($gateway != "stripe" && $gateway != "braintree") { ?>name="CVV"<?php } ?> type="text" size="4" value="<?php if(!empty($_REQUEST['CVV'])) { echo esc_attr($_REQUEST['CVV']); }?>" class=" <?php echo pmpro_getClassForField("CVV");?>" <?php if($gateway == "braintree") { ?>data-encrypted-name="cvv"<?php } ?> />  <small>(<a href="javascript:void(0);" onclick="javascript:window.open('<?php echo pmpro_https_filter(PMPRO_URL)?>/pages/popup-cvv.html','cvv','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=600, height=475');"><?php _ex("what's this?", 'link to CVV help', 'pmpro');?></a>)</small>
						</div>
						<?php
							}
						?>
					</td>
				</tr>
			</tbody>
			</table>

			<?php if($gateway == "braintree") { ?>
				<input type='hidden' data-encrypted-name='expiration_date' id='credit_card_exp' />
				<input type='hidden' name='AccountNumber' id='BraintreeAccountNumber' />
				<script type="text/javascript" src="https://js.braintreegateway.com/v1/braintree.js"></script>
				<script type="text/javascript">
					<!--
					//setup braintree encryption
					var braintree = Braintree.create('<?php echo pmpro_getOption("braintree_encryptionkey"); ?>');
					braintree.onSubmitEncryptForm('pmpro_form');

					//pass expiration dates in original format
					function pmpro_updateBraintreeCardExp()
					{
						jQuery('#credit_card_exp').val(jQuery('#ExpirationMonth').val() + "/" + jQuery('#ExpirationYear').val());
					}
					jQuery('#ExpirationMonth, #ExpirationYear').change(function() {
						pmpro_updateBraintreeCardExp();
					});
					pmpro_updateBraintreeCardExp();

					//pass last 4 of credit card
					function pmpro_updateBraintreeAccountNumber()
					{
						jQuery('#BraintreeAccountNumber').val('XXXXXXXXXXXXX' + jQuery('#AccountNumber').val().substr(jQuery('#AccountNumber').val().length - 4));
					}
					jQuery('#AccountNumber').change(function() {
						pmpro_updateBraintreeAccountNumber();
					});
					pmpro_updateBraintreeAccountNumber();
					-->
				</script>
			<?php } ?>

			<div align="center">
				<input type="hidden" name="update-billing" value="1" />
				<input type="submit" class="pmpro_btn pmpro_btn-submit" value="<?php _e('Update', 'pmpro');?>" />
				<input type="button" name="cancel" class="pmpro_btn pmpro_btn-cancel" value="<?php _e('Cancel', 'pmpro');?>" onclick="location.href='<?php echo pmpro_url("account")?>';" />
			</div>

		</form>
		<script>
			<!--
			// Find ALL <form> tags on your page
			jQuery('form').submit(function(){
				// On submit disable its submit button
				jQuery('input[type=submit]', this).attr('disabled', 'disabled');
				jQuery('input[type=image]', this).attr('disabled', 'disabled');
			});
			-->
		</script>
	<?php } ?>
<?php } else { ?>
	<p><?php _e("This subscription is not recurring. So you don't need to update your billing information.", "pmpro");?></p>
<?php } ?>

<?php
	global $wpdb, $pmpro_msg, $pmpro_msgt, $pmpro_levels, $current_user, $levels, $show_paypal_link, $pmpro_currency_symbol;
	global $free_membership_level, $paid_membership_level, $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth, $ExpirationYear;
	
	//if a member is logged in, show them some info here (1. past invoices. 2. billing information with button to update.)
	if($current_user->membership_level->ID)
	{
	?>	<div class="current-plan">	
		<h2>Current Plan</h2> <strong><?php //echo $current_user->membership_level->name?></strong>
		<ul>
		<?php if($current_user->membership_level->billing_amount > 0) { ?>
			<li>You are on the <?php echo $current_user->membership_level->name?> <strong>@ <?php echo $pmpro_currency_symbol?><?php echo $current_user->membership_level->billing_amount?>/Month</strong></li>
            <li>Your next payment is <?php echo $pmpro_currency_symbol?><?php echo $current_user->membership_level->billing_amount?> on <strong><?php $nextpayment = pmpro_next_payment(); echo date("F j, Y", $nextpayment)?></strong></li>
            
        <div class="btns"><a class="green" href="/upgrade/">Switch Plan</a> <a class="grey" href="/membership-account/membership-cancel/?level=<?php echo $current_user->membership_level->cycle_number?>">Cancel Plan</a></div>
            </ul>
            </div>
<!--
			<?php //if($current_user->membership_level->cycle_number > 1) { ?>
				per <?php //echo $current_user->membership_level->cycle_number?> <?php //echo sornot($current_user->membership_level->cycle_period,$current_user->membership_level->cycle_number)?>
			<?php //} elseif($current_user->membership_level->cycle_number == 1) { ?>
				per <?php //echo $current_user->membership_level->cycle_period?>
			<?php //} ?>
-->
		<?php } ?>						

<!--
		<?php //if($current_user->membership_level->billing_limit) { ?>
			<li><strong>Duration:</strong> <?php //echo $current_user->membership_level->billing_limit.' '.sornot($current_user->membership_level->cycle_period,$current_user->membership_level->billing_limit)?></li>
		<?php //} ?>
		
		<?php //if($current_user->membership_level->enddate) { ?>
			<li><strong>Membership Expires:</strong> <?php //echo date(get_option('date_format'), $current_user->membership_level->enddate)?></li>
		<?php //} ?>
		
		<?php //if($current_user->membership_level->trial_limit) { ?>
			Your first <?php //echo $current_user->membership_level->trial_limit?> <?php //echo sornot("payment",$current_user->membership_level->trial_limit)?> will cost $<?php //echo $current_user->membership_level->trial_amount?>.
		<?php //} ?>   
-->

		<?php
			//the nextpayment code is not tight yet
			/*
			$nextpayment = pmpro_next_payment();
			if($nextpayment)
			{
			?>
				<li><strong>Next Invoice:</strong> <?php echo date("F j, Y", $nextpayment)?></li>
			<?php
			}
			*/
		?>
		
		
		<div class="pmpro_left">
			<div class="pmpro_box myaccount">
				<?php get_currentuserinfo(); ?> 
				<h3><a class="pmpro_a-right" href="<?php echo admin_url('profile.php')?>">Edit</a>My Account</h3>
				<p>
				<?php if($current_user->user_firstname) { ?>
					<?php echo $current_user->user_firstname?> <?php echo $current_user->user_lastname?><br />
				<?php } ?>
				<small>
					<strong>Username:</strong> <?php echo $current_user->user_login?><br />
					<strong>Email:</strong> <?php echo $current_user->user_email?><br />
					<strong>Password:</strong> ****** <small><a href="<?php echo admin_url('profile.php')?>">change</a></small>				
				</small>
			</div>
			<?php
				//last invoice for current info
				//$ssorder = $wpdb->get_row("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' AND membership_id = '" . $current_user->membership_level->ID . "' AND status = 'success' ORDER BY timestamp DESC LIMIT 1");				
				$ssorder = new MemberOrder();
				$ssorder->getLastMemberOrder();
				$invoices = $wpdb->get_results("SELECT *, UNIX_TIMESTAMP(timestamp) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' ORDER BY timestamp DESC");				
				if(!empty($ssorder->id) && $ssorder->gateway != "check" && $ssorder->gateway != "paypalexpress")
				{
					//default values from DB (should be last order or last update)
					$bfirstname = get_user_meta($current_user->ID, "pmpro_bfirstname", true);
					$blastname = get_user_meta($current_user->ID, "pmpro_blastname", true);
					$baddress1 = get_user_meta($current_user->ID, "pmpro_baddress1", true);
					$baddress2 = get_user_meta($current_user->ID, "pmpro_baddress2", true);
					$bcity = get_user_meta($current_user->ID, "pmpro_bcity", true);
					$bstate = get_user_meta($current_user->ID, "pmpro_bstate", true);
					$bzipcode = get_user_meta($current_user->ID, "pmpro_bzipcode", true);
					$bcountry = get_user_meta($current_user->ID, "pmpro_bcountry", true);
					$bphone = get_user_meta($current_user->ID, "pmpro_bphone", true);
					$bemail = get_user_meta($current_user->ID, "pmpro_bemail", true);
					$bconfirmemail = get_user_meta($current_user->ID, "pmpro_bconfirmemail", true);
					$CardType = get_user_meta($current_user->ID, "pmpro_CardType", true);
					$AccountNumber = hideCardNumber(get_user_meta($current_user->ID, "pmpro_AccountNumber", true), false);
					$ExpirationMonth = get_user_meta($current_user->ID, "pmpro_ExpirationMonth", true);
					$ExpirationYear = get_user_meta($current_user->ID, "pmpro_ExpirationYear", true);	
				?>
            <div class="credit-card">	
				<div class="pmpro_box">				
					<h2><?php if((isset($ssorder->status) && $ssorder->status == "success") && (isset($ssorder->gateway) && in_array($ssorder->gateway, array("authorizenet", "paypal", "stripe")))) { ?>
<!--                        <a class="pmpro_a-right" href="<?php //echo pmpro_url("billing", "")?>">Edit</a>-->
                        <?php } ?>Credit Card</h2>
					<?php if(!empty($baddress1)) { ?>
					<p>
						<strong>Billing Address</strong><br />
						<?php echo $bfirstname . " " . $blastname?>
						<br />		
						<?php echo $baddress1?><br />
						<?php if($baddress2) echo $baddress2 . "<br />";?>
						<?php if($bcity && $bstate) { ?>
							<?php echo $bcity?>, <?php echo $bstate?> <?php echo $bzipcode?> <?php echo $bcountry?>
						<?php } ?>                         
						<br />
						<?php echo formatPhone($bphone)?>
					</p>
					<?php } ?>
                    <ul>
					<li>
<!--						<strong>Payment Method</strong><br />-->
						Your credit card on file is <strong><?php echo $CardType?>: xxxx-xxxx-xxxx-<?php echo last4($AccountNumber)?> (<?php echo $ExpirationMonth?>/<?php echo $ExpirationYear?>)</strong>
					</li>
                        <div class="btns"><a class="green" href="/edit-billing/">Edit Billing Details</a></div>
                    </ul>
				</div>	
            </div>
			<?php
			}
			?>
			<div class="pmpro_box memblinks">
				<h3>Member Links</h3>
				<ul>
					<?php 
						do_action("pmpro_member_links_top");
					?>
					<?php if((isset($ssorder->status) && $ssorder->status == "success") && (isset($ssorder->gateway) && in_array($ssorder->gateway, array("authorizenet", "paypal", "stripe")))) { ?>
						<li><a href="<?php echo pmpro_url("billing", "", "https")?>">Update Billing Information</a></li>
					<?php } ?>
					<?php if($current_user->membership_level->id == $free_membership_level) { ?>
						<li><a href="<?php echo pmpro_url("checkout")?>?level=6">Activate Your Membership Account &raquo;</a></li>             
					<?php } ?>
					<li><a href="<?php echo pmpro_url("cancel")?>">Cancel Membership</a></li>
					<?php 
						do_action("pmpro_member_links_bottom");
					?>
				</ul>
			</div>
		</div> <!-- end pmpro_left -->
		
<div class="history">	
		<div class="pmpro_right">
			<?php if(!empty($invoices)) { ?>
			<div class="pmpro_box">
				<h2>Payment History</h2>
				<ul>
					<?php 
						$count = 0;
						foreach($invoices as $invoice) 
						{ 
					?>
					<li <?php if($count++ > 10) { ?>class="pmpro_hidden pmpro_invoice"<?php } ?>>
<!--                        <a href="<?php //echo pmpro_url("invoice", "?invoice=" . $invoice->code)?>">-->
                           <p><?php echo date("F j, Y", $invoice->timestamp)?> - <?php echo $pmpro_currency_symbol?><?php echo $invoice->total?></p>
<!--                        </a>-->
                    </li>
					<?php } ?>
					<?php if($count > 10) { ?>
						<li class="pmpro_more pmpro_invoice"><a href="javascript: jQuery('.pmpro_more.pmpro_invoice').hide(); jQuery('.pmpro_hidden.pmpro_invoice').show(); void(0);">show <?php echo (count($invoices) - 10)?> more</a></li>
					<?php 
						} 
					?>
				</ul>
			</div>
			<?php } ?>
		</div>
</div><!-- end pmpro_right -->
		
<?php
	}