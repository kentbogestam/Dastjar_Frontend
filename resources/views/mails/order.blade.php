<section style="background-color: lightgray;padding: 10px">

    <a href="anar.dastjar.com"><h1 style="float: left;margin-left: 50px">Anar</h1></a> <a href="anar.dastjar.com"><img src="{{ $message->embed('images/l-logo.png') }}" style="float: right;margin-right: 50px"></a><br/><br/><br/><br/><br/>

    <div style="text-align: center">
    
    <p style="font-size: 20px">Hi {{ ucwords($data->name) }},</p>
    <p style="font-size: 20px">Your order is placed successfully.</p>


    <div style="background-color: #FFFFFF;padding: 10px;max-width: 600px;margin: 0 auto">

    @foreach($data['prod'] as $item)
    <div>
        <?php $item = $item->toArray(); ?>
        <b>{{ $item['product_name'] }}</b><br/><br/>
        <img src="{{ $message->embed(asset('storage/'.$item['image'])) }}" height="150px" width="150px"><br/><br/>
        <span>Quantity:  <b>{{ $item['product_quality'] }}</b></span><br/><br/>
        <span>Price:  <b>Rs. {{ $item['price'] }}</b></span><br/>
    </div><br/><br/>
    @endforeach


        <span>Order ID #{{ $data['ord']->CCNumber }} | Date {{ date('l, d F Y',strtotime($data['ord']->created_at)) }}</span>
    </div>

        <br/><br/>

        <span>Delivery Address</span><br/>
        <span>{{  $data['ord']->deliver_address }}</span>
    </div>

</section>