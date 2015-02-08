<style>
    #gopro{
        width: 100%;
        display: block;
        clear: both;
        padding: 10px;
        margin: 10px 8px 15px 5px;
        border: 1px solid #e1e1e1;
        background: #464646;
        color: #ffffff;
        overflow: hidden;
    }
    #wrapper{
        border: 1px solid #f0f0f0;
        width: 95%;

    }
    #wrapper{
        border: 1px solid #f0f0f0;
        width: 95%;

    }
    table.widefat{
        margin-bottom: 15px;
    }
    table.widefat tr{
        transition: 0.3s all ease-in-out;
        -moz-transition: 0.3s all ease-in-out;
        -webkit-transition: 0.3s all ease-in-out;
    }
    table.widefat tr:hover{
        /*background: #E6E6E6;*/
    }

    #wrapper input[type='text']{
        width: 80%;
        transition: 0.3s all ease-in-out;
        -moz-transition: 0.3s all ease-in-out;
        -webkit-transition: 0.3s all ease-in-out;
    }
    #wrapper input[type='text']:focus{
        border: 1px solid #1784c9;
        box-shadow: 0 0 7px #1784c9;
        -moz-box-shadow: 0 0 5px #1784c9;
        -webkit-box-shadow: 0 0 5px #1784c9;
    }
    #wrapper input[type='text'].small-text{
        width: 20%;
    }
    .proversion{
        color: red;
        font-style: italic;
    }
    .choose-progress{
        display: none;
    }
    .sc_popup_mode{
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 100%;
        position: relative;
        top: 2px;
        box-shadow: 0 0 3px #333;
        -moz-box-shadow: 0 0 3px #333;
        -webkit-box-shadow: 0 0 3px #333;
    }

    .sc_popup_mode_off{
        background: #F54412;
    }
    .sc_popup_mode_live{
        background: #84E11F;
    }
    .sc_popup_mode_test{
        background: #FF9717;
    }
    .left{ float: left;}
    .right {float: right;}
    .center{text-align: center;}
    .width70{ width: 70%;}
    .width25{ width: 25% !important;}
    .width50{ width: 50%;}
    .larger{ font-size: larger;}
    .bold{ font-weight: bold;}
    .editcursor{ cursor: text}
    .red{ color: #CC0000; font-size: 12px;}
</style>

<div id="wrapper">
    <div id="gopro">
        <div class="left">
            <h1><b><?php _e('Our Services Showcase Settings', 'smartcat-services'); ?></b></h1>
            <div> </div>
        </div>
<!--        <div class="right">
            <a href="http://smartcatdesign.net/our-services-showcase-demo/" target="_blank" class="button-secondary" style="padding: 40px;line-height: 0;font-size: 20px">View Demo</a>
        </div>
        <div class="right" style="margin-right: 20px">
            <a href="http://smartcatdesign.net/downloads/our-services-showcase/" target="_blank" class="button-primary" style="padding: 40px;line-height: 0;font-size: 20px">GO PRO NOW</a>
        </div>-->
    </div>
    <div class="width25 right">
        
        <table class="widefat">
            <thead>
            <tr>
                <th><b><?php _e('Read Me', 'smartcat-services');?></b> </th>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li><?php _e("Template 1: Uses the Service Item's featured image", 'smartcat-services'); ?></li>
                        <li><?php _e("Template 2: Uses the Service Item's current icon", 'smartcat-services'); ?></li>
                    </ul>
                    
                </td>
            </tr>
            </thead>
        </table>
        
        <table class="widefat">
            <thead>
                <tr>
                    <th><strong>Our Team/Staff Showcase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p>We also have a similar plugin dedicated to showcasing your team members or staff in a professional and appealing way. </p>
                        <p><a href="https://wordpress.org/plugins/our-team-enhanced/" target="_BLANK" class="button button-primary">Download Plugin</a></p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table class="widefat">
            <thead>
            <tr>
                <th><b>Support this plugin</b> </th>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li>Donate</li>
                    </ul>
                </td>
            </tr>
            </thead>
        </table>

    </div>