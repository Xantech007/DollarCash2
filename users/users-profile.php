<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <?php  
        if(isset($_SESSION['success']))
        { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } unset($_SESSION['success']) ?>
        <?php  
        if(isset($_SESSION['error']))
        { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>               
        <?php } unset($_SESSION['error']) ?>

      <div class="row">
        <div class="col-xl-4">
          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <h2><?= $name ?></h2>             
            </div>
          </div>
        </div>

        <div class="col-xl-8">
          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>
              </ul>
              <div class="tab-content pt-2">
                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Name</div>
                    <div class="col-lg-9 col-md-8"><?= $name ?></div>
                  </div>          
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Country</div>
                    <div class="col-lg-9 col-md-8"><?= $country ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?= $address ?></div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $_SESSION['email'] ?></div>
                  </div>
                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                  <!-- Profile Edit Form -->
                  <form action="../codes/user-profile.php" method="POST">
                    <div class="row mb-3">
                      <label class="col-md-4 col-lg-3 col-form-label">Name</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="name" type="text" class="form-control" id="fullName" value="<?= $name ?>">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="address" type="text" class="form-control" id="Address" value="<?= $address ?>">
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="email" type="email" class="form-control" id="Email" value="<?= $_SESSION['email'] ?>" readonly>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                      <div class="col-md-8 col-lg-9">
                        <select name="country" class="form-control" id="Country">
                          <option value="" <?php if (empty($country)) echo 'selected'; ?>>Select Country</option>
                          <option value="Afghanistan" <?php if ($country == 'Afghanistan') echo 'selected'; ?>>Afghanistan</option>
                          <option value="Åland Islands" <?php if ($country == 'Åland Islands') echo 'selected'; ?>>Åland Islands</option>
                          <option value="Albania" <?php if ($country == 'Albania') echo 'selected'; ?>>Albania</option>
                          <option value="Algeria" <?php if ($country == 'Algeria') echo 'selected'; ?>>Algeria</option>
                          <option value="American Samoa" <?php if ($country == 'American Samoa') echo 'selected'; ?>>American Samoa</option>
                          <option value="Andorra" <?php if ($country == 'Andorra') echo 'selected'; ?>>Andorra</option>
                          <option value="Angola" <?php if ($country == 'Angola') echo 'selected'; ?>>Angola</option>
                          <option value="Anguilla" <?php if ($country == 'Anguilla') echo 'selected'; ?>>Anguilla</option>
                          <option value="Antarctica" <?php if ($country == 'Antarctica') echo 'selected'; ?>>Antarctica</option>
                          <option value="Antigua and Barbuda" <?php if ($country == 'Antigua and Barbuda') echo 'selected'; ?>>Antigua and Barbuda</option>
                          <option value="Argentina" <?php if ($country == 'Argentina') echo 'selected'; ?>>Argentina</option>
                          <option value="Armenia" <?php if ($country == 'Armenia') echo 'selected'; ?>>Armenia</option>
                          <option value="Aruba" <?php if ($country == 'Aruba') echo 'selected'; ?>>Aruba</option>
                          <option value="Australia" <?php if ($country == 'Australia') echo 'selected'; ?>>Australia</option>
                          <option value="Austria" <?php if ($country == 'Austria') echo 'selected'; ?>>Austria</option>
                          <option value="Azerbaijan" <?php if ($country == 'Azerbaijan') echo 'selected'; ?>>Azerbaijan</option>
                          <option value="Bahamas" <?php if ($country == 'Bahamas') echo 'selected'; ?>>Bahamas</option>
                          <option value="Bahrain" <?php if ($country == 'Bahrain') echo 'selected'; ?>>Bahrain</option>
                          <option value="Bangladesh" <?php if ($country == 'Bangladesh') echo 'selected'; ?>>Bangladesh</option>
                          <option value="Barbados" <?php if ($country == 'Barbados') echo 'selected'; ?>>Barbados</option>
                          <option value="Belarus" <?php if ($country == 'Belarus') echo 'selected'; ?>>Belarus</option>
                          <option value="Belgium" <?php if ($country == 'Belgium') echo 'selected'; ?>>Belgium</option>
                          <option value="Belize" <?php if ($country == 'Belize') echo 'selected'; ?>>Belize</option>
                          <option value="Benin" <?php if ($country == 'Benin') echo 'selected'; ?>>Benin</option>
                          <option value="Bermuda" <?php if ($country == 'Bermuda') echo 'selected'; ?>>Bermuda</option>
                          <option value="Bhutan" <?php if ($country == 'Bhutan') echo 'selected'; ?>>Bhutan</option>
                          <option value="Bolivia" <?php if ($country == 'Bolivia') echo 'selected'; ?>>Bolivia, Plurinational State of</option>
                          <option value="Bonaire" <?php if ($country == 'Bonaire') echo 'selected'; ?>>Bonaire, Sint Eustatius and Saba</option>
                          <option value="Bosnia and Herzegovina" <?php if ($country == 'Bosnia and Herzegovina') echo 'selected'; ?>>Bosnia and Herzegovina</option>
                          <option value="Botswana" <?php if ($country == 'Botswana') echo 'selected'; ?>>Botswana</option>
                          <option value="Bouvet Island" <?php if ($country == 'Bouvet Island') echo 'selected'; ?>>Bouvet Island</option>
                          <option value="Brazil" <?php if ($country == 'Brazil') echo 'selected'; ?>>Brazil</option>
                          <option value="British Indian Ocean Territory" <?php if ($country == 'British Indian Ocean Territory') echo 'selected'; ?>>British Indian Ocean Territory</option>
                          <option value="Brunei" <?php if ($country == 'Brunei') echo 'selected'; ?>>Brunei Darussalam</option>
                          <option value="Bulgaria" <?php if ($country == 'Bulgaria') echo 'selected'; ?>>Bulgaria</option>
                          <option value="Burkina Faso" <?php if ($country == 'Burkina Faso') echo 'selected'; ?>>Burkina Faso</option>
                          <option value="Burundi" <?php if ($country == 'Burundi') echo 'selected'; ?>>Burundi</option>
                          <option value="Cambodia" <?php if ($country == 'Cambodia') echo 'selected'; ?>>Cambodia</option>
                          <option value="Cameroon" <?php if ($country == 'Cameroon') echo 'selected'; ?>>Cameroon</option>
                          <option value="Canada" <?php if ($country == 'Canada') echo 'selected'; ?>>Canada</option>
                          <option value="Cape Verde" <?php if ($country == 'Cape Verde') echo 'selected'; ?>>Cape Verde</option>
                          <option value="Cayman Islands" <?php if ($country == 'Cayman Islands') echo 'selected'; ?>>Cayman Islands</option>
                          <option value="Central African Republic" <?php if ($country == 'Central African Republic') echo 'selected'; ?>>Central African Republic</option>
                          <option value="Chad" <?php if ($country == 'Chad') echo 'selected'; ?>>Chad</option>
                          <option value="Chile" <?php if ($country == 'Chile') echo 'selected'; ?>>Chile</option>
                          <option value="China" <?php if ($country == 'China') echo 'selected'; ?>>China</option>
                          <option value="Christmas Island" <?php if ($country == 'Christmas Island') echo 'selected'; ?>>Christmas Island</option>
                          <option value="Cocos" <?php if ($country == 'Cocos') echo 'selected'; ?>>Cocos (Keeling) Islands</option>
                          <option value="Colombia" <?php if ($country == 'Colombia') echo 'selected'; ?>>Colombia</option>
                          <option value="Comoros" <?php if ($country == 'Comoros') echo 'selected'; ?>>Comoros</option>
                          <option value="Congo" <?php if ($country == 'Congo') echo 'selected'; ?>>Congo</option>
                          <option value="Congo, the Democratic Republic" <?php if ($country == 'Congo, the Democratic Republic') echo 'selected'; ?>>Congo, the Democratic Republic of the</option>
                          <option value="Cook Islands" <?php if ($country == 'Cook Islands') echo 'selected'; ?>>Cook Islands</option>
                          <option value="Costa Rica" <?php if ($country == 'Costa Rica') echo 'selected'; ?>>Costa Rica</option>
                          <option value="Côte d'Ivoire" <?php if ($country == "Côte d'Ivoire") echo 'selected'; ?>>Côte d'Ivoire</option>
                          <option value="Croatia" <?php if ($country == 'Croatia') echo 'selected'; ?>>Croatia</option>
                          <option value="Cuba" <?php if ($country == 'Cuba') echo 'selected'; ?>>Cuba</option>
                          <option value="Curaçao" <?php if ($country == 'Curaçao') echo 'selected'; ?>>Curaçao</option>
                          <option value="Cyprus" <?php if ($country == 'Cyprus') echo 'selected'; ?>>Cyprus</option>
                          <option value="Czech Republic" <?php if ($country == 'Czech Republic') echo 'selected'; ?>>Czech Republic</option>
                          <option value="Denmark" <?php if ($country == 'Denmark') echo 'selected'; ?>>Denmark</option>
                          <option value="Djibouti" <?php if ($country == 'Djibouti') echo 'selected'; ?>>Djibouti</option>
                          <option value="Dominica" <?php if ($country == 'Dominica') echo 'selected'; ?>>Dominica</option>
                          <option value="Dominican Republic" <?php if ($country == 'Dominican Republic') echo 'selected'; ?>>Dominican Republic</option>
                          <option value="Ecuador" <?php if ($country == 'Ecuador') echo 'selected'; ?>>Ecuador</option>
                          <option value="Egypt" <?php if ($country == 'Egypt') echo 'selected'; ?>>Egypt</option>
                          <option value="El Salvador" <?php if ($country == 'El Salvador') echo 'selected'; ?>>El Salvador</option>
                          <option value="Equatorial Guinea" <?php if ($country == 'Equatorial Guinea') echo 'selected'; ?>>Equatorial Guinea</option>
                          <option value="Eritrea" <?php if ($country == 'Eritrea') echo 'selected'; ?>>Eritrea</option>
                          <option value="Estonia" <?php if ($country == 'Estonia') echo 'selected'; ?>>Estonia</option>
                          <option value="Ethiopia" <?php if ($country == 'Ethiopia') echo 'selected'; ?>>Ethiopia</option>
                          <option value="Falkland Islands" <?php if ($country == 'Falkland Islands') echo 'selected'; ?>>Falkland Islands (Malvinas)</option>
                          <option value="Faroe Islands" <?php if ($country == 'Faroe Islands') echo 'selected'; ?>>Faroe Islands</option>
                          <option value="Fiji" <?php if ($country == 'Fiji') echo 'selected'; ?>>Fiji</option>
                          <option value="Finland" <?php if ($country == 'Finland') echo 'selected'; ?>>Finland</option>
                          <option value="France" <?php if ($country == 'France') echo 'selected'; ?>>France</option>
                          <option value="French Guiana" <?php if ($country == 'French Guiana') echo 'selected'; ?>>French Guiana</option>
                          <option value="French Polynesia" <?php if ($country == 'French Polynesia') echo 'selected'; ?>>French Polynesia</option>
                          <option value="French Southern Territories" <?php if ($country == 'French Southern Territories') echo 'selected'; ?>>French Southern Territories</option>
                          <option value="Gabon" <?php if ($country == 'Gabon') echo 'selected'; ?>>Gabon</option>
                          <option value="Gambia" <?php if ($country == 'Gambia') echo 'selected'; ?>>Gambia</option>
                          <option value="Georgia" <?php if ($country == 'Georgia') echo 'selected'; ?>>Georgia</option>
                          <option value="Germany" <?php if ($country == 'Germany') echo 'selected'; ?>>Germany</option>
                          <option value="Ghana" <?php if ($country == 'Ghana') echo 'selected'; ?>>Ghana</option>
                          <option value="Gibraltar" <?php if ($country == 'Gibraltar') echo 'selected'; ?>>Gibraltar</option>
                          <option value="Greece" <?php if ($country == 'Greece') echo 'selected'; ?>>Greece</option>
                          <option value="Greenland" <?php if ($country == 'Greenland') echo 'selected'; ?>>Greenland</option>
                          <option value="Grenada" <?php if ($country == 'Grenada') echo 'selected'; ?>>Grenada</option>
                          <option value="Guadeloupe" <?php if ($country == 'Guadeloupe') echo 'selected'; ?>>Guadeloupe</option>
                          <option value="Guam" <?php if ($country == 'Guam') echo 'selected'; ?>>Guam</option>
                          <option value="Guatemala" <?php if ($country == 'Guatemala') echo 'selected'; ?>>Guatemala</option>
                          <option value="Guernsey" <?php if ($country == 'Guernsey') echo 'selected'; ?>>Guernsey</option>
                          <option value="Guinea" <?php if ($country == 'Guinea') echo 'selected'; ?>>Guinea</option>
                          <option value="Guinea-Bissau" <?php if ($country == 'Guinea-Bissau') echo 'selected'; ?>>Guinea-Bissau</option>
                          <option value="Guyana" <?php if ($country == 'Guyana') echo 'selected'; ?>>Guyana</option>
                          <option value="Haiti" <?php if ($country == 'Haiti') echo 'selected'; ?>>Haiti</option>
                          <option value="Heard Island" <?php if ($country == 'Heard Island') echo 'selected'; ?>>Heard Island and McDonald Islands</option>
                          <option value="Holy See" <?php if ($country == 'Holy See') echo 'selected'; ?>>Holy See (Vatican City State)</option>
                          <option value="Honduras" <?php if ($country == 'Honduras') echo 'selected'; ?>>Honduras</option>
                          <option value="Hong Kong" <?php if ($country == 'Hong Kong') echo 'selected'; ?>>Hong Kong</option>
                          <option value="Hungary" <?php if ($country == 'Hungary') echo 'selected'; ?>>Hungary</option>
                          <option value="Iceland" <?php if ($country == 'Iceland') echo 'selected'; ?>>Iceland</option>
                          <option value="India" <?php if ($country == 'India') echo 'selected'; ?>>India</option>
                          <option value="Indonesia" <?php if ($country == 'Indonesia') echo 'selected'; ?>>Indonesia</option>
                          <option value="Iran" <?php if ($country == 'Iran') echo 'selected'; ?>>Iran, Islamic Republic of</option>
                          <option value="Iraq" <?php if ($country == 'Iraq') echo 'selected'; ?>>Iraq</option>
                          <option value="Ireland" <?php if ($country == 'Ireland') echo 'selected'; ?>>Ireland</option>
                          <option value="Isle of Man" <?php if ($country == 'Isle of Man') echo 'selected'; ?>>Isle of Man</option>
                          <option value="Israel" <?php if ($country == 'Israel') echo 'selected'; ?>>Israel</option>
                          <option value="Italy" <?php if ($country == 'Italy') echo 'selected'; ?>>Italy</option>
                          <option value="Jamaica" <?php if ($country == 'Jamaica') echo 'selected'; ?>>Jamaica</option>
                          <option value="Japan" <?php if ($country == 'Japan') echo 'selected'; ?>>Japan</option>
                          <option value="Jersey" <?php if ($country == 'Jersey') echo 'selected'; ?>>Jersey</option>
                          <option value="Jordan" <?php if ($country == 'Jordan') echo 'selected'; ?>>Jordan</option>
                          <option value="Kazakhstan" <?php if ($country == 'Kazakhstan') echo 'selected'; ?>>Kazakhstan</option>
                          <option value="Kenya" <?php if ($country == 'Kenya') echo 'selected'; ?>>Kenya</option>
                          <option value="Kiribati" <?php if ($country == 'Kiribati') echo 'selected'; ?>>Kiribati</option>
                          <option value="Korea, Democratic" <?php if ($country == 'Korea, Democratic') echo 'selected'; ?>>Korea, Democratic People's Republic of</option>
                          <option value="Korea, Republic" <?php if ($country == 'Korea, Republic') echo 'selected'; ?>>Korea, Republic of</option>
                          <option value="Kuwait" <?php if ($country == 'Kuwait') echo 'selected'; ?>>Kuwait</option>
                          <option value="Kyrgyzstan" <?php if ($country == 'Kyrgyzstan') echo 'selected'; ?>>Kyrgyzstan</option>
                          <option value="Lao" <?php if ($country == 'Lao') echo 'selected'; ?>>Lao People's Democratic Republic</option>
                          <option value="Latvia" <?php if ($country == 'Latvia') echo 'selected'; ?>>Latvia</option>
                          <option value="Lebanon" <?php if ($country == 'Lebanon') echo 'selected'; ?>>Lebanon</option>
                          <option value="Lesotho" <?php if ($country == 'Lesotho') echo 'selected'; ?>>Lesotho</option>
                          <option value="Liberia" <?php if ($country == 'Liberia') echo 'selected'; ?>>Liberia</option>
                          <option value="Libya" <?php if ($country == 'Libya') echo 'selected'; ?>>Libya</option>
                          <option value="Liechtenstein" <?php if ($country == 'Liechtenstein') echo 'selected'; ?>>Liechtenstein</option>
                          <option value="Lithuania" <?php if ($country == 'Lithuania') echo 'selected'; ?>>Lithuania</option>
                          <option value="Luxembourg" <?php if ($country == 'Luxembourg') echo 'selected'; ?>>Luxembourg</option>
                          <option value="Macao" <?php if ($country == 'Macao') echo 'selected'; ?>>Macao</option>
                          <option value="Macedonia" <?php if ($country == 'Macedonia') echo 'selected'; ?>>Macedonia, the former Yugoslav Republic of</option>
                          <option value="Madagascar" <?php if ($country == 'Madagascar') echo 'selected'; ?>>Madagascar</option>
                          <option value="Malawi" <?php if ($country == 'Malawi') echo 'selected'; ?>>Malawi</option>
                          <option value="Malaysia" <?php if ($country == 'Malaysia') echo 'selected'; ?>>Malaysia</option>
                          <option value="Maldives" <?php if ($country == 'Maldives') echo 'selected'; ?>>Maldives</option>
                          <option value="Mali" <?php if ($country == 'Mali') echo 'selected'; ?>>Mali</option>
                          <option value="Malta" <?php if ($country == 'Malta') echo 'selected'; ?>>Malta</option>
                          <option value="Marshall Islands" <?php if ($country == 'Marshall Islands') echo 'selected'; ?>>Marshall Islands</option>
                          <option value="Martinique" <?php if ($country == 'Martinique') echo 'selected'; ?>>Martinique</option>
                          <option value="Mauritania" <?php if ($country == 'Mauritania') echo 'selected'; ?>>Mauritania</option>
                          <option value="Mauritius" <?php if ($country == 'Mauritius') echo 'selected'; ?>>Mauritius</option>
                          <option value="Mayotte" <?php if ($country == 'Mayotte') echo 'selected'; ?>>Mayotte</option>
                          <option value="Mexico" <?php if ($country == 'Mexico') echo 'selected'; ?>>Mexico</option>
                          <option value="Micronesia" <?php if ($country == 'Micronesia') echo 'selected'; ?>>Micronesia, Federated States of</option>
                          <option value="Moldova" <?php if ($country == 'Moldova') echo 'selected'; ?>>Moldova, Republic of</option>
                          <option value="Monaco" <?php if ($country == 'Monaco') echo 'selected'; ?>>Monaco</option>
                          <option value="Mongolia" <?php if ($country == 'Mongolia') echo 'selected'; ?>>Mongolia</option>
                          <option value="Montenegro" <?php if ($country == 'Montenegro') echo 'selected'; ?>>Montenegro</option>
                          <option value="Montserrat" <?php if ($country == 'Montserrat') echo 'selected'; ?>>Montserrat</option>
                          <option value="Morocco" <?php if ($country == 'Morocco') echo 'selected'; ?>>Morocco</option>
                          <option value="Mozambique" <?php if ($country == 'Mozambique') echo 'selected'; ?>>Mozambique</option>
                          <option value="Myanmar" <?php if ($country == 'Myanmar') echo 'selected'; ?>>Myanmar</option>
                          <option value="Namibia" <?php if ($country == 'Namibia') echo 'selected'; ?>>Namibia</option>
                          <option value="Nauru" <?php if ($country == 'Nauru') echo 'selected'; ?>>Nauru</option>
                          <option value="Nepal" <?php if ($country == 'Nepal') echo 'selected'; ?>>Nepal</option>
                          <option value="Netherlands" <?php if ($country == 'Netherlands') echo 'selected'; ?>>Netherlands</option>
                          <option value="New Caledonia" <?php if ($country == 'New Caledonia') echo 'selected'; ?>>New Caledonia</option>
                          <option value="New Zealand" <?php if ($country == 'New Zealand') echo 'selected'; ?>>New Zealand</option>
                          <option value="Nicaragua" <?php if ($country == 'Nicaragua') echo 'selected'; ?>>Nicaragua</option>
                          <option value="Niger" <?php if ($country == 'Niger') echo 'selected'; ?>>Niger</option>
                          <option value="Nigeria" <?php if ($country == 'Nigeria') echo 'selected'; ?>>Nigeria</option>
                          <option value="Niue" <?php if ($country == 'Niue') echo 'selected'; ?>>Niue</option>
                          <option value="Norfolk" <?php if ($country == 'Norfolk') echo 'selected'; ?>>Norfolk Island</option>
                          <option value="Mariana" <?php if ($country == 'Mariana') echo 'selected'; ?>>Northern Mariana Islands</option>
                          <option value="Norway" <?php if ($country == 'Norway') echo 'selected'; ?>>Norway</option>
                          <option value="Oman" <?php if ($country == 'Oman') echo 'selected'; ?>>Oman</option>
                          <option value="Pakistan" <?php if ($country == 'Pakistan') echo 'selected'; ?>>Pakistan</option>
                          <option value="Palau" <?php if ($country == 'Palau') echo 'selected'; ?>>Palau</option>
                          <option value="Palestinian" <?php if ($country == 'Palestinian') echo 'selected'; ?>>Palestinian Territory, Occupied</option>
                          <option value="Panama" <?php if ($country == 'Panama') echo 'selected'; ?>>Panama</option>
                          <option value="Papua" <?php if ($country == 'Papua') echo 'selected'; ?>>Papua New Guinea</option>
                          <option value="Paraguay" <?php if ($country == 'Paraguay') echo 'selected'; ?>>Paraguay</option>
                          <option value="Peru" <?php if ($country == 'Peru') echo 'selected'; ?>>Peru</option>
                          <option value="Philippines" <?php if ($country == 'Philippines') echo 'selected'; ?>>Philippines</option>
                          <option value="Pitcairn" <?php if ($country == 'Pitcairn') echo 'selected'; ?>>Pitcairn</option>
                          <option value="Poland" <?php if ($country == 'Poland') echo 'selected'; ?>>Poland</option>
                          <option value="Portugal" <?php if ($country == 'Portugal') echo 'selected'; ?>>Portugal</option>
                          <option value="Puerto Rico" <?php if ($country == 'Puerto Rico') echo 'selected'; ?>>Puerto Rico</option>
                          <option value="Qatar" <?php if ($country == 'Qatar') echo 'selected'; ?>>Qatar</option>
                          <option value="Réunion" <?php if ($country == 'Réunion') echo 'selected'; ?>>Réunion</option>
                          <option value="Romania" <?php if ($country == 'Romania') echo 'selected'; ?>>Romania</option>
                          <option value="Russian" <?php if ($country == 'Russian') echo 'selected'; ?>>Russian Federation</option>
                          <option value="Rwanda" <?php if ($country == 'Rwanda') echo 'selected'; ?>>Rwanda</option>
                          <option value="Saint Barthélemy" <?php if ($country == 'Saint Barthélemy') echo 'selected'; ?>>Saint Barthélemy</option>
                          <option value="Saint Helena" <?php if ($country == 'Saint Helena') echo 'selected'; ?>>Saint Helena, Ascension and Tristan da Cunha</option>
                          <option value="Saint Kitts" <?php if ($country == 'Saint Kitts') echo 'selected'; ?>>Saint Kitts and Nevis</option>
                          <option value="Saint Lucia" <?php if ($country == 'Saint Lucia') echo 'selected'; ?>>Saint Lucia</option>
                          <option value="Saint Martin" <?php if ($country == 'Saint Martin') echo 'selected'; ?>>Saint Martin (French part)</option>
                          <option value="Saint Pierre" <?php if ($country == 'Saint Pierre') echo 'selected'; ?>>Saint Pierre and Miquelon</option>
                          <option value="Saint Vincent" <?php if ($country == 'Saint Vincent') echo 'selected'; ?>>Saint Vincent and the Grenadines</option>
                          <option value="Samoa" <?php if ($country == 'Samoa') echo 'selected'; ?>>Samoa</option>
                          <option value="San Marino" <?php if ($country == 'San Marino') echo 'selected'; ?>>San Marino</option>
                          <option value="Sao Tome" <?php if ($country == 'Sao Tome') echo 'selected'; ?>>Sao Tome and Principe</option>
                          <option value="Saudi Arabia" <?php if ($country == 'Saudi Arabia') echo 'selected'; ?>>Saudi Arabia</option>
                          <option value="Senegal" <?php if ($country == 'Senegal') echo 'selected'; ?>>Senegal</option>
                          <option value="Serbia" <?php if ($country == 'Serbia') echo 'selected'; ?>>Serbia</option>
                          <option value="Seychelles" <?php if ($country == 'Seychelles') echo 'selected'; ?>>Seychelles</option>
                          <option value="Sierra Leone" <?php if ($country == 'Sierra Leone') echo 'selected'; ?>>Sierra Leone</option>
                          <option value="Singapore" <?php if ($country == 'Singapore') echo 'selected'; ?>>Singapore</option>
                          <option value="Sint Maarten" <?php if ($country == 'Sint Maarten') echo 'selected'; ?>>Sint Maarten (Dutch part)</option>
                          <option value="Slovakia" <?php if ($country == 'Slovakia') echo 'selected'; ?>>Slovakia</option>
                          <option value="Slovenia" <?php if ($country == 'Slovenia') echo 'selected'; ?>>Slovenia</option>
                          <option value="Solomon Islands" <?php if ($country == 'Solomon Islands') echo 'selected'; ?>>Solomon Islands</option>
                          <option value="Somalia" <?php if ($country == 'Somalia') echo 'selected'; ?>>Somalia</option>
                          <option value="South Africa" <?php if ($country == 'South Africa') echo 'selected'; ?>>South Africa</option>
                          <option value="South Georgia" <?php if ($country == 'South Georgia') echo 'selected'; ?>>South Georgia and the South Sandwich Islands</option>
                          <option value="South Sudan" <?php if ($country == 'South Sudan') echo 'selected'; ?>>South Sudan</option>
                          <option value="Spain" <?php if ($country == 'Spain') echo 'selected'; ?>>Spain</option>
                          <option value="Sri Lanka" <?php if ($country == 'Sri Lanka') echo 'selected'; ?>>Sri Lanka</option>
                          <option value="Sudan" <?php if ($country == 'Sudan') echo 'selected'; ?>>Sudan</option>
                          <option value="Suriname" <?php if ($country == 'Suriname') echo 'selected'; ?>>Suriname</option>
                          <option value="Svalbard" <?php if ($country == 'Svalbard') echo 'selected'; ?>>Svalbard and Jan Mayen</option>
                          <option value="Swaziland" <?php if ($country == 'Swaziland') echo 'selected'; ?>>Swaziland</option>
                          <option value="Sweden" <?php if ($country == 'Sweden') echo 'selected'; ?>>Sweden</option>
                          <option value="Switzerland" <?php if ($country == 'Switzerland') echo 'selected'; ?>>Switzerland</option>
                          <option value="Syrian" <?php if ($country == 'Syrian') echo 'selected'; ?>>Syrian Arab Republic</option>
                          <option value="Taiwan" <?php if ($country == 'Taiwan') echo 'selected'; ?>>Taiwan, Province of China</option>
                          <option value="Tajikistan" <?php if ($country == 'Tajikistan') echo 'selected'; ?>>Tajikistan</option>
                          <option value="Tanzania" <?php if ($country == 'Tanzania') echo 'selected'; ?>>Tanzania, United Republic of</option>
                          <option value="Thailand" <?php if ($country == 'Thailand') echo 'selected'; ?>>Thailand</option>
                          <option value="Timor" <?php if ($country == 'Timor') echo 'selected'; ?>>Timor-Leste</option>
                          <option value="Togo" <?php if ($country == 'Togo') echo 'selected'; ?>>Togo</option>
                          <option value="Tokelau" <?php if ($country == 'Tokelau') echo 'selected'; ?>>Tokelau</option>
                          <option value="Tonga" <?php if ($country == 'Tonga') echo 'selected'; ?>>Tonga</option>
                          <option value="Trinidad and Tobago" <?php if ($country == 'Trinidad and Tobago') echo 'selected'; ?>>Trinidad and Tobago</option>
                          <option value="Tunisia" <?php if ($country == 'Tunisia') echo 'selected'; ?>>Tunisia</option>
                          <option value="Turkey" <?php if ($country == 'Turkey') echo 'selected'; ?>>Turkey</option>
                          <option value="Turkmenistan" <?php if ($country == 'Turkmenistan') echo 'selected'; ?>>Turkmenistan</option>
                          <option value="Turks" <?php if ($country == 'Turks') echo 'selected'; ?>>Turks and Caicos Islands</option>
                          <option value="Tuvalu" <?php if ($country == 'Tuvalu') echo 'selected'; ?>>Tuvalu</option>
                          <option value="Uganda" <?php if ($country == 'Uganda') echo 'selected'; ?>>Uganda</option>
                          <option value="Ukraine" <?php if ($country == 'Ukraine') echo 'selected'; ?>>Ukraine</option>
                          <option value="United Arab Emirates" <?php if ($country == 'United Arab Emirates') echo 'selected'; ?>>United Arab Emirates</option>
                          <option value="United Kingdom" <?php if ($country == 'United Kingdom') echo 'selected'; ?>>United Kingdom</option>
                          <option value="United States" <?php if ($country == 'United States') echo 'selected'; ?>>United States</option>
                          <option value="United States Minor Outlying Islands" <?php if ($country == 'United States Minor Outlying Islands') echo 'selected'; ?>>United States Minor Outlying Islands</option>
                          <option value="Uruguay" <?php if ($country == 'Uruguay') echo 'selected'; ?>>Uruguay</option>
                          <option value="Uzbekistan" <?php if ($country == 'Uzbekistan') echo 'selected'; ?>>Uzbekistan</option>
                          <option value="Vanuatu" <?php if ($country == 'Vanuatu') echo 'selected'; ?>>Vanuatu</option>
                          <option value="Venezuela" <?php if ($country == 'Venezuela') echo 'selected'; ?>>Venezuela, Bolivarian Republic of</option>
                          <option value="Viet Nam" <?php if ($country == 'Viet Nam') echo 'selected'; ?>>Viet Nam</option>
                          <option value="Virgin Islands, British" <?php if ($country == 'Virgin Islands, British') echo 'selected'; ?>>Virgin Islands, British</option>
                          <option value="Virgin Islands, U.S" <?php if ($country == 'Virgin Islands, U.S') echo 'selected'; ?>>Virgin Islands, U.S.</option>
                          <option value="Wallis and Futuna" <?php if ($country == 'Wallis and Futuna') echo 'selected'; ?>>Wallis and Futuna</option>
                          <option value="Western Sahara" <?php if ($country == 'Western Sahara') echo 'selected'; ?>>Western Sahara</option>
                          <option value="Yemen" <?php if ($country == 'Yemen') echo 'selected'; ?>>Yemen</option>
                          <option value="Zambia" <?php if ($country == 'Zambia') echo 'selected'; ?>>Zambia</option>
                          <option value="Zimbabwe" <?php if ($country == 'Zimbabwe') echo 'selected'; ?>>Zimbabwe</option>
                        </select>
                      </div>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-secondary" name="update-profile">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->
                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">
                </div>
              </div><!-- End Bordered Tabs -->
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

 <?php include('inc/footer.php'); ?>
