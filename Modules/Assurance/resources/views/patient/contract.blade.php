<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12pt;
      line-height: 1.6;
    }

    h2, h3 {
      text-align: center;
      text-decoration: underline;
    }

    .section {
      margin-top: 20px;
    }

    .signature-block {
      margin-top: 40px;
    }

    .signature {
      width: 45%;
      display: inline-block;
      vertical-align: top;
    }

    .logo-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .logo-table td {
      width: 33.33%;
      border: none;
      padding: 0;
    }

    .logo-cell {
      text-align: center;
    }

    .logo-cell img {
      width: 150px;
      height: auto;
    }
  </style>
</head>
<body>
<table class="logo-table">
  <tr>
    <td></td>
    <td class="logo-cell">
      <img src="{{ asset('assets/img/logo/logo4.png') }}" alt="Clinique Hariri International Logo">
    </td>
    <td></td>
  </tr>
</table>
<h2>Contrat de prestation de services d’assurance santé</h2>

<div class="section">
  <strong>Partie I :</strong><br>
  <strong>Clinique Hariri International – N’Djamena, Tchad</strong><br>
  Représenté par : Dr Mahamat Abdoulaye Hariri<br>
  Adresse : N’Djamena Rue de 40m<br>
  Téléphone : +23566818808 / 66122555<br>
  Email : cliniquehariri@gmail.com
</div>

<div class="section">
  <strong>Partie II :</strong><br>
  M./Mme : {{ $client_name }}<br>
  N° d’identification / Passeport : {{ $passport_number }}<br>
  Date de naissance : {{ $birth_date }}<br>
  Adresse : {{ $client_address }}<br>
  Téléphone : {{ $client_phone }}
</div>

<div class="section">
  <h3>Préambule</h3>
  Vu la volonté de la deuxième partie (le client) de bénéficier des services d’assurance santé offerts par la première
  partie (la Clinique), les deux parties conviennent de conclure le présent contrat selon les clauses suivantes :
</div>

<div class="section">
  <h3>Article 1 : Objet du contrat</h3>
  Le présent contrat a pour objet la fourniture de services de soins médicaux et d’assurance santé au patient, contre
  paiement de frais déterminés à la clinique, couvrant les examens, les traitements et l’hébergement, selon la
  couverture définie.
</div>

<div class="section">
  <h3>Article 2 : Durée du contrat</h3>
  Le contrat est valable pour une durée d’un (1) an à compter de la date de signature. Il sera renouvelé automatiquement
  sauf notification écrite de non-renouvellement par l’une des parties au moins 30 jours avant l’expiration du contrat.
</div>

<div class="section">
  <h3>Article 3 : Couverture médicale</h3>
  <ul>
    <li>Consultations médicales</li>
    <li>Analyses de laboratoire et imagerie médicale</li>
    <li>Hospitalisation</li>
    <li>Interventions chirurgicales</li>
    <li>Services d’urgence</li>
    <li>Médicaments essentiels (sur prescription médicale)</li>
  </ul>
</div>

<div class="section">
  <h3>Article 4 : Responsabilités financières</h3>
  <ol>
    <li>La Clinique s’engage à créer un compte électronique dédié à la deuxième partie via l’application officielle de
      la Clinique, destiné à la gestion des paiements et au suivi du solde disponible pour les services médicaux.
    </li>
    <li>Le patient s’engage à payer des frais d’ouverture de compte s’élevant à {{ $account_fee }}.</li>
    <li>Le patient s’engage à recharger son compte avant l’épuisement du solde, afin d’assurer la continuité des
      prestations de soins.
    </li>
    <li>En cas de solde insuffisant ou épuisé, l’hôpital notifiera le patient via l’application ou tout autre moyen de
      communication pour l’inviter à recharger son compte dans un délai déterminé.
    </li>
  </ol>
</div>

<div class="section">
  <h3>Article 5 : Obligations de la Clinique</h3>
  <ol>
    <li>Fournir des soins médicaux conformes aux normes médicales en vigueur.</li>
    <li>Assurer la confidentialité des données du patient.</li>
    <li>Remettre des rapports médicaux périodiques sur demande du patient.</li>
  </ol>
</div>

<div class="section">
  <h3>Article 6 : Résiliation du contrat</h3>
  Chacune des parties peut résilier le contrat à tout moment moyennant un préavis écrit de 30 jours, ou immédiatement en
  cas de non-respect grave de l’une des clauses essentielles.
</div>

<div class="section">
  <h3>Article 7 : Règlement des litiges</h3>
  Tout litige né de ce contrat sera d’abord résolu à l’amiable. En cas d’échec, le différend sera porté devant le
  tribunal compétent de N’Djamena.
</div>

<table style="width: 100%; margin-top: 40px;">
  <tr>
    <td style="width: 50%; vertical-align: top;">
      <strong>Partie I (la Clinique)</strong><br><br>
      Nom : __________________<br>
      Fonction : _______________<br>
      Signature : ______________<br>
      Cachet : ________________
    </td>
    <td style="width: 50%; text-align: right; vertical-align: top;">
      <strong>Partie II (le client)</strong><br><br>
      Nom : {{ $client_name }}<br>
      Signature : ______________
    </td>
  </tr>
</table>

<div style="text-align: right; margin-top: 40px;">
  <strong>Date : {{ $date }}</strong>
</div>

</body>
</html>
