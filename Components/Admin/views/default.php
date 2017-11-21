<div style="margin-top: 20px;">

    <img src="<?php echo esc_url(plugins_url('../images/logo.png', __FILE__)); ?>" style="float:left;width:200px;"/>
    <h1 style="margin-left: 20px;display: inline-block;"> Plugin Settings </h1>
    </br>
    </br>

    <form method="POST" style="display: block;clear: both; margin-top: 20px;">

        <input name="co2ok_statistics" <?php if($co2ok_statistics == 'on') echo "checked" ?> type="checkbox"/>
        <label for="plugin_language">Help us improve our product by allowing us to send anonymous statistics. </label>

        </br>
        </br>

        <label for="co2ok_template_style" ><strong> Button Style </strong> </label>
        </br></br>
        <div class="example_button" style="border:1px solid grey; border-radius:12px; padding:20px; max-width: 300px; displat:inline-block;">
            <input type="radio" name="co2ok_template_style" value="co2ok_button_template_default" <?php if($co2ok_template_style == 'co2ok_button_template_default') echo 'checked' ?> >Default

            </br></br>
            <img src="<?php echo esc_url(plugins_url('../images/site_render_button_default.png', __FILE__)); ?>"/>
        </div>
        </br>
        <input type="submit" value="Save" class="button button-primary button-large">
    </form>

</div>

