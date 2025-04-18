<?php

require __DIR__ . '/vendor/autoload.php';

use OpenTelemetry\API\Globals;


// ─── Exemplo de tracer ─────────────────────────────────────────────────────
// $exampleTracer = $tracerProvider->getTracer('io.opentelemetry.contrib.php');
// $exampleTracer
//   ->spanBuilder('example')
//   ->startSpan()
//   ->end();


// ─── Configuração do OpenTelemetry (Tracer) ──────────────────────────────────
$tracerProvider = Globals::tracerProvider();
$tracer = $tracerProvider->getTracer('app');
error_log("Tracer created: " . get_class($tracer));


// ─── Configuração do OpenTelemetry (Meter) ──────────────────────────────────
$meterProvider = Globals::meterProvider();
/** @var MeterProviderInterface $meterProvider */
$meter = $meterProvider->getMeter('app');


// Captura de métricas com OpenTelemetry
$otelRequestsCounter = $meter->createCounter('requests_total', 'Total de requisições recebidas');
$otelErrorsCounter = $meter->createCounter('errors_total', 'Total de erros lançados');

// Incrementa métricas
$otelRequestsCounter->add(1);
$otelErrorsCounter->add(1);


// ─── Simulação de erro com throw ────────────────────────────────────────────
$span = $tracer
->spanBuilder('simulacao_de_erro')
->startSpan();

$traceId = $span->getContext()->getTraceId();
echo "Trace ID: $traceId\n";

try {
  $span->addEvent('Iniciando chamada para node-app', ['operation' => 'increment']);

  // chamar o endpoint do node...
  $client = new \GuzzleHttp\Client();
  $response = $client->get('http://node-app:3000/increment');
  sleep(10); // simula lentidão na requisição

  $span->addEvent('Terminou chamada para node-app', ['operation' => 'increment']);

  // Simulando um erro:
  throw new Exception("Simulação de erro na aplicação");
} catch (\Throwable $th) {
  $span->setAttribute('error', true);
  $span->recordException($th);
} finally {
  $span->end(); // sempre finaliza o span, mesmo com ou sem erro
}
