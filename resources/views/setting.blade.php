@extends('template.principal_tamplate')
@section('title', "Mise à jour de l'utilisateur")

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mise à jour des paramètres</li>

        

        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Modifiez les paramètres</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('save_setting') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Nom de l'opérateur</label>
                        <input name="name" type="text" class="form-control  @error('name') is-invalid @enderror"
                            value="{{ $operator->name }}" value="{{ $operator->name }}" placeholder="{{ $operator->name }}"
                            required>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail10">Telephone </label>
                        <input name="tel" type="tel" class="form-control  @error('tel') is-invalid @enderror"
                            placeholder="{{ $operator->tel }}" value="{{ $operator->tel }}" required>

                        @error('tel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Email </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="" placeholder="{{ $operator->email }}" value="{{ $operator->email }}">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                </div>


                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Adresse</label>
                        <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                            id="" value="{{ $operator->adresse }}" placeholder="{{ $operator->adresse }}">

                        @error('adresse')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Pays de l'opérateur</label>
                        <select name="country" id="inputState" class="form-control">
                                <option {{$operator->country == 'Afghanistan' ? 'selected' : ''}} value="Afghanistan">Afghanistan</option>
                                <option {{$operator->country == 'Åland Islands' ? 'selected' : ''}} value="Åland Islands">Åland Islands</option>
                                <option {{$operator->country == 'Albania' ? 'selected' : ''}} value="Albania">Albania</option>
                                <option {{$operator->country == 'Algeria' ? 'selected' : ''}} value="Algeria">Algeria</option>
                                <option {{$operator->country == 'American Samoa' ? 'selected' : ''}} value="American Samoa">American Samoa</option>
                                <option {{$operator->country == 'Andorra' ? 'selected' : ''}} value="Andorra">Andorra</option>
                                <option {{$operator->country == 'Angola' ? 'selected' : ''}} value="Angola">Angola</option>
                                <option {{$operator->country == 'Anguilla' ? 'selected' : ''}} value="Anguilla">Anguilla</option>
                                <option {{$operator->country == 'Antarctica' ? 'selected' : ''}} value="Antarctica">Antarctica</option>
                                <option {{$operator->country == 'Antigua and Barbuda' ? 'selected' : ''}} value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option {{$operator->country == 'Argentina' ? 'selected' : ''}} value="Argentina">Argentina</option>
                                <option {{$operator->country == 'Armenia' ? 'selected' : ''}} value="Armenia">Armenia</option>
                                <option {{$operator->country == 'Aruba' ? 'selected' : ''}} value="Aruba">Aruba</option>
                                <option {{$operator->country == 'Australia' ? 'selected' : ''}} value="Australia">Australia</option>
                                <option {{$operator->country == 'Austria' ? 'selected' : ''}} value="Austria">Austria</option>
                                <option {{$operator->country == 'Azerbaijan' ? 'selected' : ''}} value="Azerbaijan">Azerbaijan</option>
                                <option {{$operator->country == 'Bahamas' ? 'selected' : ''}} value="Bahamas">Bahamas</option>
                                <option {{$operator->country == 'Bahrain' ? 'selected' : ''}} value="Bahrain">Bahrain</option>
                                <option {{$operator->country == 'Bangladesh' ? 'selected' : ''}} value="Bangladesh">Bangladesh</option>
                                <option {{$operator->country == 'Barbados' ? 'selected' : ''}} value="Barbados">Barbados</option>
                                <option {{$operator->country == 'Belarus' ? 'selected' : ''}} value="Belarus">Belarus</option>
                                <option {{$operator->country == 'Belgium' ? 'selected' : ''}} value="Belgium">Belgium</option>
                                <option {{$operator->country == 'Belize' ? 'selected' : ''}} value="Belize">Belize</option>
                                <option {{$operator->country == 'Benin' ? 'selected' : ''}} value="Benin">Benin</option>
                                <option {{$operator->country == 'Bermuda' ? 'selected' : ''}} value="Bermuda">Bermuda</option>
                                <option {{$operator->country == 'Bhutan' ? 'selected' : ''}} value="Bhutan">Bhutan</option>
                                <option {{$operator->country == 'Bolivia' ? 'selected' : ''}} value="Bolivia">Bolivia</option>
                                <option {{$operator->country == 'Bosnia and Herzegovina' ? 'selected' : ''}} value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option {{$operator->country == 'Botswana' ? 'selected' : ''}} value="Botswana">Botswana</option>
                                <option {{$operator->country == 'Bouvet Island' ? 'selected' : ''}} value="Bouvet Island">Bouvet Island</option>
                                <option {{$operator->country == 'Brazil' ? 'selected' : ''}} value="Brazil">Brazil</option>
                                <option {{$operator->country == 'British Indian Ocean Territory' ? 'selected' : ''}} value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                <option {{$operator->country == 'Brunei Darussalam' ? 'selected' : ''}} value="Brunei Darussalam">Brunei Darussalam</option>
                                <option {{$operator->country == 'Bulgaria' ? 'selected' : ''}} value="Bulgaria">Bulgaria</option>
                                <option {{$operator->country == 'Burkina Faso' ? 'selected' : ''}} value="Burkina Faso">Burkina Faso</option>
                                <option {{$operator->country == 'Burundi' ? 'selected' : ''}} value="Burundi">Burundi</option>
                                <option {{$operator->country == 'Cambodia' ? 'selected' : ''}} value="Cambodia">Cambodia</option>
                                <option {{$operator->country == 'Cameroon' ? 'selected' : ''}} value="Cameroon">Cameroon</option>
                                <option {{$operator->country == 'Canada' ? 'selected' : ''}} value="Canada">Canada</option>
                                <option {{$operator->country == 'Cape Verde' ? 'selected' : ''}} value="Cape Verde">Cape Verde</option>
                                <option {{$operator->country == 'Cayman Islands' ? 'selected' : ''}} value="Cayman Islands">Cayman Islands</option>
                                <option {{$operator->country == 'Central African Republic' ? 'selected' : ''}} value="Central African Republic">Central African Republic</option>
                                <option {{$operator->country == 'Chad' ? 'selected' : ''}} value="Chad">Chad</option>
                                <option {{$operator->country == 'Chile' ? 'selected' : ''}} value="Chile">Chile</option>
                                <option {{$operator->country == 'China' ? 'selected' : ''}} value="China">China</option>
                                <option {{$operator->country == 'Christmas Island' ? 'selected' : ''}} value="Christmas Island">Christmas Island</option>
                                <option {{$operator->country == 'Cocos (Keeling) Islands' ? 'selected' : ''}} value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                <option {{$operator->country == 'Colombia' ? 'selected' : ''}} value="Colombia">Colombia</option>
                                <option {{$operator->country == 'Comoros' ? 'selected' : ''}} value="Comoros">Comoros</option>
                                <option {{$operator->country == 'Congo' ? 'selected' : ''}} value="Congo">Congo</option>
                                <option {{$operator->country == 'Congo' ? 'selected' : ''}} value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The
                                </option>
                                <option {{$operator->country == 'Cook Islands' ? 'selected' : ''}} value="Cook Islands">Cook Islands</option>
                                <option {{$operator->country == 'Costa Rica' ? 'selected' : ''}} value="Costa Rica">Costa Rica</option>
                                <option {{$operator->country == "Cote D'ivoire" ? 'selected' : ''}} value="Cote D'ivoire">Cote D'ivoire</option>
                                <option {{$operator->country == 'Croatia' ? 'selected' : ''}} value="Croatia">Croatia</option>
                                <option {{$operator->country == 'Cuba' ? 'selected' : ''}} value="Cuba">Cuba</option>
                                <option {{$operator->country == 'Cyprus' ? 'selected' : ''}} value="Cyprus">Cyprus</option>
                                <option {{$operator->country == 'Czech Republic' ? 'selected' : ''}} value="Czech Republic">Czech Republic</option>
                                <option {{$operator->country == 'Denmark' ? 'selected' : ''}} value="Denmark">Denmark</option>
                                <option {{$operator->country == 'Djibouti' ? 'selected' : ''}} value="Djibouti">Djibouti</option>
                                <option {{$operator->country == 'Dominica' ? 'selected' : ''}} value="Dominica">Dominica</option>
                                <option {{$operator->country == 'Dominican Republic' ? 'selected' : ''}} value="Dominican Republic">Dominican Republic</option>
                                <option {{$operator->country == 'Ecuador' ? 'selected' : ''}} value="Ecuador">Ecuador</option>
                                <option {{$operator->country == 'Egypt' ? 'selected' : ''}} value="Egypt">Egypt</option>
                                <option {{$operator->country == 'El Salvador' ? 'selected' : ''}} value="El Salvador">El Salvador</option>
                                <option {{$operator->country == 'Equatorial Guinea' ? 'selected' : ''}} value="Equatorial Guinea">Equatorial Guinea</option>
                                <option {{$operator->country == 'Eritrea' ? 'selected' : ''}} value="Eritrea">Eritrea</option>
                                <option {{$operator->country == 'Estonia' ? 'selected' : ''}} value="Estonia">Estonia</option>
                                <option {{$operator->country == 'Ethiopia' ? 'selected' : ''}} value="Ethiopia">Ethiopia</option>
                                <option {{$operator->country == 'Falkland Islands (Malvinas)' ? 'selected' : ''}} value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                <option {{$operator->country == 'Faroe Islands' ? 'selected' : ''}} value="Faroe Islands">Faroe Islands</option>
                                <option {{$operator->country == 'Fiji' ? 'selected' : ''}} value="Fiji">Fiji</option>
                                <option {{$operator->country == 'Finland' ? 'selected' : ''}} value="Finland">Finland</option>
                                <option {{$operator->country == 'France' ? 'selected' : ''}} value="France">France</option>
                                <option {{$operator->country == 'French Guiana' ? 'selected' : ''}} value="French Guiana">French Guiana</option>
                                <option {{$operator->country == 'French Polynesia' ? 'selected' : ''}} value="French Polynesia">French Polynesia</option>
                                <option {{$operator->country == 'French Southern Territories' ? 'selected' : ''}} value="French Southern Territories">French Southern Territories</option>
                                <option {{$operator->country == 'Gabon' ? 'selected' : ''}} value="Gabon">Gabon</option>
                                <option {{$operator->country == 'Gambia' ? 'selected' : ''}} value="Gambia">Gambia</option>
                                <option {{$operator->country == 'Georgia' ? 'selected' : ''}} value="Georgia">Georgia</option>
                                <option {{$operator->country == 'Germany' ? 'selected' : ''}} value="Germany">Germany</option>
                                <option {{$operator->country == 'Ghana' ? 'selected' : ''}} value="Ghana">Ghana</option>
                                <option {{$operator->country == 'Gibraltar' ? 'selected' : ''}} value="Gibraltar">Gibraltar</option>
                                <option {{$operator->country == 'Greece' ? 'selected' : ''}} value="Greece">Greece</option>
                                <option {{$operator->country == 'Greenland' ? 'selected' : ''}} value="Greenland">Greenland</option>
                                <option {{$operator->country == 'Grenada' ? 'selected' : ''}} value="Grenada">Grenada</option>
                                <option {{$operator->country == 'Guadeloupe' ? 'selected' : ''}} value="Guadeloupe">Guadeloupe</option>
                                <option {{$operator->country == 'Guam' ? 'selected' : ''}} value="Guam">Guam</option>
                                <option {{$operator->country == 'Guatemala' ? 'selected' : ''}} value="Guatemala">Guatemala</option>
                                <option {{$operator->country == 'Guernsey' ? 'selected' : ''}} value="Guernsey">Guernsey</option>
                                <option {{$operator->country == 'Guinea' ? 'selected' : ''}} value="Guinea">Guinea</option>
                                <option {{$operator->country == 'Guinea-bissau' ? 'selected' : ''}} value="Guinea-bissau">Guinea-bissau</option>
                                <option {{$operator->country == 'Guyana' ? 'selected' : ''}} value="Guyana">Guyana</option>
                                <option {{$operator->country == 'Haiti' ? 'selected' : ''}} value="Haiti">Haiti</option>
                                <option {{$operator->country == 'Heard Island and Mcdonald Islands' ? 'selected' : ''}} value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
                                </option>
                                <option {{$operator->country == 'Holy See (Vatican City State)' ? 'selected' : ''}} value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                <option {{$operator->country == 'Honduras' ? 'selected' : ''}} value="Honduras">Honduras</option>
                                <option {{$operator->country == 'Hong Kong' ? 'selected' : ''}} value="Hong Kong">Hong Kong</option>
                                <option {{$operator->country == 'Hungary' ? 'selected' : ''}} value="Hungary">Hungary</option>
                                <option {{$operator->country == 'Iceland' ? 'selected' : ''}} value="Iceland">Iceland</option>
                                <option {{$operator->country == 'India' ? 'selected' : ''}} value="India">India</option>
                                <option {{$operator->country == 'Indonesia' ? 'selected' : ''}} value="Indonesia">Indonesia</option>
                                <option {{$operator->country == 'Iran, Islamic Republic of' ? 'selected' : ''}} value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                <option {{$operator->country == 'Iraq' ? 'selected' : ''}} value="Iraq">Iraq</option>
                                <option {{$operator->country == 'Ireland' ? 'selected' : ''}} value="Ireland">Ireland</option>
                                <option {{$operator->country == 'Isle of Man' ? 'selected' : ''}} value="Isle of Man">Isle of Man</option>
                                <option {{$operator->country == 'Israel' ? 'selected' : ''}} value="Israel">Israel</option>
                                <option {{$operator->country == 'Italy' ? 'selected' : ''}} value="Italy">Italy</option>
                                <option {{$operator->country == 'Jamaica' ? 'selected' : ''}} value="Jamaica">Jamaica</option>
                                <option {{$operator->country == 'Japan' ? 'selected' : ''}} value="Japan">Japan</option>
                                <option {{$operator->country == 'Jersey' ? 'selected' : ''}} value="Jersey">Jersey</option>
                                <option {{$operator->country == 'Jordan' ? 'selected' : ''}} value="Jordan">Jordan</option>
                                <option {{$operator->country == 'Kazakhstan' ? 'selected' : ''}} value="Kazakhstan">Kazakhstan</option>
                                <option {{$operator->country == 'Kenya' ? 'selected' : ''}} value="Kenya">Kenya</option>
                                <option {{$operator->country == 'Kiribati' ? 'selected' : ''}} value="Kiribati">Kiribati</option>
                                <option {{$operator->country == "Korea, Democratic People's Republic of" ? 'selected' : ''}} value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic
                                    of</option>
                                <option {{$operator->country == 'Korea, Republic of' ? 'selected' : ''}} value="Korea, Republic of">Korea, Republic of</option>
                                <option {{$operator->country == 'Kuwait' ? 'selected' : ''}} value="Kuwait">Kuwait</option>
                                <option {{$operator->country == 'Kyrgyzstan' ? 'selected' : ''}} value="Kyrgyzstan">Kyrgyzstan</option>
                                <option {{$operator->country == "Lao People's Democratic Republic" ? 'selected' : ''}} value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                <option {{$operator->country == 'Latvia' ? 'selected' : ''}} value="Latvia">Latvia</option>
                                <option {{$operator->country == 'Lebanon' ? 'selected' : ''}} value="Lebanon">Lebanon</option>
                                <option {{$operator->country == 'Lesotho' ? 'selected' : ''}} value="Lesotho">Lesotho</option>
                                <option {{$operator->country == 'Liberia' ? 'selected' : ''}} value="Liberia">Liberia</option>
                                <option {{$operator->country == 'Libyan Arab Jamahiriya' ? 'selected' : ''}} value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                <option {{$operator->country == 'Liechtenstein' ? 'selected' : ''}} value="Liechtenstein">Liechtenstein</option>
                                <option {{$operator->country == 'Lithuania' ? 'selected' : ''}} value="Lithuania">Lithuania</option>
                                <option {{$operator->country == 'Luxembourg' ? 'selected' : ''}} value="Luxembourg">Luxembourg</option>
                                <option {{$operator->country == 'Macao' ? 'selected' : ''}} value="Macao">Macao</option>
                                <option {{$operator->country == '' ? 'selected' : "Macedonia, The Former Yugoslav Republic of"}} value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav
                                    Republic of</option>
                                <option {{$operator->country == 'Madagascar' ? 'selected' : ''}} value="Madagascar">Madagascar</option>
                                <option {{$operator->country == 'Malawi' ? 'selected' : ''}} value="Malawi">Malawi</option>
                                <option {{$operator->country == 'Malaysia' ? 'selected' : ''}} value="Malaysia">Malaysia</option>
                                <option {{$operator->country == 'Maldives' ? 'selected' : ''}} value="Maldives">Maldives</option>
                                <option {{$operator->country == 'Mali' ? 'selected' : ''}} value="Mali">Mali</option>
                                <option {{$operator->country == 'Malta' ? 'selected' : ''}} value="Malta">Malta</option>
                                <option {{$operator->country == 'Marshall Islands' ? 'selected' : ''}} value="Marshall Islands">Marshall Islands</option>
                                <option {{$operator->country == 'Martinique' ? 'selected' : ''}} value="Martinique">Martinique</option>
                                <option {{$operator->country == 'Mauritania' ? 'selected' : ''}} value="Mauritania">Mauritania</option>
                                <option {{$operator->country == 'Mauritius' ? 'selected' : ''}} value="Mauritius">Mauritius</option>
                                <option {{$operator->country == 'Mayotte' ? 'selected' : ''}} value="Mayotte">Mayotte</option>
                                <option {{$operator->country == 'Mexico' ? 'selected' : ''}} value="Mexico">Mexico</option>
                                <option {{$operator->country == "Micronesia, Federated States of" ? 'selected' : ''}} value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option {{$operator->country == 'Moldova, Republic of' ? 'selected' : ''}} value="Moldova, Republic of">Moldova, Republic of</option>
                                <option {{$operator->country == 'Monaco' ? 'selected' : ''}} value="Monaco">Monaco</option>
                                <option {{$operator->country == 'Mongolia' ? 'selected' : ''}} value="Mongolia">Mongolia</option>
                                <option {{$operator->country == 'Montenegro' ? 'selected' : ''}} value="Montenegro">Montenegro</option>
                                <option {{$operator->country == 'Montserrat' ? 'selected' : ''}} value="Montserrat">Montserrat</option>
                                <option {{$operator->country == 'Morocco' ? 'selected' : ''}} value="Morocco">Morocco</option>
                                <option {{$operator->country == 'Mozambique' ? 'selected' : ''}} value="Mozambique">Mozambique</option>
                                <option {{$operator->country == 'Myanmar' ? 'selected' : ''}} value="Myanmar">Myanmar</option>
                                <option {{$operator->country == 'Namibia' ? 'selected' : ''}} value="Namibia">Namibia</option>
                                <option {{$operator->country == 'Nauru' ? 'selected' : ''}} value="Nauru">Nauru</option>
                                <option {{$operator->country == 'Nepal' ? 'selected' : ''}} value="Nepal">Nepal</option>
                                <option {{$operator->country == 'Netherlands' ? 'selected' : ''}} value="Netherlands">Netherlands</option>
                                <option {{$operator->country == 'Netherlands Antilles' ? 'selected' : ''}} value="Netherlands Antilles">Netherlands Antilles</option>
                                <option {{$operator->country == 'New Caledonia' ? 'selected' : ''}} value="New Caledonia">New Caledonia</option>
                                <option {{$operator->country == 'New Zealand' ? 'selected' : ''}} value="New Zealand">New Zealand</option>
                                <option {{$operator->country == 'Nicaragua' ? 'selected' : ''}} value="Nicaragua">Nicaragua</option>
                                <option {{$operator->country == 'Niger' ? 'selected' : ''}} value="Niger">Niger</option>
                                <option {{$operator->country == 'Nigeria' ? 'selected' : ''}} value="Nigeria">Nigeria</option>
                                <option {{$operator->country == 'Niue' ? 'selected' : ''}} value="Niue">Niue</option>
                                <option {{$operator->country == 'Norfolk Island' ? 'selected' : ''}} value="Norfolk Island">Norfolk Island</option>
                                <option {{$operator->country == 'Northern Mariana Islands' ? 'selected' : ''}} value="Northern Mariana Islands">Northern Mariana Islands</option>
                                <option {{$operator->country == 'Norway' ? 'selected' : ''}} value="Norway">Norway</option>
                                <option {{$operator->country == 'Oman' ? 'selected' : ''}} value="Oman">Oman</option>
                                <option {{$operator->country == 'Pakistan' ? 'selected' : ''}} value="Pakistan">Pakistan</option>
                                <option {{$operator->country == 'Palau' ? 'selected' : ''}} value="Palau">Palau</option>
                                <option {{$operator->country == 'Palestinian Territory, Occupied' ? 'selected' : ''}} value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                <option {{$operator->country == 'Panama' ? 'selected' : ''}} value="Panama">Panama</option>
                                <option {{$operator->country == 'Papua New Guinea' ? 'selected' : ''}} value="Papua New Guinea">Papua New Guinea</option>
                                <option {{$operator->country == 'Paraguay' ? 'selected' : ''}} value="Paraguay">Paraguay</option>
                                <option {{$operator->country == 'Peru' ? 'selected' : ''}} value="Peru">Peru</option>
                                <option {{$operator->country == 'Philippines' ? 'selected' : ''}} value="Philippines">Philippines</option>
                                <option {{$operator->country == 'Pitcairn' ? 'selected' : ''}} value="Pitcairn">Pitcairn</option>
                                <option {{$operator->country == 'Poland' ? 'selected' : ''}} value="Poland">Poland</option>
                                <option {{$operator->country == 'Portugal' ? 'selected' : ''}} value="Portugal">Portugal</option>
                                <option {{$operator->country == 'Puerto Rico' ? 'selected' : ''}} value="Puerto Rico">Puerto Rico</option>
                                <option {{$operator->country == 'Qatar' ? 'selected' : ''}} value="Qatar">Qatar</option>
                                <option {{$operator->country == 'Reunion' ? 'selected' : ''}} value="Reunion">Reunion</option>
                                <option {{$operator->country == 'Romania' ? 'selected' : ''}} value="Romania">Romania</option>
                                <option {{$operator->country == 'Russian Federation' ? 'selected' : ''}} value="Russian Federation">Russian Federation</option>
                                <option {{$operator->country == 'Rwanda' ? 'selected' : ''}} value="Rwanda">Rwanda</option>
                                <option {{$operator->country == 'Saint Helena' ? 'selected' : ''}} value="Saint Helena">Saint Helena</option>
                                <option {{$operator->country == 'Saint Kitts and Nevis' ? 'selected' : ''}} value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option {{$operator->country == 'Saint Lucia' ? 'selected' : ''}} value="Saint Lucia">Saint Lucia</option>
                                <option {{$operator->country == 'Saint Pierre and Miquelon' ? 'selected' : ''}} value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                <option {{$operator->country == 'Saint Vincent and The Grenadines' ? 'selected' : ''}} value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                <option {{$operator->country == 'Samoa' ? 'selected' : ''}} value="Samoa">Samoa</option>
                                <option {{$operator->country == 'San Marino' ? 'selected' : ''}} value="San Marino">San Marino</option>
                                <option {{$operator->country == 'Sao Tome and Principe' ? 'selected' : ''}} value="Sao Tome and Principe">Sao Tome and Principe</option>
                                <option {{$operator->country == 'Saudi Arabia' ? 'selected' : ''}} value="Saudi Arabia">Saudi Arabia</option>
                                <option {{$operator->country == 'Senegal' ? 'selected' : ''}} value="Senegal">Senegal</option>
                                <option {{$operator->country == 'Serbia' ? 'selected' : ''}} value="Serbia">Serbia</option>
                                <option {{$operator->country == 'Seychelles' ? 'selected' : ''}} value="Seychelles">Seychelles</option>
                                <option {{$operator->country == 'Sierra Leone' ? 'selected' : ''}} value="Sierra Leone">Sierra Leone</option>
                                <option {{$operator->country == 'Singapore' ? 'selected' : ''}} value="Singapore">Singapore</option>
                                <option {{$operator->country == 'Slovakia' ? 'selected' : ''}} value="Slovakia">Slovakia</option>
                                <option {{$operator->country == 'Slovenia' ? 'selected' : ''}} value="Slovenia">Slovenia</option>
                                <option {{$operator->country == 'Solomon Islands' ? 'selected' : ''}} value="Solomon Islands">Solomon Islands</option>
                                <option {{$operator->country == 'Somalia' ? 'selected' : ''}} value="Somalia">Somalia</option>
                                <option {{$operator->country == 'South Africa' ? 'selected' : ''}} value="South Africa">South Africa</option>
                                <option {{$operator->country == 'South Georgia and The South Sandwich Islands' ? 'selected' : ''}} value="South Georgia and The South Sandwich Islands">South Georgia and The South
                                    Sandwich Islands</option>
                                <option {{$operator->country == 'Spain' ? 'selected' : ''}} value="Spain">Spain</option>
                                <option {{$operator->country == 'Sri Lanka' ? 'selected' : ''}} value="Sri Lanka">Sri Lanka</option>
                                <option {{$operator->country == 'Sudan' ? 'selected' : ''}} value="Sudan">Sudan</option>
                                <option {{$operator->country == 'Suriname' ? 'selected' : ''}} value="Suriname">Suriname</option>
                                <option {{$operator->country == 'Svalbard and Jan Mayen' ? 'selected' : ''}} value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                <option {{$operator->country == 'Swaziland' ? 'selected' : ''}} value="Swaziland">Swaziland</option>
                                <option {{$operator->country == 'Sweden' ? 'selected' : ''}} value="Sweden">Sweden</option>
                                <option {{$operator->country == 'Switzerland' ? 'selected' : ''}} value="Switzerland">Switzerland</option>
                                <option {{$operator->country == 'Syrian Arab Republic' ? 'selected' : ''}} value="Syrian Arab Republic">Syrian Arab Republic</option>
                                <option {{$operator->country == 'Taiwan' ? 'selected' : ''}} value="Taiwan">Taiwan</option>
                                <option {{$operator->country == 'Tajikistan' ? 'selected' : ''}} value="Tajikistan">Tajikistan</option>
                                <option {{$operator->country == 'Tanzania, United Republic of' ? 'selected' : ''}} value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                <option {{$operator->country == 'Thailand' ? 'selected' : ''}} value="Thailand">Thailand</option>
                                <option {{$operator->country == 'Timor-leste' ? 'selected' : ''}} value="Timor-leste">Timor-leste</option>
                                <option {{$operator->country == 'Togo' ? 'selected' : ''}} value="Togo">Togo</option>
                                <option {{$operator->country == 'Tokelau' ? 'selected' : ''}} value="Tokelau">Tokelau</option>
                                <option {{$operator->country == 'Tonga' ? 'selected' : ''}} value="Tonga">Tonga</option>
                                <option {{$operator->country == 'Trinidad and Tobago' ? 'selected' : ''}} value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option {{$operator->country == 'Tunisia' ? 'selected' : ''}} value="Tunisia">Tunisia</option>
                                <option {{$operator->country == 'Turkey' ? 'selected' : ''}} value="Turkey">Turkey</option>
                                <option {{$operator->country == 'Turkmenistan' ? 'selected' : ''}} value="Turkmenistan">Turkmenistan</option>
                                <option {{$operator->country == 'Turks and Caicos Islands' ? 'selected' : ''}} value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                <option {{$operator->country == 'Tuvalu' ? 'selected' : ''}} value="Tuvalu">Tuvalu</option>
                                <option {{$operator->country == 'Uganda' ? 'selected' : ''}} value="Uganda">Uganda</option>
                                <option {{$operator->country == 'Ukraine' ? 'selected' : ''}} value="Ukraine">Ukraine</option>
                                <option {{$operator->country == 'United Arab Emirates' ? 'selected' : ''}} value="United Arab Emirates">United Arab Emirates</option>
                                <option {{$operator->country == 'United Kingdom' ? 'selected' : ''}} value="United Kingdom">United Kingdom</option>
                                <option {{$operator->country == 'United States' ? 'selected' : ''}} value="United States">United States</option>
                                <option {{$operator->country == 'United States Minor Outlying Islands' ? 'selected' : ''}} value="United States Minor Outlying Islands">United States Minor Outlying Islands
                                </option>
                                <option {{$operator->country == 'Uruguay' ? 'selected' : ''}} value="Uruguay">Uruguay</option>
                                <option {{$operator->country == 'Uzbekistan' ? 'selected' : ''}} value="Uzbekistan">Uzbekistan</option>
                                <option {{$operator->country == 'Vanuatu' ? 'selected' : ''}} value="Vanuatu">Vanuatu</option>
                                <option {{$operator->country == 'Venezuela' ? 'selected' : ''}} value="Venezuela">Venezuela</option>
                                <option {{$operator->country == 'Viet Nam' ? 'selected' : ''}} value="Viet Nam">Viet Nam</option>
                                <option {{$operator->country == 'Virgin Islands, British' ? 'selected' : ''}} value="Virgin Islands, British">Virgin Islands, British</option>
                                <option {{$operator->country == 'Virgin Islands, U.S.' ? 'selected' : ''}} value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                <option {{$operator->country == 'Wallis and Futuna' ? 'selected' : ''}} value="Wallis and Futuna">Wallis and Futuna</option>
                                <option {{$operator->country == 'Western Sahara' ? 'selected' : ''}} value="Western Sahara">Western Sahara</option>
                                <option {{$operator->country == 'Yemen' ? 'selected' : ''}} value="Yemen">Yemen</option>
                                <option {{$operator->country == 'Zambia' ? 'selected' : ''}} value="Zambia">Zambia</option>
                                <option {{$operator->country == 'Zimbabwe' ? 'selected' : ''}} value="Zimbabwe">Zimbabwe</option>
                            </select>
                    </div>

                    <input type="hidden" name="id" value="{{ $operator->id}}" id="">


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Devise de l'opérateur </label>
                        <select name="currency" id="inputState" class="form-control">
                            <option {{$operator->currency == 'USD' ? 'selected' : ''}} value="USD" selected="selected" label="US dollar">USD</option>
                            <option {{$operator->currency == 'XOF' ? 'selected' : ''}} value="XOF" label="West African CFA franc">XOF</option>
                            <option {{$operator->currency == 'EUR' ? 'selected' : ''}} value="EUR" label="Euro">EUR</option>
                            <option {{$operator->currency == 'AED' ? 'selected' : ''}} value="AED" label="United Arab Emirates dirham">AED</option>
                            <option {{$operator->currency == 'JPY' ? 'selected' : ''}} value="JPY" label="Japanese yen">JPY</option>
                            <option {{$operator->currency == 'GBP' ? 'selected' : ''}} value="GBP" label="Pound sterling">GBP</option>
                            <option {{$operator->currency == '' ? 'selected' : ''}} disabled>──────────</option>
                            <option {{$operator->currency == 'AFN' ? 'selected' : ''}} value="AFN" label="Afghan afghani">AFN</option>
                            <option {{$operator->currency == 'ALL' ? 'selected' : ''}} value="ALL" label="Albanian lek">ALL</option>
                            <option {{$operator->currency == 'AMD' ? 'selected' : ''}} value="AMD" label="Armenian dram">AMD</option>
                            <option {{$operator->currency == 'ANG' ? 'selected' : ''}} value="ANG" label="Netherlands Antillean guilder">ANG</option>
                            <option {{$operator->currency == 'AOA' ? 'selected' : ''}} value="AOA" label="Angolan kwanza">AOA</option>
                            <option {{$operator->currency == 'ARS' ? 'selected' : ''}} value="ARS" label="Argentine peso">ARS</option>
                            <option {{$operator->currency == 'AUD' ? 'selected' : ''}} value="AUD" label="Australian dollar">AUD</option>
                            <option {{$operator->currency == 'AWG' ? 'selected' : ''}} value="AWG" label="Aruban florin">AWG</option>
                            <option {{$operator->currency == 'AZN' ? 'selected' : ''}} value="AZN" label="Azerbaijani manat">AZN</option>
                            <option {{$operator->currency == 'BAM' ? 'selected' : ''}} value="BAM" label="Bosnia and Herzegovina convertible mark">BAM</option>
                            <option {{$operator->currency == 'BBD' ? 'selected' : ''}} value="BBD" label="Barbadian dollar">BBD</option>
                            <option {{$operator->currency == 'BDT' ? 'selected' : ''}} value="BDT" label="Bangladeshi taka">BDT</option>
                            <option {{$operator->currency == 'BGN' ? 'selected' : ''}} value="BGN" label="Bulgarian lev">BGN</option>
                            <option {{$operator->currency == 'BHD' ? 'selected' : ''}} value="BHD" label="Bahraini dinar">BHD</option>
                            <option {{$operator->currency == 'BIF' ? 'selected' : ''}} value="BIF" label="Burundian franc">BIF</option>
                            <option {{$operator->currency == 'BMD' ? 'selected' : ''}} value="BMD" label="Bermudian dollar">BMD</option>
                            <option {{$operator->currency == 'BND' ? 'selected' : ''}} value="BND" label="Brunei dollar">BND</option>
                            <option {{$operator->currency == 'BOB' ? 'selected' : ''}} value="BOB" label="Bolivian boliviano">BOB</option>
                            <option {{$operator->currency == 'BRL' ? 'selected' : ''}} value="BRL" label="Brazilian real">BRL</option>
                            <option {{$operator->currency == 'BSD' ? 'selected' : ''}} value="BSD" label="Bahamian dollar">BSD</option>
                            <option {{$operator->currency == 'BTN' ? 'selected' : ''}} value="BTN" label="Bhutanese ngultrum">BTN</option>
                            <option {{$operator->currency == 'BWP' ? 'selected' : ''}} value="BWP" label="Botswana pula">BWP</option>
                            <option {{$operator->currency == 'BYN' ? 'selected' : ''}} value="BYN" label="Belarusian ruble">BYN</option>
                            <option {{$operator->currency == 'BZD' ? 'selected' : ''}} value="BZD" label="Belize dollar">BZD</option>
                            <option {{$operator->currency == 'CAD' ? 'selected' : ''}} value="CAD" label="Canadian dollar">CAD</option>
                            <option {{$operator->currency == 'CDF' ? 'selected' : ''}} value="CDF" label="Congolese franc">CDF</option>
                            <option {{$operator->currency == 'CHF' ? 'selected' : ''}} value="CHF" label="Swiss franc">CHF</option>
                            <option {{$operator->currency == 'CLP' ? 'selected' : ''}} value="CLP" label="Chilean peso">CLP</option>
                            <option {{$operator->currency == 'CNY' ? 'selected' : ''}} value="CNY" label="Chinese yuan">CNY</option>
                            <option {{$operator->currency == 'COP' ? 'selected' : ''}} value="COP" label="Colombian peso">COP</option>
                            <option {{$operator->currency == 'CRC' ? 'selected' : ''}} value="CRC" label="Costa Rican colón">CRC</option>
                            <option {{$operator->currency == 'CUC' ? 'selected' : ''}} value="CUC" label="Cuban convertible peso">CUC</option>
                            <option {{$operator->currency == 'CUP' ? 'selected' : ''}} value="CUP" label="Cuban peso">CUP</option>
                            <option {{$operator->currency == 'CVE' ? 'selected' : ''}} value="CVE" label="Cape Verdean escudo">CVE</option>
                            <option {{$operator->currency == 'CZK' ? 'selected' : ''}} value="CZK" label="Czech koruna">CZK</option>
                            <option {{$operator->currency == 'DJF' ? 'selected' : ''}} value="DJF" label="Djiboutian franc">DJF</option>
                            <option {{$operator->currency == 'DKK' ? 'selected' : ''}} value="DKK" label="Danish krone">DKK</option>
                            <option {{$operator->currency == 'DOP' ? 'selected' : ''}} value="DOP" label="Dominican peso">DOP</option>
                            <option {{$operator->currency == 'DZD' ? 'selected' : ''}} value="DZD" label="Algerian dinar">DZD</option>
                            <option {{$operator->currency == 'EGP' ? 'selected' : ''}} value="EGP" label="Egyptian pound">EGP</option>
                            <option {{$operator->currency == 'ERN' ? 'selected' : ''}} value="ERN" label="Eritrean nakfa">ERN</option>
                            <option {{$operator->currency == 'ETB' ? 'selected' : ''}} value="ETB" label="Ethiopian birr">ETB</option>
                            <option {{$operator->currency == 'EUR' ? 'selected' : ''}} value="EUR" label="EURO">EUR</option>
                            <option {{$operator->currency == 'FJD' ? 'selected' : ''}} value="FJD" label="Fijian dollar">FJD</option>
                            <option {{$operator->currency == 'FKP' ? 'selected' : ''}} value="FKP" label="Falkland Islands pound">FKP</option>
                            <option {{$operator->currency == 'GBP' ? 'selected' : ''}} value="GBP" label="British pound">GBP</option>
                            <option {{$operator->currency == 'GEL' ? 'selected' : ''}} value="GEL" label="Georgian lari">GEL</option>
                            <option {{$operator->currency == 'GGP' ? 'selected' : ''}} value="GGP" label="Guernsey pound">GGP</option>
                            <option {{$operator->currency == 'GHS' ? 'selected' : ''}} value="GHS" label="Ghanaian cedi">GHS</option>
                            <option {{$operator->currency == 'GIP' ? 'selected' : ''}} value="GIP" label="Gibraltar pound">GIP</option>
                            <option {{$operator->currency == 'GMD' ? 'selected' : ''}} value="GMD" label="Gambian dalasi">GMD</option>
                            <option {{$operator->currency == 'GNF' ? 'selected' : ''}} value="GNF" label="Guinean franc">GNF</option>
                            <option {{$operator->currency == 'GTQ' ? 'selected' : ''}} value="GTQ" label="Guatemalan quetzal">GTQ</option>
                            <option {{$operator->currency == 'GYD' ? 'selected' : ''}} value="GYD" label="Guyanese dollar">GYD</option>
                            <option {{$operator->currency == 'HKD' ? 'selected' : ''}} value="HKD" label="Hong Kong dollar">HKD</option>
                            <option {{$operator->currency == 'HNL' ? 'selected' : ''}} value="HNL" label="Honduran lempira">HNL</option>
                            <option {{$operator->currency == 'HRK' ? 'selected' : ''}} value="HRK" label="Croatian kuna">HRK</option>
                            <option {{$operator->currency == 'HTG' ? 'selected' : ''}} value="HTG" label="Haitian gourde">HTG</option>
                            <option {{$operator->currency == 'HUF' ? 'selected' : ''}} value="HUF" label="Hungarian forint">HUF</option>
                            <option {{$operator->currency == 'IDR' ? 'selected' : ''}} value="IDR" label="Indonesian rupiah">IDR</option>
                            <option {{$operator->currency == 'ILS' ? 'selected' : ''}} value="ILS" label="Israeli new shekel">ILS</option>
                            <option {{$operator->currency == 'IMP' ? 'selected' : ''}} value="IMP" label="Manx pound">IMP</option>
                            <option {{$operator->currency == 'INR' ? 'selected' : ''}} value="INR" label="Indian rupee">INR</option>
                            <option {{$operator->currency == 'IQD' ? 'selected' : ''}} value="IQD" label="Iraqi dinar">IQD</option>
                            <option {{$operator->currency == 'IRR' ? 'selected' : ''}} value="IRR" label="Iranian rial">IRR</option>
                            <option {{$operator->currency == 'ISK' ? 'selected' : ''}} value="ISK" label="Icelandic króna">ISK</option>
                            <option {{$operator->currency == 'JEP' ? 'selected' : ''}} value="JEP" label="Jersey pound">JEP</option>
                            <option {{$operator->currency == 'JMD' ? 'selected' : ''}} value="JMD" label="Jamaican dollar">JMD</option>
                            <option {{$operator->currency == 'JOD' ? 'selected' : ''}} value="JOD" label="Jordanian dinar">JOD</option>
                            <option {{$operator->currency == 'JPY' ? 'selected' : ''}} value="JPY" label="Japanese yen">JPY</option>
                            <option {{$operator->currency == 'KES' ? 'selected' : ''}} value="KES" label="Kenyan shilling">KES</option>
                            <option {{$operator->currency == 'KGS' ? 'selected' : ''}} value="KGS" label="Kyrgyzstani som">KGS</option>
                            <option {{$operator->currency == 'KHR' ? 'selected' : ''}} value="KHR" label="Cambodian riel">KHR</option>
                            <option {{$operator->currency == 'KID' ? 'selected' : ''}} value="KID" label="Kiribati dollar">KID</option>
                            <option {{$operator->currency == 'KMF' ? 'selected' : ''}} value="KMF" label="Comorian franc">KMF</option>
                            <option {{$operator->currency == 'KPW' ? 'selected' : ''}} value="KPW" label="North Korean won">KPW</option>
                            <option {{$operator->currency == 'KRW' ? 'selected' : ''}} value="KRW" label="South Korean won">KRW</option>
                            <option {{$operator->currency == 'KWD' ? 'selected' : ''}} value="KWD" label="Kuwaiti dinar">KWD</option>
                            <option {{$operator->currency == 'KYD' ? 'selected' : ''}} value="KYD" label="Cayman Islands dollar">KYD</option>
                            <option {{$operator->currency == 'KZT' ? 'selected' : ''}} value="KZT" label="Kazakhstani tenge">KZT</option>
                            <option {{$operator->currency == 'LAK' ? 'selected' : ''}} value="LAK" label="Lao kip">LAK</option>
                            <option {{$operator->currency == 'LBP' ? 'selected' : ''}} value="LBP" label="Lebanese pound">LBP</option>
                            <option {{$operator->currency == 'LKR' ? 'selected' : ''}} value="LKR" label="Sri Lankan rupee">LKR</option>
                            <option {{$operator->currency == 'LRD' ? 'selected' : ''}} value="LRD" label="Liberian dollar">LRD</option>
                            <option {{$operator->currency == 'LSL' ? 'selected' : ''}} value="LSL" label="Lesotho loti">LSL</option>
                            <option {{$operator->currency == 'LYD' ? 'selected' : ''}} value="LYD" label="Libyan dinar">LYD</option>
                            <option {{$operator->currency == 'MAD' ? 'selected' : ''}} value="MAD" label="Moroccan dirham">MAD</option>
                            <option {{$operator->currency == 'MDL' ? 'selected' : ''}} value="MDL" label="Moldovan leu">MDL</option>
                            <option {{$operator->currency == 'MGA' ? 'selected' : ''}} value="MGA" label="Malagasy ariary">MGA</option>
                            <option {{$operator->currency == 'MKD' ? 'selected' : ''}} value="MKD" label="Macedonian denar">MKD</option>
                            <option {{$operator->currency == 'MMK' ? 'selected' : ''}} value="MMK" label="Burmese kyat">MMK</option>
                            <option {{$operator->currency == 'MNT' ? 'selected' : ''}} value="MNT" label="Mongolian tögrög">MNT</option>
                            <option {{$operator->currency == 'MOP' ? 'selected' : ''}} value="MOP" label="Macanese pataca">MOP</option>
                            <option {{$operator->currency == 'MRU' ? 'selected' : ''}} value="MRU" label="Mauritanian ouguiya">MRU</option>
                            <option {{$operator->currency == 'MUR' ? 'selected' : ''}} value="MUR" label="Mauritian rupee">MUR</option>
                            <option {{$operator->currency == 'MVR' ? 'selected' : ''}} value="MVR" label="Maldivian rufiyaa">MVR</option>
                            <option {{$operator->currency == 'MWK' ? 'selected' : ''}} value="MWK" label="Malawian kwacha">MWK</option>
                            <option {{$operator->currency == 'MXN' ? 'selected' : ''}} value="MXN" label="Mexican peso">MXN</option>
                            <option {{$operator->currency == 'MYR' ? 'selected' : ''}} value="MYR" label="Malaysian ringgit">MYR</option>
                            <option {{$operator->currency == 'MZN' ? 'selected' : ''}} value="MZN" label="Mozambican metical">MZN</option>
                            <option {{$operator->currency == 'NAD' ? 'selected' : ''}} value="NAD" label="Namibian dollar">NAD</option>
                            <option {{$operator->currency == 'NGN' ? 'selected' : ''}} value="NGN" label="Nigerian naira">NGN</option>
                            <option {{$operator->currency == 'NIO' ? 'selected' : ''}} value="NIO" label="Nicaraguan córdoba">NIO</option>
                            <option {{$operator->currency == 'NOK' ? 'selected' : ''}} value="NOK" label="Norwegian krone">NOK</option>
                            <option {{$operator->currency == 'NPR' ? 'selected' : ''}} value="NPR" label="Nepalese rupee">NPR</option>
                            <option {{$operator->currency == 'NZD' ? 'selected' : ''}} value="NZD" label="New Zealand dollar">NZD</option>
                            <option {{$operator->currency == 'OMR' ? 'selected' : ''}} value="OMR" label="Omani rial">OMR</option>
                            <option {{$operator->currency == 'PAB' ? 'selected' : ''}} value="PAB" label="Panamanian balboa">PAB</option>
                            <option {{$operator->currency == 'PEN' ? 'selected' : ''}} value="PEN" label="Peruvian sol">PEN</option>
                            <option {{$operator->currency == 'PGK' ? 'selected' : ''}} value="PGK" label="Papua New Guinean kina">PGK</option>
                            <option {{$operator->currency == 'PHP' ? 'selected' : ''}} value="PHP" label="Philippine peso">PHP</option>
                            <option {{$operator->currency == 'PKR' ? 'selected' : ''}} value="PKR" label="Pakistani rupee">PKR</option>
                            <option {{$operator->currency == 'PLN' ? 'selected' : ''}} value="PLN" label="Polish złoty">PLN</option>
                            <option {{$operator->currency == 'PRB' ? 'selected' : ''}} value="PRB" label="Transnistrian ruble">PRB</option>
                            <option {{$operator->currency == 'PYG' ? 'selected' : ''}} value="PYG" label="Paraguayan guaraní">PYG</option>
                            <option {{$operator->currency == 'QAR' ? 'selected' : ''}} value="QAR" label="Qatari riyal">QAR</option>
                            <option {{$operator->currency == 'RON' ? 'selected' : ''}} value="RON" label="Romanian leu">RON</option>
                            <option {{$operator->currency == 'RSD' ? 'selected' : ''}} value="RSD" label="Serbian dinar">RSD</option>
                            <option {{$operator->currency == 'RUB' ? 'selected' : ''}} value="RUB" label="Russian ruble">RUB</option>
                            <option {{$operator->currency == 'RWF' ? 'selected' : ''}} value="RWF" label="Rwandan franc">RWF</option>
                            <option {{$operator->currency == 'SAR' ? 'selected' : ''}} value="SAR" label="Saudi riyal">SAR</option>
                            <option {{$operator->currency == 'SEK' ? 'selected' : ''}} value="SEK" label="Swedish krona">SEK</option>
                            <option {{$operator->currency == 'SGD' ? 'selected' : ''}} value="SGD" label="Singapore dollar">SGD</option>
                            <option {{$operator->currency == 'SHP' ? 'selected' : ''}} value="SHP" label="Saint Helena pound">SHP</option>
                            <option {{$operator->currency == 'SLL' ? 'selected' : ''}} value="SLL" label="Sierra Leonean leone">SLL</option>
                            <option {{$operator->currency == 'SLS' ? 'selected' : ''}} value="SLS" label="Somaliland shilling">SLS</option>
                            <option {{$operator->currency == 'SOS' ? 'selected' : ''}} value="SOS" label="Somali shilling">SOS</option>
                            <option {{$operator->currency == 'SRD' ? 'selected' : ''}} value="SRD" label="Surinamese dollar">SRD</option>
                            <option {{$operator->currency == 'SSP' ? 'selected' : ''}} value="SSP" label="South Sudanese pound">SSP</option>
                            <option {{$operator->currency == 'STN' ? 'selected' : ''}} value="STN" label="São Tomé and Príncipe dobra">STN</option>
                            <option {{$operator->currency == 'SYP' ? 'selected' : ''}} value="SYP" label="Syrian pound">SYP</option>
                            <option {{$operator->currency == 'SZL' ? 'selected' : ''}} value="SZL" label="Swazi lilangeni">SZL</option>
                            <option {{$operator->currency == 'THB' ? 'selected' : ''}} value="THB" label="Thai baht">THB</option>
                            <option {{$operator->currency == 'TJS' ? 'selected' : ''}} value="TJS" label="Tajikistani somoni">TJS</option>
                            <option {{$operator->currency == 'TMT' ? 'selected' : ''}} value="TMT" label="Turkmenistan manat">TMT</option>
                            <option {{$operator->currency == 'TND' ? 'selected' : ''}} value="TND" label="Tunisian dinar">TND</option>
                            <option {{$operator->currency == 'TOP' ? 'selected' : ''}} value="TOP" label="Tongan paʻanga">TOP</option>
                            <option {{$operator->currency == 'TRY' ? 'selected' : ''}} value="TRY" label="Turkish lira">TRY</option>
                            <option {{$operator->currency == 'TTD' ? 'selected' : ''}} value="TTD" label="Trinidad and Tobago dollar">TTD</option>
                            <option {{$operator->currency == 'TVD' ? 'selected' : ''}} value="TVD" label="Tuvaluan dollar">TVD</option>
                            <option {{$operator->currency == 'TWD' ? 'selected' : ''}} value="TWD" label="New Taiwan dollar">TWD</option>
                            <option {{$operator->currency == 'TZS' ? 'selected' : ''}} value="TZS" label="Tanzanian shilling">TZS</option>
                            <option {{$operator->currency == 'UAH' ? 'selected' : ''}} value="UAH" label="Ukrainian hryvnia">UAH</option>
                            <option {{$operator->currency == 'UGX' ? 'selected' : ''}} value="UGX" label="Ugandan shilling">UGX</option>
                            <option {{$operator->currency == 'USD' ? 'selected' : ''}} value="USD" label="United States dollar">USD</option>
                            <option {{$operator->currency == 'UYU' ? 'selected' : ''}} value="UYU" label="Uruguayan peso">UYU</option>
                            <option {{$operator->currency == 'UZS' ? 'selected' : ''}} value="UZS" label="Uzbekistani soʻm">UZS</option>
                            <option {{$operator->currency == 'VES' ? 'selected' : ''}} value="VES" label="Venezuelan bolívar soberano">VES</option>
                            <option {{$operator->currency == 'VND' ? 'selected' : ''}} value="VND" label="Vietnamese đồng">VND</option>
                            <option {{$operator->currency == 'VUV' ? 'selected' : ''}} value="VUV" label="Vanuatu vatu">VUV</option>
                            <option {{$operator->currency == 'WST' ? 'selected' : ''}} value="WST" label="Samoan tālā">WST</option>
                            <option {{$operator->currency == 'XAF' ? 'selected' : ''}} value="XAF" label="Central African CFA franc">XAF</option>
                            <option {{$operator->currency == 'XCD' ? 'selected' : ''}} value="XCD" label="Eastern Caribbean dollar">XCD</option>
                            <option {{$operator->currency == 'XPF' ? 'selected' : ''}} value="XPF" label="CFP franc">XPF</option>
                            <option {{$operator->currency == 'ZAR' ? 'selected' : ''}} value="ZAR" label="South African rand">ZAR</option>
                            <option {{$operator->currency == 'ZMW' ? 'selected' : ''}} value="ZMW" label="Zambian kwacha">ZMW</option>
                            <option {{$operator->currency == 'ZWB' ? 'selected' : ''}} value="ZWB" label="Zimbabwean bonds">ZWB</option>
                        </select>
                    </div>


                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Conversion en EUR</label>
                        <input name="euro_conversion" type="text" class="form-control  @error('euro_conversion') is-invalid @enderror"
                            value="{{ $operator->euro_conversion }}" value="{{ $operator->euro_conversion }}" placeholder="{{ $operator->euro_conversion }}"
                            required>

                        @error('euro_conversion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail10">Conversion en USSD </label>
                        <input name="dollar_conversion" type="text" class="form-control  @error('tel') is-invalid @enderror"
                            placeholder="{{ $operator->dollar_conversion }}" value="{{ $operator->dollar_conversion }}" required>

                        @error('dollar_conversion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Conversion en XAF </label>
                        <input type="text" name="xaf_conversion" class="form-control @error('email') is-invalid @enderror"
                            id="" placeholder="{{ $operator->xaf_conversion }}" value="{{ $operator->xaf_conversion }}">

                        @error('xaf_conversion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                </div>




                <div class="form-group">
                    <label>Description de l'operateur</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ $operator->description }}</textarea>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Sauvegarder</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
