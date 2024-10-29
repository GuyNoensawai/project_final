<?php
    session_start();
    if (!isset($_SESSION['employee_login'])) {
        header("location: ../index.php");
    }


    require_once 'connect_db.php';

    try {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $object = new stdClass();
            $amount = 0;
            $product = $_POST['product'];
            
            $email = $_SESSION['employee_login'];


                    if (isset($_SESSION['employee_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["employee_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                

                        $phone = $row["phone"];
                        $address = $row["address"];
                        $username = $row["firstname"];

                
             }

            $stmt = $db->prepare('SELECT id, price, stock from sp_product order by id desc');
            if($stmt->execute()) {

                $queryproduct = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                     $items = array(
                         "id" => $id,
                         "price" => $price,
                         "stock" => $stock
                     );
                     array_push( $queryproduct, $items );
                }

                $db->beginTransaction();
                try {
                    for ($i = 0; $i < count($product); $i++) {
                        for ($j = 0; $j < count($queryproduct); $j++) {
                            if(intval($product[$i]['id']) == intval($queryproduct[$j]['id'])) {
                                $amount += intval($product[$i]['count']) * intval($queryproduct[$j]['price']);
                                $count = count($product);

                                // Decrease the stock
                                $newStock = intval($queryproduct[$j]['stock']) - intval($product[$i]['count']);
                                if ($newStock < 0) {
                                    throw new Exception('Insufficient stock for product ID: ' . $queryproduct[$j]['id']);
                                }
                                $updateStmt = $db->prepare('UPDATE sp_product SET stock = ? WHERE id = ?');
                                $updateStmt->execute([$newStock, $queryproduct[$j]['id']]);
                                
                                break;
                            }
                        }
                    }

                    $shipping = 0 * $count;
                    $vat = (($amount + $shipping) * 0.07);
                    $netamount = $amount + $shipping + $vat;
                    $transid = round(microtime(true) * 1000);
                    $productJson = json_encode($product);
                    $mil = time() * 1000;
                    $updated_at = date("Y-m-d h:i:sa");

                    $stmt = $db->prepare('INSERT INTO sp_transaction (transid, orderlist, amount, shipping, vat, netamount, operation, mil, updated_at, username, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                    if($stmt->execute([
                        $transid, $productJson, $amount, $shipping, $vat, $netamount, 'จัดส่งสําเร็จ', $mil, $updated_at, $username, $email, $phone, $address
                    ])) {
                        if($address != null) {
                            $object->RespCode = 200;
                            $object->RespMessage = 'success';
                            $object->Amount = new stdClass();
                            $object->Amount->Amount = $amount;
                            $object->Amount->Shipping = $shipping;
                            $object->Amount->Vat = $vat;
                            $object->Amount->Netamount = $netamount;

                            $db->commit();
                            http_response_code(200);
                        } else {
                            throw new Exception('Address is null');
                        }
                    } else {
                        throw new Exception('Insert transaction failed');
                    }
                } catch (Exception $e) {
                    $db->rollBack();
                    $object->RespCode = 500;
                    $object->RespMessage = 'Transaction failed: ' . $e->getMessage();
                    http_response_code(500);
                }
            } else {
                $object->RespCode = 500;
                $object->RespMessage = 'Failed to fetch product data';
                http_response_code(500);
            }
        } else {
            http_response_code(405);
        }

        echo json_encode($object);
    }
} catch(Exception $e) {     
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>