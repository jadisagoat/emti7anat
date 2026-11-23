<!-- requried-jsfiles-for owl -->
							<link href="<?=base_url()?>csssite/owl.carousel.css" rel="stylesheet">
							    <script src="<?=base_url()?>js/owl.carousel.js"></script>
							        <script>
									    $(document).ready(function() {
									      $("#owl-demo").owlCarousel({
									        items : 4,
									        itemsMobile : [480,1],
									        lazyLoad : true,
									        autoPlay : true,
									        navigation : true,
									        navigationText :  true,
									        pagination : false,
									      });
									    });
									  </script>
							 <!-- //requried-jsfiles-for owl -->

		<!-- start content_slider -->
		<div id="example1">
		<div id="owl-demo" class="owl-carousel text-center">
        <? foreach($banner as $banner1){ ?>
			<div class="item">
				<a href="<?=$banner1['link']?>" title="" rel="">
					<img class="img-responsive lot" src="<?=base_url()?>uploads/banner/374x229/<?=$banner1['image']?>"  alt="">
				</a>
			</div>
            <?  } ?>
		</div>
		</div>