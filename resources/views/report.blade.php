<!DOCTYPE html>
<html>
<head>
<style>
    
*{
  margin:0;
  padding:0;
}
#head{

}
.title{
  text-align:center;
  padding: 10px 0;
}
.subTitle{
  text-align:center;
  padding:10px 0;
}

#items {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#items td, #items th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align:center;
}

#items tr:nth-child(even){background-color: #f2f2f2;}

#items tr:hover {background-color: #ddd;}

#items th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align:center;
  background-color: #4CAF50;
  color: white;
}
.table_wrapper{
  padding: 0 10%;
}
    
#listname{
  padding:10px;
}
    
#desc{
  padding: 10px 10%;
}
#descmsg{
  padding: 10px 0;
}
#line{
  margin: 10px 10%;
}
#linebetweeb{
  margin: 10px 0;
}
.container{
  padding: 10px 0;
}


.column {
  float: left;
  width: 200px;
  /* padding: 5px; */
  margin:2px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}
</style>
</head>
<body>
<div class="container">
<div class="title">
<h1>Loyal Partners<h1>
</div>
<div class="subTitle">
<h3>{{$data->property_name}}</h3>
<p>Inspected By : {{$data->inspectorEmail}} </p>
</div>

<div id="desc">
<h3 id="descmsg">Description</h3>
<hr id="linebetween"/>
<p>{{$data->description}}</p>
</div>
<hr id="line"/>
<div class="table_wrapper">

@foreach($data->propertylist as $plist)
<p id="listname">
{{$plist->listname}}
</p>
<table id="items">
  <tr>
    <th>name</th>
    <th>clean</th>
    <th>unclean</th>
    <th>Work needed </th>
    <th>Description </th>
  </tr>
  @foreach($plist->propertyitems as $pItems)
  <tr>
    <td>{{$pItems->name}}</td>
    @if($pItems->clean==0)
    <td>No</td>
    @else
    <td>Yes</td>
    @endif
    @if($pItems->unclean==0)
    <td>No</td>
    @else
    <td>Yes</td>
    @endif
    @if($pItems->work_needed==0)
    <td>No</td>
    @else
    <td>Yes</td>
    @endif
    <td>{{$pItems->description}}</td>
  </tr>
  @endforeach
  <div class="row">
  @foreach($plist->images as $pImages)
  <div class="column">
  <img src={{$pImages->image}} style="height:200px"/>
  </div>
  @endforeach
  </div>
</table>
@endforeach
</div>
<div class="subTitle">
<h3>Review</h3>
<p>{{$data->reviews}} </p>
</div>
</div>
</body>
</html>
