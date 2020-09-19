@extends('layouts.master')

@section('link-to-terms')
side-nav-active
@endsection

@section('main')
<div class="col-12 col-md-6 clean-white pt-2">
  <h3>Terms Of Service</h3>
  <ul>
    <li>We don’t charge you to use Connect or the other products and services covered by these Terms</li>
    <li>We neither sell you data nor analyze your data.</li>
    <li>You give us permission to store your data</li>
    <li>For more information, check our <a href="{{url('/privacy')}}">Data Privacy</a></li>
  </ul>
</div>
@endsection
