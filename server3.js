const express = require('express');
const sql = require('mssql');
const multer = require('multer');
const fs = require('fs').promises;
const path = require('path');
const moment = require('moment-timezone');

const app = express();
const port = 3000;

const upload = multer({ dest: 'uploads/' });
const logFile = path.join(__dirname, 'app_combined.log');
const localTimeZone = 'America/Santiago';

const log = async (message) => {
    const timestamp = new Date().toISOString();
    const logMessage = `${timestamp} - ${message}\n`;
    await fs.appendFile(logFile, logMessage);
    console.log(logMessage);
};

const dbConfig = {
    user: 'SA',
    password: 'Aperfect3316.',
    server: 'localhost',
    database: 'Cabalza',
    options: {
        encrypt: true,
        trustServerCertificate: true,
        connectionTimeout: 30000,
        requestTimeout: 30000
    }
};

async function connectToDatabase() {
    try {
        await sql.connect(dbConfig);
        await log('Conexión exitosa a la base de datos SQL Server');
    } catch (err) {
        await log(`Error al conectar a la base de datos: ${err.message}`);
        setTimeout(connectToDatabase, 5000);
    }
}

connectToDatabase();

app.use(async (req, res, next) => {
    await log(`${req.method} ${req.url}`);
    await log(`Headers: ${JSON.stringify(req.headers)}`);
    next();
});

app.post('/log', upload.any(), async (req, res) => {
    await handleEvent(req, res, 'Registro');
});

app.post('/cd', upload.any(), async (req, res) => {
    await handleEvent(req, res, 'Central');
});

async function handleEvent(req, res, tableName) {
    await log(`Recibida solicitud POST en /${tableName.toLowerCase()}`);

    try {
        await log(`Cuerpo de la solicitud: ${JSON.stringify(req.body)}`);

        let eventData;
        if (req.body && req.body.event_log) {
            eventData = JSON.parse(req.body.event_log);
        }

        if (!eventData) {
            throw new Error('No se recibieron datos de evento');
        }

        await log(`Datos de evento procesados: ${JSON.stringify(eventData)}`);

        if (eventData.AccessControllerEvent) {
            await handleAccessControllerEvent(eventData, tableName);
        } else {
            throw new Error('Tipo de evento no reconocido');
        }

        res.status(200).json({ message: `Datos procesados con éxito en la tabla ${tableName}` });
    } catch (err) {
        await log(`Error en el procesamiento de la solicitud para ${tableName}: ${err.message}`);
        res.status(500).json({ error: `Error al procesar la solicitud para ${tableName}`, details: err.message });
    }
}

async function handleAccessControllerEvent(eventData, tableName) {
    await log(`Procesando evento de AccessControllerEvent para ${tableName}`);

    const {
        dateTime,
        macAddress,
        AccessControllerEvent: {
            deviceName,
            employeeNoString,
            name,
            attendanceStatus,
            serialNo
        }
    } = eventData;

    if (!dateTime || !deviceName) {
        throw new Error('Faltan datos obligatorios');
    }

    const deviceIdentifier = `${deviceName}_${macAddress}`;
    const localDateTime = moment.tz(dateTime, localTimeZone);

    await log(`Original dateTime: ${dateTime}`);
    await log(`Local dateTime: ${localDateTime.format()}`);
    await log(`Server time: ${new Date().toISOString()}`);

    const employeeID = employeeNoString || '';
    const direction = attendanceStatus || '';
    const personName = name || '';
    const pool = await sql.connect(dbConfig);

    const query = `
        INSERT INTO ${tableName} (employeeID, authDateTime, authDate, authTime, direction, deviceName, deviceSN, personName, cardNo)
        VALUES (@employeeID, @authDateTime, @authDate, @authTime, @direction, @deviceName, @deviceSN, @personName, @cardNo)
    `;

    await log(`Ejecutando query en la tabla ${tableName}: ${query}`);

    await pool.request()
        .input('employeeID', sql.VarChar(50), employeeID)
        .input('authDateTime', sql.DateTime2, new Date(dateTime))
        .input('authDate', sql.Date, new Date(dateTime))
        .input('authTime', sql.Time, new Date(dateTime))
        .input('direction', sql.VarChar(50), direction)
        .input('deviceName', sql.VarChar(50), deviceName)
        .input('deviceSN', sql.VarChar(50), deviceIdentifier)
        .input('personName', sql.VarChar(50), personName)
        .input('cardNo', sql.VarChar(50), '')
        .query(query);

    await log(`Datos de evento de acceso insertados con éxito en ${tableName}. Serial del evento: ${serialNo}`);
}

app.get('/', async (req, res) => {
    await log('Solicitud GET recibida en la ruta raíz');
    res.json({ message: 'API combinada de Control de Acceso Hikvision funcionando' });
});

app.listen(port, '0.0.0.0', async () => {
    try {
        await log(`Servidor API combinado escuchando en el puerto ${port}`);
    } catch (err) {
        await log(`Error al iniciar el servidor en el puerto ${port}: ${err.message}`);
        process.exit(1);
    }
});

process.on('unhandledRejection', (reason, promise) => {
    console.log('Unhandled Rejection at:', promise, 'reason:', reason);
});