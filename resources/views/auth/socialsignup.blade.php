@include('auth.default')
<?php
$countries = file_get_contents(public_path('countriesdata.json'));
$countries = json_decode($countries);
$countries = (array) $countries;
$newcountries = array();
$newcountriesjs = array();
foreach ($countries as $keycountry => $valuecountry) {
    $newcountries[$valuecountry->phoneCode] = $valuecountry;
    $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
}
?>
<link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet">
<div class="siddhi-signup login-page vh-100">
    <div class="d-flex align-items-center justify-content-center py-3">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <h3 class="text-dark my-0 mb-3">{{trans('lang.sign_up_with_us')}}</h3>
                <p class="text-50">{{trans('lang.sign_up_to_continue')}}</p>
                <div class="error" style="color: red" id="field_error"></div>
                <div class="error" id="field_error1" style="color:red;display:none;"></div>
                <form class="mt-3 mb-4" action="javascript:void(0)" onsubmit="return signupClick()">
                    <div class="form-group" id="firstName_div">
                        <label for="firstName" class="text-dark">{{trans('lang.first_name')}}</label>
                        <input type="text" placeholder="Enter FirstName" value="{{ old('firstName', $firstName) }}" class="form-control" id="firstName" required>
                        <input type="hidden" id="hidden_fName" />
                    </div>
                    <div class="form-group" id="lastName_div">
                        <label for="lastName" class="text-dark">{{trans('lang.last_name')}}</label>
                        <input type="text" placeholder="Enter LastName" value="{{ old('lastName', $lastName) }}" class="form-control" id="lastName" required>
                        <input type="hidden" id="hidden_lName" />
                    </div>
                    <div class="form-group" id="email_div">
                        <label for="email" class="text-dark">{{trans('lang.email_address')}}</label>
                        <input type="email" placeholder="Enter Email Address" disabled class="form-control" value="{{ old('email', $email) }}" id="email" required
                            autocomplete="new-password" >
                    </div>
                    <div class="form-group" id="phone-box">
                        <?php
                        $countryCode = '';
                        $localNumber = $phoneNumber;
                        if (preg_match('/^\+(\d{1,3})\s?(\d+)$/', $phoneNumber, $matches)) {
                            $countryCode = $matches[1];
                            $localNumber = $matches[2];
                        }
                        ?>
                        <div class="col-xs-12">
                            <select name="country" id="country_selector">
                                <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                    <?php
                                    $selected = ($countryCode == $valuecy->phoneCode) ? "selected" : "";
                                    ?>
                                    <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                        value="<?php echo $keycy; ?>">+<?php echo $valuecy->phoneCode; ?>  <?php echo $valuecy->countryName; ?></option>
                                <?php } ?>
                            </select>
                            <input class="form-control" placeholder="{{trans('lang.user_phone')}}" id="mobileNumber"
                                type="number" name="mobileNumber" value="{{ old('phoneNumber', $localNumber) }}" required
                                autocomplete="mobileNumber">
                        </div>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group" id="referral_div">
                        <label for="referral_code" class="text-dark">{{trans('lang.referral_code')}}
                            ({{trans('lang.optional')}})</label>
                        <input type="text" placeholder="Enter Referral Code" class="form-control" id="referral_code">
                        <input type="hidden" id="hidden_referral" />
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="email_valid" id="email_valid" value="1">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block btn-sign-up" id="btn-sign-up">
                        {{trans('lang.sign_up')}}
                    </button>
                </form>
            </div>
            <div class="new-acc d-flex align-items-center justify-content-center mt-4 mb-3">
                <a href="{{url('login')}}">
                    <p class="text-center m-0"> {{trans('lang.already_an_account')}} {{trans('lang.sign_in')}}</p>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
    var createdAtman = firebase.firestore.Timestamp.fromDate(new Date());
    var database = firebase.firestore();
    async function signupClick() {
        $(".btn-sign-up").text('Please wait...');
        var email = $("#email").val();
        var password = $("#password").val();
        var countryCode = '+' + jQuery("#country_selector").val();
        var mobile = jQuery("#mobileNumber").val();
        var mobileNumber = '+' + jQuery("#country_selector").val() + '' + jQuery("#mobileNumber").val();
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();
        var referralCode = $("#referral_code").val();
        var referralBy = '';
        if (referralCode) {
            var referralByRes = getReferralUserId(referralCode);
            var referralBy = await referralByRes.then(function (refUserId) {
                return refUserId;
            });
        }
        var userReferralCode = Math.floor(Math.random() * 899999 + 100000);
        userReferralCode = userReferralCode.toString();
        var uuid = "{{$uuid}}";
                var photourl = "{{$photoURL}}";
                database.collection("referral").doc(uuid).set({
                    'id': uuid,
                    'referralBy': referralBy ? referralBy : '',
                    'referralCode': userReferralCode,
                });
                database.collection("users").doc(uuid).set({
                    'appIdentifier':"web",
                    'email': email,
                    'firstName': firstName,
                    'lastName': lastName,
                    'id': uuid,
                    'countryCode':countryCode,
                    'phoneNumber': mobile,
                    'role': "customer",
                    'profilePictureURL': photourl,
                    'provider':'google',
                    'createdAt': createdAtman,
                    'active':true
                }).then(() => {
                    var url = "{{route('newRegister')}}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            userId: uuid,
                            email: email,
                            password: '',
                            firstName: firstName,
                            lastName: lastName
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
                    })
                }).catch((error) => {
                        console.error("Error writing document: ", error);
                        $("#field_error").html(error);
                        window.scrollTo(0, 0);
                });
        return false;
    }
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    async function getReferralUserId(referralCode) {
        var refUserId = database.collection('referral').where('referralCode', '==', referralCode).get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                var referralData = snapshots.docs[0].data();
                return referralData.id;
            }
        });
        return refUserId;
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
</script>
