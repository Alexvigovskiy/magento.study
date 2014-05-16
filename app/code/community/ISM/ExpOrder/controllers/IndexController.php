<?php

class ISM_Exporder_IndexController extends Mage_Core_Controller_Front_Action {

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
            //Get all items from this order
            $items = $order_model->getAllItems();
            //Get informstion about adrress
            $addres_id = $order_model->getShippingAddress()->getId();
            $_address = Mage::getModel('sales/order_address')->load($addres_id);
            //Get information about payment
            $payment = $order_model->getPayment();
            //Order info
            $export_array['id'] = $order_model->getRealOrderId();
            $export_array['total_price'] = $order_model->getBaseGrandTotal();
            $export_array['created'] = $order_model->getCreatedAtFormated('long');
            $export_array['total_item_count'] = count($items);
            $export_array['store_id'] = $order_model->getStoreId();
            //Shipping info
            $shipping_array['shipping_amount'] = $order_model->getBaseShippingAmount();
            $shipping_array['shipping_method'] = $order_model->getShippingMethod();
            $shipping_array['shipping_desc'] = $order_model->getShippingDescription();
            $export_array['shipping'] = $shipping_array;
            //Customer info
            $customer_array['customer_firstname'] = $order_model->getCustomerFirstname();
            $customer_array['customer_lastname'] = $order_model->getCustomerLastname();
            $customer_array['email'] = $order_model->getCustomerEmail();
            $export_array['customer'] = $customer_array;
            //Addres info
            $address_array['country_id'] = $_address->getCountryId();
            $address_array['postcode'] = $_address->getPostcode();
            $address_array['city'] = $_address->getCity();
            $str = $_address->getStreet();
            $address_array['street'] = $str[0];
            $address_array['telephone'] = $_address->getTelephone();
            $export_array['address'] = $address_array;
            //Payment info
            $payment_array['payment_method'] = $payment->getMethod();
            $payment_array['cc_owner'] = $payment->getCcOwner();
            $payment_array['cc_exp_month'] = $payment->getCcExpMonth();
            $payment_array['cc_exp_year'] = $payment->getCcExpYear();
            $payment_array['cc_type'] = $payment->getCcType();
            $payment_array['cc_number_enc'] = $payment->getCcNumberEnc();
            $payment_array['po_number'] = $payment->getPoNumber();
            $export_array['payment'] = $payment_array;
            //Items info
            foreach ($items as $item_id => $item) {
                if ($item->hasData('parent_item_id')) {
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

            //Prepare xml document
            $xml_file = $xml_path . "/" . $order_model->getRealOrderId() . ".xml";
            $doc = new DomDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
            $root = $doc->createElement('order');
            $root = $doc->appendChild($root);
            //Add all nodes
            foreach ($export_array as $field_name => $field_value) {
                $child = $doc->createElement($field_name);
                $child = $root->appendChild($child);
                if (is_array($field_value)) {
                    foreach ($field_value as $child_name => $child_value) {
                        if (strpos($child_name, 'item') !== false) {
                            $itm = $doc->createElement('item');
                        } else {
                            $itm = $doc->createElement($child_name);
                        }
                        $itm = $child->appendChild($itm);
                        $value = $doc->createTextNode($child_value);
                        $value = $itm->appendChild($value);
                        if (is_array($child_value)) {
                            foreach ($child_value as $item_name => $item_value) {
                                $child_itm = $doc->createElement($item_name);
                                $child_itm = $itm->appendChild($child_itm);
                                $value_itm = $doc->createTextNode($item_value);
                                $value_itm = $child_itm->appendChild($value_itm);
                            }
                        }
                    }
                } else {
                    $value = $doc->createTextNode($field_value);
                    $value = $child->appendChild($value);
                }
            }
            //Save all changes
            $order_model->setExported('1');
            $order_model->save();
            $doc->save($xml_file);
        }

        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

    public function importAction() {
        //Import data
        //Prepare all paths and models
        $magento_base_path = Mage::getBaseDir();
        $xml_path = $magento_base_path . "/var/export";
        $xml_file = $xml_path . "/" . $_GET["id"] . ".xml";
        $xml_doc = new DOMDocument();
        $xml_doc->load($xml_file);
        //Import all nodes from order xml(except items)
        $order = $xml_doc->getElementsByTagName('order');
        foreach ($order as $field_name => $field_value) {
            //Import store id
            $import_array['store_id'] = $xml_doc->getElementsByTagName('store_id')->item($field_name)->nodeValue;
            //Import shipping data
            $import_array['shipping_method'] = $xml_doc->getElementsByTagName('shipping_method')->item($field_name)->nodeValue;
            $import_array['shipping_amount'] = $xml_doc->getElementsByTagName('shipping_amount')->item($field_name)->nodeValue;
            $import_array['shipping_desc'] = $xml_doc->getElementsByTagName('shipping_desc')->item($field_name)->nodeValue;
            //Import payment data
            $import_array['payment_method'] = $xml_doc->getElementsByTagName('payment_method')->item($field_name)->nodeValue;
            $import_array['email'] = $xml_doc->getElementsByTagName('email')->item($field_name)->nodeValue;
            $import_array['cc_owner'] = $xml_doc->getElementsByTagName('cc_owner')->item($field_name)->nodeValue;
            $import_array['cc_exp_month'] = $xml_doc->getElementsByTagName('cc_exp_month')->item($field_name)->nodeValue;
            $import_array['cc_exp_year'] = $xml_doc->getElementsByTagName('cc_exp_year')->item($field_name)->nodeValue;
            $import_array['cc_type'] = $xml_doc->getElementsByTagName('cc_type')->item($field_name)->nodeValue;
            $import_array['cc_number_enc'] = $xml_doc->getElementsByTagName('cc_number_enc')->item($field_name)->nodeValue;
            $import_array['po_number'] = $xml_doc->getElementsByTagName('po_number')->item($field_name)->nodeValue;
        }
        //Import all items nodes from order xml
        $items = $xml_doc->getElementsByTagName('items');
        $count_of_items = $xml_doc->getElementsByTagName('total_item_count')->item($field_name)->nodeValue;
        for ($i = 0; $i <= $count_of_items - 1; $i++) {
            foreach ($items as $field_name => $field_value) {
                $import_sku_array['item_sku_' . $i] = $xml_doc->getElementsByTagName('item_sku_' . $i)->item($field_name)->nodeValue;
                $import_qty_array['item_qty_' . $i] = $xml_doc->getElementsByTagName('item_qty_' . $i)->item($field_name)->nodeValue;
            }
        }

        //Creating order
        //Set in wich store we want to create order 
        $store_id = $import_array['store_id'];
        //Load customer by email
        $customer_email = $import_array['email'];
        $customer = Mage::getModel("customer/customer")
                ->setWebsiteId($store_id)
                ->loadByEmail($customer_email);
        //Prepare transaction
        $transaction = Mage::getModel('core/resource_transaction');
        $reserved_order_id = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($store_id);
        //Set basic configuration data for our order
        $order = Mage::getModel('sales/order')
                ->setIncrementId($reserved_order_id)
                ->setStoreId($store_id)
                ->setQuoteId(0)
                ->setGlobal_currency_code('EUR')
                ->setBase_currency_code('EUR')
                ->setStore_currency_code('EUR')
                ->setOrder_currency_code('EUR');
        //Set customer data
        $order->setCustomer_email($customer->getEmail())
                ->setCustomerFirstname($customer->getFirstname())
                ->setCustomerLastname($customer->getLastname())
                ->setCustomerGroupId($customer->getGroupId())
                ->setCustomer_is_guest(0)
                ->setCustomer($customer);
        //Set billing data
        $billing = $customer->getDefaultBillingAddress();
        $billing_address = Mage::getModel('sales/order_address')
                ->setStoreId($store_id)
                ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
                ->setCustomerId($customer->getId())
                ->setCustomerAddressId($customer->getDefaultBilling())
                ->setCustomer_address_id($billing->getEntityId())
                ->setPrefix($billing->getPrefix())
                ->setFirstname($billing->getFirstname())
                ->setMiddlename($billing->getMiddlename())
                ->setLastname($billing->getLastname())
                ->setSuffix($billing->getSuffix())
                ->setCompany($billing->getCompany())
                ->setStreet($billing->getStreet())
                ->setCity($billing->getCity())
                ->setCountry_id($billing->getCountryId())
                ->setRegion($billing->getRegion())
                ->setRegion_id($billing->getRegionId())
                ->setPostcode($billing->getPostcode())
                ->setTelephone($billing->getTelephone())
                ->setFax($billing->getFax());
        //Set billing data to order
        $order->setBillingAddress($billing_address);
        //Set shipping data
        $shipping = $customer->getDefaultShippingAddress();
        $shipping_address = Mage::getModel('sales/order_address')
                ->setStoreId($store_id)
                ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
                ->setCustomerId($customer->getId())
                ->setCustomerAddressId($customer->getDefaultShipping())
                ->setCustomer_address_id($shipping->getEntityId())
                ->setPrefix($shipping->getPrefix())
                ->setFirstname($shipping->getFirstname())
                ->setMiddlename($shipping->getMiddlename())
                ->setLastname($shipping->getLastname())
                ->setSuffix($shipping->getSuffix())
                ->setCompany($shipping->getCompany())
                ->setStreet($shipping->getStreet())
                ->setCity($shipping->getCity())
                ->setCountry_id($shipping->getCountryId())
                ->setRegion($shipping->getRegion())
                ->setRegion_id($shipping->getRegionId())
                ->setPostcode($shipping->getPostcode())
                ->setTelephone($shipping->getTelephone())
                ->setFax($shipping->getFax());
        //Set shipping data to order
        $order->setShippingAddress($shipping_address)
                ->setShipping_method($import_array['shipping_method'])
                ->setShippingDescription($import_array['shipping_desc'])
                ->setShipping_amount($import_array['shipping_amount']);
        //Set order payment data
        $order_payment = Mage::getModel('sales/order_payment')
                ->setStoreId($store_id)
                ->setCustomerPaymentId(0)
                ->setMethod($import_array['payment_method']);
        if ($import_array['payment_method'] == 'purchaseorder') {
            $order_payment->setPoNumber($import_array['po_number']);
        }
        if ($import_array['payment_method'] == 'ccsave') {
            $order_payment->setCcOwner($import_array['cc_owner'])
                    ->setCcExpMonth($import_array['cc_exp_month'])
                    ->setCcExpYear($import_array['cc_exp_year'])
                    ->setCcType($import_array['cc_type'])
                    ->setCcNumberEnc($import_array['cc_number_enc']);
        }
        //Set order payment data to order
        $order->setPayment($order_payment);
        //Items
        $subtotal = 0;
        for ($i = 0; $i <= $count_of_items - 1; $i++) {
            $qty['qty'] = $import_qty_array['item_qty_' . $i];
            $products[Mage::getModel('catalog/product')->getIdBySku($import_sku_array['item_sku_' . $i])] = $qty;
        }
        foreach ($products as $product_id => $product) {
            $product_model = Mage::getModel('catalog/product')->load($product_id);
            $row_total = $product_model->getPrice() * $product['qty'];
            $order_item = Mage::getModel('sales/order_item')
                    ->setStoreId($store_id)
                    ->setQuoteItemId(0)
                    ->setQuoteParentItemId(NULL)
                    ->setProductId($product_id)
                    ->setProductType($product_model->getTypeId())
                    ->setQtyBackordered(NULL)
                    ->setTotalQtyOrdered($product['qty'])
                    ->setQtyOrdered($product['qty'])
                    ->setName($product_model->getName())
                    ->setSku($product_model->getSku())
                    ->setPrice($product_model->getPrice())
                    ->setBasePrice($product_model->getPrice())
                    ->setOriginalPrice($product_model->getPrice())
                    ->setRowTotal($row_total)
                    ->setBaseRowTotal($row_total);

            $subtotal += $row_total;
            $order->addItem($order_item);
        }
        //Set subtotal
        $order->setSubtotal($subtotal)
                ->setBaseSubtotal($subtotal)
                ->setGrandTotal($subtotal + $import_array['shipping_amount'])
                ->setBaseGrandTotal($subtotal + $import_array['shipping_amount']);
        //Try to save this order
        try {
            $transaction->addObject($order);
            $transaction->addCommitCallback(array($order, 'place'));
            $transaction->addCommitCallback(array($order, 'save'));
            $transaction->save();
        } catch (Exception $ex) {
            throw new Exception("Unable to import order");
        }
        //Shippment
        //Creating shipment to tjis order
        /* if ($order->canShip()) {
          $itemQty = $order->getItemsCollection()->count();
          $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
          $shipment = new Mage_Sales_Model_Order_Shipment_Api();
          $shipmentId = $shipment->create($order->getIncrementId());
          } */
        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

    public function productAction() {
        //Import data
        //Prepare all paths and models
        $magento_base_path = Mage::getBaseDir();
        $xml_path = $magento_base_path . "/var/export/products";
        $xml_file = $xml_path . "/" . $_GET["name"] . ".xml";
        $xml_doc = new DOMDocument();
        $xml_doc->load($xml_file);
        $product = Mage::getModel('catalog/product');
        //Import all nodes from product xml file
        $import_product = $xml_doc->getElementsByTagName('product');
        foreach ($import_product as $field_name => $field_value) {
            //Import product data 
            $import_array['name'] = $xml_doc->getElementsByTagName('name')->item($field_name)->nodeValue;
            $import_array['sku'] = $xml_doc->getElementsByTagName('sku')->item($field_name)->nodeValue;
            $import_array['type'] = $xml_doc->getElementsByTagName('type')->item($field_name)->nodeValue;
            $import_array['attribute_set'] = $xml_doc->getElementsByTagName('attribute_set')->item($field_name)->nodeValue;
            $import_array['category_ids'] = $xml_doc->getElementsByTagName('category_ids')->item($field_name)->nodeValue;
            $import_array['website_ids'] = $xml_doc->getElementsByTagName('website_ids')->item($field_name)->nodeValue;
            $import_array['description'] = $xml_doc->getElementsByTagName('description')->item($field_name)->nodeValue;
            $import_array['short_description'] = $xml_doc->getElementsByTagName('short_description')->item($field_name)->nodeValue;
            $import_array['price'] = $xml_doc->getElementsByTagName('price')->item($field_name)->nodeValue;
            $import_array['weight'] = $xml_doc->getElementsByTagName('weight')->item($field_name)->nodeValue;
            $import_array['status'] = $xml_doc->getElementsByTagName('status')->item($field_name)->nodeValue;
            $import_array['tax_class_id'] = $xml_doc->getElementsByTagName('tax_class_id')->item($field_name)->nodeValue;
            $import_array['is_in_stock'] = $xml_doc->getElementsByTagName('is_in_stock')->item($field_name)->nodeValue;
            $import_array['qty'] = $xml_doc->getElementsByTagName('qty')->item($field_name)->nodeValue;
        }
        //Set main product data
        $product->setSku($import_array['sku'])
                ->setAttributeSetId($import_array['attribute_set'])
                ->setTypeId($import_array['type'])
                ->setName($import_array['name'])
                ->setCategoryIds(explode('/', $import_array['category_ids']))
                ->setWebsiteIDs(explode('/', $import_array['website_ids']))
                ->setDescription($import_array['description'])
                ->setShortDescription($import_array['short_description'])
                ->setPrice($import_array['price']);
        //Set default attributes data
        $product->setWeight($import_array['weight'])
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->setStatus($import_array['status'])
                ->setTaxClassId($import_array['tax_class_id'])
                ->setStockData(array(
                    'is_in_stock' => $import_array['is_in_stock'],
                    'qty' => $import_array['qty']
        ));
        //Set created at time
        $product->setCreatedAt(strtotime('now'));
        //try to save this product
        try {
            $product->save();
        } catch (Exception $ex) {
            throw new Exception("Unable to import product");
        }
        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sortAction() {
        //Import data
        //Prepare all paths and models
        $magento_base_path = Mage::getBaseDir();
        //Get path to input files
        $xml_path_input = $magento_base_path . "/var/export/products/input";
        //Get path to success imported files
        $xml_path_success = $magento_base_path . "/var/export/products/success";
        //Get path to failed imported files
        $xml_path_failed = $magento_base_path . "/var/export/products/failed";
        $xml_file_input = $xml_path_input . "/" . $_GET["name"] . ".xml";
        //Create document
        $xml_doc = new DOMDocument();
        $xml_doc->load($xml_file_input);
        //Import all nodes from products xml file
        $root = $xml_doc->getElementsByTagName('products');
        foreach ($root as $product) {
            $import_product = $product->getElementsByTagName('product');
            foreach ($import_product as $node) {
                //Import product data 
                $import_array['name'] = $node->getElementsByTagName('name')->item(0)->nodeValue;
                $import_array['sku'] = $node->getElementsByTagName('sku')->item(0)->nodeValue;
                $import_array['type'] = $node->getElementsByTagName('type')->item(0)->nodeValue;
                $import_array['attribute_set'] = $node->getElementsByTagName('attribute_set')->item(0)->nodeValue;
                $import_array['category_ids'] = $node->getElementsByTagName('category_ids')->item(0)->nodeValue;
                $import_array['website_ids'] = $node->getElementsByTagName('website_ids')->item(0)->nodeValue;
                $import_array['description'] = $node->getElementsByTagName('description')->item(0)->nodeValue;
                $import_array['short_description'] = $node->getElementsByTagName('short_description')->item(0)->nodeValue;
                $import_array['price'] = $node->getElementsByTagName('price')->item(0)->nodeValue;
                //Import default attribute data 
                $def_att_array['weight'] = $node->getElementsByTagName('weight')->item(0)->nodeValue;
                $def_att_array['status'] = $node->getElementsByTagName('status')->item(0)->nodeValue;
                $def_att_array['tax_class_id'] = $node->getElementsByTagName('tax_class_id')->item(0)->nodeValue;
                //Import stock data
                $stock_array['is_in_stock'] = $node->getElementsByTagName('is_in_stock')->item(0)->nodeValue;
                $stock_array['qty'] = $node->getElementsByTagName('qty')->item(0)->nodeValue;
                $def_att_array['stock_data'] = $stock_array;
                $import_array['default_attributes'] = $def_att_array;
                //Get product module
                $product_model = Mage::getModel('catalog/product');
                //Set product data
                $product_model->setSku($import_array['sku'])
                        ->setAttributeSetId($import_array['attribute_set'])
                        ->setTypeId($import_array['type'])
                        ->setName($import_array['name'])
                        ->setCategoryIds(explode('/', $import_array['category_ids']))
                        ->setWebsiteIDs(explode('/', $import_array['website_ids']))
                        ->setDescription($import_array['description'])
                        ->setShortDescription($import_array['short_description'])
                        ->setPrice($import_array['price']);
                //Set default attributes data
                $product_model->setWeight($def_att_array['weight'])
                        ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                        ->setStatus($def_att_array['status'])
                        ->setTaxClassId($def_att_array['tax_class_id'])
                        ->setStockData(array(
                            'is_in_stock' => $stock_array['is_in_stock'],
                            'qty' => $stock_array['qty']
                ));
                //Set created at time
                $product_model->setCreatedAt(strtotime('now'));
                //Set succes and failed product xml files
                $success_file = $xml_path_success . "/" . $import_array['sku'] . ".xml";
                $failed_file = $xml_path_failed . "/" . $import_array['sku'] . ".xml";
                //Prepare documenet
                $doc = new DomDocument('1.0', 'UTF-8');
                $doc->preserveWhiteSpace = false;
                $doc->formatOutput = true;
                $root = $doc->createElement('product');
                $root = $doc->appendChild($root);
                foreach ($import_array as $field_name => $field_value) {
                    $child = $doc->createElement($field_name);
                    $child = $root->appendChild($child);
                    if (is_array($field_value)) {
                        foreach ($field_value as $child_name => $child_value) {
                            $def = $doc->createElement($child_name);
                            $def = $child->appendChild($def);
                            $def_value = $doc->createTextNode($child_value);
                            $def_value = $def->appendChild($def_value);
                            if (is_array($child_value)) {
                                foreach ($child_value as $sub_name => $sub_value) {
                                    $stock_child = $doc->createElement($sub_name);
                                    $stock_child = $def->appendChild($stock_child);
                                    $stock_value = $doc->createTextNode($sub_value);
                                    $stock_value = $stock_child->appendChild($stock_value);
                                }
                            }
                        }
                    } else {
                        $value = $doc->createTextNode($field_value);
                        $value = $child->appendChild($value);
                    }
                }
                //try to save and sort this product
                try {
                    $product_model->save();
                    $doc->save($success_file);
                } catch (Exception $ex) {
                    $doc->save($failed_file);
                }
            }
        }
        //Delete input files
        unlink($xml_file_input);
        //Load layout
        $this->loadLayout();
        $this->renderLayout();
    }

}
