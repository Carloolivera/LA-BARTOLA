<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Payer;

class MercadoPago extends Controller
{
    protected $accessToken;
    protected $publicKey;

    public function __construct()
    {
        $this->accessToken = getenv('MERCADOPAGO_ACCESS_TOKEN');
        $this->publicKey = getenv('MERCADOPAGO_PUBLIC_KEY');

        // Configurar SDK de Mercado Pago
        if ($this->accessToken && $this->accessToken !== 'TU_ACCESS_TOKEN_AQUI') {
            SDK::setAccessToken($this->accessToken);
        }
    }

    /**
     * Crea una preferencia de pago para el carrito
     */
    public function crearPreferencia()
    {
        try {
            // Verificar que el usuario esté logueado
            if (!auth()->loggedIn()) {
                return redirect()->to('/login')->with('error', 'Debe iniciar sesión para continuar');
            }

            // Verificar que no sea admin o vendedor
            $user = auth()->user();
            if ($user && ($user->inGroup('admin') || $user->inGroup('vendedor'))) {
                return redirect()->to('/admin/pedidos')->with('error', 'Los administradores no pueden realizar pedidos como clientes');
            }

            // Obtener carrito de la sesión
            $carrito = session()->get('carrito') ?? [];

            if (empty($carrito)) {
                return redirect()->to('/carrito')->with('error', 'El carrito está vacío');
            }

            // Obtener datos del formulario
            $nombre_cliente = esc($this->request->getPost('nombre_cliente'));
            $tipo_entrega = esc($this->request->getPost('tipo_entrega'));
            $direccion = esc($this->request->getPost('direccion'));

            // Validar datos
            if (empty($nombre_cliente) || empty($tipo_entrega)) {
                return redirect()->to('/carrito')->with('error', 'Todos los campos son obligatorios');
            }

            if (!in_array($tipo_entrega, ['retiro', 'delivery'])) {
                return redirect()->to('/carrito')->with('error', 'Tipo de entrega inválido');
            }

            // Crear preferencia
            $preference = new Preference();

            // Agregar items del carrito
            $items = [];
            $total = 0;

            foreach ($carrito as $plato_id => $item) {
                $mpItem = new Item();
                $mpItem->id = (string)$plato_id;
                $mpItem->title = $item['nombre'];
                $mpItem->quantity = (int)$item['cantidad'];
                $mpItem->unit_price = (float)$item['precio'];
                $mpItem->currency_id = 'ARS';

                $items[] = $mpItem;
                $total += $item['precio'] * $item['cantidad'];
            }

            $preference->items = $items;

            // Configurar datos del pagador
            $payer = new Payer();
            $payer->name = $nombre_cliente;
            $payer->email = auth()->user()->email ?? 'cliente@labartola.com';
            $preference->payer = $payer;

            // Configurar URLs de retorno
            $baseUrl = base_url();
            $preference->back_urls = [
                'success' => $baseUrl . 'mercadopago/success',
                'failure' => $baseUrl . 'mercadopago/failure',
                'pending' => $baseUrl . 'mercadopago/pending'
            ];

            // Auto return después del pago aprobado
            $preference->auto_return = 'approved';

            // URL de notificación (webhook)
            $preference->notification_url = $baseUrl . 'mercadopago/webhook';

            // Metadata adicional
            $preference->external_reference = 'PEDIDO_' . auth()->id() . '_' . time();
            $preference->metadata = [
                'usuario_id' => auth()->id(),
                'nombre_cliente' => $nombre_cliente,
                'tipo_entrega' => $tipo_entrega,
                'direccion' => $direccion ?? '',
                'timestamp' => time()
            ];

            // Guardar preferencia
            $preference->save();

            // Guardar ID de preferencia en sesión para usar después
            session()->set('mercadopago_preference_id', $preference->id);
            session()->set('mercadopago_metadata', $preference->metadata);

            // Redirigir al checkout de Mercado Pago
            return redirect()->to($preference->init_point);

        } catch (\Exception $e) {
            log_message('error', 'Error al crear preferencia de Mercado Pago: ' . $e->getMessage());
            return redirect()->to('/carrito')->with('error', 'Error al procesar el pago. Por favor, intente nuevamente.');
        }
    }

    /**
     * Página de éxito después del pago
     */
    public function success()
    {
        $payment_id = $this->request->getGet('payment_id');
        $status = $this->request->getGet('status');
        $external_reference = $this->request->getGet('external_reference');

        // Guardar información del pago en sesión
        session()->set('last_payment', [
            'payment_id' => $payment_id,
            'status' => $status,
            'external_reference' => $external_reference
        ]);

        // Limpiar carrito
        session()->remove('carrito');

        return redirect()->to('/pedido')->with('success', '¡Pago realizado con éxito! Tu pedido está siendo procesado.');
    }

    /**
     * Página de error después del pago
     */
    public function failure()
    {
        $payment_id = $this->request->getGet('payment_id');
        $status = $this->request->getGet('status');

        log_message('info', "Pago rechazado - Payment ID: {$payment_id}, Status: {$status}");

        return redirect()->to('/carrito')->with('error', 'El pago no pudo ser procesado. Por favor, intente nuevamente con otro método de pago.');
    }

    /**
     * Página de pendiente después del pago
     */
    public function pending()
    {
        $payment_id = $this->request->getGet('payment_id');
        $status = $this->request->getGet('status');

        log_message('info', "Pago pendiente - Payment ID: {$payment_id}, Status: {$status}");

        // Limpiar carrito
        session()->remove('carrito');

        return redirect()->to('/pedido')->with('message', 'Tu pago está siendo procesado. Te notificaremos cuando se confirme.');
    }

    /**
     * Webhook para recibir notificaciones de Mercado Pago
     */
    public function webhook()
    {
        try {
            // Obtener datos del webhook
            $data = json_decode(file_get_contents('php://input'), true);

            // Log de la notificación
            log_message('info', 'Webhook de Mercado Pago recibido: ' . json_encode($data));

            // Verificar que sea una notificación de pago
            if (!isset($data['type']) || $data['type'] !== 'payment') {
                return $this->response->setStatusCode(200);
            }

            // Obtener ID del pago
            $payment_id = $data['data']['id'] ?? null;

            if (!$payment_id) {
                log_message('error', 'Webhook sin payment_id');
                return $this->response->setStatusCode(400);
            }

            // Consultar información del pago a Mercado Pago
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/{$payment_id}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}"
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                log_message('error', "Error al consultar pago {$payment_id}: HTTP {$httpCode}");
                return $this->response->setStatusCode(500);
            }

            $payment = json_decode($response, true);

            // Extraer información importante
            $status = $payment['status'] ?? '';
            $external_reference = $payment['external_reference'] ?? '';
            $metadata = $payment['metadata'] ?? [];

            log_message('info', "Pago {$payment_id} - Status: {$status}, Ref: {$external_reference}");

            // Si el pago fue aprobado, crear el pedido
            if ($status === 'approved') {
                $this->crearPedidoDesdeWebhook($payment, $metadata);
            }

            // Responder con 200 OK
            return $this->response->setStatusCode(200);

        } catch (\Exception $e) {
            log_message('error', 'Error en webhook de Mercado Pago: ' . $e->getMessage());
            return $this->response->setStatusCode(500);
        }
    }

    /**
     * Crea un pedido en la base de datos desde el webhook
     */
    private function crearPedidoDesdeWebhook($payment, $metadata)
    {
        try {
            $db = db_connect();
            $platoModel = model('App\Models\PlatoModel');

            $usuario_id = $metadata['usuario_id'] ?? null;
            $nombre_cliente = $metadata['nombre_cliente'] ?? 'Cliente';
            $tipo_entrega = $metadata['tipo_entrega'] ?? 'retiro';
            $direccion = $metadata['direccion'] ?? '';

            if (!$usuario_id) {
                log_message('error', 'Webhook sin usuario_id en metadata');
                return;
            }

            $notas = "A nombre de: {$nombre_cliente}\n";
            $notas .= "Tipo de entrega: {$tipo_entrega}\n";
            if ($tipo_entrega === 'delivery' && !empty($direccion)) {
                $notas .= "Direccion: {$direccion}\n";
            }
            $notas .= "Forma de pago: Mercado Pago\n";
            $notas .= "Payment ID: {$payment['id']}\n\n";
            $notas .= "Detalle del pedido:\n";

            // Crear pedidos para cada item
            foreach ($payment['additional_info']['items'] ?? [] as $item) {
                $plato_id = $item['id'];
                $cantidad = $item['quantity'];
                $subtotal = $item['unit_price'] * $cantidad;

                $pedidoData = [
                    'usuario_id' => $usuario_id,
                    'plato_id' => $plato_id,
                    'cantidad' => $cantidad,
                    'total' => $subtotal,
                    'estado' => 'pagado',
                    'notas' => $notas . $item['title'] . ' x' . $cantidad,
                    'payment_id' => $payment['id']
                ];

                $db->table('pedidos')->insert($pedidoData);

                // Descontar stock
                $plato = $platoModel->find($plato_id);
                if ($plato && $plato['stock_ilimitado'] == 0) {
                    $nuevoStock = $plato['stock'] - $cantidad;
                    $updateData = ['stock' => max(0, $nuevoStock)];

                    if ($nuevoStock <= 0) {
                        $updateData['disponible'] = 0;
                    }

                    $platoModel->update($plato_id, $updateData);
                }
            }

            log_message('info', "Pedido creado desde webhook - Usuario: {$usuario_id}, Payment ID: {$payment['id']}");

        } catch (\Exception $e) {
            log_message('error', 'Error al crear pedido desde webhook: ' . $e->getMessage());
        }
    }
}
