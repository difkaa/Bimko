@extends('guru.templates.wtsidebar')
@push('uploadicon')
<style>
.image-upload > input
{
    display: none;
}

.image-upload a
{
    width: 80px;
    cursor: pointer;
}
</style>
@endpush
@section('pageTitle', 'Edit Profil')
@section('content')

<div class="col col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-12">
        @include('admin.templates.partials._alerts')
    <div class="ui-block">
        <div class="ui-block-title">
            <h6 class="title">Edit Informasi Profil</h6>
        </div>
        <div class="ui-block-content">
            <!-- Personal Information Form  -->
            <form action="{{ route('guru.updateprofil')}}" class="form-horizontal editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")

                <div class="row">
                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group label-floating is-select">
                            <label class="control-label">Nama Lengkap</label>
                            <input class="form-control" placeholder="" type="text" name="name" value="{{ucwords($user->name)}}">
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-select">
                            <label class="control-label">Email</label>
                            <input class="form-control" placeholder="" type="email" name="email" value="{{$user->email}}">
                        <span class="material-input"></span>
                        </div>

                        <div class="form-group label-floating is-select">
                                <label class="control-label">NIP</label>
                                <input class="form-control" placeholder="" type="number" name="nip" value="{{$user->nip}}">
                            <span class="material-input"></span>
                        </div>

                        <div class="form-group label-floating is-select">
                                <label class="control-label">Sekolah</label>
                            <div class="btn-group bootstrap-select form-control">
                                <select name="school" class="selectpicker form-control" tabindex="-98">
                                    @foreach ($schools as $school)
                                    @if ($user->school)
                                    <option value="{{$school->id}}" {{$user->school->id == $school->id  ? 'selected' : ''}}>{{$school->name}}</option>
                                    @else
                                    <option value="{{$school->id}}">{{$school->name}}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>
                                <span class="material-input"></span>
                        </div>

                        <div class="form-group label-floating is-select">
                            <label class="control-label">Kelas</label>
                        <div class="btn-group bootstrap-select form-control">
                            <select name="grade" class="selectpicker form-control" tabindex="-98">
                                <option value="10" {{$user->grade == 10  ? 'selected' : ''}}>10</option>
                                <option value="11" {{$user->grade == 11  ? 'selected' : ''}}>11</option>
                                <option value="12" {{$user->grade == 12  ? 'selected' : ''}}>12</option>
                            </select>
                        </div>
                            <span class="material-input"></span>
                        </div>

                    </div>

                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">

                        {{-- <div class="form-group date-time-picker label-floating is-empty">
                            <label class="control-label">Tanggal Lahir</label>
                            <input name="datetimepicker" value="">
                            <span class="input-group-addon">
                                    <svg class="olymp-month-calendar-icon icon"><use xlink:href="{{asset('guru/svg-icons/sprites/icons.svg#olymp-month-calendar-icon')}}"></use></svg>
                            </span>
                        </div> --}}
                        <div class="form-group label-floating is-empty">
                                <label class="control-label">Password</label>
                                <input class="form-control" placeholder="" type="password" name="password">
                                <span >(*)untuk password baru</span>
                            <span class="material-input"></span>
                        </div>

                        <div class="form-group label-floating is-select">
                                <label class="control-label">Telepon</label>
                                <input class="form-control" placeholder="" type="number" name="phone" value="{{$user->phone}}">
                            <span class="material-input"></span>
                        </div>
                        <div class="form-group label-floating">
                        <div class="photo-album-item" data-mh="album-item" style="height: 560px">
								<div class="photo-item" style="height: 500px;max-height: 500px;display:inline-block">
									<img id="showImg" style="height: 100%;width: 100%" src="{{$user->getImage()}}" alt="photo">
									<div class="overlay overlay-dark"></div>
								</div>
								<div class="content">
                                    <div class="image-upload">
                                   <label for="imgProfil">
                                        <b><a style="color: black">Pilih Gambar</a></b>
                                    </label>
                                        <input accept="image/x-png,image/gif,image/jpeg" type="file" id="imgProfil" name="file">
                                    </div>
								</div>

                        </div>
                        <span class="material-input"></span>

                        </div>
                        {{-- <div class="form-group label-floating is-empty">
                            <label class="control-label">Guru Kelas</label>
                            <input class="form-control" placeholder="" type="text">
                            <span class="material-input"></span>
                        </div> --}}
                    </div>

                    {{-- <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="form-group label-floating is-select">
                            <label class="control-label">Your Country</label>
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-secondary" data-toggle="dropdown" role="button" title="United States" aria-expanded="false"><span class="filter-option pull-left">United States</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" role="combobox" x-placement="bottom-start" style="max-height: 407.828px; overflow: hidden; min-height: 0px; position: absolute; transform: translate3d(0px, 58px, 0px); top: 0px; left: 0px; will-change: transform;"><ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 389.828px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">United States</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Australia</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="selectpicker form-control" tabindex="-98">
                                <option value="US">United States</option>
                                <option value="AU">Australia</option>
                            </select></div>
                        <span class="material-input"></span></div>
                    </div> --}}
                    {{-- <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="form-group label-floating is-select">
                            <label class="control-label">Your State / Province</label>
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-secondary" data-toggle="dropdown" role="button" title="California" aria-expanded="false"><span class="filter-option pull-left">California</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" role="combobox" x-placement="bottom-start" style="max-height: 407.828px; overflow: hidden; min-height: 0px; position: absolute; transform: translate3d(0px, 58px, 0px); top: 0px; left: 0px; will-change: transform;"><ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 389.828px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">California</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Texas</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="selectpicker form-control" tabindex="-98">
                                <option value="CA">California</option>
                                <option value="TE">Texas</option>
                            </select></div>
                        <span class="material-input"></span></div>
                    </div> --}}
                    {{-- <div class="col col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="form-group label-floating is-select">
                            <label class="control-label">Your City</label>
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-secondary" data-toggle="dropdown" role="button" title="San Francisco" aria-expanded="false"><span class="filter-option pull-left">San Francisco</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" role="combobox" x-placement="bottom-start" style="max-height: 407.828px; overflow: hidden; min-height: 0px; position: absolute; transform: translate3d(0px, 58px, 0px); top: 0px; left: 0px; will-change: transform;"><ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 389.828px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">San Francisco</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">New York</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="selectpicker form-control" tabindex="-98">
                                <option value="SF">San Francisco</option>
                                <option value="NY">New York</option>
                            </select></div>
                        <span class="material-input"></span></div>
                    </div> --}}
                    {{-- <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group label-floating is-empty">
                            <label class="control-label">Write a little description about you</label>
                            <textarea class="form-control" placeholder="">text</textarea>
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-select">
                            <label class="control-label">Your Gender</label>
                            <div class="btn-group bootstrap-select form-control dropup"><button type="button" class="btn dropdown-toggle btn-secondary" data-toggle="dropdown" role="button" title="Male" aria-expanded="false"><span class="filter-option pull-left">Male</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" role="combobox" x-placement="top-start" style="max-height: 1035.95px; overflow: hidden; min-height: 0px; position: absolute; transform: translate3d(0px, -2px, 0px); top: 0px; left: 0px; will-change: transform;"><ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 1017.95px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">Male</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Female</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="selectpicker form-control" tabindex="-98">
                                <option value="MA">Male</option>
                                <option value="FE">Female</option>
                            </select></div>
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-empty">
                            <label class="control-label">Religious Belifs</label>
                            <input class="form-control" placeholder="" type="text">
                        <span class="material-input"></span></div>
                    </div> --}}
                    {{-- <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group label-floating is-empty">
                            <label class="control-label">Your Birthplace</label>
                            <input class="form-control" placeholder="" type="text">
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-empty">
                            <label class="control-label">Your Occupation</label>
                            <input class="form-control" placeholder="" type="text" value="">
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-select">
                            <label class="control-label">Status</label>
                            <div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-secondary" data-toggle="dropdown" role="button" title="Married" aria-expanded="false"><span class="filter-option pull-left">Married</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" role="combobox" x-placement="top-start" style="max-height: 163.656px; overflow: hidden; min-height: 0px; position: absolute; transform: translate3d(0px, -2px, 0px); top: 0px; left: 0px; will-change: transform;"><ul class="dropdown-menu inner" role="listbox" aria-expanded="false" style="max-height: 145.656px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="true"><span class="text">Married</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class=" dropdown-item" style="" data-tokens="null" role="option" aria-disabled="false" aria-selected="false"><span class="text">Not Married</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div><select class="selectpicker form-control" tabindex="-98">
                                <option value="MA">Married</option>
                                <option value="FE">Not Married</option>
                            </select></div>
                        <span class="material-input"></span></div>

                        <div class="form-group label-floating is-empty">
                            <label class="control-label">Political Incline</label>
                            <input class="form-control" placeholder="" type="text" value="">
                        <span class="material-input"></span></div>
                    </div> --}}
                    {{-- <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group with-icon label-floating">
                            <label class="control-label">Your Facebook Account</label>
                            <input class="form-control" type="text" value="www.facebook.com/james-spiegel95321">
                            <svg class="svg-inline--fa fa-facebook-f fa-w-9 c-facebook" aria-hidden="true" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 264 512" data-fa-i2svg=""><path fill="currentColor" d="M76.7 512V283H0v-91h76.7v-71.7C76.7 42.4 124.3 0 193.8 0c33.3 0 61.9 2.5 70.2 3.6V85h-48.2c-37.8 0-45.1 18-45.1 44.3V192H256l-11.7 91h-73.6v229"></path></svg><!-- <i class="fab fa-facebook-f c-facebook" aria-hidden="true"></i> -->
                        <span class="material-input"></span></div>
                        <div class="form-group with-icon label-floating">
                            <label class="control-label">Your Twitter Account</label>
                            <input class="form-control" type="text" value="www.twitter.com/james_spiegelOK">
                            <svg class="svg-inline--fa fa-twitter fa-w-16 c-twitter" aria-hidden="true" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg><!-- <i class="fab fa-twitter c-twitter" aria-hidden="true"></i> -->
                        <span class="material-input"></span></div>
                        <div class="form-group with-icon label-floating is-empty">
                            <label class="control-label">Your RSS Feed Account</label>
                            <input class="form-control" type="text">
                            <svg class="svg-inline--fa fa-rss fa-w-14 c-rss" aria-hidden="true" data-prefix="fa" data-icon="rss" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M128.081 415.959c0 35.369-28.672 64.041-64.041 64.041S0 451.328 0 415.959s28.672-64.041 64.041-64.041 64.04 28.673 64.04 64.041zm175.66 47.25c-8.354-154.6-132.185-278.587-286.95-286.95C7.656 175.765 0 183.105 0 192.253v48.069c0 8.415 6.49 15.472 14.887 16.018 111.832 7.284 201.473 96.702 208.772 208.772.547 8.397 7.604 14.887 16.018 14.887h48.069c9.149.001 16.489-7.655 15.995-16.79zm144.249.288C439.596 229.677 251.465 40.445 16.503 32.01 7.473 31.686 0 38.981 0 48.016v48.068c0 8.625 6.835 15.645 15.453 15.999 191.179 7.839 344.627 161.316 352.465 352.465.353 8.618 7.373 15.453 15.999 15.453h48.068c9.034-.001 16.329-7.474 16.005-16.504z"></path></svg><!-- <i class="fa fa-rss c-rss" aria-hidden="true"></i> -->
                        <span class="material-input"></span></div>
                        <div class="form-group with-icon label-floating">
                            <label class="control-label">Your Dribbble Account</label>
                            <input class="form-control" type="text" value="www.dribbble.com/thecowboydesigner">
                            <svg class="svg-inline--fa fa-dribbble fa-w-16 c-dribbble" aria-hidden="true" data-prefix="fab" data-icon="dribbble" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119.252 8 8 119.252 8 256s111.252 248 248 248 248-111.252 248-248S392.748 8 256 8zm163.97 114.366c29.503 36.046 47.369 81.957 47.835 131.955-6.984-1.477-77.018-15.682-147.502-6.818-5.752-14.041-11.181-26.393-18.617-41.614 78.321-31.977 113.818-77.482 118.284-83.523zM396.421 97.87c-3.81 5.427-35.697 48.286-111.021 76.519-34.712-63.776-73.185-116.168-79.04-124.008 67.176-16.193 137.966 1.27 190.061 47.489zm-230.48-33.25c5.585 7.659 43.438 60.116 78.537 122.509-99.087 26.313-186.36 25.934-195.834 25.809C62.38 147.205 106.678 92.573 165.941 64.62zM44.17 256.323c0-2.166.043-4.322.108-6.473 9.268.19 111.92 1.513 217.706-30.146 6.064 11.868 11.857 23.915 17.174 35.949-76.599 21.575-146.194 83.527-180.531 142.306C64.794 360.405 44.17 310.73 44.17 256.323zm81.807 167.113c22.127-45.233 82.178-103.622 167.579-132.756 29.74 77.283 42.039 142.053 45.189 160.638-68.112 29.013-150.015 21.053-212.768-27.882zm248.38 8.489c-2.171-12.886-13.446-74.897-41.152-151.033 66.38-10.626 124.7 6.768 131.947 9.055-9.442 58.941-43.273 109.844-90.795 141.978z"></path></svg><!-- <i class="fab fa-dribbble c-dribbble" aria-hidden="true"></i> -->
                        <span class="material-input"></span></div>
                        <div class="form-group with-icon label-floating is-empty">
                            <label class="control-label">Your Spotify Account</label>
                            <input class="form-control" type="text">
                            <svg class="svg-inline--fa fa-spotify fa-w-16 c-spotify" aria-hidden="true" data-prefix="fab" data-icon="spotify" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" data-fa-i2svg=""><path fill="currentColor" d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"></path></svg><!-- <i class="fab fa-spotify c-spotify" aria-hidden="true"></i> -->
                        <span class="material-input"></span></div>
                    </div> --}}

                    <input type="hidden" value="{{$user->id}}" name="id">
                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        {{-- <button class="btn btn-secondary btn-lg full-width">Restore all Attributes</button> --}}
                    </div>
                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <span class="size_error" style="color:red"></span>
                        <button type="submit" class="btn btn-primary btn-lg full-width">Simpan Perubahan Informasi</button>
                    </div>
                </div>
            </form>
            <!-- ... end Personal Information Form  -->
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script>
    $("#alert-guru-danger").fadeTo(2000, 500).slideUp(500, function(){
        $("#alert-guru-danger").slideUp(500);
    });
</script>

<script>
$('.editForm').on('submit',(function(e) {
var fileku = $('#imgProfil').val();
    if(fileku!='')
    {
        $('.size_error').html("");
        var file_size = $('#imgProfil')[0].files[0].size;
        if(file_size > 10000000) {
            swal({
                title: "gagal posting status",
                text: "maksimal gambar berukuran 10MB",
                icon: "error",
                buttons: true,
                dangerMode: true,
                });
            return false;
        }
    }
}));
</script>
<script>
function readURL(input) {

if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function(e) {
    $('#showImg').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
}
}

$("#imgProfil").change(function() {
readURL(this);
});
</script>

@endpush
