<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask RASA</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>Ask RASA</h1>
    <form id="RASA-form">
        <textarea id="message" placeholder="Type your message here"></textarea>
        <button type="submit">Send</button>
    </form>
    <div id="response"></div>

    <script>
        document.getElementById('RASA-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const message = document.getElementById('message').value;

            axios.post('/ask', { message })
                .then(response => {
                    document.getElementById('response').innerText = response.data.choices[0].message.content;
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
</body>
</html>
