<!doctype html>
<html lang="en">
  <head>
    <title>Forbidden !</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>
      <style>
            @import url('https://fonts.googleapis.com/css?family=Press+Start+2P');
            html,body{
            width: 100%;
            height: 100%;
            margin: 0;
            }

            *{
            font-family: 'Press Start 2P', cursive;
            box-sizing: border-box;
            }
            #app{
                padding: 1rem;
                background: black;
                display: flex;
                height: 100%;
                justify-content: center;
                align-items: center;
                color: #54FE55;
                text-shadow: 0px 0px 10px ;
                font-size: 6rem;
                flex-direction: column;
                
            }
            .txt {
                    font-size: 1.8rem;
                }
            @keyframes blink {
                0%   {opacity: 0}
                49%  {opacity: 0}
                50%  {opacity: 1}
                100% {opacity: 1}
            }

            .blink {
            animation-name: blink;
            animation-duration: 1s;
            animation-iteration-count: infinite;
            }
      </style>
  <div id="app">
   <div>403</div>
   <div class="txt">
      Forbidden<span class="blink">_</span>
   </div>
</div>
  </body>
</html>
