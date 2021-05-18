/* global jQuery, svlDemoOptions */

/**
 * If you're reading this, then perhaps you're trying to figure out how I put this demo site together with the
 * demo blocks and flyout toggle.  Don't go to all the trouble of piecing it together via this JavaScript and HTML.
 * I put this demo toggle code into a GitHub repository.  You're welcome to fork it, modify it, use it, whatever.
 * I only ask you leave this message, the repository link, and the credits intact.
 *
 * - https://github.com/svl-studios/svl-demo-toggle
 *
 * Enjoy!
 *
 * - Kev
 */

( function( $ ) {
	'use strict';

	var adjustWrap;
	var con = $( '.svl-demos' );

	function loadDemos() {
		var div;
		var theme;
		var wrap = $( '.svl-demo-select-wrap' );

		wrap.removeClass( 'init-onload' );

		theme = wrap.data( 'theme' );

		div = $( '<div>' );
		div.load(
			svlDemoOptions.baseURL + '/ .svl-demos',
			function() {
				var demoWrap = div.find( '.demo-wrap' );

				div.imagesLoaded(
					function() {
						var count = 0;

						div.remove();

						$( '.loading-demos' ).remove();
						$( '.svl-more-demos-text' ).text( 'Scroll For More' );

						$.each(
							demoWrap,
							function() {
								var img   = $( this ).find( 'img' ).attr( 'src' );
								var url   = $( this ).find( '.title-link' ).attr( 'href' );
								var title = $( this ).find( '.title-link h4' ).text();

								if ( '' !== title && $( '.svl-demo-window ul' ).append( '<li><a title="' + theme + ' ' + title + ' Demo" href="' + url + '" target=_blank><img src="' + img + '" alt="Qixi ' + title + ' Demo"></a></li>' ) ) {
									count++;
								}
							}
						);

						$( '.demos-count' ).text( count + ' Demos Included!' );

						adjustWrap();
					}
				);
			}
		);
	}

	adjustWrap = function() {
		var height = $( window ).height() - $( '.svl-demos-info-box' ).height();

		$( '.svl-demo-window ul' ).css( 'height', height );

		if ( $( window ).width() < $( '.svl-demo-select-wrap' ).width() && ! $( '.svl-demo-select-wrap' ).hasClass( 'hide-small' ) ) {
			$( '.svl-demo-select-wrap' ).addClass( 'hide-small' ).hide();
		} else if ( $( window ).width() > $( '.svl-demo-select-wrap' ).width() && $( '.svl-demo-select-wrap' ).hasClass( 'hide-small' ) ) {
			$( '.svl-demo-select-wrap' ).removeClass( 'hide-small' ).show();
		}
	};

	adjustWrap();

	$( window ).on( 'load resize', adjustWrap );

	$( 'body' ).on(
		'click',
		'.svl-demo-toggle',
		function( e ) {
			e.preventDefault();

			if ( $( '.svl-demo-select-wrap' ).hasClass( 'init-onload' ) ) {
				loadDemos();
			}

			if ( $( '.svl-demo-select-wrap' ).hasClass( 'open' ) ) {
				$( '.svl-demo-select-wrap' ).stop().removeClass( 'open' ).animate( { right: '-350px' }, 'slow' );
			} else {
				$( '.svl-demo-select-wrap' ).stop().addClass( 'open' ).animate( { right: '0' }, 'slow' );
			}
		}
	);

	$( window ).on(
		'load',
		function() {
			var rand     = con.data( 'randomize' );
			var interval = con.data( 'interval' );

			if ( 'yes' === rand ) {
				if ( $( 'body' ).hasClass( 'page-id-' + svlDemoOptions.pageID ) ) {
					$( '.svl-demos' ).randomizeDemos( '.demo-block' );
					$( '.svl-demos' ).css( { visibility: 'visible' } );

					setInterval(
						function() {
							$( '.svl-demos' ).randomizeDemos( '.demo-block' );
						},
						interval
					);
				}
			}
		}
	);

	$.fn.randomizeDemos = function( childElem ) {
		return this.each(
			function() {
				var $this = $( this );
				var elems = $this.children( childElem );
				var i;
				var len;

				elems.sort(
					function() {
						return ( Math.round( Math.random() ) - 0.5 );
					}
				);

				$this.remove( childElem );

				len = elems.length;
				for ( i = 0; i < len; i++ ) {
					$this.append( elems[i] );
				}
			}
		);
	};
}( jQuery ) );
