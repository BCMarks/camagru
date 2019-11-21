'use strict';
    const video = document.getElementById('cam');
    const constraints = 
    {
        audio: false,
        video: true
    };
// Access webcam
    async function init ()
    {
        try{
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            handleSuccess(stream);
        }
        catch(e)
        {
            //errorMsgElement.innerHTML = `navigator.getUserMedia.error:${e.toString()}`;
        }
    }
//Success
    function handleSuccess(stream)
    {
        var snap = document.getElementById('snap');
        window.stream = stream
        cam.srcObject = stream;
        snap.disabled = false;
    }
// Load init
    init()
// Draw image
    function snape()
    {
        var canvas = document.getElementById('cnvs');
        var ctx = canvas.getContext('2d');
        document.getElementById('wimg').value = cnvs.toDataURL();
        ctx.drawImage(cam, 0, 0, canvas.width, canvas.height);
        //console.log(cnvs.toDataURL());
    }

    window.addEventListener('load', resize, false);
    window.addEventListener('resize', resize, false);

    function resize() {
        var canvas = document.getElementById('cnvs');
        canvas.style.width ='100%';
        canvas.style.height='100%';
        canvas.width  = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }