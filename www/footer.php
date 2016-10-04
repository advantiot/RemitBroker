<!--Footer-->
<footer id="bottom" class="main" style="padding:20px;">
    <!--Container-->
    <div class="container">

        <!--rows-->
        <div class=row">
            <!--Important Links-->
            <div id="tweets" class="col-md-3">
                <h4>OUR COMPANY</h4>
                <div>
                    <ul class="arrow">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>  
            </div>
            <!--Important Links-->

            <!--Products Links-->
            <div id="tweets" class="col-md-3">
                <h4>PRODUCTS</h4>
                <div>
                    <ul class="arrow">
                        <li><a href="#">RemitBroker</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>  
            </div>
            <!--Products Links-->

            <!--Legal-->
            <div id="legal" class="col-md-3">
                <h4>LEGAL</h4>
                <div>
                    <ul class="arrow">
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <!--End Legal-->

            <!--Contact Form-->
            <div class="col-md-3">
                <h4>CONTACT US</h4>
                <ul class="arrow address">
                    <li>
                        <i class="fa fa-envelope"></i>
                        support@email.com
                    </li>
                    <li>
                        <i class="fa fa-phone"></i>
			1-800-555-5555
                    </li>
                    <li>
			<i class="fa fa-twitter"></i>
			#advantapi 
                    </li>
                </ul>
            </div>
            <!--End Contact Form-->

    	</div>
    	<!--row-->
	
	<div class="row">
            <div class="col-md-12 cp">
                &copy; 2016 <a target="_blank" href="http://www.remitbroker.com/" title="">RemitBroker</a>. All Rights Reserved.
            </div>
            <!--/Copyright-->
	</div>
</div>
<!--/container-->

</footer>
<!--/footer-->

<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<!-- Required javascript files for Slider -->
<script src="js/jquery.ba-cond.min.js"></script>
<script src="js/jquery.slitslider.js"></script>
<!-- /Required javascript files for Slider -->

<!-- SL Slider -->
<script type="text/javascript"> 
$(function() {
    var Page = (function() {

        var $navArows = $( '#nav-arows' ),
        slitslider = $( '#slider' ).slitslider( {
            autoplay : true
        } ),

        init = function() {
            initEvents();
        },
        initEvents = function() {
            $navArows.children( ':last' ).on( 'click', function() {
                slitslider.next();
                return false;
            });

            $navArows.children( ':first' ).on( 'click', function() {
                slitslider.previous();
                return false;
            });
        };

        return { init : init };

    })();

    Page.init();
});
</script>
<!-- /SL Slider -->
</body>
</html>
