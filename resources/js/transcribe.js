
// Speech To Text

function scrollToBottom() {
    $('#convert_text').scrollTop($('#convert_text')[0].scrollHeight);
  }

function download(file, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:application/msword;charset=utf-8, ' + encodeURIComponent(text));
    element.setAttribute('download', file);
    document.body.appendChild(element);
    //onClick property
    element.click();
    document.body.removeChild(element);
}


document.getElementById('speechToText').addEventListener('click', function() {
    $('#speechToText').toggleClass('activeAudio');
    const currentDate = new Date();
    const currentDateTime = 'Mikit Transcriptio - ' + currentDate.getFullYear()+"-"+(currentDate.getMonth()+1)+"-"+ currentDate.getDate()+"-"+ currentDate.getTime() + '.doc';
    if((document.getElementById('convert_text').value).length > 1 && !$('#speechToText').hasClass('activeAudio')){
        var text = document.getElementById("convert_text").value;
        var filename = currentDateTime;
        download(filename, text);
    }

    if($('#speechToText').hasClass('activeAudio')){
        var speech = true;
        window.SpeechRecognition = window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.interimResults = true;
        recognition.continuous = true;
        recognition.lang = "fil-PH";


        recognition.addEventListener('result', e=>{
            recognition.addEventListener('end', recognition.start);

            if($('#speechToText').hasClass('activeAudio')){
                const transcript = Array.from(e.results)
                .map(result =>result[0])
                .map(result =>result.transcript)

                convert_text.innerHTML = transcript;
                recognition.addEventListener('end', recognition.start);

            }
        });
        if($('#speechToText').hasClass('activeAudio') && speech == true) {
            recognition.start();


        } else {
            recognition.continuous = false;
            recognition.interimResults = false;
            speech = false;
            recognition.end();
            return;
        }
    }
});

