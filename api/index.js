const express = require('express');

const app = express();

app.use(express.json());

app.get('/', (req, res) => {
	res.send('hi there');
});

app.listen(3000, () => {
	console.log('Server start at: http://localhost:3000');
});