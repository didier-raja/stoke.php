const mysql = require('mysql');
const express = require('express');
const cors = require('cors');
const bodyparser = require('body-parser');

const app = express();

app.use(cors());
app.use(bodyparser.json());
app.use(express.json());


const logger = (req, res, next) => {
    console.log(`${req.method} ${req.url}`);
    next();
};
app.use(logger);


const validateUser = (req, res, next) => {
    const { names, email, password } = req.body;

    if (!names || !email || !password) {
        return res.status(400).json({ error: "All fields are required!" });
    }
    next();
};
const connection = mysql.createConnection({
    host:"localhost",
    user:"root",
    password:"",
    database:"last"
});

connection.connect((err)=>{
    if(err){
        console.log('error in connection');
        return;
    }
    console.log('connection is well done');
});


app.post('/insert', validateUser, (req, res) => {
    const { names, email, password } = req.body;
    connection.query(
        "INSERT INTO users(names,email,password) VALUES(?,?,?)",
        [names, email, password],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.status(201).json(result);
        }
    );
});

app.get('/select', (req, res) => {
    connection.query("SELECT * FROM users", (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.status(200).json({ select: result });
    });
});

app.put('/update/:id', (req, res) => {
    const {id} = req.params;
    const {names, email, password} = req.body;

    connection.query(
        "UPDATE users SET names = ?, email = ?, password = ? WHERE id = ?",
        [names, email, password, id],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.status(200).json({ message: "update is well done", result });
        }
    );
});

app.delete('/delete/:id', (req, res) => {
    const {id} = req.params;
    connection.query(
        "DELETE FROM users WHERE id = ?",
        [id],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.status(200).json({ message: "delete is well done", result });
        }
    );
});

const PORT = 5000;
app.listen(PORT, () => {
    console.log(`server is running at http://localhost:${PORT}`);
});
