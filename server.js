const express = require('express');
const { Sequelize, DataTypes } = require('sequelize');
const bodyParser = require('body-parser');
require('dotenv').config();

const app = express();
app.use(bodyParser.json());

// Configurar o Sequelize com MySQL
const sequelize = new Sequelize(process.env.DB_URI, {
    dialect: 'mysql',
});

// Modelo de Denúncia
const Denuncia = sequelize.define('Denuncia', {
    descricao: {
        type: DataTypes.STRING,
        allowNull: false,
    },
    categoria: {
        type: DataTypes.STRING,
        allowNull: false,
    },
});

// Sincronizar o modelo com o banco de dados
sequelize.sync()
    .then(() => console.log("Modelo sincronizado com o banco de dados"))
    .catch(err => console.error("Erro ao sincronizar o modelo", err));

// Rota para enviar denúncia
app.post('/denuncias', async (req, res) => {
    try {
        const denuncia = await Denuncia.create(req.body);
        res.status(201).send(denuncia);
    } catch (error) {
        res.status(400).send(error);
    }
});

// Rota para acessar denúncias
app.get('/denuncias', async (req, res) => {
    try {
        const denuncias = await Denuncia.findAll();
        res.send(denuncias);
    } catch (error) {
        res.status(500).send(error);
    }
});

// Iniciar o servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor rodando na porta ${PORT}`);
});
