<?php include_once 'setting.php';
extract(get_option('smartcat_services_options'));
?>
<div class="width70 left">
    <p>To display the Services, copy and paste this shortcode into the page or widget where you want to show it. 
        <input type="text" readonly="readonly" value="[our-services]" style="width: 100px" onfocus="this.select()"/>
    </p>
    <p><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FSmartcatDesign&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=233286813420319" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:35px;" allowTransparency="true"></iframe></p>

    <form name="sc_our_services_post_form" method="post" action="" enctype="multipart/form-data">
        <table class="widefat">
            <thead>
                <tr>
                    <th colspan="2"><b><?php _e('Services Settings', 'smartcat-services'); ?></b></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php _e('Template', 'smartcat-services'); ?></td>
                    <td>
                        <select name="smartcat_services_options[template]" id="sc_our_services_template">
                            <option><?php _e('Select Template', 'smartcat-services'); ?></option>

                                                        
                            <option value="icons" <?php echo 'icons' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Template 1 - Icons', 'smartcat-services'); ?></option>
                            <option value="images" <?php echo 'images' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Template 2 - Images', 'smartcat-services'); ?></option>
                            <option disabled <?php echo 'zoomOut' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Template 3 - Images (pro)', 'smartcat-services'); ?></option>
                            <option disabled <?php echo 'slide' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Template 4 - Images (pro)', 'smartcat-services'); ?></option>
                            <option disabled <?php echo 'quad' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Tenplate 5 - Images (pro)', 'smartcat-services'); ?></option>
                            <option disabled <?php echo 'columns' == esc_attr($template) ? 'selected=selected' : ''; ?>><?php _e('Template 6 - Icons (pro)', 'smartcat-services'); ?></option>

                        </select>
                    </td>
                </tr>

<!--                <tr id="columns-row">
    <td>Grid Columns</td>
    <td>
        <select name="smartcat_services_options[columns]">
            <option value="2" <?php echo '2' == esc_attr($columns) ? 'selected=selected' : ''; ?>>2</option>
            <option value="3" <?php echo '3' == esc_attr($columns) ? 'selected=selected' : ''; ?>>3</option>
            <option value="4" <?php echo '4' == esc_attr($columns) ? 'selected=selected' : ''; ?>>4</option>
            <option value="5" <?php echo '5' == esc_attr($columns) ? 'selected=selected' : ''; ?>>5</option>
            <option value="10" <?php echo '10' == esc_attr($columns) ? 'selected=selected' : ''; ?>>10</option>
        </select>
    </td>
</tr>                   -->

                <tr id="font-size-row">
                    <td><?php _e('Title Font Size', 'smartcat-services'); ?></td>
                    <td>
                        <input type="text" name="smartcat_services_options[title_size]" value="<?php echo esc_attr($title_size); ?>" class="width25"/>px
                    </td>
                </tr>       
                <tr>
                    <td><?php _e('Title Color', 'smartcat-services'); ?></td>
                    <td>
                        <input class="wp_popup_color width25" type="text" value="<?php echo esc_attr($main_color); ?>" name="smartcat_services_options[main_color]"/>
                    </td>
                </tr>                


<!--                <tr id="columns-row">
    <td>Grid Columns</td>
    <td>
        <select name="smartcat_services_options[columns]">
            <option value="6" <?php echo '6' == esc_attr($columns) ? 'selected=selected' : ''; ?>>2</option>
            <option value="4" <?php echo '4' == esc_attr($columns) ? 'selected=selected' : ''; ?>>3</option>
            <option value="3" <?php echo '3' == esc_attr($columns) ? 'selected=selected' : ''; ?>>4</option>
        </select>
    </td>
</tr>                -->

                <tr id="height-row">
                    <td><?php echo _e('Maximum Item Height', 'smartcat-services'); ?></td>
                    <td>
                        <input type="text" name="smartcat_services_options[height]" value="<?php echo esc_attr($height); ?>" class="width25"/>px<br>
                        <em><?php _e('', 'smartcat-services'); ?></em>
                    </td>
                </tr>   

                <tr id="height-row">
                    <td><?php echo _e('Word Count', 'smartcat-services'); ?></td>
                    <td>
                        <input type="text" name="smartcat_services_options[word_count]" value="<?php echo esc_attr($word_count); ?>" class="width25"/><br>
                    </td>
                </tr>   
                <tr>
                    <td><?php _e('Text Color', 'smartcat-services'); ?></td>
                    <td>
                        <input class="wp_popup_color width25" type="text" value="<?php echo esc_attr($text_color); ?>" name="smartcat_services_options[text_color]"/>
                    </td>
                </tr>                


                <tr id="height-row">
                    <td><?php echo _e('Link Text', 'smartcat-services'); ?></td>
                    <td>
                        <input type="text" name="smartcat_services_options[link_text]" value="<?php echo esc_attr($link_text); ?>" class="width25"/><br>
                        <em><?php _e('Leave this field empty to hide the link button'); ?></em>
                    </td>
                </tr>   
                <tr>
                    <td><?php _e('Link Color', 'smartcat-services'); ?></td>
                    <td>
                        <input class="wp_popup_color width25" type="text" value="<?php echo esc_attr($accent_color); ?>" name="smartcat_services_options[accent_color]"/>
                    </td>
                </tr>                             



<!--                <tr id="margin-row">
                    <td>Margin</td>
                    <td>
                        <select name="smartcat_services_options[margin]">
                            <option value="0" <?php echo '0' == esc_attr($margin) ? 'selected=selected' : ''; ?>>No margin</option>
                            <option value="5" <?php echo '5' == esc_attr($margin) ? 'selected=selected' : ''; ?>>5</option>
                            <option value="10" <?php echo '10' == esc_attr($margin) ? 'selected=selected' : ''; ?>>10</option>
                            <option value="15" <?php echo '15' == esc_attr($margin) ? 'selected=selected' : ''; ?>>15</option>
                        </select>px
                    </td>
                </tr>                -->

<!--                <tr id="social_icons_row">
    <td>Display Social Icons</td>
    <td>
        <select name="smartcat_services_options[social]">
            <option value="yes" <?php echo 'yes' == esc_attr($social) ? 'selected=selected' : ''; ?>>Yes</option>
            <option value="no" <?php echo 'no' == esc_attr($social) ? 'selected=selected' : ''; ?>>No</option>
        </select>
    </td>
</tr>

<tr id="">
    <td>Display Name</td>
    <td>
        <select name="smartcat_services_options[name]">
            <option value="yes" <?php echo 'yes' == esc_attr($name) ? 'selected=selected' : ''; ?>>Yes</option>
            <option value="no" <?php echo 'no' == esc_attr($name) ? 'selected=selected' : ''; ?>>No</option>
        </select>
    </td>
</tr>

<tr id="">
    <td>Display Title</td>
    <td>
        <select name="smartcat_services_options[title]">
            <option value="yes" <?php echo 'yes' == esc_attr($title) ? 'selected=selected' : ''; ?>>Yes</option>
            <option value="no" <?php echo 'no' == esc_attr($title) ? 'selected=selected' : ''; ?>>No</option>
        </select>
    </td>
</tr>-->
                <tr>
                    <td><?php _e('Number of Services to display', 'smartcat-services'); ?></td>
                    <td>
                        <input type="text" value="<?php echo esc_attr($member_count); ?>" name="smartcat_services_options[member_count]" placeholder="number of members to show"/><br>
                        <em><?php _e('Set to -1 to display all members', 'smartcat-services'); ?></em>
                    </td>
                </tr>




            </tbody>
        </table>

<!--        <table class="widefat">
    <thead>
        <tr>
            <th colspan="2"><b><?php _e('Single Service View Settings', 'smartcat-services'); ?></b></th>
        </tr>
        <tr>
            <td><?php _e('Template', 'smartcat-services'); ?></td>
            <td>
                <select name="smartcat_services_options[single_template]">
                    <option><?php _e('Select Template', 'smartcat-services'); ?></option>
                    <option value="standard" <?php echo 'standard' == esc_attr($single_template) ? 'selected=selected' : ''; ?>><?php _e('Theme Default (single post)', 'smartcat-services'); ?></option>

                </select>
            </td>
        </tr>

    </thead>
</table>-->

        <input type="hidden" name="smartcat_services_options[redirect]" value=""/>
        <div style="text-align: right">
            <input type="submit" name="sc_our_services_save" value="Update" class="button button-primary" />
        </div>

    </form>

    <div class="clear"></div>
    <br>



</div>


</div>
