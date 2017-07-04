<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
</head>
<body>
	<h1>Hola mundo</h1>
	<p>Te enviamos los datos de tu compra realizada den ProductsFacilito</p>
	<table>
		<thead>
			<tr>
				<th>Producto</th>
				<th>Costo</th>
			</tr>
		</thead>

		<tbody>
			@foreach($products as $product)
			<tr>
				<td>{{$product->title}}</td>
				<td>{{$product->pricing}}</td>
			</tr>
			@endforeach
			<tr>
				<td>Total</td>
				<td>{{$order->total}}</td>
			</tr>
		</tbody>



	</table>
</body>
</html>