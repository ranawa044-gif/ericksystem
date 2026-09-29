import fs from 'fs';
import { JSDOM } from 'jsdom';

const dom = new JSDOM('<!DOCTYPE html><html><body></body></html>');
global.window = dom.window;
global.document = dom.window.document;
global.Element = dom.window.Element;
global.HTMLElement = dom.window.HTMLElement;

const mermaid = (await import('mermaid')).default;

mermaid.initialize({ startOnLoad: false, securityLevel: 'loose' });

const content = fs.readFileSync('resources/views/pengelola/analisis-perancangan.blade.php', 'utf8');

const regex = /<div class="mermaid"[^>]*id="([^"]+)"[^>]*>([\s\S]*?)<\/div>/g;
let match;
let count = 0;

while ((match = regex.exec(content)) !== null) {
    count++;
    const id = match[1];
    const code = match[2].trim();
    console.log(`\n---------------------------------`);
    console.log(`Testing [${id}]...`);
    try {
        await mermaid.parse(code);
        console.log(`✅ [${id}] VALID!`);
    } catch (err) {
        console.error(`❌ [${id}] SYNTAX ERROR:`);
        console.error(err.message || err.str || err);
    }
}

console.log(`\nTested ${count} diagrams.`);
