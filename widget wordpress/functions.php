<?php



//============================== sidebarinfopost widget ================================================

class sidebarinfopost_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Выбираем ID для своего виджета
'sidebarinfopost_widget', 

// Название виджета, показано в консоли
__('SidebarInfoPost Widget', 'sidebarinfopost_widget_domain'), 

// Описание виджета
array( 'description' => __( 'Виджет выводит информацию данного поста', 'sidebarinfopost_widget_domain' ), ) 
);
}

// Создаем код для виджета - 
// сначала небольшая идентификация
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// до и после идентификации переменных темой
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// Именно здесь записываем весь код и вывод данных

echo '<div id="minibarpost">';


    echo '<div id="minibarpostimg">';
    the_post_thumbnail();
echo '</div>';

echo '<div class="minibarposttitle">';
echo(single_post_title());
if (null!==(pods_field_display ( $pod, $id = false, $name = 'sidbarbittomname', $single = false ))){
echo '<div class="btmkey"> <a href="'.pods_field_display ( $pod, $id = false, $name = 'sidbarbittomname', $single = false ).'" target="_blank" class="btmpost2">Купить</a></div>';
}

if (null!==(pods_field_display ( $pod, $id = false, $name = 'textafterbottom', $single = false ))){
    echo '<div class="textafterbottom">';
    echo (pods_field_display ( $pod, $id = false, $name = 'textafterbottom', $single = false ));
    echo '</div>';
}

echo '</div>';
if(function_exists('the_ratings')) { the_ratings(); };
echo'<div class="social"> <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus"></div> </div>'; //выводит рейтинг
if (null!==(pods_field_display ( $pod, $id = false, $name = 'adres', $single = false )))
{
    echo '<div class="minibarpostadr1">Адрес:</div>';
    echo '<div id="minibarpostadr">';
    echo (pods_field_display ( $pod, $id = false, $name = 'adres', $single = false ));
    echo '</div>';
}
if (null!==(pods_field_display ( $pod, $id = false, $name = 'phone', $single = false )))
{
    echo '<div class="minibarpostphone1">Телефон:</div>';
    echo '<div id="minibarpostphone">';
    echo (pods_field_display ( $pod, $id = false, $name = 'phone', $single = false ));
    echo '</div>';
}
if (null!==(pods_field_display ( $pod, $id = false, $name = 'site', $single = false )))
{
    echo '<div class="minibarpostadr1">Сайт:</div>';
    echo '<div id="minibarpostsite">';
    echo (pods_field_display ( $pod, $id = false, $name = 'site', $single = false ));
    echo '</div>';
}
echo '</div>';


}


// Закрываем код виджета
public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
    }
    else {
        $title = __( '', 'sidebarinfopost_widget_domain' );
    }
    // Для административной консоли
    ?>
<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}

// Обновление виджета
public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
}
} // Закрываем класс btru_widget

// Регистрируем и запускаем виджет
function sidebarinfopost_load_widget() {
    register_widget( 'sidebarinfopost_widget' );
}
add_action( 'widgets_init', 'sidebarinfopost_load_widget' );

//================================= end sidebarinfopost widget ==================================================


//================================================ mostpoplarposts widget =======================================

class mostpoplarposts_widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
            // Выбираем ID для своего виджета
            'mostpoplarposts_widget', 
            
            // Название виджета, показано в консоли
            __('MostPoplarPosts Widget', 'mostpoplarposts_widget_domain'), 
            
    // Описание виджета
    array( 'description' => __( 'Виджет выводит информацию данного поста', 'mostpoplarposts_widget_domain' ), ) 
);
    }
    
    // Создаем код для виджета - 
    // сначала небольшая идентификация
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
    // до и после идентификации переменных темой
    echo $args['before_widget'];
    if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
    
    // Именно здесь записываем весь код и вывод данных
    
    /* цикл должен всять данные всех постов, перебрать каждый пост откладывая его в массив, сравнивая с новым элементом. вывести масив*/
    
    global $post;
    $args = array( 'posts_per_page' => 3, 'order'=> 'DESC', 'orderby' => 'meta_value_num' );
    $postslist = get_posts( $args );
    foreach ( $postslist as $post ){
        setup_postdata( $post );
        
        
        echo '<div id="minibarpost">';
        
        
        echo '<div class="imageratepost">';
        the_post_thumbnail();
        echo '</div>';
        
        echo '<div class="textpost">';
        echo '<a href="';
        echo (get_permalink( $post->ID ));
        echo '">';
        the_title();
        echo '</a>';
        if(function_exists('the_ratings')) { the_ratings(); }; 
    echo '</div>';
    echo '</div>';
    
}
wp_reset_postdata();
    


}


// Закрываем код виджета
public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
    }
    else {
        $title = __( 'Заголовок', 'mostpoplarposts_widget_domain' );
    }
    // Для административной консоли
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php 
    }
    
    // Обновление виджета
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Закрываем класс btru_widget

// Регистрируем и запускаем виджет
function mostpoplarposts_load_widget() {
    register_widget( 'mostpoplarposts_widget' );
}
add_action( 'widgets_init', 'mostpoplarposts_load_widget' );
    

//==================================================== end mostpoplarposts widget ================================

//==================================================== newposts widget ===========================================


class newposts_widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
            // Выбираем ID для своего виджета
            'newposts_widget', 
            
            // Название виджета, показано в консоли
            __('NewPosts Widget', 'newposts_widget_domain'), 
    
    // Описание виджета
    array( 'description' => __( 'Виджет показывает новые посты', 'newposts_widget_domain' ), ) 
);
}

// Создаем код для виджета - 
// сначала небольшая идентификация
public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    // до и после идентификации переменных темой
    echo $args['before_widget'];
    if ( ! empty( $title ) )
    echo $args['before_title'] . $title . $args['after_title'];
    
    // Именно здесь записываем весь код и вывод данных
    
    
    /* цикл должен всять данные всех постов, перебрать каждый пост откладывая его в массив, сравнивая с новым элементом. вывести масив*/
    
    global $post;
    $args = array(  'posts_per_page' => 4, 'order'=> 'DESC', 'orderby' => 'date' );
    $postslist = get_posts( $args );
    foreach ( $postslist as $post ){
        setup_postdata( $post );
        
        
        
    
        echo '<div id="minibarpost">';
        
        
        echo '<div class="imageratepost">';
        the_post_thumbnail();
        echo '</div>';
    
        echo '<div class="textpost">';
        echo '<a href="';
        echo (get_permalink( $post->ID ));
        echo '">';
        the_title();
        echo '</a>';
        if(function_exists('the_ratings')) { the_ratings(); }; 
        echo '</div>';
        echo '</div>';
        
    }
    wp_reset_postdata();
    

    
}


// Закрываем код виджета
public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
    }
    else {
        $title = __( 'Заголовок', 'newposts_widget_domain' );
    }
    // Для административной консоли
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php 
    }
    
    // Обновление виджета
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Закрываем класс btru_widget

// Регистрируем и запускаем виджет
function newposts_load_widget() {
    register_widget( 'newposts_widget' );
    }
    add_action( 'widgets_init', 'newposts_load_widget' );
    
    
    
    
    
    add_filter('wp_nav_menu_items','add_search_box', 10, 2);
    function add_search_box($items, $args) {
        ob_start();
        get_search_form();
        $searchform = ob_get_contents();
        ob_end_clean();
        $items .= '<li class = "my_search">' . $searchform . '</li>';
        return $items;
    }
    
    //===================
    
    
    function wpb_widgets_init() {
        register_sidebar( array(
            'name'          => 'Произвольная область для виджетов в хидере',
            'id'            => 'custom-header-widget',
            'before_widget' => '<div class="chw-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="chw-title">',
            'after_title'   => '</h2>',) );
        }
        add_action( 'widgets_init', 'wpb_widgets_init' );
        
        //===================================== comments
        
        add_action( 'comment_form_logged_in_after', 'extend_comment_custom_fields' );
        add_action( 'comment_form_after_fields', 'extend_comment_custom_fields' );
        function extend_comment_custom_fields() {
            
    echo '<p class="comment-form-plus">'.
    '<label for="plus">' . __( 'Достоинства' ) . '</label>'.
    '<input id="plus" name="plus" type="text" size="30"/></p>';
    
	echo '<p class="comment-form-minus">'.
    '<label for="minus">' . __( 'Недостатки' ) . '</label>'.
    '<input id="minus" name="title" type="text" size="30"/></p>';
    
    
	
    
	echo'</span></p>';
}

add_action( 'comment_post', 'save_extend_comment_meta_data' );
function save_extend_comment_meta_data( $comment_id ){
    
    if( !empty( $_POST['plus'] ) ){
        $plus = sanitize_text_field($_POST['plus']);
		add_comment_meta( $comment_id, 'plus', $plus );
	}
    
	if( !empty( $_POST['minus'] ) ){
        $minus = sanitize_text_field($_POST['minus']);
		add_comment_meta( $comment_id, 'minus', $minus );
	}
    
	
}

function custom_rating_image_extension() {
    return 'png';
}
add_filter( 'wp_postratings_image_extension', 'custom_rating_image_extension' );

function custom_content_after_post($content){
    if (is_single()) {  
        if( null!==(pods_field_display ( $pod, $id = false, $name = 'sidbarbittomname', $single = false )))
        $content .= '<div class="btnpost">
        <a target="_blank" href="'.pods_field_display ( $pod, $id = false, $name = 'sidbarbittomname', $single = false ).'" class="btmpost"> '.pods_field_display ( $pod, $id = false, $name = 'textbottomsidebar', $single = false ).'</div>';
    }
        return $content;
    }
     
    add_filter( "the_content", "custom_content_after_post" );


    //добавляем нужные кнопки в меню
add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) {
$loginoutlink = wp_loginout('index.php', false);
if(!is_user_logged_in()) 
    // $items .= '<li class="regbtm"><a href="/wp-login.php?action=register">Регистрация</a></li>';
    
    $items .='<li class="regbtm"><a href="'.get_page_link( $post = '286', $leavename = false, $sample = false ).'">Регистрация</a></li>';
else 
$items .= '<li class="kabinetbtm"><a href="/wp-admin/">Личный Кабинет</a></li>';
$items .= '<li class="loginbtm">'. $loginoutlink .'</li>';
 return $items;
}



    