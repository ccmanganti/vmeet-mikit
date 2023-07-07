import './bootstrap';
import jQuery from 'jquery';

window.onload = function(){

    const inputSubmit = document.getElementById('form');
    const listMessage = document.getElementById('thread');
    

    inputSubmit.addEventListener("submit", function(event) {
        event.preventDefault();
        
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'chat-message',
            dataType: 'text',
            type: 'post',
            contentType: 'application/x-www-form-urlencoded',
            data: {message: $('#new-chat-input').val()}
        });            
    });
    

    // Get the current URL and search the database to listen to the Channel
    // var privateChannel = (document.location.pathname.split('/')[2]);
    const channelId = (`${window.CHANNEL_ID}`);
    const channelType = "presence.chat.";
    console.log(channelType.concat("", channelId));

    let onlineUsers = [];

    Echo.join(channelType.concat("", channelId))
        .here((users) => {
            console.log(users);
            onlineUsers = users;
        })

        .joining((user) => {
            console.log(user.name, 'joined')
            if(onlineUsers.includes(user) == false) {
                onlineUsers.push(user);
            }
            console.log(onlineUsers);
        })

        .leaving((user) => {
            console.log(user.name, 'left')
            for (let i = 0; i < onlineUsers.length; i++){
                if (onlineUsers[i].email == (user.email)) {
                    // console.log(onlineUsers[i]);
                    onlineUsers.splice(i, 1);

                }
            }
            console.log(onlineUsers);
        })

        .listen('.message', (e) => {
            for (let i = 0; i < onlineUsers.length; i++){
                if (e.user.email === onlineUsers[i].email){
                    console.log(e.user.name + ": " + e.message);
                    break;
                }
            }

            // const li = document.createElement('li');
            // li.textContent = e.user.name + ": " + e.message;
            jQuery("#thread").append(
                `
                <li class="chat-message-content">
                    <p class="chat-message-author">${(e.user.name)}</p>
                    <p class="chat-message-message">${(e.message)}</p>
                </li>
                `
            );
            $("#form")[0].reset();
        });

    };
