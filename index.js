const state = {
    type: 'story'
}

const storyButton = $('#story');
const poemButton = $('#poem');

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
}

window.onOptionChange = optionName => {
    state[optionName] = $(`#${optionName}`).val();
    reRender();
}

const option = (optionName, inputType, defaultValue, width) => {
    state[optionName] ??= defaultValue;
    return (`
        <input type="${inputType}" onchange="window.onOptionChange('${optionName}')" value="${state[optionName]}" style="width: ${width}px" id="${optionName}">
    `);
}

const dropOption = (optionName, options) => {
    state[optionName] ??= options[0];
    return (`
        <select onchange="window.onOptionChange('${optionName}')" id="${optionName}">
            ${
            options.map(option => (`
                    <option value="${option}">${option}</option>
                `)).join('')
        }
        </select>
    `);
}

const poemOptions = () => (`
    I would like one poem please! <br>
    With ${option('lines', 'number', '5', 30)} lines with about ${option('syllables per line', 'number', '10', 30)} syllables in every line? <br>
    And it should rhyme like this: ${option('rhyming pattern', 'text', 'ABABC', 80)}. <br>
    Oh, and it should definitely include the word ${option('must-include', 'text', 'fish', 100)}, and could even have ${option('should-include', 'text', 'night', 100)} in. <Br>
    Thanks!
`);

const storyOptions = () => (`
    I would like one story please! <br>
    I don't want it to be longer than ${option('maxWords', 'number', '500', 40)} words. <br>
    It should be a ${dropOption('genre', ['fantasy', 'science fiction', 'murder mystery', 'romance'])} story, 
    and it should take place at ${dropOption('location', ['a factory', 'a massive castle', 'down a sewer', 'in the sky'])} <br>
    The main character should be a ${dropOption('character', ['adventurer', 'landlord', 'japanese peasant girl', 'princess'])}
    Oh, and it should definitely include the word ${option('must-include', 'text', 'fish', 100)}, and could even have ${option('should-include', 'text', 'night', 100)} in. <Br>
    Thanks!
`);

function reRender () {
    console.log(state);
    storyButton.css({
        'color': state.type === 'story' ? 'var(--text)' : 'var(--text-accent)',
        'background-color': state.type === 'story' ? 'var(--monotone-accent)' : 'var(--bg)',
        'font-size': state.type === 'story' ? '60px' : '45px'
    });
    poemButton.css({
        'color': state.type === 'poem' ? 'var(--text)' : 'var(--text-accent)',
        'background-color': state.type === 'poem' ? 'var(--monotone-accent)' : 'var(--bg)',
        'font-size': state.type === 'poem' ? '60px' : '45px'
    });

    $('#options').html(state.type === 'poem' ? poemOptions() : storyOptions());
}
reRender();

function toggleState () {
    state.type = state.type === 'story' ? 'poem' : 'story';
    reRender();
}

storyButton.click(() => {
    toggleState();
});

poemButton.click(() => {
    toggleState();
});