<?php

class ISM_Exporder_AllController extends Mage_Core_Controller_Front_Action {

    public function exportAction() {
        //Export data
        //Prepare all paths and models
        $magento_base_path = Mage::getBaseDir();
        $xml_path = $magento_base_path . "/var/export";
        $orders_collection = Mage::getResourceModel('sales/order_collection')->addFieldToFilter('exported', array('null' => true))->addAttributeToFilter('total_due', 0);
        $all_id = $orders_collection->getAllIds();
        //Export all orders
        foreach ($all_id as $this_id) {
            $order_model = Mage::getModel('sales/order')->load($this_id);
            $export_array['id'] = $order_model->getRealOrderId();
            //Get all items from this order
            $items = $order_model->getAllItems();
            //Items info
            foreach ($items as $item_id => $item) {
                if ($order_model->hasData($item->getParentItemId())) {
                    $items_array['name'] = $item->getName();
                    $items_array['type'] = $item->getProductType();
                    $items_array['price'] = $item->getPrice();
                    $items_array['sku'] = $item->getSku();
                    $items_array['prodid'] = $item->getProductId();
                    $items_array['qty'] = $item->getQtyOrdered();
                    $items_array['discount'] = $item->getDiscountAmount();
                }
                $all_items_array['item_' . $item_id] = $items_array;
                unset($items_array);
            }
            unset($items);
            $export_array['items'] = $all_items_array;
            unset($all_items_array);
            $all_orders['order_' . $this_id] = $export_array;
        }
        //Prepare xml document
        $xml_file = $xml_path . "/orders.xml";
        $doc = new DomDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $root = $doc->createElement('orders');
        $root = $doc->appendChild($root);
        foreach ($all_orders as $field_name => $field_value) {
            $child = $doc->createElement('order');
            $child = $root->appendChild($child);
            foreach ($field_value as $order_name => $order_value) {
                if (is_array($order_value)) {
                    $order_node = $doc->createElement($order_name);
                    $order_node = $child->appendChild($order_node);
                    $val = $doc->createTextNode($order_value);
                    $val = $order_node->appendChild($val);
                    foreach ($order_value as $child_name => $child_value) {
                        $itm = $doc->createElement('item');
                        $itm = $order_node->appendChild($itm);
                        $value = $doc->createTextNode($child_value);
                        $value = $itm->appendChild($value);
                        foreach ($child_value as $item_name => $item_value) {
                            $child_itm = $doc->createElement($item_name);
                            $child_itm = $itm->appendChild($child_itm);
                            $value_itm = $doc->createTextNode($item_value);
                            $value_itm = $child_itm->appendChild($value_itm);
                        }
                    }
                } else {
                    $order_node = $doc->createElement($order_name);
                    $order_node = $child->appendChild($order_node);
                    $val = $doc->createTextNode($order_value);
                    $val = $order_node->appendChild($val);
                }
            }
        }
        $doc->save($xml_file);
        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

    public function importAction() {
        //Import data
        //Prepare all paths and models
        $magento_base_path = Mage::getBaseDir();
        $xml_path = $magento_base_path . "/var/export";
        $xml_file = $xml_path . "/" . $_GET["file"] . ".xml";
        $xml_doc = new DOMDocument();
        $xml_doc->load($xml_file);
        //Import all nodes from order xml(except items)
        $root = $xml_doc->getElementsByTagName('orders');
        foreach ($root as $field_name) {
            $all_orders = $field_name->getElementsByTagName('order');
            foreach ($all_orders as $order) {
                $import_array['id'] = $order->getElementsByTagName('id')->item(0)->nodeValue;
                $all_items = $order->getElementsByTagName('item');
                foreach ($all_items as $item) {
                    $import_array['qty'] = $import_array['qty'] + $item->getElementsByTagName('qty')->item(0)->nodeValue;
                }
                $order_model = Mage::getModel('sales/order')->loadByIncrementId($import_array['id']);
                if ($order_model->canShip()) {
                    $shipment = Mage::getModel('sales/service_order', $order_model)->prepareShipment($import_array['qty']);
                    $shipment = new Mage_Sales_Model_Order_Shipment_Api();
                    $shipmentId = $shipment->create($import_array['id']);
                }
                unset($import_array['qty']);
            }
        }
        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

}
