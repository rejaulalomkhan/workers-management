const fs = require('fs');
const pdf = require('pdf-parse');

async function extract() {
    let data = await pdf(fs.readFileSync('./FaizaHost_PRD (1).pdf'));
    console.log("--- PRD ---");
    console.log(data.text);

    data = await pdf(fs.readFileSync('./Overview.pdf'));
    console.log("--- OVERVIEW ---");
    console.log(data.text);
}
extract().catch(e => console.error(e));
