/*jslint devel: true */
// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
	/*** UTILITIES ***/
function isInt(value){
	if((parseFloat(value) === parseInt(value)) && !isNaN(value)) {
		return true;
	} else {
		return false;
	}
}

// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
	/*** CHECK URL FOR HASHTAG INLINE ANCHOR ***/
	var $HASH = false;
	function url_hash_process(val) {
		console.log('Hash Marker Value: '+val);
	}
	function url_hash_sniff(){
		console.log('Parsing document location string for a hash marker (#)');
		var hash_check = (window.location.hash) ? window.location.hash : null;
		if(hash_check && (hash_check !== '#'+$HASH) ) {
			$HASH = window.location.hash.substring(1);
			url_hash_process($HASH);
		}
		// Put it on a timer:
		// t=setTimeout("log()",3000);
	}
	function url_hash_set(val) {
		if(val) {
			// Update the URL hash marker
			window.location.hash = '#'+val;
		}
	}
	// First Run:
	url_hash_sniff();

// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
function LoadImage(index, total, data, context) {
	if(index < total) {
		var img = new Image();
		var img_src = data[index].src;	// img_base +
		context = ((typeof context !== "undefined")) ? context : null ;
		if( context ) {
			$(img)
				.addClass('director-preview')
				.attr({'src': img_src,'id':'director-preview-'+index}) // , id: g_UUID()
				.load(function () {
					$(this).hide();
					$(context).append(this);
					//	$(this).fadeIn('slow', function() {
					LoadImage(index+1, total, data, context);
					//	});
				})
				.error(function () { // on error remove current && trigger the next image
					$(this).remove();
					LoadImage( index+1, total, data, context ); // max is not defined
				});

		} else {
			// console.log("Our fancy images have finally loaded.");
		} // ...if( index < total )...
	}
	
} // ...LoadImage()...

$.fn.DRAWER_PREVIEW = function( data, context ) {
	context = ((typeof context !== "undefined")) ? context : null ;
	var img_len = $(data).length;
	if( context ) {
		if(img_len > 0) {
			LoadImage(0, img_len, data, context); // Error: LoadImage is used before it is defined
		}
	}
}; // ...LOAD_IMG()...




// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
	$(document).ready(function() {

		// -------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------
		/*** SETUP GLOBAL VARIABLES ***/
		var _topMargin;			// height of upper menu
		var $scrollContainer,
			// $videoContainer,
			$correspondingVideo;

		var isMobile = null;	// boolean:true => the site is < 568px wide; used for director reels
		var $body = $( "body" );


		// -------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------
		/*** MAIN MENU LINKS OPEN DRAWER ON THE LEFT ***/
			var DRAWER = (function() {
				var contentWidth,
					contentWidthNav,
					displacementValue,
					$contentFog,
					$body;

				var init = function() { // init() will run, when layout detection yields non-mobile flag

					/*
					Run a loop over the list items' anchors of the drawer nav Element,
					and build an array of thumbnails that are referenced with a 'data'
					attribute. Attach a hover state to each anchor, to swap thumbs when
					the user rolls over each.
					*/
					var director_thumbs = new Array();
					$('#drawer nav ul li a').each( function( index ) {
						director_thumbs.push({ 'src':$(this).data('thumb') });
						if( index === ( $('#drawer nav ul li a').length-1 ) ) {
							$('document').DRAWER_PREVIEW(director_thumbs, "#drawer-preview-container" );
						}
					});
					$('#drawer nav ul li a').hover(
						function() {
							var index = $(this).parent().index();
							$('#director-preview-'+index+'.in-view').removeClass('in-view').stop().fadeOut('fast');
							$('#director-preview-'+index).addClass('in-view').stop().fadeIn('fast');
						},
						function() {
							var index = $(this).parent().index();
							$('#director-preview-'+index).removeClass('in-view').stop().fadeOut('fast');
						}
					);

					setWidths();
					initContentFog();
				},

				unInit = function() {
					$body = (typeof $body === 'undefined') ? $( "body" ): $body;
					$.debounce( 100, hideDrawerMenu() );
					$( ".mainmenu .mainmenu-directors" ).off();
				},

				initContentFog = function() {
					$contentFog = (typeof $contentFog === 'undefined') ? $( "#contentFog" ): $contentFog;
				},

				initScrollContainer = function() {
//					$scrollContainer = (typeof $scrollContainer === 'undefined') ? $( "#bodymask" ): $scrollContainer;
					$scrollContainer = (typeof $scrollContainer === 'undefined') ? $( "body" ): $scrollContainer;
				},

				setWidths = function() {
					$body = (typeof $body === 'undefined') ? $( "body" ): $body;

					// Set the width of primary content container
					// -> content should not scale while animating
					contentWidth       = $body.width();
					contentWidthNav    = $( "nav.main" ).width();
					displacementValue  = ( 1.5 * contentWidthNav );
					initScrollContainer();
				},

				showDrawerMenu = function() {
					setWidths();
					if( parseInt(contentWidth) > 568 ) {
						// Set the content with the width that it has originally
						$body.css( "width", contentWidth ).addClass( "drawer-open" );
						showFog();

						// Disable all scrolling on mobile devices while menu is shown
						$scrollContainer.bind( 'touchmove', function( event ) {
							event.preventDefault();
						});
						$( "#drawer" ).css({'left':-displacementValue, 'min-width':displacementValue}).stop(true,false).animate( {
							"left": '0px' // [ '0px', 'swing']
							}, { duration: 250 }
						);

						// Set margin for the whole container with a jquery UI animation
						var $bodyobjects = $contentFog.add( $body );
						$bodyobjects.stop(true,false).animate( {
							"margin-left": displacementValue, // [ displacementValue, 'swing']
							}, { duration: 250 }
						);

						// Enable the close button on the drawer
						$( "#drawer .close-button" ).on( 'click', function( event ) {
							event.preventDefault();
							hideDrawerMenu();
						});

						$body.on( 'resize', $.debounce( 200, function() {
								contentWidth = $body.width();
								$body.css( "width", "auto" );
							})
						);
					}
				},

				hideDrawerMenu = function() {
					// Enable all scrolling on mobile devices when menu is closed
					$body.unbind( 'touchmove' ).removeClass( "drawer-open" );

/* NOTE: AJAXING - Timing will have to be dynamically adjusted... */
					$( "#drawer" ).animate( {
						"left": '-100%' // [ '-100%', 'swing']
						}, { duration: 250 }
					);

					// Set margin for the whole container with a jquery UI animation
					var $bodyobjects = $contentFog.add( $body ); // QUESTION: Add the top directors menu?
					$bodyobjects.animate({ "margin-left":"0" }, //[ "0", 'swing' ]
						{
						duration: 250,
						complete: function() {
							$( "#body" ).css( "width", "auto" );
						}
					});
					hideFog();
					$( "#drawer .close-button" ).off( 'click' );
				},

				showFog = function() {
					// Display a layer to disable clicking and scrolling on the content while menu is shown
					initContentFog();
					$contentFog.on( 'click', function( event ) {
						event.preventDefault();
						hideDrawerMenu();		// close the sliding menu
					});
					$contentFog
						.css( {"z-index":"801","left":"0px", "margin-left":"0px"})
						.fadeIn('fast', function() {
							$contentFog.css( "display","block" );
						});
				},

				hideFog = function() {
					// Hide a layer that disables clicking and scrolling on the content while menu is shown
					initContentFog();
					$contentFog
						.fadeOut('fast', function() {
							$contentFog.css( {"display":"none","z-index":"3"});
						});
					$contentFog.off();
				};

				return {
					init: init,
					unInit: unInit,
					showDrawerMenu: showDrawerMenu,
					hideDrawerMenu: hideDrawerMenu
				};

			})();

		// -------------------------------------------------------------------------------
		// -------------------------------------------------------------------------------
		/*** SOCIAL MENU ***/
		/*** IN SOCIAL MENU, TOGGLE THE LATEST TWEET ***/
			var LATESTTWEET = (function() {
				var init = function() {
					var $latesttweet = $( ".latesttweet" );
						$latesttweet.hide().removeClass( "hidden" );

					$( "nav.social" ).on( {
						mouseleave: function() {
							$latesttweet.stop().fadeOut();
						}
					});
					$( "nav.social .icon-twitter" ).on( {
						mouseenter: function() {
							$latesttweet.stop().fadeIn();
						}
					});
				};

				return {
					init: init
				};
			})();

			if( $( "body.theme-dark .latesttweet" ) !== "undefined" ) {
				LATESTTWEET.init();
			}

			// Init #bodymask
			if( typeof $( "#bodymask" ) === 'undefined' ) {
				$( "#body" ).wrap( '<div id="bodymask"></div>' );
			}


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

		/*** MODULE: .videoblock ***/
			var VIDEO = (function() {

				var $allVideoBlocks,
					$activeVideo,
					$activeVideoWrapper,
					$activeVideoKeyframe,
					$activeVideoFrame,
					$activeVideoPlayer;

				var initVideoBlock = function ( $videoBlock ) {
					clearActiveVideo(); // Remove active label from others
					makeActiveVideo( $videoBlock ); // Make this video active
				},

				initCaptions = function ( ) {
					var $videoblocks = $( ".videoblock" );
					var $myvideoblocks = $( ".my-videoblock" );
					var $imageblocks = $( ".imageblock:has(a)" );

					var $blocks = $videoblocks.add( $imageblocks );
                                        
                                        
					// if( !$('body').hasClass('mobile') ) {
						$blocks.hover(
							function () {
								showCaption( $(this) );
							},
							function () {
								hideCaption( $(this) );
							}
						);
					// }
                                        
					var $blocks = $myvideoblocks.add( $imageblocks );
                                        
                                        
					// if( !$('body').hasClass('mobile') ) {
						$blocks.hover(
							function () {
								showCaption( $(this) );
							},
							function () {
								hideCaption( $(this) );
							}
						);
					// }
				},

				showCaption = function( $this ) {
					// showCaption takes a .videoblock element
					var _animateSpeed = 0;
					if( !$('body').hasClass('mobile') ) {
						if( !($this.hasClass( "is-active" )) ) { // only if not active

							_animateSpeed = 400;

							// Show play button
							$this.find( "i" ).addClass('is-visible');

							// Zoom keyframe image
							$this.find( ".keyframe" ).find( "img" ) // $this.find( ".keyframe > img" )
								.stop( true, false )
								.addClass( "is-zoomed" );
						}
						$this.find( ".caption" )
							.stop(true, false)
							.addClass('show')
							.animate( { top: [ "0", 'swing' ] }, 0, function() {} ) // prevents the caption from climbing up
							.animate( { top: [ "-=25px", 'swing' ] }, _animateSpeed, function() {} );
						}
				},

				hideCaption = function( $this ) {
					if( !$('body').hasClass('mobile') ) {
						// Hide play button
						$this.find( "i.is-visible" ).removeClass('is-visible');

						// Reset caption
						$this.find( ".caption" )
							.stop(true, false)
							.animate( { top: [ "0", 'swing' ] }, 300, function() {} );

						// Reset keyframe image
						$this.find( ".keyframe").find( "img" ) // $this.find( ".keyframe > img" )
							.stop(true, false)
							.removeClass('show')
							.removeClass( "is-zoomed" );
					}
				},

				showTitle = function( $this ) {

					if( !$('body').hasClass('mobile') ) {
						var $captionTitle = $this.find( ".caption" );
						$captionTitle.stop(true, false).addClass( "is-shown" );
					}

				},

				hideTitle = function( $this ) {

					if( !$('body').hasClass('mobile') ) {
						var $captionTitle = $this.find( ".caption" );
						$captionTitle.stop(true, false).removeClass( "is-shown" );
					}

				},

				showSocial = function( $this ) {

					if( !$('body').hasClass('mobile') ) {
						var $captionSocial = $this.find( ".social" );
						$captionSocial.stop(true, false).addClass( "is-shown" );
					}
				},

				hideSocial = function( $this ) {

					if( !$('body').hasClass('mobile') ) {
						var $captionSocial = $this.find( ".social" );
						$captionSocial.stop(true, false).removeClass( "is-shown" );
					}
				},

				startPlayingVideo = function ( $videoBlock ) {
					console.log( "startPlayingVideo" );

					// Takes as input the jQuery object of a .videoblock
					if( !$allVideoBlocks ) { $allVideoBlocks = $( ".videoblock" ); }

					initVideoBlock( $videoBlock ); // set active/visible

					// Load and start playing $activeVideo
					if( $videoBlock.data('type') === "VIDEO" ) {
						
						/*
						$videoBlock.css({
							'min-height': $videoBlock.find('.keyframe').height()+'px',
							'min-width': $videoBlock.find('.keyframe').width()+'px',
						});
						$activeVideo.find( ".videowrapper").css({
							'min-height': $videoBlock.find('.keyframe').height()+'px',
							'min-width': $videoBlock.find('.keyframe').width()+'px',
						});
						*/
						
						createVimeoFrame();
						$activeVideoFrame = $activeVideo.find( ".videowrapper > iframe" );

						// According to http://developer.vimeo.com/player/js-api
						// Froogaloop should return "ready"
						setTimeout( function () {
							$activeVideoFrame.load( function() {
								// use Froogaloop to watch current video
								// When the player is ready, add listener for finish
								var player = Froogaloop( this );
								player.addEvent( "ready", bindFinish);
							});
						}, 100 );

					}
						// Add a very short delay
						// Show/hide caption with css hover & z-index or bind mouseover to show caption

						/* NOTE:
						// currently using z-index 58, 59, 60 because
						// of strange interactions with right-size reel menu
						*/

						/*	QUESTION:
						//	what should happen if a video has already been
						//	played some, and then is played again?
						//	finished? unfinished? continue?
						*/
				},

				clearActiveVideo = function () {
					console.log('clearActiveVideo');
					// Remove active label from any other videos
					$activeVideo = $( ".videoblock.is-active" );
					if( $activeVideo.length > 0 ) { // NOTE: this needs a better conditional statement than if($activeVideo)
						$activeVideo.find( ".placeholder" ).css( "visibility", "visible" ); // do this in css
						$activeVideo.find( ".videowrapper > iframe" ).css( "visibility", "hidden" ); // do this in css
						$activeVideo.find( ".videowrapper" ).empty( ).hide();
						$activeVideo.find( ".icon-play" ).css( "visibility", "visible" );
						$activeVideo.find( ".keyframe" ).css( "z-index", 58 );
						$activeVideo.removeClass( "is-active" );
					}

					hideSocial( $activeVideo );

				},

				makeActiveVideo = function ( videoBlock ) {
					console.log('makeActiveVideo');
					$activeVideo = videoBlock;

					if ( $activeVideo.hasClass('imageblock') ) {
						// alert("Don't makeActiveVideo()");
						console.log("Video playback stopped because this video is a link.");
						return false;
					}

					if( $activeVideo.data('type') === "VIDEO" ) {
						$activeVideo.addClass( "is-active" );
						// alert( "makeActiveVideo: " + $activeVideo.data('type') );
						$activeVideoWrapper = $activeVideo.find( ".videowrapper" );
						$activeVideoKeyframe = $activeVideo.find( ".keyframe" );
						$activeVideoKeyframe.css( "z-index", -1 );
						// $activeVideoKeyframe.css( "visibility", "hidden" ); // please do this in css instead
						$activeVideo.find( ".icon-play" ).css( "visibility", "hidden" );
						$activeVideo.hover(
							function() {
								showSocial( $(this) );
							},
							function() {
								hideSocial( $(this) );
							}
						);
					}

				},

				createVimeoFrame = function ( ) {
					// Important are ?api=1 and ?player_id={ID of IFRAME}
					var _vimeoId = getVimeoId();
					console.log( "Play the video with id: " + _vimeoId );

					url_hash_set( 'video-'+_vimeoId );

					var _videoSrcOptions = "";
						_videoSrcOptions += 'api=1' + '&amp;player_id=player_' + _vimeoId;
						_videoSrcOptions += '&amp;autoplay=1';
						_videoSrcOptions += '&amp;badge=0' + '&amp;portrait=0' + '&amp;title=0' + '&amp;byline=0';
					var _videoHTML = '<iframe class="videoplayer" id="player_'+ _vimeoId;
						_videoHTML += '" src=\"\/\/player.vimeo.com\/video\/' + _vimeoId +'?'+ _videoSrcOptions;
						_videoHTML += '\" width=\"100%\" height=\"auto\" frameborder=\"0\" title=\"Test\" webkitallowfullscreen mozallowfullscreen allowfullscreen><\/iframe>';
					if( $activeVideo.data('type') === "VIDEO" ) {
						$activeVideoWrapper.show();
						$activeVideoWrapper.html( _videoHTML );
					}
				},

				getVimeoId = function ( ) {
					var _vimeoId = ($activeVideoWrapper.data( "vimeoId" )) ? $activeVideoWrapper.data( "vimeoId" ) : $activeVideoWrapper.data( "vimeo" ); // get the vimeo ID
						// this needs parameters
						//	if(!isInt( _videoId )) {
						//		var _defaultVideoId = '63689570';
						//		_videoId = _defaultVideoId;
						//	}
						console.log( $activeVideo.find( ".videowrapper" ).data( "vimeo" ) );
					if( ( typeof _vimeoId !== 'undefined' ) && isInt( _vimeoId ) ) {
						return _vimeoId;
					}
				},

				bindFinish = function ( playerId ) {
					// Do things when iframe is done loading
					// Should these Froogaloop( playerId ) all be an object first?
					var $player = Froogaloop( playerId );
					$player.addEvent( 'pause', onPause );
					$player.addEvent( 'finish', onFinish );
					$player.addEvent( 'play', onPlay );
					$player.api( 'play' );

							// if this is mobile, start playing the video
							// console.log( "Video should start playing on mobile now." );
							// According to Vimeo support forums, iPad cannot be made to play without a user interaction
							// the Froogaloop javascript can't override that.
							// even if autoplay is in the URL, like it is.
							// player.play(); // this isn't necessary since the videos already have Autoplay set

				},

				onPause = function ( ) {

					/* Make this so it works after AJAX loading, too */

					console.log( "Video Paused." );
					if( !$('body').hasClass('mobile') ) {
						showTitle( $activeVideo );
						// showSocial( $activeVideo );
					}

				},

				onPlay = function ( ) {

					console.log( "Video Playing." );
					if( !$('body').hasClass('mobile') ) {
						hideTitle( $activeVideo );
						// hideSocial( $activeVideo );
					}
				},

				onFinish = function ( ) {
					console.log( "Video Finished." );
					if( $('body').hasClass('full-screen') ) {
						_FULLSCREEN = false;
						$('body').removeClass('full-screen');
						clearFullscreen();
						initCaptions();
						/*
						Exiting out of full screen is making our scrollTo actions and .caption areas get thrown
						*/
						if( $('#DIRECTORS').hasClass('ACTIVE-CONTENT') ) {
						//	$.debounce( 750, playNext() );
						}
						return false;
					} else {
						_FULLSCREEN = false;
						$('body').removeClass('full-screen');
						if( $('#DIRECTORS').hasClass('ACTIVE-CONTENT') ) {
							// keep playing through, on loop
							playNext();
						}
					}
				},

				clearFullscreen = function ( ) {
					// cf. http://www.sitepoint.com/use-html5-full-screen-api/
					// exit full-screen
					if (document.exitFullscreen) {
						document.exitFullscreen();
						return false;
					} else if (document.webkitExitFullscreen) {
						document.webkitExitFullscreen();
						return false;
					} else if (document.mozCancelFullScreen) {
						document.mozCancelFullScreen();
						return false;
					} else if (document.msExitFullscreen) {
						document.msExitFullscreen();
						return false;
					}
					return true;
				},

				playNext = function ( ) {
					// get the index of this videoblock
					// get the total number of videoblocks
					// get the id of the next videoblock
					// scroll to & play it
					// if this is the last videoblock,
					// scroll to & play it play the first.
					// alert ("Play the next video.");

					if( typeof $allVideoBlocks === "undefined" ) {
						$allVideoBlocks = $( ".videoblock" );
					}

					var _videoBlockIndex = parseInt( $allVideoBlocks.index($activeVideo) );
//-					console.log( 'That was video # ' + _videoBlockIndex + ' of '+ $allVideoBlocks.length  +' videos.' );
					if( _videoBlockIndex < ($allVideoBlocks.length - 1 ) ) {
						_videoBlockIndex = _videoBlockIndex + 1;
					} else {
						_videoBlockIndex = 0;
					}
					var $nextVideoBlock = $allVideoBlocks.eq( _videoBlockIndex );

					// VIDEO.startPlayingVideo( $nextVideoBlock );
//					REEL_VIDEOS.scrollToVideo( $scrollContainer, $nextVideoBlock );
					REEL_VIDEOS.scrollToVideo( $nextVideoBlock );
					

				},

				/*** PLAY A VIDEO WHEN CLICKED ***/
				initVideoPlayback = function () {
					$( ".videoblock" ).on( "click", function ( event ) {
						event.stopPropagation();
						$('body').removeClass('full-screen');
						if( !$(this).hasClass( "is-active" ) ) {
							startPlayingVideo( $(this) );
						}
					});
				};

				return {
					startPlayingVideo: startPlayingVideo,
					clearActiveVideo: clearActiveVideo,
					initCaptions: initCaptions,
					hideCaption: hideCaption,
					showCaption: showCaption,
					hideSocial: hideSocial,
					showSocial: showSocial,
					initVideoPlayback: initVideoPlayback
				};
			})();


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

		/*** INTERACTION ON MENU OF VIDEOS IN A REEL ***/
			/*
			// MENU : right side director reel video //
			1. initial load to anchor of first video
			2. rollovers on small placeholders -> small placeholder image
			3. animation onclick
			4. trigger area of placeholder
			*/
			var REEL_MENU = (function() {

				var $reelMenu,
					$reelNav,
					_hoverClass,
					$previewParent,
					_REEL_HTML;

				var init = function () {
					/*
					console.log( $('#VIDEOREEL').length );
					$('#DIRECTORS .directors-reel .videoblock').each( function(index) {

						var _V_ID = $(this).attr('id');
						var _R_ID = $(this).data('reelId');
						var _R_TYPE = $(this).data('type');
						console.log('Videoblock: ' + _R_ID );

					});
					*/

					_REEL_HTML = $('#VIDEOREEL').html();
					$('#reel-navigation-thumbs').empty();
					$('#reel-navigation-thumbs').append(_REEL_HTML);


					// If the menu exists...
					if( typeof $reelMenu  === 'undefined' ) {
						$reelMenu = $( "nav.reel > ul > li" );
					}
					_hoverClass = hoverClass();
					$( "nav.reel li.is-active i" ).addClass( "icon-play" );
					$( "nav.reel li > i" ).hover(
						function() {
							showReelPreview( $(this) );
						},
						function() {
							hideReelPreview( $(this) );
						}
					);
					// end if the menu exivts

					/*
					this might be unnecessary; enable it if the reel menu seems too high or too low
					*/
					// positionReelMenu();
				},

				addPlayButton = function( $this ) {
					REEL_MENU.clearPlayButton();

					// Add a play button
					$this.addClass( "icon-play" );

					// Removing "is-hover" hides the thumbnail
					$this.parent().removeClass( "is-hover" ).addClass( "is-active" );

				},

				clearPlayButton = function() {

					// Define the menu object
					if( typeof $reelNav === 'undefined' ) {
						$reelNav = $( "nav.reel > ul" );
					}
					$reelNav.find( "li.is-active > i" ).removeClass( "icon-play" );
					$reelNav.find( "li.is-active" ).removeClass( "is-active" );
				},

				addHighlight = function( _reelIndex ) {

					// Verify _reelIndex is an integer
					if( !isInt( _reelIndex ) ) { return; }
					//init();

					// Verify _reelIndex < size of _reelMenu
					if( _reelIndex > $reelMenu.size() ) { return; }
					var $reelItem = $reelMenu.eq( _reelIndex ); // the correct menu item

					_hoverClass = hoverClass();
					$reelMenu.find( "i.icon." + _hoverClass ).removeClass( _hoverClass );
					$reelItem.find( "i.icon" ).addClass( _hoverClass );

				},

				removeHighlight = function( _reelIndex ) {

					// Verify _reelIndex is an integer
					if( !isInt( _reelIndex ) ) { return; }
					//init();

					// Verify _reelIndex < size of _reelMenu
					if( _reelIndex > $reelMenu.size() ) { return; }

					// The correct menu item
					var $reelMenuItem = $reelMenu.eq( _reelIndex);

					// Remove hover class
					_hoverClass = hoverClass();
					$reelMenuItem.find( "i.icon." + _hoverClass ).removeClass( _hoverClass );

				},

				reelIndex = function( $this ) {
					init();
					return $reelMenu.index( $this.parent() ); // which thumbnail is this?
				},

				validateReel = function ( ) {

					// VALIDATION about size of reel
					// need some checks on the valid numbers
					// if reelIndex = 0, that is the BIO

					init();

					var _sizeofReel = $reelMenu.length; // how many thumbnails are there?
					var _sizeofReelVideos = $( "div.videoblock").length; // how many videos are there?
					if( _sizeofReel !== _sizeofReelVideos ) {
						console.log( "sizeofReel: "+ _sizeofReel + " && sizeofReelVideos: "+ _sizeofReelVideos );
					}
					// DEBUG
					// else { console.log( "This is image number: "+ _reelIndex +" of "+ _sizeofReel ); }
				},

				positionReelMenu = function () {
					REEL_VIDEOS.initTopMargin();
					$( "nav.reel" ).css( "margin-top", _topMargin + "px" );
				},

				hoverClass = function () {
					if( typeof _hoverClass === 'undefined' ) {
						_hoverClass = "is-hover";
					}
					return _hoverClass;
				},

				showReelPreview = function ( $preview ) {
					_hoverClass = hoverClass();
					$previewParent = $preview.parent();
					$previewParent.addClass( _hoverClass );

					// Resize and fade in
					$previewParent.find(" .reel-preview ")
						.stop(true, false).addClass( "is-shown" );
						// .stop(true, false).animate( { top: "-34px", opacity: 1.0, width: "160", height: "90" }, 300 );
					$previewParent.find(" .title ")
						// .stop(true, false).delay(150).addClass( "is-shown" );
						.stop(true, false).delay(150).animate( { opacity: 1.0 }, 300 ); // right: "180px", 
				},

				hideReelPreview = function ( $preview ) {
					_hoverClass = hoverClass();
					$previewParent = $preview.parent();
					$previewParent.removeClass( _hoverClass );
					// Fade out
					$previewParent.find(" .reel-preview ")
						.stop(true, false).removeClass( "is-shown" );
						// .stop(true, false).animate( { opacity: 0, width: "40", height: "22.5" }, 150 );
					$previewParent.find(" .title ")
						.stop(true, false).animate( { right: "0", opacity: 0 }, 0 );
				};

				return {
					positionReelMenu: positionReelMenu,
					validateReel: validateReel,
					reelIndex: reelIndex,
					removeHighlight: removeHighlight,
					addHighlight: addHighlight,
					addPlayButton: addPlayButton,
					clearPlayButton: clearPlayButton,
					showReelPreview: showReelPreview,
					hideReelPreview: hideReelPreview,
					init: init
				};

			})();


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

			/*** VIDEOS ON A PAGE THAT HAS A DIRECTOR'S REEL */
			/*
			// TO DO:
			PULL OUT VIDEO PLAYBACK METHODS INTO A VIDEO CLASS
			SO THEY WORK ON THE HOME PAGE AND OTHER PAGES THAT
			DON'T HAVE VIDEO-TO-VIDEO PLAYBACK
			*/
			var REEL_VIDEOS = (function() {

				var _paddingNeeded;
				var scrollToVideo = function( $scrollToElement ) {
					
					// SCROLLTOP
					_coordinate =($scrollToElement.offset().top)-85;  // 85px offset to account for fixed headers
					$('html, body').animate({
						scrollTop: _coordinate
					}, 500);
					
					setTimeout(function () {
						VIDEO.startPlayingVideo( $scrollToElement );
					}, 800); // add a short delay
					
					
					var _reelIndex = $scrollToElement.index();
					var $correspondingMenu = $( "nav.reel li > i").eq( _reelIndex );
					REEL_MENU.addPlayButton( $correspondingMenu );
					
				},	
				scrollToVideo_NIXED = function( $scrollContainer, $videoElement ) {
					
					// $scrollContainer = body element that scrolls; default = $('#body')
					// $videoContainer = container of all video blocks
					// $videoElement = video block to scroll to
					// var $scrollContainer = $( "#body" );
					// var $videoContainer = $( ".directors-reel" );

					initTopMargin();
					$scrollContainer.scrollTo(
						$videoElement,
						{ offsetTop : _topMargin },
						function() {
							setTimeout(function () {
								VIDEO.startPlayingVideo( $videoElement );
							}, 800); // add a short delay
						}
					);

					// Add the play button to the corresponding menu item
					var _reelIndex = $videoElement.index();
					var $correspondingMenu = $( "nav.reel li > i").eq( _reelIndex );
					REEL_MENU.addPlayButton( $correspondingMenu );

					/*
					// IMPROVEMENT:
					make the scroll speed even
					you are at the first one
					and you want to scroll to the 10th

					_currentReelIndex = 0
					_scrollToReelIndex = 9
					var _currentReelIndex = reelIndex( $(this) );
					_scrollTime =  jQuery.abs( _scrollToReelIndex - _currentReelIndex ) * _scrollSpeed
					*/
				},

				addPaddingBelowLastVideo = function() {

					// In order to align the last video to the top of the playing window,
					// this measures the height of a video being shown and adds
					// padding to the bottom of the element that contains the videos.

					var $container = $( "#page-directors .directors-reel" );
					var $videoElement = $( ".videoblock").eq(0);
					_paddingNeeded = $(window).innerHeight() - $videoElement.innerHeight(); // Padding below last video

					// DEBUG
					/*
					console.log( '$(window).innerHeight() = ' + $(window).innerHeight() );
					console.log( '$videoElement.innerHeight() = ' + $videoElement.innerHeight() );
					console.log( "You will need " + _paddingNeeded + " pixels of padding.");
					*/
					$container.css( "padding-bottom" , _paddingNeeded );
				},

				reelIndex = function( $this ) {
					return $this.index( $this.parent() ); // which video is this?
				},

				labelVideoIds = function () {
					$( ".directors-reel .videoblock" ).each( function() {
						var _reelIndex = reelIndex( $(this) );
						$(this).addClass( "videoblock-" + _reelIndex );
					});
				},

				initTopMargin = function () {
					// DEBUG
					// console.log( "css = " + $( ".menu-director" ).css( "height" ) );
					// console.log( "height = " + $( ".menu-director" ).height() );
					/*
					var _newTopMargin = $( ".menu-director" ).css( "height" );
					if(_topMargin !== _newTopMargin) {
						_topMargin = _newTopMargin;
//						$( "article.directors-reel" ).css( "padding-top", _topMargin );
					}
					*/
				},

				initShareMenu = function () {
					// Link icon in sharing menu
					$( ".menu-share" ).on( "click", ".icon-link", function ( event ) {
						event.preventDefault();

						// var $linkicon = $( this );
						var $linkblock = $(this).closest( "li" );

						if( !$linkblock.hasClass( "is-open" ) ) {
							$linkblock.addClass( "is-open" );
							var $permalink = $linkblock.find( ".permalink" );
							$permalink.removeClass( "hidden" ).show().selectText();
							// selectText( 'permalink' );
							$permalink.selectText();
						} else {
							$linkblock.removeClass( "is-open" );
							$linkblock.find( ".permalink" ).addClass( "hidden" ).hide();
						}
					});
				},

				init = function () {
					// labelVideoIds();
					initTopMargin();
//					initShareMenu();
				};

				return {
					init: init,
					initTopMargin: initTopMargin,
//					initShareMenu: initShareMenu,
					addPaddingBelowLastVideo: addPaddingBelowLastVideo,
					reelIndex: reelIndex,
					scrollToVideo: scrollToVideo,
					labelVideoIds: labelVideoIds
				};

			})();

// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

		/*** DIRECTOR PAGE ***/
		/*** TOP MENU: bio ***/
			var MENU_DIRECTOR = ( function() {

				var clearBioButton = function() {
					$( ".menu-director-bio.is-active" ).removeClass( "is-active is-expanded" );
				},

				highlightBioButton = function() {
					$( ".menu-director-bio" ).addClass( "is-active is-expanded" );
				},

				showBio = function () {
					var _tempOffset = $('.director-bio article.accordion-block').outerHeight();
						$('.director-bio').stop(true,false).animate( {
							"height": _tempOffset+'px',
							"marginBottom": '40px'
							}, { duration: 250 }
						);
				},

				clearBio = function () {
					console.log('clear bio');
					MENU_DIRECTOR.clearBioButton();
//					if( $(body).hasClass('mobile') ) {
					$('.director-bio').stop(true,false).animate( {
						"height": '0px',
						"marginBottom": '0px'
						}, { duration: 250 }
					);
//					}
				},

				initBioButton = function () {

					$( ".menu-director-bio" ).on( "click", function ( event ) {
						event.preventDefault();
						if( !$(this).hasClass('is-active') ) {
							
							url_hash_set( 'biography' );
							VIDEO.clearActiveVideo();
							REEL_MENU.clearPlayButton();
							
							MENU_DIRECTOR.highlightBioButton();
							MENU_DIRECTOR.showBio();
							
							var $scrollTarget = $( ".director-bio" );
							var $scrollContainer = $( "body" );
							/*
							Highlighting and playNext removed...
							*/
							
							// SCROLLTOP
							_coordinate =($scrollTarget.offset().top)-85;  // 85px offset to account for fixed headers
							$('html, body').animate({
								scrollTop: _coordinate
							}, 500);
						} else {
							if( $('body').scrollTop() == 0 ) {
								clearBio();
								MENU_DIRECTOR.clearBioButton();
								MENU_DIRECTOR.clearBio();
							} else {
								$('html, body').animate({
									scrollTop: _coordinate
								}, 500);
							}
						}
					});
				};

				return {
					clearBioButton: clearBioButton,
					highlightBioButton: highlightBioButton,
					showBio: showBio,
					clearBio: clearBio,
					initBioButton: initBioButton
				};

			})();



// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
		/*** DIRECTOR PAGE : MENU : nav.reel ***/
			var DIRECTOR_LOAD = function() {

				var init = function() {
					if( $body.hasClass( "DIRECTORS" ) ) {

						/*
						Reference the $HASH variable from script startup, and if it has a value,
						match it up with a video in the reel. If there is a result, ScrollToVideo
						and begin playback.
						*/
						var VIDEO_SELECT = null;
						VIDEO_SELECT = ( $HASH ) ? $( ".directors-reel" ).find( ".videoblock[data-reel-id='"+$HASH+"']" ) : null;
							if( $(VIDEO_SELECT) && ( $(VIDEO_SELECT).length === 1 ) ) {
								$HASH = null;
								$nextVideoBlock = $(VIDEO_SELECT);
								
								setTimeout(function () {
//									REEL_VIDEOS.scrollToVideo( $( "#bodymask" ), $(VIDEO_SELECT) );
//									REEL_VIDEOS.scrollToVideo( $( "body" ), $(VIDEO_SELECT) );
									REEL_VIDEOS.scrollToVideo( $nextVideoBlock );
									VIDEO_SELECT = null;
								}, 100);
							}
					}

					$( ".directors-reel" ).on( "click", ".videoblock", function ( event) {
						event.stopPropagation();
						// If the video isn't playing already, scroll to this video and play the video
						if( !$(this).hasClass( "is-active" ) ) {
							$('body').removeClass('full-screen');
//							REEL_VIDEOS.scrollToVideo( $( "#body" ), $(this) );
							$nextVideoBlock = $(this);
							REEL_VIDEOS.scrollToVideo( $nextVideoBlock );
						}
					});

					$( "nav.reel" ).on( "click", "li > i", function( event ) {
							event.stopPropagation();
							/*
							Clicking on an elementin the REEL navigation will set a hash-tag marker
							in the URL for sharing; that marker is sniffed and immediately on load.
							*/
							var _parentLi = $(this).parent('li');
							var _newHash  = $( _parentLi ).data('reelId');
							url_hash_set( _newHash );
							
							$('body').removeClass('full-screen');
							
							REEL_MENU.clearPlayButton();			// clear everything else
							REEL_MENU.addPlayButton( $(this) );		// add a play button and hide hover image
							REEL_MENU.hideReelPreview( $(this) );



							/*** SCROLLING ***/
						//	var _reelIndex = REEL_MENU.reelIndex( $(this) );
						//	console.log( '_reelIndex: ' + _reelIndex );

							REEL_MENU.validateReel();

							//	var $correspondingVideo = $( ".videoblock" ).eq( _reelIndex ); // make a jQuery object for the video we want to scroll to
							$correspondingVideo = $( ".videoblock[data-reel-id='"+_newHash+"']" ); // make a jQuery object for the video we want to scroll to

							if( $(this).parent('li').data('type') === 'VIDEO' ) { }
							// scroll so the top of $correspondingVideo
							// lines up with the top of the visible area
							// of the directors page

//							$scrollContainer = $( "#bodymask" ); // The layout element that actually scrolls
							$scrollContainer = $( "body" ); // The layout element that actually scrolls
							// var $videoContainer = $( ".directors-reel" ); // The element that contains the sequence of videos

//							REEL_VIDEOS.scrollToVideo( $scrollContainer, $correspondingVideo );
							$nextVideoBlock = $correspondingVideo;
							REEL_VIDEOS.scrollToVideo( $nextVideoBlock );
							
							REEL_VIDEOS.addPaddingBelowLastVideo();
							// start playing the $correspondingVideo
					});

				/*** HIGHLIGHT REEL icons while scrolling ***/
				// Using jQuery.inview
				// https://github.com/protonet/jquery.inview
				// Make sure we are on a directors page ... [TK]
					$( ".videoblock" ).bind( "inview", function( event, isInView, visiblePartX, visiblePartY ) {
//						console.log( "Video : " + $(this).index() + " Y="+visiblePartYCoord );
						if( isInView ) { // Element is now visible in the viewport

							if( visiblePartY === "top" && ( $(this).index() === 0) ) { // top part of element is visible
//								console.log( "A video is in view: " + $(this).index() + " Y="+visiblePartYCoord );
								REEL_MENU.addHighlight( $(this).index() );
							} else if( visiblePartY === "both" ) { // top part of element is visible
//								console.log( "A video is in view: " + $(this).index() + " Y="+visiblePartYCoord );
								REEL_MENU.addHighlight( $(this).index() );

							} else if( visiblePartY === "top" ) { // top part of element is visible

							} else if( visiblePartY === "bottom" ) { // bottom part of element is visible

							} else { // Whole part of element is visible

							}
						} else { // Element has gone out of viewport
							REEL_MENU.removeHighlight( $(this).index() );
						}
					});
				};

				return {
					init: init
				};
			}();


		/*** DIRECTOR PAGE ***/
		/*** TOP MENU: sharing ***/
			// Show icons in sharing menu
			$( ".menu-director" ).hover( function () {

				$( ".menu-director-share" ).hover(
					function ( ) {
						$( this ).addClass( "is-open" );
						// Updated and replaced with CSS that will always show the icons for the sharing menu
						// $( this ).find( ".menu-share" ).removeClass( "hidden" ).show().css( "display", "inline-block" );

//						var $scrollContainer = $( '#bodymask' );
						var $scrollContainer = $( 'body' );

						// Question: How expensive is this?
						$scrollContainer.scroll(
							$.throttle( 150, function () {
								// MENU_DIRECTOR.clearBioButton();
								//		$( ".menu-director-share .menu-share .permalink" ).hide();
								//		$( ".menu-director-share .menu-share" ).fadeOut();
								//		$( ".menu-director-share.is-open" ).removeClass( "is-open" );
								$( ".menu-share .item-link.is-open .permalink" ).fadeOut('slow', function() {
									$( ".menu-share .item-link.is-open" ).removeClass( "is-open" );
								});
								$( this ).unbind( "scroll" );
							})
						);
					}, function ( ) {
						// DEBUG:
						// Temporarily hide this to see the CSS of the thing
						// $( ".menu-director .share-link > .permalink" ).hide();
						// $( ".menu-director .menu-share" ).fadeOut();
					}
				);

			}, function () {

				// Unnecessary? Just here to make sure BIO stays
				// lit up (white) after mouseOff

				// Need some kind of buffer. If you leave the hover
				// While scrollng is happening (ie: from the .reel-menu)
				// the color goes off.

//				var $scrollContainer = $( "#bodymask" );
				var $scrollContainer = $( "body" );
				$scrollContainer.scroll(
					$.throttle( 100, function () {
					//	MENU_DIRECTOR.clearBio();
						$( this ).unbind( "scroll" );
					})
				);
			});

			$( ".share-link" ).click( function ( event ) {
				event.preventDefault();
			});


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------

		/*** RESPONSIVE MENU ***/
			var MENU_MOBILE = ( function() {

				var expandedClass = 'is-expanded';
				var $menuMobile = $('nav.menu-mobile');
				var init = function() {
//-					console.log( 'MENU_MOBILE.init' );

					if( typeof $menuMobile === 'undefined' ) {
						$menuMobile = $('nav.menu-mobile');
					}

					$menuMobile.removeClass( expandedClass ).on('click', function(){
						$(this).toggleClass( expandedClass );
					});
//-					console.log( "Mobile menu should be active" );
				},

				unInit = function() {
//-					console.log( 'MENU_MOBILE.unInit' );

					if( typeof $menuMobile === 'undefined' ) {
						$menuMobile = $('nav.menu-mobile');
					}

					$menuMobile.off();
//-					console.log( "Mobile menu should be inactive" );
				};

				return {
					init: init,
					unInit: unInit
				};

			})();

			var ACCORDION = ( function() {

				var $CONTEXT;
				// var $accordionBlocks;
				var init = function() {
//-					console.log( 'ACCORDION.init' );

					// TEMPLATE-2 Specific / Contact Page
					$( "body article.accordion.template-2 .textblock" ).each( function() {
						$CONTEXT = ( $(this).children( "h1:first-child" ) ) ? $(this).children( "h1:first-child" ) : null;
						$CONTEXT = ( $CONTEXT ) ? $CONTEXT : $(this).children( "h2:first-child" );
						if( $CONTEXT) {
							$( $CONTEXT ).addClass('accordion-header');
							if( !$(this).hasClass('js-altered') ) {
								$(this).addClass('js-altered');
								$( $CONTEXT ).nextUntil(".textblock").wrapAll('<div class="accordion-block" />');
							}
						}
					});

					$( ".accordion-header" ).on( 'click', function( event ) {
						event.preventDefault();
						$(this).parent().toggleClass( "is-expanded" );
					});

				},
				unInit = function() {
//-					console.log( 'ACCORDION.unInit' );
					$( ".accordion-header" ).off( 'click' ); // turn off mouseclicks on .accordion-header
					$( ".accordion-block" ).parent( ".is-expanded" ).removeClass( "is-expanded" );
				};
				return {
					init: init,
					unInit: unInit
				};
			})();

		/*** RESPONSIVE LISTENER ***/
			function setAsMobile() {

				isMobile = true;
				$body.addClass( "mobile" );
				MENU_MOBILE.init();
				ACCORDION.init();
				
				// MENU_DIRECTOR.clearBio();
				
				$( ".menu-mobile.is-expanded" ).removeClass( "is-expanded" );
				if( $body.hasClass( "drawer-open") ) {
					DRAWER.unInit(); // disable drawer
				}

				// DEBUG
				// $( "#body" ).hide().show(); // trigger a display reload

				/* LIST */
				/* ACCORDION CONTENT */
				// Enable accordion areas
			}

			function setAsNotMobile() {
				isMobile = false;

				$( "body.mobile" ).removeClass( "mobile" );

				// DEBUG
				$( "#body" ).hide().show(); // trigger a display reload

				// DIRECTORS PAGE
				// MENU_DIRECTOR.clearBio();
				MENU_DIRECTOR.initBioButton();

				/* LIST */
				if( typeof MENU_MOBILE !== 'undefined' ) {
					MENU_MOBILE.unInit();
				}
				if( typeof ACCORDION !== 'undefined' ) {
					ACCORDION.unInit();
				}
				DRAWER.init(); // enable drawer

				// Disable accordion areas
				// Enable the close button on the drawer
				/* This should happen when a Director page loads or resizes */
				if( typeof REEL_VIDEOS !== 'undefined' ) {
					REEL_VIDEOS.addPaddingBelowLastVideo();
				}
			}

			function checkMobile() {

				// var _mobileBreakpoint = 540;
				var _mobileBreakpoint = 568;
				var _pageWidth = $body.width();

				// If this page is narrow
				if( parseInt( _pageWidth ) <= parseInt( _mobileBreakpoint ) ) {
//-					console.log( 'The page is mobile width.' );

					// If this was already mobile, do nothing. Otherwise set the page as mobile
					if( isMobile !== 'true' ) {
//-						console.log( "This page is narrow: " + _pageWidth + " px" );
						setAsMobile();
						return;
					}
				}

				// If this page is wide
				else {
//-					console.log( 'The page is not mobile width: ' + _pageWidth + ' px' );
					// If this was already not mobile, do nothing. Otherwise set the page as not mobile
					setAsNotMobile();
				}
			}


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
		/*** ON RESIZE ***/
			$(window).resize( $.throttle( 100, function() {
				// Handle your resize only once total, after a 200 millisecond calm.
				if( typeof $body === 'undefined' ) {
					$body = $( "body" );
				}
				$body.css( "width", "auto" );

				// $.debounce( 100, checkMobile() )
				checkMobile();
				if( $body.hasClass( "drawer-open" ) ) {
					DRAWER.hideDrawerMenu();
				}
			}));


		/***  ON PAGE LOAD ***/
			checkMobile();


		/*** OVERALL: visual effects ***/
			// init main menu
			VIDEO.initCaptions();
//			REEL_VIDEOS.initShareMenu();

		/*** SPECIFIC TYPES OF PAGE ***/
			// If this is a page with a video reel:
			if( $body.hasClass( "DIRECTORS" ) ) {
				REEL_VIDEOS.init();
				REEL_MENU.init();
				DIRECTOR_LOAD.init();

				/* This should happen when a Director page loads or resizes */
				if( typeof REEL_VIDEOS !== 'undefined' ) {
					REEL_VIDEOS.addPaddingBelowLastVideo();
				}
			} else { // Not a Director:
				VIDEO.initVideoPlayback();
			}


			/* Hover on thumbnails in News index */
			/* Make this so it works after AJAX loading, too */
			(function() {

				var $thumbs = $("#POSTS").find(".imageblock.thumbnail");

				if ( typeof $thumbs !== 'undefined' ) {
					$thumbs.hover(
						function () {
							VIDEO.showCaption( $(this) );
						},
						function () {
							VIDEO.hideCaption( $(this) );
						}
					);
				}

			})();


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
/* NOTE: AJAXING */
			/*
			Director's Navigation
			*/
			$('#drawer nav ul li a').on( 'click',  function( event ) {
				if( !$('body').hasClass('mobile') ) {
					event.preventDefault();
					$('body').switchClass("theme-light", "theme-dark", 500, "easeOutCubic", function() {
						DRAWER.hideDrawerMenu();
					});
				}
			});
			$('nav.mainmenu ul li a:not(#mainmenu-directors)').on( 'click',  function( event ) {
				if( !$('body').hasClass('mobile') ) {
					event.preventDefault();
					$('body').switchClass("theme-dark", "theme-light", 500, "easeOutCubic", function() {
						$('#reel-navigation-thumbs').empty();
						$('body').removeClass('HOMEPAGE PAGES POSTS');
						if($('body').hasClass('DIRECTORS')) { $('body').removeClass('DIRECTORS'); }

					});
				}
			});
			/*
			Moved this from line 135ish, inside the 'DRAWER' namespace
			*/
			$( ".mainmenu .mainmenu-directors" ).on( 'click',  function( event ) {
				if( !$('body').hasClass('mobile') ) {
					event.preventDefault();
					DRAWER.showDrawerMenu(); // open the sliding menu
				} else {
					event.preventDefault();
					window.location.href = $(this).data('url');
					return true;
				}
			});
			$( ".mainmenu .mainmenu-directors a" ).on( 'click',  function( event ) {
				event.preventDefault();
			});


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
/* NOTE: AJAXING */
			/*
			Main Menu Navigation
			*/
			$('nav.mainmenu ul li a:not(#mainmenu-directors), #drawer nav ul li a').on( 'click',  function( event ) {
				if( !$('body').hasClass('mobile') ) {
					event.preventDefault();
					var $this = $(this);
					var $parent = $this.parent('li');

					if( !$parent.hasClass('is-active') && !$parent.hasClass('mainmenu-news') ) {

						$this.parents('ul').find('li.is-active').removeClass('is-active');
						$parent.addClass('is-active');

						/*
						Parameters
						*/
						var _URL = $this.attr('href');
							_URL = _URL.replace('#','');
						var _TARGET = $this.data('target');

						/*
						Rewrite URL
						*/
						window.history.ready = true;
						window.history.pushState('OBJECT', null, _URL); // APP.BASE+'show:'+$_URL

						/*
						Check 'ACTIVE-CONTENT' & Load Request Objects
						*/


						if( !$('#'+_TARGET).hasClass('ACTIVE-CONTENT') ) {
							if( _TARGET === 'DIRECTORS' ) {
								$('nav.mainmenu ul li.is-active').removeClass('is-active');
								$('nav.mainmenu ul li a#mainmenu-directors').parent('li').addClass('is-active');
							}
							$('body').removeClass('HOMEPAGE PAGES POSTS DIRECTORS');
							if(!$('body').hasClass( _TARGET )) { $('body').addClass(_TARGET); }

							$('.ACTIVE-CONTENT').removeClass('ACTIVE-CONTENT').fadeOut( 'fast', function() {
								$(this).empty();
								$("div.PARKED").show();
								_AJAX(_URL,'#'+_TARGET);

							});
						}
						else {
							$('.ACTIVE-CONTENT').fadeOut( 'fast', function() {
								$(this).empty();
								$("div.PARKED").show();
								_AJAX(_URL,'#'+_TARGET);
							});
						}
					}
					return false;
				}
			});

			/*
			News Failsafe
			*/
			$('nav.mainmenu ul li.mainmenu-news a').on( 'click',  function( event ) {
				if( !$('body').hasClass('mobile') ) {
					event.preventDefault();
					var $this = $(this);
					var $parent = $this.parent('li');

				// if( !$parent.hasClass('is-active') ) {

						$this.parents('ul').find('li.is-active').removeClass('is-active');
						$parent.addClass('is-active');

						/*
						Parameters
						*/
						var _URL = $this.attr('href');
							_URL = _URL.replace('#','');
						var _TARGET = '#'+$this.data('target');

						/*
						Rewrite URL
						*/
						window.history.ready = true;
						window.history.pushState('OBJECT', null, _URL); // APP.BASE+'show:'+$_URL

						/*
						Check 'ACTIVE-CONTENT' & Load Request Objects
						*/
						if( !$(_TARGET).hasClass('ACTIVE-CONTENT') ) {

							$('body').removeClass('HOMEPAGE PAGES POSTS DIRECTORS');
							if(!$('body').hasClass( _TARGET )) { $('body').addClass(_TARGET); }

							$('.ACTIVE-CONTENT').removeClass('ACTIVE-CONTENT').fadeOut( 'fast', function() {

								$(this).empty();
								$("div.PARKED").show();
								_AJAX(_URL,_TARGET);

							});
						}
						else {
							$('.ACTIVE-CONTENT').fadeOut( 'fast', function() {
								$(this).empty();
								$("div.PARKED").show();
								_AJAX(_URL,_TARGET);
							});
						}
				//	}
					return false;
				}
			});


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
/* NOTE: AJAXING */
			var _AJAX = function($url,$target) {
				$.ajax({
					type: "GET",
					dataType: "html",
					url: $url,
					success: function(data) {
						$( $target ).fadeIn( 'fast', function() {
							document.title = "Park Pictures";
							if( !$(this).hasClass('ACTIVE-CONTENT') ) { $(this).addClass('ACTIVE-CONTENT'); }
							$( this ).html(data+'<div class="clearfix"></div>');
							$("div.PARKED").hide();
							if( $(this).attr('id') === 'DIRECTORS' ) {
								REEL_MENU.init();
								REEL_VIDEOS.init();
								VIDEO.initCaptions();
								DIRECTOR_LOAD.init();
								MENU_DIRECTOR.initBioButton();
							}
							if( $(this).attr('id') === 'PAGES' ) {
								ACCORDION.init();
								VIDEO.initCaptions();
								DIRECTOR_LOAD.init();
							}
							
							// Create a re-usable function from this...
							$( ".icon-link" ).click( function ( event ) {
								event.preventDefault();
								// var $linkicon = $( this );
								var $linkblock = $(this).closest( "li" );
								if( !$linkblock.hasClass( "is-open" ) ) {
									$linkblock.addClass( "is-open" );
									var $permalink = $linkblock.find( ".permalink" );
									$permalink.removeClass( "hidden" ).show().selectText();
									// selectText( 'permalink' );
									$permalink.selectText();
								} else {
									$linkblock.removeClass( "is-open" );
									$linkblock.find( ".permalink" ).addClass( "hidden" ).hide();
								}
							});
							
						});
					}
				});
			};
// ---------------------------------------------------------------------------------------


		/*
		Open external links in a new Tab/Window
		*/
		$('a').each(function() {
			var a = new RegExp('/' + window.location.host + '/');
			if(!a.test(this.href)) {
				$(this).click(function(event) {
					event.preventDefault();
					event.stopPropagation();
					window.open(this.href, '_blank');
				});
			}
		});



		// Link icon in sharing menu
		// Create a re-usable function from this...it is used in Ajax success callback
		$( ".icon-link" ).click( function ( event ) {
			event.preventDefault();
			// var $linkicon = $( this );
			var $linkblock = $(this).closest( "li" );
			if( !$linkblock.hasClass( "is-open" ) ) {
				$linkblock.addClass( "is-open" );
				var $permalink = $linkblock.find( ".permalink" );
				$permalink.removeClass( "hidden" ).show().selectText();
				// selectText( 'permalink' );
				$permalink.selectText();
			} else {
				$linkblock.removeClass( "is-open" );
				$linkblock.find( ".permalink" ).addClass( "hidden" ).hide();
			}
		});
		
		
	}); /* end of $(document).ready */


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
// https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history
// http://stackoverflow.com/questions/6421769/popstate-on-pages-load-in-chrome/7060766#7060766
$(window).bind('popstate', function (ev){
	if (!window.history.ready && !ev.originalEvent.state) {
		return; // workaround for popstate on load
	}
});


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
// http://www.sitepoint.com/use-html5-full-screen-api/
// http://hacks.mozilla.org/2012/01/using-the-fullscreen-api-in-web-browsers/
document.addEventListener("fullscreenchange", function () {
	_FULLSCREEN = (document.fullscreen) ? false : true;
	if( _FULLSCREEN && !$('body').hasClass('full-screen') ) {
		$('body').addClass('full-screen');
	} else {
	//	$('body').removeClass('full-screen');
	}
}, false);
document.addEventListener("mozfullscreenchange", function () {
	_FULLSCREEN = (document.mozFullScreen) ? false : true;
	if( _FULLSCREEN && !$('body').hasClass('full-screen') ) {
		$('body').addClass('full-screen');
	} else {
	//	$('body').removeClass('full-screen');
	}
}, false);
document.addEventListener("webkitfullscreenchange", function () {
	_FULLSCREEN = (document.webkitIsFullScreen) ? false : true;
	if( _FULLSCREEN && !$('body').hasClass('full-screen') ) {
		$('body').addClass('full-screen');
	} else {
	//	$('body').removeClass('full-screen');
	}
}, false);
document.addEventListener("msfullscreenchange", function () {
    _FULLSCREEN = (document.msFullscreenElement) ? false : true;
    if( _FULLSCREEN && !$('body').hasClass('full-screen') ) {
		$('body').addClass('full-screen');
	} else {
	//	$('body').removeClass('full-screen');
	}
}, false);


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------
$(document).ready(function() {
    $(window).on('orientationchange', function(event) {
//		window.setTimeout('location.reload()', 100);
    });
});

