$(document).ready(() => {
	fetch("/api").then(reply => reply.json()).then(result => console.log(result));
});