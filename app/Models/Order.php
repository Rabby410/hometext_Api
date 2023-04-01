<?php

namespace App\Models;

use App\Manager\OrderManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded =[];

    const STATUS_PENDING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_COMPLETED = 3;
    const SHIPMENT_STATUS_COMPLETED = 1;

    /**
     * @throws Exception
     */
    public function placeOrder(array $input, $auth)
    {
      $order_data = $this->prepareData($input, $auth);
      if(isset($order_data['error_description'])){
        return $order_data;
      }
      $order = self::query()->create($order_data['order_data']);
      return (new OrderDetails())->storeOrderDetails($order_data['order_details'],$order);
    }

    /**
     * @throws Exception
     */
    private function prepareData(array $input, $auth)
    {
       $price = OrderManager::handle_order_data($input);
       if(isset($price['error_description'])){
        return $price;
       }else{

           $order_data = [
               'customer_id' =>$input['orderSummary']['customer_id'],
               'sales_manager_id'=> $auth->id,
               'shop_id' => $auth->shop_id,
               'sub_total' => $price['sub_total'],
               'discount' => $price['discount'],
               'total' => $price['total'],
               'quantity' => $price['quantity'],
               'paid_amount' =>$input['orderSummary']['paid_amount'],
               'due_amount' =>$input['orderSummary']['due_amount'],
               'order_status' => self::STATUS_COMPLETED,
               'order_number' => OrderManager::generateOrderNumber($auth->shop_id),
               'payment_method_id'=> $input['orderSummary']['payment_method_id'],
               'payment_status'=> OrderManager::decidePaymentStatus($price['total'], $input['orderSummary']['paid_amount']),
               'shipment_status' => self::SHIPMENT_STATUS_COMPLETED,
       ];
       return ['order_data'=>$order_data, 'order_details'=>$price['order_details']];
       }
    }
}
