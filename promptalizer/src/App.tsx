import React, {FC, useState} from 'react';
import './App.css';

function updateURLParameter(url: string, param: string, paramVal: string){
    // from here: https://stackoverflow.com/questions/1090948/change-url-parameters
    let newAdditionalURL = "";
    let tempArray = url.split("?");
    let baseURL = tempArray[0];
    let additionalURL = tempArray[1];
    let temp = "";

    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (let i = 0; i < tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    let rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function newSeed (): number {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const currentSeed = parseInt(urlParams.get('seed') ?? '') ?? 0;

    const genSeed = () => Math.floor(Math.random() * 10**3);

    let seed = genSeed();

    while (seed === currentSeed)
        seed = genSeed();

    return seed;
}

const App: FC = () => {

    return (
        <div className="App">
            <div id="top-bar">
                <a className="top-bar-item big-button" href="">
                    Poem
                </a>
                <a className="top-bar-item big-button">
                    Story
                </a>
            </div>

            <div id="options"/>

            <div id="go-button" className="center">
                <a href={updateURLParameter(window.location.pathname, 'seed', newSeed().toString())} className="button">
                    Prompt me!
                </a>
            </div>

            <div id="content"/>
        </div>
    );
}

export default App;
