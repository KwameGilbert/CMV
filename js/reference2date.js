// Example reference string
let reference = 'Gili168434908171810374';

// Extract the timestamp (the numeric part immediately after 'Gili')
let timestamp = reference.match(/\d{13}/)[0];

// Convert the timestamp to a Date object
let date = new Date(parseInt(timestamp));

// Log the human-readable date
console.log(date.toString());
