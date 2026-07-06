const puppeteer = require('puppeteer');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  
  page.on('console', msg => console.log('PAGE LOG:', msg.text()));
  page.on('pageerror', error => console.log('PAGE ERROR:', error.message));
  
  await page.goto('http://localhost/photographers', { waitUntil: 'networkidle2' });
  
  const html = await page.evaluate(() => document.documentElement.outerHTML);
  
  const fs = require('fs');
  fs.writeFileSync('test_browser.html', html);
  
  await browser.close();
})();
