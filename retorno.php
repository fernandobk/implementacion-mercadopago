<?php
$data = (object)$_GET;

echo '<center>';
switch($data->collection_status){
    case 'failure':
        echo '
            <h1>Parece que hubo un error con el pago. No se efectuó.</h1>
        ';
        break;

        case 'pending':
        case 'in_process':
            echo '
                <h1>¡Perfecto! Ahora esperaremos a recibir tu pago.<br />
                Nos comunicaremos con usted cuando eso ocurra.<br />
                Muchas gracias.</h1>
            ';
        break;

    case 'approved': 
        echo '<h1>¡Pago aprobado!<br />¡Muchas gracias por su compra!</h1>';

        echo '<h3><i>';
        switch($_GET['payment_type']){
            case 'credit_card': echo 'Pagó con tarjeta de crédito.<br />'; break;
            case 'debit_card': echo 'Pagó con tarjeta de débito.<br />'; break;
            case 'ticket': case 'Pagó en kiosco o comercio.<br />'; break;
            default: echo 'Tipo de pago: ' . $_GET['payment_type'];
        }
        echo '</i></h3>';

        echo "<kbd>
            Referencia: $data->external_reference<br />
            ID del pago: $data->collection_id<br />
            Preferencia: $data->preference_id<br />
        </kbd><hr />";
    break;

    default: echo 'Error encontrando el estado del pago.';
}

exit('<br /><a href="/">Ir a Inicio</a>');
