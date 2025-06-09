@include('auth.default')
<?php
$filepath = public_path('countriesdata.json');
$countries = file_get_contents($filepath);
$countries = json_decode($countries);
$countries = (array)$countries;
$newcountries = array();
$newcountriesjs = array();
foreach ($countries as $keycountry => $valuecountry) {
    $newcountries[$valuecountry->phoneCode] = $valuecountry;
    $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
}
?>
<link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet">
<div class="login-page vh-100">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <h3 class="text-dark my-0 mb-3">{{trans('lang.login')}}</h3>
                <p class="text-50">{{trans('lang.sign_in_to_continue')}}</p>
                <div class="error" id="error"></div>
                <form class="mt-3 mb-4" action="#" onsubmit="return loginClick()" id="login-box">
                    <div class="form-group">
                        <label for="email" class="text-dark">{{trans('lang.user_email')}}</label>
                        <input type="email" placeholder="{{trans('lang.user_email_help_2')}}" class="form-control"
                               id="email" aria-describedby="emailHelp" name="email">
                        <div  class="error" id="email_required"></div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="text-dark">{{trans('lang.password')}}</label>
                        <input type="password" placeholder="{{trans('lang.user_password_help_2')}}" class="form-control"
                               id="password" name="password">
                        <div class="error" id="password_required"></div>
                    </div>
                    <div class="forgot-password">
                        <p><a href="{{url('forgot-password')}}" class="standard-link"
                              target="_blank">{{trans('lang.forgot_password')}}?</a></p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block"
                            id="login_btn">{{trans('lang.log_in')}}</button>
                    <a href="{{route('signup')}}" class="btn btn-primary btn-lg btn-block">{{trans('lang.sign_up')}}</a>
                    <button onclick="googleAuth()" type="button"
                            class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                            <i class="fa fa-google"> </i>{{trans('lang.auth_google')}}
                    </button>
                    <div class="or-line mb-3 mt-3">
                        <span>OR</span>
                    </div>
                    <button type="button" onclick="loginWithPhoneClick()" id="loginphon_btn"
                            class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                        <i class="fa fa-phone mr-2"> </i> {{ __('Login') }} {{trans('lang.with_phone')}}</button>
                </form>
                <form class="form-horizontal form-material" name="loginwithphon" id="login-with-phone-box" action="#"
                      style="display:none;">
                    @csrf
                    <div class="box-title m-b-20">{{ __('Login') }}</div>
                    <div class="form-group " id="phone-box">
                        <div class="col-xs-12">
                            <select name="country" id="country_selector">
                                <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                <?php $selected = ""; ?>
                                <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                        value="<?php echo $keycy; ?>">+<?php echo $valuecy->phoneCode; ?>  {{$valuecy->countryName}}</option>
                                <?php } ?>
                            </select>
                            <input class="form-control" placeholder="{{trans('lang.user_phone')}}" id="phone"
                                   type="number" class="form-control" name="phone" value="{{ old('phone') }}" required
                                   autocomplete="phone" autofocus>
                        </div>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
                        @enderror
                    </div>
                    <div class="error" id="password_required_new1" style="display:none;"></div>
                    <div class="form-group " id="otp-box" style="display:none;">
                        <input class="form-control" placeholder="{{trans('lang.otp')}}" id="verificationcode"
                               type="text" class="form-control" name="otp" value="{{ old('otp') }}" required
                               autocomplete="otp" autofocus>
                    </div>
                    <div id="recaptcha-container" style="display:none;"></div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button type="button" style="display:none;" onclick="applicationVerifier()" id="verify_btn"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_verify')}}</button>
                            <button type="button" style="display:none;" onclick="sendOTP()" id="sendotp_btn"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_send')}}</button>
                            <div class="or-line mb-3 mt-3">
                                <span>OR</span>
                            </div>
                            <button type="button" onclick="loginBackClick()"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                                <i class="fa fa-envelope mr-2"> </i> {{ __('Login') }} {{trans('lang.with_email')}}
                            </button>
                            <div class="error" id="password_required_new"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-database.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
    var database = firebase.firestore();
    function loginClick() {
        var email = $("#email").val();
        var password = $("#password").val();
        $("#email_required").css('display', 'none');
        $("#password_required").html("");
        if (email == '') {
            $("#email_required").css('display','block');
            jQuery("#email_required").html("Please enter email id").css("color", "red");
            return false;    
        }
        else if (password == '') {
            $("#email_required").css('display','none');
            jQuery("#password_required").html("Please enter valid password").css("color", "red");
            return false;
        }
                $("#email_required").css('display', 'none');
                firebase.auth().signInWithEmailAndPassword(email, password).then(function (result) {
                    var userEmail = result.user.email;
                    database.collection("users").where("email", "==", email).where('active', '==', true).get().then(async function (snapshots) {
                        if (snapshots.docs.length) {
                            var userData = snapshots.docs[0].data();
                            if (userData.role == "customer") {
                                var userToken = result.user.getIdToken();
                                var uid = result.user.uid;
                                var user = userData.id;
                                var firstName = userData.firstName;
                                var lastName = userData.lastName;
                                var imageURL = userData.profilePictureURL;
                                var url = "{{route('setToken')}}";
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        id: uid,
                                        userId: user,
                                        email: email,
                                        password: password,
                                        firstName: firstName,
                                        lastName: lastName,
                                        profilePicture: imageURL
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (data) {
                                        if (data.access) {
                                            setCookie("loginType", "EmailPassword");
                                            window.location = "{{url('/')}}";
                                        }
                                    }
                                });
                            }
                        } else {
                            $("#password_required").html("");
                            $("#password_required").append("<p>{{trans('lang.waiting_for_approval')}}</p>");
                        }
                    })
                })
                .catch(function (error) {
                    $("#password_required").html("The entered password is invalid. Please check and try again.").css("color", "red");
                });
                return false;
    }
    function loginWithPhoneClick() {
        jQuery("#login-box").hide();
        jQuery("#login-with-phone-box").show();
        jQuery("#phone-box").show();
        jQuery("#recaptcha-container").show();
        jQuery("#sendotp_btn").show();
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
            }
        });
    }
    function loginBackClick() {
        jQuery("#login-box").show();
        jQuery("#login-with-phone-box").hide();
        jQuery("#sendotp_btn").hide();
    }
    function sendOTP() {
        if(jQuery("#phone").val() == "")
        {
            $("#password_required_new1").css('display','block');
            jQuery("#password_required_new1").html("Enter valid phone number.").css("color", "red");
        }
        else if (jQuery("#phone").val() && jQuery("#country_selector").val()) {
            var countryCode = '+' + jQuery("#country_selector").val();
            var phone = jQuery("#phone").val();
            var phoneNumber = '+' + jQuery("#country_selector").val() + '' + jQuery("#phone").val();
            database.collection("users").where("phoneNumber", "==", phone).where("role", "==", 'customer').where('active', '==', true).get().then(async function (snapshots) {
                if (snapshots.docs.length) {
                    var userData = snapshots.docs[0].data();
                    firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                        .then(function (confirmationResult) {
                            window.confirmationResult = confirmationResult;
                            if (confirmationResult.verificationId) {
                                jQuery("#phone-box").hide();
                                jQuery("#recaptcha-container").hide();
                                jQuery("#otp-box").show();
                                jQuery("#verify_btn").show();
                                $("#password_required_new1").css('display','none');
                            }
                        });
                } else {
                    jQuery("#password_required_new").html("User not found.");
                }
            });
        }
    }
    function applicationVerifier() {
        window.confirmationResult.confirm(document.getElementById("verificationcode").value)
            .then(function (result) {
                var countryCode = '+' + jQuery("#country_selector").val();
                var phone = jQuery("#phone").val();
                database.collection("users").where('phoneNumber', '==', phone).get().then(async function (snapshots_login) {
                    userData = snapshots_login.docs[0].data();
                    if (userData) {
                        if (userData.role == "customer") {
                            var uid = result.user.uid;
                            var user = result.user.uid;
                            var firstName = userData.firstName;
                            var lastName = userData.lastName;
                            var imageURL = userData.profilePictureURL;
                            var url = "{{route('setToken')}}";
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: uid,
                                    userId: user,
                                    email: userData.phoneNumber,
                                    password: '',
                                    firstName: firstName,
                                    lastName: lastName,
                                    profilePicture: imageURL
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (data) {
                                    if (data.access) {
                                        setCookie("loginType", "Phone");
                                        window.location = "{{url('/')}}";
                                    }
                                }
                            });
                        } else {
                            $('#email_required').text('');
                            jQuery("#password_required_new").html("User not found.");
                        }
                    }
                })
            }).catch(function (error) {
            $('#email_required').text('');
            jQuery("#password_required_new").html(error.message);
        });
    }
    var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
    var newcountriesjs = JSON.parse(newcountriesjs);
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/";
        var $state = $(
            '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
        );
        return $state;
    }
    function formatState2(state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/"
        var $state = $(
            '<span><img class="img-flag" /> <span></span></span>'
        );
        $state.find("span").text(state.text);
        $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".png");
        return $state;
    }
    jQuery(document).ready(function () {
        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });
    });
    function googleAuth() {
        var provider = new firebase.auth.GoogleAuthProvider();
        firebase.auth().signInWithPopup(provider)
            .then(function(result) {
                var user = result.user;
                saveUserData(user);
            }).catch(function(error) {
                console.error("Google Sign-In Error:", error.message);
            });
    }
    function saveUserData(user, event) {
        jQuery('#overlay').show();
        database.collection("users").doc(user.uid).get().then(async function (snapshots_login) {
            var userData = snapshots_login.data();
            if (userData) {
                if (userData.role == "customer") {
                    var uid = user.uid;
                    var firstName = userData.firstName || '';
                    var lastName = userData.lastName || '';
                    var imageURL = userData.profilePictureURL || '';
                    var url = "{{route('setToken')}}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: uid,
                            userId: user.uid,
                            email: userData.email || '',
                            password: '',
                            firstName: firstName,
                            lastName: lastName,
                            profilePicture: imageURL,
                            provider:'google'
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data.access) {
                                setCookie("loginType", "Social");
                                window.location = "{{url('/')}}";
                            }
                        }
                    });
                } else {
                    $("#password_required").html("");
                    $("#password_required").append("<p>User not found.</p>");
                }
            } else {
                var phoneNumber = user.phoneNumber || '';
                var firstName = user.displayName ? user.displayName.split(' ')[0] : '';
                var lastName = '';
                if(user.displayName.split(' ')[1] == "" || user.displayName.split(' ')[1] == null || user.displayName.split(' ')[1] == undefined){
                    lastName = " ";
                }
                else{
                    
                    lastName = user.displayName.split(' ')[1];
                }
                var uuid = user.uid;
                var email = user.email || '';
                var photoURL = user.photoURL || '';
                var createdAtman = firebase.firestore.Timestamp.fromDate(new Date());
                var redirectUrl = `{{ url('socialsignup') }}?uuid=${encodeURIComponent(uuid)}&phoneNumber=${encodeURIComponent(phoneNumber)}&firstName=${encodeURIComponent(firstName)}&lastName=${encodeURIComponent(lastName)}&email=${encodeURIComponent(email)}&photoURL=${encodeURIComponent(photoURL)}&createdAt=${createdAtman.toDate()}`;
                window.location.href = redirectUrl;
            }
        }).catch(function (error) {
            console.log(error);
            $("#password_required").html("");
            $("#password_required").append("<p>"+ error.message +"</p>");
        });
    }
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
</script>
