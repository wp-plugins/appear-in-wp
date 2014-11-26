/**
 * Theme cards for displaying excerpt and post content.
 */
jQuery(document).ready(function( $ ) {

	if ( '' != lookToURI( 'room' ) ) {
		$( window ).on( 'load', function() {
			window.location.hash = 'appearin-room';
		});
	}

	if ( 'https:' === location.protocol ) {
		// check if roomname is defined in URI
		var aiRoom = lookToURI( 'room' );

		$('#webrtc-compatability-tester').hide();
        if ( '' != aiRoom ) {

        	// hide room type selection
        	$('#aiwp-room-type-selection').hide();

        	// launch room
        	launchAppearInRoom( aiRoom, 'invite' );

        } // end if
	} else {
		API.isAppearinCompatible(function (data) {
			// check if roomname is define in URI
			var aiRoom = lookToURI( 'room' );

			// if webRTC not supported show incompatibility message and hide room type selection
			// otherwise, if room set in URI, set up room
			// otherwise, do nothing
			if ( data.isSupported ) {
				$('#webrtc-compatability-tester').hide();
			}
	        if ( !data.isSupported ) {
	            $('#appearin-incompatibility').show();
	            $('#aiwp-room-type-selection').hide();
	        } else if ( '' != aiRoom ) {

	        	// hide room type selection
	        	$('#aiwp-room-type-selection').hide();

	        	// launch room
	        	launchAppearInRoom( aiRoom, 'invite' );

	        } // end if
	    });
	}

	function randomStringGenerator() {
        // predefine the alphabet used
        var alphabet = 'qwertyuiopasdfghjklzxcvbnm1234567890';

        // set the length of the string
        var stringLength = 30;

        // initialize the room name as an empty string
        var randomString = '';

        // repeat this 30 times
        for ( var i=0; i<stringLength; i++) {
            // get a random character from the alphabet
            var character = alphabet[Math.round(Math.random()*(alphabet.length-1))];

            // add the character to the roomName
            randomString = randomString + character;
        }

        // return the result
        return randomString;
    }

	// Handle Room Selection
	$('#aiwp-select-public-room,#aiwp-select-private-room,#aiwp-select-post-room').click( function () {
			
		var roomType = $(this).data('room-type');
		var roomInvites = $(this).data('room-invites');

		if ( 'post' == roomType ) {
			var roomURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
			launchAppearInRoom( roomURL );
		} else if ( 'public' == roomType ) {
			var roomName = $('#appearin-room').attr('data-room-name');
			launchAppearInRoom( roomName );
		} else if ( 'private' == roomType ) {
			var randomString = 'private-' + randomStringGenerator();
			launchAppearInRoom( randomString );
		}

	});

	function launchAppearInRoom( randomString, origin ) {
		if ( randomString.indexOf('appear.in') >= 0 ) {
			var roomName = randomString.replace('?lite','');
		} else {
			var roomName = 'appear.in/' + randomString;
		}
		var roomNameLite = roomName + '?lite';
		if ( '/' != window.location.pathname ) {
			var roomURL = window.location.host + window.location.pathname;
		} else if ( '' != window.location.search ) {
			var roomURL = window.location.host + window.location.search;
			var n = roomURL.indexOf('?room=');
			roomURL = roomURL.substring(0, n != -1 ? n : roomURL.length);
		} else {
			var roomURL = window.location.host
		}
		// set the iframe source to load the room
		var iframe = document.getElementById('appearin-room');
		iframe.setAttribute('src', window.location.protocol + "//" + roomNameLite);

		$('#aiwp-room-type-selection').hide();
		$('#appearin-room').css('height','700px');
		$('#aiwp-invites').show();
		$('#appearin-room-label').html(roomURL+'?room='+roomNameLite);
		$('#aiwp-invite-facebook').attr('href','https://facebook.com/sharer.php?u='+roomURL+'?room='+roomNameLite);
		$('#aiwp-invite-twitter').attr('href','https://twitter.com/intent/tweet?url='+window.location.protocol+'//'+roomURL+'?room='+roomNameLite+'&text=Join%20me%20in%20an%20%23appear_inWP%20video%20chat%20at');
		$('#aiwp-invite-email').attr('href','mailto:?subject=You\'ve%20been%20invited%20to%20appear.in%20a%20video%20chat&body='+roomURL+'?room='+roomNameLite);
		$('#appearin-room-label-external').html('<a href="https://'+roomName+'" target="_self">Visit Full Room</a>');

		window.onbeforeunload = function(){
		    return 'Active sessions at ' + roomName + ' will be ended.'; 
		}

	}

	function lookToURI(name){
		if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) {
			return decodeURIComponent(name[1]);
		} else {
			return '';
		}
	}

});
