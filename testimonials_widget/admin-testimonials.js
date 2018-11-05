/*
	 * Location Single Page Testimonial Slideshow
	 * Add slick slider to the testimonial section of the lcoation page
	*/
	if ($(".testimonial-slideshow").length > 0) {
		$(".testimonial-slideshow").slick({
			dots: false,
			infinite: true,
			speed: 300,
			fade: false,
			cssEase: "ease",
			autoplay: true,
			autoplaySpeed: 8000,
			prevArrow:
				'<div class="slick-prev"><i class="fa fa-chevron-left"></i></div>,',
			nextArrow:
				'<div class="slick-next"><i class="fa fa-chevron-right"></i></div>',
			draggable: false,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						draggable: true
					}
				}
			]
		});
    }
    
    $('.testimonial-slideshow').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
      });