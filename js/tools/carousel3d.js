(function($) {

	$.fn.extend({
		
		carousel3d: function(options) { 

				opt = $.extend({},$.carouselSetup.defaults, options);		// extend options
				
				$this = $(this); 
				
				opt.speed = parseInt(6 - opt.speed);																		
        		btnSpeed = Math.round(10 - opt.speed) * $.browser.msie ? 0.01 : 0.2;					// original button speed
        		contSpeed = btnSpeed * $.browser.msie ? 0.5 : 0.15;											// original continuous speed
           		opt.speed = opt.speed * $.browser.msie ? 2500 : 1500;											// original mouse speed
				
				
				$imgs = $('img', $this).hide();											// set variable with carousel images; hide for now
				$texts = $('span', $('#carouselText')).hide();					// set variable with carousel text boxes; hide for now
				if (opt.textBox && !$texts.size()) alert('<div id="carouselText" /> + <span>\'s. . . do not exist.  either add them or set textBox option to 0');
				
				items = $imgs.size(); 														// number of icons in carousel
				numSlots = items * opt.padding; 										// in order for the movement to flow smoothly, there are additional 'slots' in the carousel which the images will pace through
				
				if (opt.padding == 0) opt.padding = 1;								// padding must be at least 1
				
				$imgs.each(function(i) { new $.imageSetUp(this, i) });	// setup images
				new $.carouselSetup();													// setup carousel        		
           }
	});
    
    
	$.imageSetUp = function(im, _index) {
		
		im.orig_w = $(im).width(), im.orig_h = $(im).height();				// save the original dimensions; used when image is clicked
		
		var w_h = resize( im.orig_w, 128, im.orig_h, 108).split('|');		// calculate w/h of images in carousel
		im.h = w_h[1], im.w = w_h[0];
		
		im.slot = _index * opt.padding;												// position of the image in the carousel
		im.angle = parseInt(( _index * opt.padding ) * (( Math.PI * 2 ) / numSlots )*1000)/1000;		// original angle of the image
		
		im.clicked = { 																				// css to animate when image is clicked
			top: parseInt(opt.centerY - im.orig_h/3) + 'px',
			left: parseInt(opt.centerX - im.orig_w/3) + 'px',
			width: im.orig_w + 'px',
			height: im.orig_h + 'px' 
		};
		im.animateOn = function(el) { $imgs.fadeOut(700); el.animate( im.clicked, 500 ) }; 	// hide the carousel
		
		new $.TextBoxSetUp(im, _index);											// setup text box	
		
		$(im).attr('id', 'pix'+_index).css({position: 'absolute'});				// id will be referenced when moving the image
			
		if ($(im).hasClass('link')) $(im).click( function(){ window.open($(this).attr('longdesc')) } );
		else $(im).one('click', clickOn);												// bind clickOn to image
		
	};  
	
    $.TextBoxSetUp = function(im, _index) {
     	im.textClicked = {																			// css to animate when image is clicked
			left: opt.centerX - opt.radiusX - im.w + 'px',
			top: opt.centerY + 'px',
			width: im.w + 'px',
			height: im.h + 'px' 
		};
		im.textBoxCss = {																			// textBox positioning css 
			top: opt.centerY *0.7 + 'px',
			left: opt.centerX - opt.radiusX * 0.9 + 'px' 
		};
		im.textAnimateOn = function(el) { 
			var msg = '';
			if ( !$texts.size() ) msg += '<div id="carouselText" /> + <span>\'s. . . do not exist';
			else if ( !$('#text').size() ) msg += '<div id="text" /> does not exist';
			else {
				$imgs.fadeOut(700);
				el.animate( im.textClicked, 500 );
				$('#text')
					.css( im.textBoxCss )
					.html( $texts.eq(_index).html() )
					.fadeIn(700);
			}
			if (msg) alert( 'Cannot setup text box; ' + msg );
		};
		
	};  
	
	$.carouselSetup = function() {
	
		var im, _t, _s;
		
		if (opt.control=='buttons') controls();
		else if (opt.control=='continuous') rate = btnSpeed *.3;
		else $().mousemove(function(e) {
			var distance = Math.abs(e.pageX - opt.centerX);
			if (distance>20) distance = 20;
			if (distance<-20) distance = -20;
			rate = distance / opt.speed; 
		});
		
	
		// javascript Motion Tween by PHILIPPE MAEGERMAN; very similar to tweening in Flash.
		// check out the full details at his site: http://jstween.blogspot.com/
		t1 = new Tween(new Object(), 'xyz', Tween.regularEaseInOut, 0, 10000, 10000);
            
		t1.onMotionChanged = function(event) {
			for (var j=0; j<items, im=$('#pix'+j)[0]; j++) {
				
				im.slot = (im.slot == numSlots - 1) ? 0: im.slot++;			// if image is in last slot, set as first slot; else advance 1 slot
				_t = Math.sin(im.angle) * opt.radiusY + opt.centerY;		// calculate top positioning
				_s = ((_t - opt.perspective) / (opt.centerY + opt.radiusY - opt.perspective));	// calculate size of image based on position in carsousel
				
				$(im).css({ 																		// set css values for image
					top: _t, 
					left: Math.cos(im.angle) * opt.radiusX + opt.centerX, 	// calculate left positioning
					width: im.w * _s, 															// multiply image size by newly calculated size
					height: im.h * _s, 
					zIndex: Math.round(_t)+100,										// z-index makes front images fully visible
					opacity: (opt.fadeEffect == 1) ? Math.sin(im.angle) : 1 });	// if fadeEffect is set, calculate opacity based on location in carousel
				
				if (opt.fadeEffect == 1) {
					$(im)[ Math.sin(im.angle)<=0 ? 'hide' : !$(im).is(':visible') ? 'show' : ''  ];
				}
				
				im.angle += opt.control=='continuous' ? contSpeed : rate;			// change image angle based on carousel speed
           	}
       	};
       	
       	t1.start();																					// start the motion
       	$imgs.fadeIn(1500);																// fadeIn the images
    };
    
    var opt, numSlots, items, rate = contSpeed = btnSpeed = 0;		// initialize variables

	function clickOn() {																		// actions when image in carousel is clicked
		var elem = this;
		t1.stop(); 																					// stop the Tween motion
		$('#buttonwrapper:visible').fadeOut();										// hide the buttons
			
		$cloned = 																				// clone the image clicked and leave the original in place (this seemed easier than pulling the orig out of place)
		$(this).clone().prependTo($this).click(function() { 					// add to carousel, when clicked again . . .	
			$imgs.fadeIn();																	// show the carousel images
			$(this)																					// animate back to carousel slot
				.animate({ 
					left: $(elem).position().left + 'px', 									// change position
					top: $(elem).position().top + 'px', 
					width: $(elem).width() + 'px', 										//  and size
					height: $(elem).height() + 'px' 
             	}, function() {	
					$(this).remove();															// remove the cloned image, 
            		$(elem).one('click', clickOn);										// rebind the image click event,
            		t1.start();																		// and restart carousel 
          		});
          	$('#text:visible').fadeOut();													// if showing, hide the text box
       		$('#buttonwrapper:hidden').fadeIn();									// if hidden, show the buttons
       	});
       	
       	(opt.textBox) ? elem.textAnimateOn($cloned) : elem.animateOn($cloned);			// animate the clone; enlarge or position to side of text box
    };
	
	function controls() {																		// add button and functions
		rate=0;
		var btns = $('<div id="buttonwrapper" />').css({ left: opt.centerX+'px', top: opt.centerY*0.5+'px'}), 
			left_btn = $('<div id="left" />').hover(function(){rate=btnSpeed}, function(){rate=0}), 
			right_btn = $('<div id="right" />').hover(function(){rate=-btnSpeed}, function(){rate=0});
		$this.prepend( btns.append( left_btn, right_btn ));
    };
    
	function resize(w, max_w, h, max_h) {										// resize the images
		if (w>max_w || h>max_h) {
			var x_ratio = max_w / w;
			var y_ratio = max_h / h;
			if ((x_ratio * h) < max_h) return max_w + '|' + Math.ceil(x_ratio * h);
			else return Math.ceil(y_ratio * w) + '|' + max_h;
		}
		else return w + '|' + h;
	};
	
	function resetAnimations() {														// clicked images and text boxes must be repositioned after resizing screen
		$imgs.each(function(i) { 
			this.clicked.left = parseInt(opt.centerX*1 - this.orig_w/2) + 'px';
       		this.textClicked.left = parseInt(opt.centerX*1 - opt.radiusX*1 - this.w*1) + 'px';
        	this.textBoxCss.left = parseInt(opt.centerX*1 - opt.radiusX*1) + 'px';
		});
	};
	
    $(window).resize(function() {
		opt.centerX = $this.offset().left+$this.width()/2;
		resetAnimations();
	});

    $.carouselSetup.defaults = {	
        control: 'mouse',	  																	// 'button', 'mouse', or  'continuous' control
        speed: 5,																					// speed of mouse or button.  use scale of 1-5
        radiusX: 250,																			// x radius of the carousel
        radiusY: 5,																				// y radius of the carousel
        centerX: 0,																				// x position on the screen
        centerY: 250,																			// y position on the screen
        perspective: 5,																		// perspective of the image as it travels around the carousel
      	padding: 2, 																			// the number of padded items in between each icon.  
        																								// the more padding, the more precise the incremental movement,
        																								// however this also create a lot more calculations
        																								// to keep icons evenly spaced, the num of icons should be a multiple of the padding
        fadeEffect: 0,																			// fade icons as cycle to the back of the carousel
        textBox: 0																				//  1 = display text area for each icon, 0 = no display
    };

})(jQuery);