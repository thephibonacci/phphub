<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERROR | 403</title>
</head>
<style>
    @import url("https://fonts.googleapis.com/css?family=Press+Start+2P");

    * {
        padding: 0;
        margin: 0;
    }

    body {
        background: repeating-linear-gradient(0deg, #000 25%, #000 50%, #060606 50%, #060606 75%);
        background-size: 10px 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        color: white;
        font-family: 'Press Start 2P', monospace;
        justify-content: start;
        padding-top: 100px;
        align-items: center;
    }

    h2 {
        font-weight: 300;
        margin-top: 40px;
        color: red;
    }

    p {
        font-size: 12px;
        font-weight: lighter;
        text-align: center;
        line-height: 26px;
        margin: 20px;
    }

    @media only screen and (min-width: 550px) {
        p {
            font-size: 16px;
            margin: 30px;
        }
    }

    .glitch {
        position: relative;
        color: #fff;
        font-size: 80px;
    }

    .line:not(:first-child) {
        position: absolute;
        top: 0;
        left: 0;
    }

    .line:nth-child(1) {
        animation: clip 3000ms 0s linear infinite, glitch1 600ms -0ms linear infinite;
    }

    .line:nth-child(2) {
        animation: clip 3000ms -300ms linear infinite, glitch2 600ms -400ms linear infinite;
    }

    .line:nth-child(3) {
        animation: clip 3000ms -600ms linear infinite, glitch3 500ms -800ms linear infinite;
    }

    .line:nth-child(4) {
        animation: clip 3000ms -900ms linear infinite, glitch4 500ms -1200ms linear infinite;
    }

    .line:nth-child(5) {
        animation: clip 3000ms -1200ms linear infinite, glitch5 500ms -1600ms linear infinite;
    }

    .line:nth-child(6) {
        animation: clip 3000ms -1500ms linear infinite, glitch6 500ms -2000ms linear infinite;
    }

    .line:nth-child(7) {
        animation: clip 3000ms -1800ms linear infinite, glitch7 500ms -2400ms linear infinite;
    }

    .line:nth-child(8) {
        animation: clip 3000ms -2100ms linear infinite, glitch8 500ms -2800ms linear infinite;
    }

    .line:nth-child(9) {
        animation: clip 3000ms -2400ms linear infinite, glitch9 500ms -3200ms linear infinite;
    }

    .line:nth-child(10) {
        animation: clip 3000ms -2700ms linear infinite, glitch10 500ms -3600ms linear infinite;
    }

    @keyframes clip {
        0% {
            clip-path: polygon(0 100%, 100% 100%, 100% 120%, 0 120%);
        }

        100% {
            clip-path: polygon(0 -20%, 100% -20%, 100% 0%, 0 0);
        }
    }

    @keyframes glitch1 {
        0% {
            transform: translateX(0);
        }

        80% {
            transform: translateX(0);
            color: #fff;
        }

        85% {
            transform: translateX(-3px);
            color: deepskyblue;
        }

        90% {
            transform: translateX(3px);
            color: deeppink;
        }

        95% {
            transform: translateX(-3px);
            color: #fff;
        }

        100% {
            transform: translateX(0);
        }
    }

    @keyframes glitch2 {
        0% {
            transform: translateX(0);
        }

        80% {
            transform: translateX(0);
            color: #fff;
        }

        85% {
            transform: translateX(-4px);
            color: deepskyblue;
        }

        90% {
            transform: translateX(4px);
            color: deeppink;
        }

        95% {
            transform: translateX(-4px);
            color: #fff;
        }

        100% {
            transform: translateX(0);
        }
    }
</style>

<body>
    <div class="glitch">
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
        <div class="line">403</div>
    </div>
    <h2>Forbidden</h2>
    <p>You do not have permission to access this resource</p>
</body>

</html>