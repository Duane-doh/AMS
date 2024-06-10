var Service = require('node-windows').Service;

// Create a new service object
var svc = new Service({
  name:'Nodejs',
  description: 'Real time event web server.',
  script: 'C:\\xammp\\htdocs\\doh_ptis\\nodejs\\server.js'
});

// Install the script as a service.
svc.install();