const fs = require('fs');

function getStructure(html) {
    const tags = [];
    const regex = /<\/?([a-zA-Z0-9]+)(?:\s+[^>]*?)?>/g;
    let match;
    while ((match = regex.exec(html)) !== null) {
        const tag = match[0];
        const tagName = match[1].toLowerCase();
        if (['meta', 'link', 'img', 'input', 'hr', 'br'].includes(tagName)) continue;
        if (tag.startsWith('</')) {
            tags.push('/' + tagName);
        } else {
            tags.push(tagName);
        }
    }
    return tags;
}

const dash = fs.readFileSync('doctor/dashboard.html', 'utf8');
const appt = fs.readFileSync('doctor/appointments.html', 'utf8');

const dashTags = getStructure(dash);
const apptTags = getStructure(appt);

console.log('Dashboard tag count:', dashTags.length);
console.log('Appointments tag count:', apptTags.length);

// Compare depth
function analyzeDepth(tags) {
    let depth = 0;
    let seq = [];
    for (let t of tags) {
        if (!t.startsWith('/')) depth++;
        else depth--;
        if (depth < 0) console.log("NEGATIVE DEPTH at: ", t);
    }
    return depth;
}

console.log("Dashboard final depth:", analyzeDepth(dashTags));
console.log("Appointments final depth:", analyzeDepth(apptTags));

