import './instrumentation.js'

import { metrics, trace } from '@opentelemetry/api';
const tracer = trace.getTracer('app-tracer');
const meter = metrics.getMeter('app-meter');

import express from 'express';
const app = express();


const requestCounter = meter.createCounter('minhas_requisicoes_total', {
  description: 'Contador de requisições feitas na rota /increment',
});


app.get('/', (req, res) => {
  res.send(`Hello OpenTelemetry! Acesse /error para simular um erro. Acesse /increment para incrementar a metic.`);
});


// Rota para simular um erro
app.get('/error', (req, res) => {
  const span = tracer.startSpan('processar-erro');

  try {
    throw new Error('Erro simulado para teste de trace!');
  } catch (error) {
    span.recordException(error);
    span.setStatus({ code: 2, message: error.message }); // 2 = ERROR
    res.status(500).send('Erro capturado e registrado no trace!');
  } finally {
    span.end();
  }
});


// Rota para incrementar a metic
app.get('/increment', (req, res) => {
  requestCounter.add(1, {
    rota: '/increment',
    metodo: req.method,
  });

  res.send('Métrica incrementada!');
});


app.listen(3000, () => {
  console.log('Servidor rodando na porta 3000');
});
