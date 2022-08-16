<?php
$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
?>
<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="loc">
               <?php /*?> <div class="location">
              </div><?php */?>
                <p class="date">
				<?php 
				date_default_timezone_set('Asia/Kolkata'); // this sets time zone to IST
                     echo date('h:i A ').'-IST'.', </br>'.date('l').'</br> '.date(' jS  F Y ');
			    ?>
                </p>
              </div>
          </div>
          </div>
        <?php $frontend_js  = base_url()."js/FrontEnd/js/";?>  
          <!-- <script type="text/javascript" src="<?php echo $frontend_js;?>jquery.simpleopenweather.js"></script>
           <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>-->
            <script type="text/javascript" src="<?php echo $frontend_js;?>simpleWeather.js"></script>

<script type="text/javascript">
      $(document).ready(function() {
	/*	$.ajax({
                type: "GET",
                dataType: "jsonp",
                url: "http://www.geoplugin.net/json.gp?jsoncallback=?",
                success: function(Location) {
       	    var city = Location.geoplugin_city;
			var region = Location.geoplugin_region;
			
			var Countrycode = Location.geoplugin_countryCode;
			$.simpleWeather({
    location: city+','+Countrycode,
    woeid: '',
    unit: 'c',
    success: function(weather) {
      html = '<h2><i class="icon-'+weather.code+'"></i><img data-src="'+weather.thumbnail+'"/>'+weather.temp+'&deg;'+weather.units.temp+'</h2>';
      html += '<ul><li>'+weather.city+', '+weather.region+'</li>';
      html += '<li class="currently">'+weather.currently+'</li>';
      html += '<li>'+weather.wind.direction+' '+weather.wind.speed+' '+weather.units.speed+'</li></ul>';
      weatherhtml ='<p> <span class="WeatherIcon" id="weather_icon_<?php echo $widget_instance_id;?>"></span><img data-src="'+weather.thumbnail+'" title="'+weather.currently+'"/>';
	  weatherhtml +='<span class="WeatherIcon simpleopenweather" id="celcius_<?php echo $widget_instance_id;?>">'+weather.temp+'&deg;'+weather.units.temp+'</span>&nbsp;&nbsp;';
      weatherhtml +='<span id="city_<?php echo $widget_instance_id;?>">'+weather.city+'</span>&nbsp;,&nbsp;<span id="region_<?php echo $widget_instance_id;?>">'+weather.region+'</span></p>';
	  $(".location").html(weatherhtml);
	  console.log(weather);
    },
    error: function(error) {
      $(".location").html('<p>'+error+'</p>');
    }
  });
		}
            });*/
		
    });
</script>
