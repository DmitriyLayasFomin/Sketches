<?php


/**
 * Plugin Name: Bonus Field
 * Plugin URI: 
 * Description: Add field bonus iiko
 * Version: 1.0
 * Author: Dmitriy Fomin
 * Author URI: http://itpolice.ru
 * License: GPL
*/


include 'Auth.php';
function kia_filter_checkout_fields($fields){
    $fields['extra_fields'] = array(
            'bonus' => array(
                'type' => 'number',
                'required'      => false,
                'label' => __( 'Оплата бонусами' ),
                'class' => array('input-text'),    // add class name
        'custom_attributes' => array('style'=>"max-width:263.2px;")
                )
            );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'kia_filter_checkout_fields' );

// display the extra field on the checkout form
function kia_extra_checkout_fields(){ 
    
    if(empty($_POST['bonus'])){
    global $post;
    $enum_items = array();
    $cart = WC()->cart->get_totals();
    $cart2 = WC()->cart->get_cart();
    $iiko = new Auth();
    $nomenclature = $iiko->get_nomenclature();
    $category = $nomenclature['productCategories'];
    $category_name;
    if (isset($_POST['phone'])){

    $i = 0;
    foreach ($cart2 as $key)
    {
        $product = new  WC_Product($key['product_id']);
        foreach($nomenclature["products"] as $k)
        {
            if($k['id'] == $product->get_sku()){
            $productCategoryId = $k['productCategoryId'];
            foreach($category as $el)
            {
               
                if($el['id'] == $productCategoryId)
                $category_name = $el['name'];
            }
            }
        }

        $enum_items[$i]=['amount' => $key['quantity'],
        'id'=>$product->get_sku(),
        'code' => get_post_meta( $product->get_id(), 'iiko_code', true ),
        'sum'=>(string)$key["line_total"],
        'category' => $category_name
    ];
                    
        $i++;
        
    }
    
    $customer = [
        // 'id' => '0a7cab77-056b-467e-8141-cc03f23a36b6',
        'name' => trim($_POST['name']),
        'phone' => trim($_POST['phone']),
    ];
    $orderArr = array(
        'phone' => trim($_POST['phone']),
        'items' => $enum_items,
        //'fullSum' => '6897',
        'date' => date('Y-m-d H:i:s P', time()+18000),
        "isSelfService" => false
        
    );
    $arr = array(
        'customer' => $customer,
        'order' => $orderArr,
        "availablePaymentMarketingCampaignIds" => [
            "d2cef933-3661-11e8-80e0-d8d38565926f"
            ]
    );
   
   

    

    
    $bonus = $iiko->get_bonuses(trim($_POST['phone']));
    
    
    if($bonus>(float)1.0){
    $dis_sum = 0;
    $result = $iiko->get_discount($arr);
    // var_dump($result['availablePayments'][0]['walletInfos'][0]["maxSum"]);
    // die();
    $dis_sum = (float)$result['availablePayments'][0]['walletInfos'][0]["maxSum"];
        

    $checkout = WC()->checkout(); 
    // var_dump($cart['total']);
    // die();
    $total = (float)$cart["cart_contents_total"];
    $total = $total - $dis_sum;
if($dis_sum > 0){
   
   echo '<div class="extra-fields"> ';
    // because of this foreach, everything added to the array in the previous function will display automagically
    foreach ( $checkout->checkout_fields['extra_fields'] as $key => $field ){

             echo (woocommerce_form_field( $key, $field, $checkout->get_value( $key ) )); 
    }
   
?>
<div id="errm" style="display: none; color: red;">Вы ввели больше бонусов, чем доступно</div>
<b id="bonusnumb" lt="<?=$bonus?>">Доступно баллов: <b style="color:#32CD32 ;"><?=$bonus?></b><br>
<b id="bonusmax" lt="<?=$dis_sum?>">Максимальная скидка: <b style="color:#32CD32 ;"><?=$dis_sum?></b>
</b></b>
<?php
}

    } // end of bonus
wp_die();
    }
}
}
add_action( 'woocommerce_checkout_shipping' ,'kia_extra_checkout_fields' );


// save the extra field when checkout is processed
function kia_save_extra_checkout_fields( $order, $data ){

    // don't forget appropriate sanitization if you are using a different field type
    if( isset( $data['bonus'] ) ) {
        $order->update_meta_data( '_bonus', sanitize_text_field( $data['bonus'] ) );
    }
}
add_action( 'woocommerce_checkout_create_order', 'kia_save_extra_checkout_fields', 10, 2 );

	
// display the extra data on order recieved page and my-account order review
function kia_display_order_data( $order_id ){  
    $order = wc_get_order( $order_id ); ?>
    <h2><?php _e( '' ); ?></h2>
    <table class="shop_table shop_table_responsive additional_info">
        <tbody>
            <tr>
                <th><?php _e( 'Оплата бонусами:' ); ?></th>
                <td><?php echo $order->get_meta( '_bonus' ); ?></td>
            </tr>
        </tbody>
    </table>
<?php }
add_action( 'woocommerce_thankyou', 'kia_display_order_data', 20 );
add_action( 'woocommerce_view_order', 'kia_display_order_data', 20 );


// display the extra data in the order admin panel
function kia_display_order_data_in_admin( $order ){  ?>
    <div class="order_data_column">
        <?php 
            echo '<p><strong>' . __( 'Оплата бонусами' ) . ':</strong>' . $order->get_meta( '_bonus' ) . '</p>';
            ?>
    </div>
<?php }
add_action( 'woocommerce_admin_order_data_after_billing_address', 'kia_display_order_data_in_admin' );

 

  // Register my custom function for AJAX processing
add_action('wp_ajax_kia_extra_checkout_fields', 'kia_extra_checkout_fields'); // Logged-in users
add_action('wp_ajax_nopriv_kia_extra_checkout_fields', 'kia_extra_checkout_fields'); // Guest users


// Inline JavaScript
add_action('woocommerce_after_checkout_form', 'my_inline_js');
function my_inline_js() { ?>

    <script>
        // Set the "ajax_url" variable available globally
        ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";

        // Make your AJAX request on document ready:
        (function ($) {
            $(document).ready(function() {
    var my_data = {
        action: 'kia_extra_checkout_fields', // This is required so WordPress knows which func to use
        phone: "",
        name: "",
        bonus: ''
    };

    // $('form').on('submit', function() {
    //     if($('#bonus').attr('lt') > $('#bonusmax'))
    //     {alert('ti ohuerl');}
    // });
    var phone;
    phone = $('#billing_phone').val();
    name = $('#billing_first_name').val();
    phone = phone.replace(/[^a-z0-9\s]/gi, '').replace(/\s/g, '');
    name = name.replace(/[^a-z0-9\s]/gi, '').replace(/\s/g, '');
    if (phone.length == 11 && name.length > 3 && !$('#payment_method_rbspayment').is(':checked')) {

        my_data.phone = phone;
        my_data.name = name;
        $('.col-inner').prop('readonly', true);
        $('input[type=submit]', this).attr('disabled', 'disabled');
        $.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
            if (response) {
                $('.extra-fields').detach();
                $('#billing_address_2_field').after(response);
                $('.col-inner').prop('readonly', false);
                $('input[type=submit]', this).removeAttr('disabled');
            } else if (response == null) {
                $('.extra-fields').detach();
                $('input[type=submit]', this).removeAttr('disabled');
            }
        
        });
        $('input[type=submit]', this).removeAttr('disabled');
    }

    // $('form').on('change', function() {
        $(document).on('change', 'form', function(){
            $('#errm').hide();
        if( (parseFloat($('#bonus').val())) > parseFloat($('#bonusmax').attr('lt')) )
        { 
            // mes_er = '<div id="errmes222" style="color: red;">Вы ввели больше бонусов, чем доступно</div>';
            $('input[type=submit]').attr('disabled', 'disabled');
            // $('#bonusmax').after(mes_er);
            $('#errm').css('display', 'block');
            

        }else{$('input[type=submit]', this).removeAttr('disabled'); $('#errm').hide();}

        if ($('#payment_method_rbspayment').is(':checked') && $('#payment_method_rbspayment').val() == 'rbspayment') {
            $('.extra-fields').detach();
            $('input[type=submit]', this).removeAttr('disabled');
        }

        //alert(parseFloat($('#bonus').val()));
        my_data.phone = $('#billing_phone').val().replace(/[^a-z0-9\s]/gi, '').replace(/\s/g, '');
        my_data.name = $('#billing_first_name').val().replace(/[^a-z0-9\s]/gi, '').replace(/\s/g, '');
        if ($('#bonus').val() == null && !$('#payment_method_rbspayment').is(':checked')) {
           
            $('input[type=submit]', this).attr('disabled', 'disabled');
            $.post(ajax_url, my_data, function(response) { // This will make an AJAX request upon page load
                if (response) {
                    $('.extra-fields').detach();
                    $('#billing_address_2_field').after(response);
                    $('input[type=submit]', this).removeAttr('disabled');
                   
                } else {
                    $('.extra-fields').detach();
                    $('input[type=submit]', this).removeAttr('disabled');
                }
            });
            $('input[type=submit]', this).removeAttr('disabled');
            
        }
    });



});
        })(jQuery);
    </script>

    <?php
}


//prop field

function pr_create_custom_field() {
    $args = array(
    'id' => 'iiko_code',
    'label' => __( 'Код товара айко' ),
    'class' => 'woocommerce_process_product_meta',
    'desc_tip' => true,
    'description' => __( '' ),
    );
    woocommerce_wp_text_input( $args );
   }
   add_action( 'woocommerce_product_options_inventory_product_data', 'pr_create_custom_field' );

   function pr_save_custom_field( $post_id ) {
    $product = wc_get_product( $post_id );
    $title = isset( $_POST['iiko_code'] ) ? $_POST['iiko_code'] : '';
    $product->update_meta_data( 'iiko_code', sanitize_text_field( $title ) );
    $product->save();
   }
   add_action( 'woocommerce_process_product_meta', 'pr_save_custom_field' );
   function pr_add_custom_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) {
    if( ! empty( $_POST['iiko_code'] ) ) {
    // Add the item data
    $cart_item_data['iiko_code'] = $_POST['iiko_code'];
    }
    return $cart_item_data;
   }
   add_filter( 'woocommerce_add_cart_item_data', 'pr_add_custom_field_item_data', 10, 4 );