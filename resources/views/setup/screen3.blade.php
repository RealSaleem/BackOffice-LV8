@extends('layouts.wizard')
@section('content')
      <!-- col1 -->
      <div class="w-1">
         <div class="m-col">
            <div class="row">
               <div class="col-xl-12 col-md-12 m-lg-5 ">
                  <div class="card shadow border-0 h-100 py-2">
                     <div class="card-body pb-0">
                        <div class="text-center"><img src="{{ url('backoffice/assets/img/icon3.png') }}"></div>

                        <h2 class="mt-4 text-center">{{ __('setup.complete_congrate') }}</h2>
                        <div class="mt-5 mb-5"><br></div>

                        <h2 class="mt-4 text-center">Start Selling with Us </h2>
                        <div class="w-forms ml-5 mr-5">
                           <form>
                              <div class="form-row">
                                 <div class="col-md-12 mb-2 text-center mt-5">
                                    <!-- <a href="{{route('backoffice.dashboard')}}" class="btn btn-primary p-5">Start Selling</a> -->
                                    <a href="{{route('backoffice.dashboard')}}" class="btn btn-primary p-5 mt-3 mt-lg-0">Visit Your Store</a>
                                 </div>
                              </div>
                           </form>
                        </div>
                        <div class="mt-5 mb-5"><br></div>
                        <div class="mt-5 mb-5"><br></div>
                       <div class="top bottom-btns row pb-0 pt-3 p-2 mt-5">
                           <div class="col-md-6 text-lg-left">
                              <!-- <a href="{{ route('setup.step2') }}" class="btn btn-secondary mb-2 text-muted"><i class="icon-arrow-left"></i> {{ __('setup.previous') }} </a> -->
                              <!-- <button type="submit" class="btn btn-grey mb-2 text-muted">Cancel</button> -->
                           </div>
                           <div class="col-md-6 text-lg-right">
                              <!-- <a href="{{ route('setup.step2') }}" class="btn btn-secondary mb-2 text-muted"><i class="icon-arrow-left"></i> {{ __('setup.previous') }} </a> -->
                              <!-- <button type="submit" class="btn btn-primary mb-2">{{ __('setup.next') }} <i class="icon-arrow-right"></i> </button> -->
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
               <li>
                  <span class="unfilled">1</span>
                  <h3 class="mb-2">{{ __('setup.store_setup') }}</h3>
                  <p>{{ __('setup.message1') }}</p>
               </li>
               <li>
                  <span class="unfilled">2</span>
                  <h3 class="mb-2">{{ __('setup.import_catalogue') }}</h3>
                  <p>{{ __('setup.message2') }}</p>
               </li>
               <li class="active">
                  <span class="active filled"><i class="fa fa-check"></i></span>
                  <h3 class="mb-2">{{ __('setup.complete_congrate') }}</h3>
                  <p>{{ __('setup.message3') }}</p>
               </li>
               <div class="text-white n-help">
                  <h3>{{ __('setup.helping_hand') }}</h3>
                  <p class=" pt-2">{{ __('setup.call_us') }} : <a href="tel:{{ $phone }}">{{ $phone }}</a>
                     <br> {{ __('setup.email') }} : <a href="mailto:{{ $email }}">{{ $email }}</a>
                  </p>
               </div>
            </ul>
         </div>
      </div>
      <!-- JavaScript Libraries -->
@endsection
