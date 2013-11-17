/**
 * Functionality specific to Twenty Thirteen.
 *
 * Provides helper functions to enhance the theme experience.
 */

( function( $ ) {
    
        var scroller = {
            
            wrapper: null,
            scrollable: null,
            
            initPhotoClick: function()
            {               
                
                this.scrollable.on('click', 'a', function(e) {
                    
                    e.preventDefault();
                    
                    var href = $(this).attr('href');
                    var pic = $('.pic', this.wrapper).addClass('loading');
                    
                    var img = new Image();
                    
                    $(img)
                    // once the image has loaded, execute this code
                    .load(function () {
                      // set the image hidden by default    
                      $(this).hide();

                      // with the holding div #loader, apply:
                      pic
                        // remove the loading class (so no background spinner), 
                        .removeClass('loading')
                        // then insert our image
                        .empty().append(this);

                      // fade our image in to create a nice effect
                      $(this).fadeIn();
                    })

                    // if there was an error loading the image, react accordingly
                    .error(function () {
                      // notify the user that the image could not be loaded
                    })

                    // *finally*, set the src attribute of the new image to our image
                    .attr('src', href);
                    
                });
                
                var firstPhoto = $('a:first-child', this.scrollable).trigger('click');
                
                
            },
            
            init: function()
            {
                
                this.wrapper = $('.ngg-galleryoverview');
                
                if(this.wrapper.length) {
                
                    this.scrollable = $("#makeMeScrollable");
                            
                    this.scrollable.smoothDivScroll({
                            mousewheelScrolling: "allDirections",
                            manualContinuousScrolling: true,
                            autoScrollingMode: "onStart",
                            autoScrollingInterval: 50,
                            autoScrollingStarted: this.initPhotoClick()
                    });
                
                }
                
                
            }
            
        }
    
	var body    = $( 'body' ),
	    _window = $( window );

	/**
	 * Adds a top margin to the footer if the sidebar widget area is higher
	 * than the rest of the page, to help the footer always visually clear
	 * the sidebar.
	 */
	$( function() {
		if ( body.is( '.sidebar' ) ) {
			var sidebar   = $( '#secondary .widget-area' ),
			    secondary = ( 0 == sidebar.length ) ? -40 : sidebar.height(),
			    margin    = $( '#tertiary .widget-area' ).height() - $( '#content' ).height() - secondary;

			if ( margin > 0 && _window.innerWidth() > 999 )
				$( '#colophon' ).css( 'margin-top', margin + 'px' );
		}
                
                
                if($('#sequence').length > 0){
                  var options = {
                    autoPlay: true,
                    autoPlayDelay: 12000,
                    pauseOnHover: false,
                    hidePreloaderDelay: 1000,
                    nextButton: true,
                    prevButton: true,
                    pauseButton: true,
                    preloader: true,
                    hidePreloaderUsingCSS: false,                   
                    animateStartingFrameIn: true,    
                    navigationSkipThreshold: 1700,
                    preventDelayWhenReversingAnimations: true,
                    customKeyEvents: {
                      80: "pause"
                    }
                  };


                  var sequence = $("#sequence").sequence(options).data("sequence");
                  
                  scroller.init();
                }
                
	} );

	/**
	 * Enables menu toggle for small screens.
	 */
	( function() {
		var nav = $( '#site-navigation' ), button, menu;
		if ( ! nav )
			return;

		button = nav.find( '.menu-toggle' );
		if ( ! button )
			return;

		// Hide button if menu is missing or empty.
		menu = nav.find( '.nav-menu' );
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.twentythirteen', function() {
			nav.toggleClass( 'toggled-on' );
		} );
	} )();

	/**
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.twentythirteen', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
				element.tabIndex = -1;

			element.focus();
		}
	} );

	/**
	 * Arranges footer widgets vertically.
	 */
	if ( $.isFunction( $.fn.masonry ) ) {
		var columnWidth = body.is( '.sidebar' ) ? 228 : 245;

		$( '#secondary .widget-area' ).masonry( {
			itemSelector: '.widget',
			columnWidth: columnWidth,
			gutterWidth: 20,
			isRTL: body.is( '.rtl' )
		} );
	}
} )( jQuery );