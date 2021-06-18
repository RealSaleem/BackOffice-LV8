@extends('layouts.wizard')
@section('content')

    <!-- col1 -->

    <div class="w-1">

        <div class="m-col">

            <div class="row">

                <div class="col-xl-12 col-md-12 m-lg-5 ">

                    <div class="card shadow border-0 h-100 py-2">

                        <div class="card-body pb-0">

                            <div class="text-center"><img src="backoffice/assets/img/icon1.png"></div>

                            <h2 class="mt-4 text-center">Store Setup</h2>

                            <div class="w-forms ml-lg-5 mr-lg-5">

                                <form>

                                    <div class="form-row">

                                        <div class="col-md-6 mb-2">

                                            <label for="validationDefault01">Store Name</label>

                                            <input type="text" class="form-control" id="validationDefault01" placeholder="Fashion Hub" required>

                                        </div>

                                        <div class="col-md-6 mb-2">

                                            <label for="">Industry</label>
                                            <select name="" class="form-control m-b select2 " id="industry"  required>
                                            <option value="abc">abc</option>
                                            <option value="def">def</option>
                                            </select>

                                        </div>

                                        <div class="col-md-6 rounded">
					                    <label for="">Currency</label>
					                <select name="currency" id="currency" form="form" class="form-control m-b select2" value="" >
						             <option value="KWD">KWD</option>
						            <option value="PKR">PKR</option>
                                     <option value="USD">USD</option>
				               	</select>
				                    
                                    </div>
                                        <div class="col-md-6 mb-3">

                                    <div class="custom-file">

                             <label for="">Logo</label>

                        <input type="file" class="form-control form-logo" placeholder="Add Logo" id="customFile">

                                     </div>

                                    </div>
                                    </div>

                                    <h2 class="mt-2 text-center">Receipt Information</h2>

                                    <div class="form-row">

                                            <label for="">Disclaimer</label>

                                           
                                    <i class="" style="font-size:20px;color:grey"></i></a>
                                            <textarea rows="6"  class="form-control rounded " type="text" required></textarea>
                                        <div class="col-md-12 mb-6">

                                        </div>

                                        

                                    </div>

                                    <h2 class="mt-2 text-center">Address</h2>

                                    <div class="">

                                        <div class="col-md-12 mb-3">

                                            <label for="">Address</label>

                                            <input type="text" class="form-control" id="mymap" placeholder="117 street" required>

                                        </div>

                                       
                                        <div class="row">
                            <div class="col-md-12 rounded p-4 mb-1">
                             <div class="form-group">

              <div id="map" style="width:100%;height:400px"></div>
              <input form="form" style="display: none;" class="form-control" value="67.0589736" name="longitude" placeholder="Longitude" id="longitude" type="decimal" readonly>
              <input form="form" style="display: none;" class="form-control" value="24.9554899" name="latitude" placeholder="Latitude" id="latitude" type="decimal" readonly>
            </div>
          </div>
        </div>
                                        
                                     </div>
                                      </div>

                                </form>

                            <div class="top bottom-btns row pb-0 pt-3 p-2 mt-5">

                                <div class="col-md-6 text-lg-left">

                                    <button type="submit" class="btn btn-grey mb-2 text-muted">Cancel</button>

                                </div>

                                <div class="col-md-6 text-lg-right">
                                    <!-- <a href="{{ url('wizard.add2')}}"  class="btn m-b-xs v  btn-danger pull-right">Next step</a> -->

                                    <button type="submit" class="btn btn-grey mb-2 text-muted"><i class="icon-arrow-left"></i> Previous Step </button>

                                    <button type="submit" class="btn btn-primary mb-2">Next Step <i class="icon-arrow-right"></i> </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- col2 -->

    <div class="w-2">

        <div class="right_content">

            <ul>

                <li class="active">

                    <span class="active filled"><i class="fa fa-check"></i></span>

                    <h3 class="mb-2">Store Setup</h3>

                    <p>Exercitation mus! Ipsam pulvinar ratione aliquid, aperiam optio. Sed rerum, nullam leo! Ut enim omnis! Provident itaque montes! Ex reprehenderit, felis sequi</p>

                </li>

                <li>

                    <span class="unfilled">2</span>

                    <h3 class="mb-2">Import Catalogue</h3>

                    <p>Exercitation mus! Ipsam pulvinar ratione aliquid, aperiam optio. Sed rerum, nullam leo! Ut enim omnis! Provident itaque montes! Ex reprehenderit, felis sequi</p>

                </li>

                <li>

                    <span class="unfilled">3</span>

                    <h3 class="mb-2">Complete | Congrats</h3>

                    <p>Exercitation mus! Ipsam pulvinar ratione aliquid, aperiam optio. Sed rerum, nullam leo! Ut enim omnis! Provident itaque montes! Ex reprehenderit, felis sequi</p>

                </li>

                <div class="text-white n-help pt-4 mt-4 pb-4 mb-4">

                    <h3>Need a helping hand?</h3>

                    <p class=" pt-2">Call Us : +965 99732998
                        <br> Email : info@nextaxe.com</p>

                </div>

            </ul>

        </div>

    </div>
@endsection
    <!-- JavaScript Libraries -->

    @section('scripts')


      <script type="text/javascript">

         function myMap() {
            var latitude = '67.0589736';
         var longitude = '24.9554899';
          // console.log(latitude);
          // console.log(longitude);
          var myCenter = new google.maps.LatLng(latitude, longitude);
          var mapOptions = {
            center: myCenter,
            zoom: 17
          };

          var mapCanvas = document.getElementById("map");
          var map = new google.maps.Map(mapCanvas, mapOptions);
          var marker = new google.maps.Marker({
            position: myCenter
          });
          marker.setMap(map);

          $(document).ready(function() {
            // click on map and set you marker to that position
            google.maps.event.addListener(map, 'click', function(event) {
              marker.setPosition(event.latLng);
              $('#longitude').val(event.latLng.lng().toFixed(6));
              $('#latitude').val(event.latLng.lat().toFixed(6));
              getAddress(event.latLng.lat(), event.latLng.lng())
              // alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
            });
          });

        }

        function getAddress(lat, long) {
          $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + long + '&sensor=false&key=AIzaSyDRCd_mF3VtrWp8rdRtnOjZkdE9P_-kxRc',
            success: function(data) {
              $('#mymap').val(data.results[0].formatted_address);
            }
          });
        }
        
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDRCd_mF3VtrWp8rdRtnOjZkdE9P_-kxRc&callback=myMap"></script>
      <script>
   @endsection