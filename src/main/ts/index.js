const welcomeContainer = document.querySelector("#welcome");

fetch("http://localhost:8080/api/welcome")
    .then((message) => message.text())
    .then((message) => welcomeContainer.innerText = message);