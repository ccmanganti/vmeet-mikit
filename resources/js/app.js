import './bootstrap';
import jquery from 'jquery';

let meetingJoined = false;
const meeting = new Metered.Meeting();
let cameraOn = false;
let enableRecording = true;
let micOn = false;
let screenSharingOn = false;
let localVideoStream = null;
let activeSpeakerId = null;

async function initializeView() {
    /**
     * Populating the cameras
     */
     const videoInputDevices = await meeting.listVideoInputDevices();
     const videoOptions = [];
     for (let item of videoInputDevices) {
        videoOptions.push(
            `<option value="${item.deviceId}">${item.label}</option>`
        )
     }
    jquery("#cameraSelectBox").html(videoOptions.join(""));

    /**
     * Populating Microphones
     */
    const audioInputDevices = await meeting.listAudioInputDevices();
    const audioOptions = [];
    for (let item of audioInputDevices) {
        audioOptions.push(
            `<option value="${item.deviceId}">${item.label}</option>`
        )
    }
    jquery("#microphoneSelectBox").html(audioOptions.join(""));

    jquery("#waitingAreaToggleMicrophone").on("click", function() {
        if (micOn) {
            micOn = false;
            jquery("#waitingAreaToggleMicrophone").removeClass("active");
        } else {
            micOn = true;
            jquery("#waitingAreaToggleMicrophone").addClass("active");
        }
    });

    let cameraOn = false;
    let localVideoStream = null;
    jquery("#waitingAreaToggleCamera").on("click", async function() {
        if (cameraOn) {
            cameraOn = false;
            jquery("#waitingAreaToggleCamera").removeClass("active");
            const tracks = localVideoStream.getTracks();
            tracks.forEach(function (track) {
              track.stop();
            });
            localVideoStream = null;
            jquery("#waitingAreaLocalVideo")[0].srcObject = null;
        } else {
            cameraOn = true;
            jquery("#waitingAreaToggleCamera").addClass("active");
            localVideoStream = await meeting.getLocalVideoStream();
            jquery("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
            cameraOn = true;
        }
    });

    /**
     * Adding Event Handlers
     */
    jquery("#cameraSelectBox").on("change", async function() {
        const deviceId = jquery("#cameraSelectBox").val();
        await meeting.chooseVideoInputDevice(deviceId);
        if (cameraOn) {
            localVideoStream = await meeting.getLocalVideoStream();
            jquery("#waitingAreaLocalVideo")[0].srcObject = localVideoStream;
        }
    });

    jquery("#microphoneSelectBox").on("change", async function() {
        const deviceId = jquery("#microphoneSelectBox").val();
        await meeting.chooseAudioInputDevice(deviceId);
    });

    let meetingInfo = {};
    jquery("#joinMeetingBtn").on("click", async function () {
        var username = jquery("#username").val();
        if (!username) {
          return alert("Please enter a username");
        }
        // console.log(`${window.MEETING_ID}`);
        try {
          meetingInfo = await meeting.join({
            roomURL: `mikitvmeet.metered.live/${window.MEETING_ID}`,
            name: username,
          });
          jquery("header").toggleClass("hidden");
          jquery("#meetingView").toggleClass("meetingView");
          console.log("Meeting joined", meetingInfo);
          jquery("#waitingArea").addClass("hidden");
          jquery("#meetingView").removeClass("hidden");
          jquery("#meetingView").addClass("activeMeet");
          jquery("#meetingAreaUsername").text(username);

          /**
           * If camera button is clicked on the meeting view
           * then sharing the camera after joining the meeting.
           */
          if (cameraOn) {
            await meeting.startVideo();
            // jquery("#toggleCamera").removeClass("bg-gray-400");
            jquery("#toggleCamera").addClass("activeCam");
            jquery("#localVideoTag")[0].srcObject = localVideoStream;
          }
          
          /**
           * Microphone button is clicked on the meeting view then
           * sharing the microphone after joining the meeting
           */
          if (micOn) {
            await meeting.startAudio();
            jquery("#toggleMicrophone").addClass("activeMic");
          }
          
        } catch (ex) {
          console.log("Error occurred when joining the meeting", ex);
        }
      });

    
    meeting.on("onlineParticipants", function(participants) {
    meeting.enableRecording;
    for (let participantInfo of participants) {
        // Checking if a div to hold the participant already exists or not.
        // If it exisits then skipping.
        // Also checking if the participant is not the current participant.
      if (!jquery(`#participant-${participantInfo._id}`)[0] && participantInfo._id !== meeting.participantInfo._id) {
        jquery("#remoteParticipantContainer").append(
          `
          <div id="participant-${participantInfo._id}"">
            <div id="localParticipantContainer" class="">
              <div class="localVideoContainer">
                <video id="video-${participantInfo._id}" src="" autoplay class="localVideoContain"></video>
                <video id="audio-${participantInfo._id}" src="" autoplay class="hidden"></video>
              </div>
              <div class="localUsername">
                  ${(participantInfo.name)}
              </div>
            </div>
          </div>
          `
        );
      }
    }
  });


  meeting.on("participantLeft", function(participantInfo) {
    jquery("#participant-" + participantInfo._id).remove();
    if (participantInfo._id === activeSpeakerId) {
      jquery("#activeSpeakerUsername").text("");
      jquery("#activeSpeakerUsername").addClass("hidden");

    }
  });

  meeting.on("remoteTrackStarted", function(remoteTrackItem) {
    jquery("#activeSpeakerUsername").removeClass("hidden");

    if (remoteTrackItem.type === "video") {
      let mediaStream = new MediaStream();
      mediaStream.addTrack(remoteTrackItem.track);
      if (jquery("#video-" + remoteTrackItem.participantSessionId)[0]) {
        jquery("#video-" + remoteTrackItem.participantSessionId)[0].srcObject = mediaStream;
        jquery("#video-" + remoteTrackItem.participantSessionId)[0].play();
      }
    }

    if (remoteTrackItem.type === "audio") {
      let mediaStream = new MediaStream();
      mediaStream.addTrack(remoteTrackItem.track);
      if ( jquery("#video-" + remoteTrackItem.participantSessionId)[0]) {
        jquery("#audio-" + remoteTrackItem.participantSessionId)[0].srcObject = mediaStream;
        jquery("#audio-" + remoteTrackItem.participantSessionId)[0].play();
      }
    }
    setActiveSpeaker(remoteTrackItem);
  });


  function setActiveSpeaker(activeSpeaker) {

    if (activeSpeakerId  != activeSpeaker.participantSessionId) {
      jquery(`#participant-${activeSpeakerId}`).show();
    } 

    activeSpeakerId = activeSpeaker.participantSessionId;
    jquery(`#participant-${activeSpeakerId}`).hide();

    jquery("#activeSpeakerUsername").text(activeSpeaker.name || activeSpeaker.participant.name);
    
    if (jquery(`#video-${activeSpeaker.participantSessionId}`)[0]) {
      let stream = jquery(
        `#video-${activeSpeaker.participantSessionId}`
      )[0].srcObject;
      jquery("#activeSpeakerVideo")[0].srcObject = stream.clone();
    }
  
    if (activeSpeaker.participantSessionId === meeting.participantSessionId) {
      let stream = jquery(`#localVideoTag`)[0].srcObject;
      if (stream) {
        jquery("#localVideoTag")[0].srcObject = stream.clone();
      }
    }
  }


   meeting.on("remoteTrackStopped", function(remoteTrackItem) {
    if (remoteTrackItem.type === "video") {
      if ( jquery("#video-" + remoteTrackItem.participantSessionId)[0]) {
        jquery("#video-" + remoteTrackItem.participantSessionId)[0].srcObject = null;
        jquery("#video-" + remoteTrackItem.participantSessionId)[0].pause();
      }
      
      if (remoteTrackItem.participantSessionId === activeSpeakerId) {
        jquery("#activeSpeakerVideo")[0].srcObject = null;
        jquery("#activeSpeakerVideo")[0].pause();
      }
    }

    if (remoteTrackItem.type === "audio") {
      if (jquery("#audio-" + remoteTrackItem.participantSessionId)[0]) {
        jquery("#audio-" + remoteTrackItem.participantSessionId)[0].srcObject = null;
        jquery("#audio-" + remoteTrackItem.participantSessionId)[0].pause();
      }
    }
  });


  meeting.on("activeSpeaker", function(activeSpeaker) {
    setActiveSpeaker(activeSpeaker);
  });

    jquery("#toggleMicrophone").on("click",  async function() {
    if (micOn) {
            jquery("#toggleMicrophone").removeClass("activeMic");
      micOn = false;
      await meeting.stopAudio();
    } else {
            jquery("#toggleMicrophone").addClass("activeMic");
      micOn = true;
      await meeting.startAudio();
    }
  });

   jquery("#toggleCamera").on("click",  async function() {
    if (cameraOn) {
      jquery("#toggleCamera").removeClass("activeCam");
      cameraOn = false;
      await meeting.stopVideo();
      const tracks = localVideoStream.getTracks();
      tracks.forEach(function (track) {
        track.stop();
      });
      localVideoStream = null;
      jquery("#localVideoTag")[0].srcObject = null;
    } else {
      jquery("#toggleCamera").addClass("activeCam");
      cameraOn = true;
      await meeting.startVideo();
      localVideoStream = await meeting.getLocalVideoStream();
      jquery("#localVideoTag")[0].srcObject = localVideoStream;
    }
  });

  jquery("#toggleScreen").on("click",  async function() {
    if (screenSharingOn) {
      jquery("#toggleScreen").removeClass("activeScreen");
      screenSharingOn = false;
      await meeting.stopVideo();
      const tracks = localVideoStream.getTracks();
      tracks.forEach(function (track) {
        track.stop();
      });
      localVideoStream = null;
      jquery("#localVideoTag")[0].srcObject = null;

    } else {
      // jquery("#toggleCamera").removeClass("bg-gray-500");
      screenSharingOn = true;
      localVideoStream = await meeting.startScreenShare();
      jquery("#localVideoTag")[0].srcObject = localVideoStream;
      jquery("#toggleScreen").addClass("activeScreen");
      
    }
  });

  jquery("#leaveMeeting").on("click", async function() {
    await meeting.leaveMeeting();
    jquery("#meetingView").addClass("hidden");
    jquery("#leaveMeetingView").removeClass("hidden");
    jquery("header").toggleClass("hidden");
  });

}



initializeView();
    
