<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajax</title>
    <script>
        const ajax = new XMLHttpRequest();

        function getSessionByClient() {
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4 && ajax.status === 200) {
                    document.getElementById("resultClient").innerHTML = ajax.responseText;
                }
            };
            const clientId = document.getElementById("clientId").value;
            ajax.open('GET', 'getSessionByClient.php?clientId=' + clientId, true);
            ajax.send();
        }

        function getSessionByTime() {
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4 && ajax.status === 200) {
                    const xml = ajax.responseXML;
                    let output = "";
                    let sessions = xml.getElementsByTagName("session");
                    for (let i = 0; i < sessions.length; i++) {
                        const session = sessions[i];
                        const login = session.getElementsByTagName("login")[0].childNodes[0].nodeValue;
                        const start = session.getElementsByTagName("start")[0].childNodes[0].nodeValue;
                        const stop = session.getElementsByTagName("stop")[0].childNodes[0].nodeValue;
                        const trafficDifference = session.getElementsByTagName("trafficDifference")[0].childNodes[0].nodeValue;
                        output += `<li>Користувач з логіном ${login} має останній сеанс з ${start} по ${stop} використав ${trafficDifference}</li>`;
                    }
                    document.getElementById("resultTime").innerHTML = output;
                }
            };
            const start = document.getElementById("start").value;
            const stop = document.getElementById("stop").value;
            ajax.open('GET', 'getSessionByTime.php?start=' + start + '&stop=' + stop, true);
            ajax.send();
        }

        function getClientByBalance() {
            ajax.onreadystatechange = function() {
                if (ajax.readyState === 4 && ajax.status === 200) {
                    const responseJSON = JSON.parse(ajax.responseText);
                    let output = "";
                    responseJSON.forEach(item => {
                        output += `<li>Користувач ${item.name} з айпі адресою ${item.ip} має баланс ${item.balance}</li>`;
                    });
                    document.getElementById("resultBalance").innerHTML = output;
                }
            };
            ajax.open('GET', 'getClientByBalance.php', true);
            ajax.send();
        }
    </script>
</head>
<body>
    <h3>Виведення даних:</h3>
    <div>
        Отримати сеанси роботи в мережі для обраного клієнта
        <form onsubmit="event.preventDefault(); getSessionByClient();">
            <select name="clientId" id="clientId">
                <?php
                require 'connect.php';
                $sth = $dbh->prepare("SELECT name, ID_Client FROM client");
                $sth->execute();
                $clients = $sth->fetchAll(PDO::FETCH_ASSOC); 
                foreach ($clients as $client) {
                    echo "<option value='{$client['ID_Client']}'>{$client['name']}</option>";
                }
                ?>
            </select>
            <button type="submit">Опрацювати</button>
        </form>
        <ul id="resultClient"></ul>
    </div>

    <div> 
        Отримати сеанси роботи в мережі за вказаний проміжок часу
        <form onsubmit="event.preventDefault(); getSessionByTime();">
            <input type="time" id="start" name="start" required/>
            <input type="time" id="stop" name="stop" required/>
            <button type="submit">Опрацювати</button>
        </form>
        <ul id="resultTime"></ul>
    </div>

    <div>
        Отримати список клієнтів з від'ємним балансом рахунку
        <form onsubmit="event.preventDefault(); getClientByBalance();">
            <button type="submit">Опрацювати</button>
        </form>
        <ul id="resultBalance"></ul>
    </div>
</body>
</html>