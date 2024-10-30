<?php
/*
Plugin Name: Content Insights
Description: This plugin adds smartocto insights (former Content Insights) tracking code to your WordPress.
Version: 1.2.2
Author: smartocto insights
Author URI: https://contentinsights.com
Text Domain: contentinsights
Domain Path: /languages
*/

$ci_plugin_version = '1.2.2';

function contentinsights_options_page () {
$contentinsights_id_txt = '';
?>
<div class="wrap">

    <h2><?php echo __('Content Insights Configuration', 'contentinsights'); ?></h2>

    <form method="post" action="options.php" name="contentinsights_form">
        <?php settings_fields('contentinsights_options'); ?>
        <?php $options = get_option('contentinsights'); ?>
        
        <h3><?php echo __('Required Settings', 'contentinsights'); ?></h3>
        
        <?php if (! $options['site_id']) {
            $contentinsights_id_txt = __('Manually enter your ', 'contentinsights'); ?>
            
            <p><?php echo __('To set up smartocto insights, select one of the options below.', 'contentinsights'); ?></p>
            <p>
            <span id="contentinsights_setup_wait" style="display:none;"><?php echo __('Please wait ...', 'contentinsights'); ?></span> 
            <span id="contentinsights_setup_fail" style="display:none;"><?php echo __('Sorry, we were unable to get you Site ID. Please try again with', 'contentinsights'); ?></span> 
            <button type="button" class="button button-primary" id="contentinsights_setup"><?php echo __('Automatic Setup', 'contentinsights'); ?></button>
            </p>


            <p><?php echo __('OR', 'contentinsights'); ?></p>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#contentinsights_setup').click(function(e) {
                    e.preventDefault();
                    
                    $('#contentinsights_setup_fail, #contentinsights_setup').hide();
                    $('#contentinsights_setup_wait').show();
                    
                    var siteurl = '<?php echo esc_js( get_site_url() ); ?>';
                    var apiurl = 'https://api.contentinsights.com/info/?domain=' + siteurl; 

                    $.ajax({
                        url: apiurl,
                        dataType: 'jsonp',
                        cache: false,
                        timeout: 5000,
                        success: function(res) {
                            $('#contentinsights_setup_wait').hide();
                            if (! res.data.domain_id) {
                                $('#contentinsights_setup_fail, #contentinsights_setup').show();
                            } else {
                                $("input[type=text][name='contentinsights[site_id]']").val(res.data.domain_id);
                                $("form[name='contentinsights_form'").submit();
                            }
                        },
                        error: function() {
                            $('#contentinsights_setup_wait').hide();
                            $('#contentinsights_setup_fail, #contentinsights_setup').show();
                        }
                    });
                });
            });
            </script>
            
        <?php } ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo $contentinsights_id_txt; ?>Site ID:</th>
            
                <td>
                    <input type="text" name="contentinsights[site_id]" value="<?php echo esc_attr($options['site_id']); ?>" />
                    <p class="description">
                        <?php echo __('Your Site ID from ', 'contentinsights'); ?> <a href="https://app.contentinsights.com" target="_blank">smartocto insights</a> <?php echo __('application.', 'contentinsights'); ?>
                    </p>
                </td>
            </tr>
        </table>
        
        <h3><?php echo __('Optional Settings (advanced)', 'contentinsights'); ?></h3>
    
        <table class="form-table">

            <tr>
                <th scope="row"><?php echo __('Ignore Admin users', 'contentinsights'); ?>:</th>

                <td>
                    <input type="checkbox" name="contentinsights[ignore_admin]" <?php echo (isset($options['ignore_admin']) && $options['ignore_admin'] == 'on') ? 'checked="checked"' : '';?> />
                    <p class="description">
                        <?php echo __('If you want to ignore visits performed by Admin users while logged in, enable this check box.', 'contentinsights'); ?><br/>
                        <?php echo __('If enabled, you will no longer see Content Insights tracking code on your website while browsing as long as you\'re logged in.', 'contentinsights'); ?>
                    </p>
                </td>
            </tr> 

            <tr valign="top">
                <th scope="row"><?php echo __('Include Pages', 'contentinsights'); ?>:</th>
                
                <td>
                    <input type="checkbox" name="contentinsights[include_pages]" <?php echo (isset($options['include_pages']) && $options['include_pages'] == 'on') ? 'checked="checked"' : '';?> />
                    <p class="description">
                        <?php echo __('Unlike Posts, Pages are disabled from tracking by default. If you also want to track Pages, enable this check box.', 'contentinsights'); ?><br/>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Maincontent:</th>
                
                <td>
                    <input type="text" name="contentinsights[maincontent]" value="<?php echo esc_attr($options['maincontent']); ?>" placeholder=".ciTrackContent" />
                    <p class="description">
                        <?php echo __('HTML element or comma separated list of elements on the page where the main article content is located.', 'contentinsights'); ?><br/>
                        <?php echo __('Default selector is <strong>.ciTrackContent</strong> but you can set a different value to better match article content if needed.', 'contentinsights'); ?><br/>
                        <?php echo __('For example, <strong>article</strong> for &lt;article&gt; element or <strong>#article-body</strong> for &lt;div id="article-body"&gt; element.', 'contentinsights'); ?>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">Excludecontent:</th>
                
                <td>
                    <input type="text" name="contentinsights[excludecontent]" value="<?php echo esc_attr($options['excludecontent']); ?>" />
                    <p class="description">
                        <?php echo __('HTML element or comma separated list of elements on the page which should be excluded from the actual content.', 'contentinsights'); ?><br/>
                        <?php echo __('This is blank by default, but you can exclude some widgets, sliders, plugins etc. wrapped within the content, if needed.', 'contentinsights'); ?><br/>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __('Settings override', 'contentinsights'); ?>:</th>
                
                <td>
                    <p class="description">
                        <?php echo __('You can manually override default tracker settings (for topics, authors, etc.) in <strong>function.php</strong> file by applying filters.', 'contentinsights'); ?><br/>
                        <?php echo __('Visit <a target="_blank" href="https://docs.contentinsights.com/help/tracking-code-for-wordpress-site">WordPress Plugin Instructions</a> for more info.', 'contentinsights'); ?>
                    </p>
                </td>
            </tr>

        </table>

        <p class="description">
            <?php echo __('If you\'re having any difficulties configuring the plugin, please <a href="mailto:support@smartocto.com">contact our support team</a>.', 'contentinsights'); ?></br>
            <?php echo __('Please make sure to include your domain name in your email.', 'contentinsights'); ?>
        </p>

        <input type="hidden" name="action" value="update" />
        
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>

    </form>

</div>
<?php
}

function contentinsights_add_tracker () {
    global $post;
    global $ci_plugin_version;

    $options = get_option('contentinsights');

    $_ain_config['site_id'] = $options['site_id'];
    $_ain_config['maincontent'] = ($options['maincontent'] != '') ? $options['maincontent'] : '.ciTrackContent';
    $_ain_config['excludecontent'] = ($options['excludecontent'] != '') ? $options['excludecontent'] : '';

    if ($_ain_config['site_id']) {
        if (current_user_can('manage_options') && isset($options['ignore_admin']))
            return;
    
        if (is_single() || ( is_page() && isset($options['include_pages']) ) && $post->post_status == "publish") {
            $_ain_config['authors'] = (array)apply_filters('contentinsights_config_authors', get_the_author_meta('display_name', $post->post_author));
            $_ain_config['postid'] = get_the_ID();
            $_ain_config['url'] = get_permalink();
            $_ain_config['title'] = get_the_title();
            $_ain_config['pubdate'] = get_post_time('Y-m-d\TH:i:s\Z', true);
            $_ain_config['comments'] = get_comments_number();

            $_ain_cats = get_the_category($post->ID);
            $_ain_clist = array();
            if ($_ain_cats) {
                foreach ($_ain_cats as $_ain_cat) {
                    $_ain_clist[] = esc_js($_ain_cat->name);
                }
            }
            $_ain_config['sections'] = (array)apply_filters('contentinsights_config_sections', $_ain_clist);
            
            $_ain_tags = get_the_tags($post->ID);
            $_ain_tags_list = array();
            if ($_ain_tags) {
                foreach($_ain_tags as $tag) {
                    $_ain_tags_list[] = esc_js($tag->name);
                }
            }
            $_ain_config['tags'] = (array)apply_filters('contentinsights_config_tags', $_ain_tags_list);

            $_ain_config['articletype'] = is_page() ? 'wp-page-' . $ci_plugin_version : 'wp-post-' . $ci_plugin_version;

            $_ain_config['accesslevel'] = 'free';
            $_ain_config['readertype']  = 'anonymous';

            if (current_user_can('read')) {
                $_ain_config['readertype'] = 'registered';
            }

?>
<script type="text/javascript">
    var _ain = {
        id: "<?php echo esc_js($_ain_config['site_id']); ?>",
        maincontent: "<?php echo esc_js($_ain_config['maincontent']); ?>",
        excludecontent: "<?php echo esc_js($_ain_config['excludecontent']); ?>",
        authors: "<?php echo implode(',', $_ain_config['authors']); ?>",
        postid: "<?php echo esc_js(apply_filters('contentinsights_config_postid', $_ain_config['postid'])); ?>",
        url: "<?php echo apply_filters('contentinsights_config_url', $_ain_config['url']); ?>",
        title: "<?php echo esc_js(apply_filters('contentinsights_config_title', $_ain_config['title'])); ?>",
        pubdate: "<?php echo apply_filters('contentinsights_config_pubdate', $_ain_config['pubdate']); ?>",
        comments: "<?php echo apply_filters('contentinsights_config_comments', $_ain_config['comments']); ?>",
        sections: "<?php echo implode(',', $_ain_config['sections']); ?>",
        tags: "<?php echo implode(',', $_ain_config['tags']); ?>",
        article_type: "<?php echo apply_filters('contentinsights_config_articletype', $_ain_config['articletype']); ?>",
        access_level: "<?php echo apply_filters('contentinsights_config_accesslevel', $_ain_config['accesslevel']); ?>",
        reader_type: "<?php echo apply_filters('contentinsights_config_readertype', $_ain_config['readertype']); ?>"
    };
    <?php do_action('contentinsights_config_extend'); ?>
    
    (function (d, s) {
    var sf = d.createElement(s); sf.type = 'text/javascript'; sf.async = true;
    sf.src = (('https:' == d.location.protocol) 
        ? 'https://d7d3cf2e81d293050033-3dfc0615b0fd7b49143049256703bfce.ssl.cf1.rackcdn.com' 
        : 'http://t.contentinsights.com')+'/stf.js';
    var t = d.getElementsByTagName(s)[0]; 
    t.parentNode.insertBefore(sf, t);
    })(document, 'script');
</script>
<?php
        }
    }
}

function admin_menu_contentinsights() {
    add_options_page('Content Insights', 'Content Insights', 'manage_options', 'contentinsights_add_tracker', 'contentinsights_options_page');
}

function admin_init_contentinsights() {
    register_setting('contentinsights_options', 'contentinsights', 'contentinsights_validate');
}

function contentinsights_validate($input) {
    if (isset($input['site_id']))
        $input['site_id'] = is_numeric($input['site_id']) ? $input['site_id'] : '';

    if (isset($input['ignore_admin']) && $input['ignore_admin'] !== 'on')
        unset($input['ignore_admin']);

    if (isset($input['include_pages']) && $input['include_pages'] !== 'on')
        unset($input['include_pages']);

    return $input;
}

function contentinsights_mark_content($classes) {

    $classes[] = 'ciTrackContent';

    return $classes;
}

// Add settings link on plugin page
function contentinsights_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=contentinsights_add_tracker">'.__('Settings').'</a>';
    array_unshift($links, $settings_link); 
    return $links;
}

function contentinsights_text_domain() {
    load_plugin_textdomain( 'contentinsights', false, dirname( plugin_basename(__FILE__) ). '/languages/' );
}

function admin_init_contentinsights_check() {
    $options = get_option('contentinsights');
    
    if (! $options['site_id'])
        add_action( 'admin_notices', 'contentinsights_check_error' );
}

function contentinsights_check_error() {
    $class = 'notice notice-error';
    $message = __('Please configure your <a href="'.esc_url(admin_url('options-general.php?page=contentinsights_add_tracker')).'">Content Insights</a> settings');

    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
}

if (is_admin()) {
    add_action('init', 'contentinsights_text_domain');
    add_action('admin_init', 'admin_init_contentinsights');
    add_action('admin_init', 'admin_init_contentinsights_check');
    add_action('admin_menu', 'admin_menu_contentinsights');
    add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'contentinsights_settings_link');
}

add_filter('post_class', 'contentinsights_mark_content');
add_action('wp_footer', 'contentinsights_add_tracker');