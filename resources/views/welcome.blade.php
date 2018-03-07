@extends('layouts.app')

@section('title','Home')

@section('header_styles')
  <style type="text/css">
    #header{
      background-image: url("{{ asset('images/desk-background.jpg') }}");
      min-height: 500px;
    }
    #callToAction{
      background: #FFF;
      opacity: .6;
    }
    .yellow-text{
      color: #FCD307;
    }
    .grey-text{
      color: #717070;
    }
  </style>
@endsection

@section('content')
  <div id="header" class="ui grid">
    <div class="ten wide column">
        
    </div>
    <div id="callToAction" class="six wide column">
      <div class="middle aligned" style="height: 100%">
        <h1>LET'S <br> <span class="yellow-text">TRANSFORM</span> <br>THE WAY<br>WE WORK</h1>
        <h4 class="grey-text">Find help when you need it, <br>help others when you can.</h4>
      </div>
    </div>
  </div>
@endsection