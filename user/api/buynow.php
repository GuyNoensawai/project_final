<?php
    session_start();
    if (!isset($_SESSION['user_login'])) {
        header("location: ../index.php");
    }


    require_once 'connect_db.php';

    try {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $object = new stdClass();
            $amount = 0;
            $totalWeight = 0;
            $product = $_POST['product'];
            
            $email = $_SESSION['user_login'];


                    if (isset($_SESSION['user_login'])) {
                    $select_stmt = $db->prepare("SELECT * FROM masterlogin WHERE email = '".$_SESSION["user_login"]."'");
                    $select_stmt->execute();

                    while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
                

                        $phone = $row["phone"];
                        $address = $row["address"];
                        $username = $row["firstname"];

                
             }

             $stmt = $db->prepare('SELECT id, price, stock, weight FROM sp_product ORDER BY id DESC');
             if ($stmt->execute()) {
 
                 $queryproduct = array();
                 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                     $items = array(
                         "id" => $row['id'],
                         "price" => $row['price'],
                         "stock" => $row['stock'],
                         "weight" => $row['weight']
                     );
                     array_push($queryproduct, $items);
                 }
 
                 $db->beginTransaction();
                 try {
                     foreach ($product as $pd) {
                         foreach ($queryproduct as $qryPd) {
                             if (intval($pd['id']) == intval($qryPd['id'])) {
                                 $amount += intval($pd['count']) * intval($qryPd['price']);
                                 $totalWeight += floatval($pd['count']) * floatval($qryPd['weight']);
 
                                 // Decrease the stock
                                 $newStock = intval($qryPd['stock']) - intval($pd['count']);
                                 if ($newStock < 0) {
                                     throw new Exception('Insufficient stock for product ID: ' . $qryPd['id']);
                                 }
                                 $updateStmt = $db->prepare('UPDATE sp_product SET stock = ? WHERE id = ?');
                                 $updateStmt->execute([$newStock, $qryPd['id']]);
                                 
                                 break;
                             }
                         }
                     }
 
                    $shipping = 0;

                    if ($totalWeight <= 50000) {
                     for ($i = 1000; $i <= 50000; $i += 1000) {
                         if ($totalWeight <= $i) {
                             $shipping = 30 + (($i / 1000 - 1) * 10);
                             break;
                         }
                    }
                    } else if ($totalWeight >= 50001 && $totalWeight <= 200000) {
                        $shipping = 520;
                    } else if ($totalWeight >= 200001 && $totalWeight <= 999999) {
                        $shipping = 2500;
                    } else if ($totalWeight >= 1000000 && $totalWeight <= 7500000) {
                        $shipping = 5000;
                    } else if ($totalWeight >= 7500001 && $totalWeight <= 10000000) {
                        $shipping = 10000;
                    } else if ($totalWeight >= 10000001) {
                        $shipping = 15000;
                    }

                    $vat = (($amount + $shipping) * 0.07);
                    $netamount = $amount + $shipping + $vat;
                    $transid = round(microtime(true) * 1000);
                    $productJson = json_encode($product);
                    $mil = time() * 1000;
                    $updated_at = date("Y-m-d h:i:sa");

                    $stmt = $db->prepare('INSERT INTO sp_transaction (transid, orderlist, amount, shipping, vat, netamount, operation, mil, updated_at, username, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                    if($stmt->execute([
                        $transid, $productJson, $amount, $shipping, $vat, $netamount, 'รอชำระเงิน', $mil, $updated_at, $username, $email, $phone, $address
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