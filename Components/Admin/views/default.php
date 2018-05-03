<?php

        add_action( 'admin_post_co2ok_save_options', 'co2ok_save_options' );
        
        function co2ok_save_options() {
            echo('Hello World');
            print_r($_POST);
        }

?>

<div style="margin-top: 20px;">

    <img src="<?php echo esc_url(plugins_url('../images/logo.png', __FILE__)); ?>" style="float:left;width:200px;"/>
    <h1 style="margin-left: 20px;display: inline-block;"> Plugin Settings </h1>
    </br>
    </br>

    <div id="col-container">
    
        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <h3>
                        <h1>Thanks for helping out the world :)</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae saepe reprehenderit eaque deserunt aspernatur cumque magni culpa excepturi ullam laboriosam corrupti corporis non sint, quibusdam, nam facere. Quo deserunt, pariatur voluptatibus ab sed quae culpa ad repellendus omnis autem voluptates.</p>
                        
                        <h2>Compensation preferences:</h2>
                        <p>By default we have set the button to off. But you can decide to set the CO2 OK button to default on. This way yopu are 
                        helping the environment even more!</p>
                        
                        <form method="POST">
                        
                            <input type="radio" name="co2ok_optin" id="on" value="on" <?php if($co2ok_optin == 'on') echo "checked" ?> >
                            <label style="display: inline" for="on">Turn on compensation over all purchases. (Preferred)</label>
                            <br>
                            <input type="radio" name="co2ok_optin" id="off" value="off" <?php if($co2ok_optin == 'off') echo "checked" ?> >
                            <label style="display: inline" for="off">Turn on the option to let my customers decide. (Default)</label>
                            
                            <!--                             
                            <br><br>
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sapiente doloribus quasi nobis distinctio, minima similique a quos unde quam hic.</p>
                            <input type="checkbox" name="compensate_all" id="no" value="no" checked=checked>
                            <label style="display: inline" for="no">Auto reload page after click.</label>
                             -->
                            <p style="margin-top: 12px">
                                <input type="submit" value="Save" class="button button-primary button-large"></p>

                        </form>
                        
                        <h2>Want to help us some more?</h2>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum neque, quo similique consectetur eius optio quisquam facere, labore temporibus reiciendis repudiandae ab quod at repellat nesciunt! Dolor laudantium consectetur dicta.</p>
                        <h2>Like us on:</h2>
                        <p><a href="#"><span>Facebook</span></a></p>
                        <p><a href="#"><span>Instagram</span></a></p>
                        <p><a href="#"><span>Patriot</span></a></p>
                        <br>
                        <h2>Need help?</h2>
                        <p><a href="mailto: support@co2ok.eco"><span>Send a mail to our support.</span></a></p>
                        <br>
                        <p>Thanks,<br>The CO2ok team.</p>
                        <p><a href="#">www.co2ok.eco</a></p>
                        <br>
                        <hr>


                    </h3>
                </div>
            </div>
        </div>
    </div>
 