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

    h2 {
      text-align: center;
      text-decoration: underline;
    }

    .section {
      margin-top: 20px;
    }

    .field {
      margin-bottom: 15px;
    }

    .field label {
      display: inline-block;
      width: 250px;
      font-weight: bold;
    }

    .checkboxes {
      margin-top: 10px;
    }

    .checkboxes label {
      margin-right: 20px;
    }

    .signature-block {
      margin-top: 40px;
    }

    .signature {
      display: inline-block;
      width: 45%;
      vertical-align: top;
    }
  </style>
</head>
<body>

<h2>Reçu de Paiement / Recharge de Compte</h2>

<div class="section">
  <div class="field">
    <label>Numéro du reçu :</label> {{ $receipt_number }}
  </div>
  <div class="field">
    <label>Date :</label> {{ $date }}
  </div>
  <div class="field">
    <label>Nom du patient :</label> {{ $patient_name }}
  </div>
  <div class="field">
    <label>Numéro de compte :</label> {{ $account_number }}
  </div>
  <div class="field">
    <label>Montant reçu de M./Mme :</label> {{ $payer_name }}
  </div>
  <div class="field">
    <label>Montant payé :</label> {{ $amount }} FCFA
  </div>
  <div class="field">
    <label>Mode de paiement :</label>
    <div class="checkboxes">
      ☐ Espèces &nbsp;&nbsp; ☐ Virement bancaire &nbsp;&nbsp; ☐ Application mobile &nbsp;&nbsp; ☐ Carte bancaire
    </div>
  </div>
</div>

<div class="section">
  <strong>Objet du paiement :</strong><br>
  Recharge du compte électronique du patient pour couvrir les frais médicaux à l’hôpital, conformément au contrat d’assurance santé signé.
</div>

<table style="width: 100%; margin-top: 40px;">
  <tr>
    <td style="width: 50%; vertical-align: top;">
      <strong>Employé responsable :</strong><br><br>
      Nom : ____________________________<br><br>
      Signature : ____________________________
    </td>
    <td style="width: 50%; vertical-align: top; text-align: start;">
      <strong>Patient / Client :</strong><br><br>
      Signature : ____________________________<br><br>
      Cachet officiel de la clinique :<br>
    </td>
  </tr>
</table>


</body>
</html>
