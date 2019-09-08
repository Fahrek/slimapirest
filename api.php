<?php use Slim\Slim;

require 'vendor/autoload.php';

$app = new Slim();
$db  = new mysqli('localhost', 'root', '', 'slimapirest');

$app->get('/products', static function() use($db) {
    $sql = $db->query('SELECT * FROM productos');

    while ($row = $sql->fetch_assoc()) {
        $prod[] = $row;
    }

    echo json_encode($prod);
});

$app->post('/products', static function() use($db, $app) {
    $sql = 'INSERT INTO productos VALUES (NULL, '
           ."'{$app->request->post('name' )}', "
           ."'{$app->request->post('des'  )}', "
           ."'{$app->request->post('price')}'  "
           . ')';

    $add = $db->query($sql);

    if ($add) {
        $res = ['STATUS' => 'true',  'message' => 'Producto creado correctamente'];
    } else {
        $res = ['STATUS' => 'false', 'message' => 'Error al crear producto'];
    }

    echo json_encode($res);
});

$app->put('/products/:id', static function($id) use($db, $app) {
   $sql = "UPDATE productos SET 
           name  = '{$app->request->post('name')}', 
           des   = '{$app->request->post('des')}', 
           price = '{$app->request->post('price')}' 
           WHERE id = {$id}";

   $update = $db->query($sql);

    if ($update) {
        $res = ['STATUS' => 'true',  'message' => 'Producto actualizado correctamente'];
    } else {
        $res = ['STATUS' => 'false', 'message' => 'Error al actualizar producto'];
    }

    echo json_encode($res);
});

$app->delete('/products/:id', static function($id) use($db, $app) {
    $sql = "DELETE FROM productos WHERE id = {$id}";

    $delete = $db->query($sql);

    if ($delete) {
        $res = ['STATUS' => 'true',  'message' => 'Producto eliminado correctamente'];
    } else {
        $res = ['STATUS' => 'false', 'message' => 'Error al eliminar producto'];
    }

    echo json_encode($res);
});

$app->run();
