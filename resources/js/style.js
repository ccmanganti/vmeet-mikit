
$(document).ready(function() {
// Navigation Bar Interactive Scroll
    $(window).on("scroll", function(oldScrollTop) {
        var newScrollTop = $(window).scrollTop();

        if ($(window).scrollTop()<100) {
            $("header").removeClass("scrolledUp");
            $("header").css("position", "")
        } else if (($(window).scrollTop()<oldScrollTop)) {
            $("header").removeClass("scrolledUp");
            $("header").css("position", "fixed")
        } else {
            $("header").css("position", "fixed")
            $("header").addClass("scrolledUp");
        }
        oldScrollTop = newScrollTop;
    });
    $(".hamburger").click(function() {
        $(".hamburger").toggleClass("active");
        $(".navlist").toggleClass("active");
    });


    if (window.location.pathname == "/"){
        $(".registerBtn").removeClass("activeNav");
        $(".aboutBtn").removeClass("activeNav");
        $(".loginBtn").addClass("activeNav");
    } else if (window.location.pathname == "/register") {
        $(".registerBtn").addClass("activeNav");
        $(".aboutBtn").removeClass("activeNav");
        $(".loginBtn").removeClass("activeNav");
    }

    function copyToClipboard() {
        var copyText = document.getElementById("lobbyMikitDetails").value;
        navigator.clipboard.writeText(copyText).then(() => {
            // Alert the user that the action took place.
            // Nobody likes hidden stuff being done under the hood!
          //   alert("Copied to clipboard");
          $(".lobbyIconDetails").addClass("fa-check");
          $(".lobbyIconDetails").removeClass("fa-clipboard");
          $(".lobbyDetailsCopy").addClass("activeCopy");
          $(".lobbyDetailsCopyMeet").addClass("activeCopy");

          setTimeout(() => { 
              $(".lobbyIconDetails").addClass("fa-clipboard"); 
              $(".lobbyIconDetails").removeClass("fa-check"); 
              $(".lobbyDetailsCopy").removeClass("activeCopy");
              $(".lobbyDetailsCopyMeet").removeClass("activeCopy");
          }, 2000);

      });
    }
    $("#copyDetails").click(function() {
        copyToClipboard();
    });
    $("#chatContainerBtn").click(function() {
        $(".chatContainer").toggleClass("chatActive"); 
        $("#chatContainerBtn").toggleClass("chatActiveBtn"); 

    });

    $("#copyNewDetails").click(function() {
        copyToClipboard();
    });


});
