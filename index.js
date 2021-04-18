const state = {
    type: 'poem'
}

function generate () {
    fetch('./backend/get-prompt.php', {
        method: 'POST',
        cache: 'no-cache',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(state) // body data type must match "Content-Type" header
    })
        .then(data => data.json())
        .then(data => {
            $('#content').html(data['prompt']);
        })
        .then(() => console.log('Got data!'))
}