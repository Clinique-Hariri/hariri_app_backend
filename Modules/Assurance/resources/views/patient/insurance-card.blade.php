<?php

?>
  <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assurance Cart - Clinique Hariri</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

  <!-- External Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

  <style>

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .card-container {
      background: white;
      /*border-radius: 15px;*/
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      width: 390px;
      height: 220px;
      /*transform: scale(1.2);*/
    }

    .card-header {
      position: relative; /* added */
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-title {
      background-color: #2A9A53;
      border-radius: 0 30px 30px 0;
      width: fit-content;
      color: white;
      font-size: 10px;
      padding: 5px 40px;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    .hospital-info {
      position: absolute;
      top: 8px;
      right: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
      z-index: 1; /* Make sure it’s above other elements */
    }

    .hospital-name {
      color: white;
      text-align: right;
    }

    .hospital-name h2 {
      font-size: 12px;
      font-weight: 700;
      margin-bottom: 2px;
      letter-spacing: 1px;
    }

    .hospital-name .subtitle {
      font-size: 8px;
      opacity: 0.9;
      font-weight: 400;
    }

    .hospital-name .arabic {
      font-size: 8px;
      margin-top: 2px;
      opacity: 0.9;
    }

    .card-content {
      padding: 15px;
      display: flex;
      gap: 15px;
      align-items: flex-start;
      height: 150px;
    }

    .left-section {
      display: flex;
      flex-direction: column;
      gap: 8px;
      min-width: 70px;
    }

    .photo-placeholder {
      width: 85px;
      height: 85px;
      background: #e0e0e0;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .barcode {
      width: 85px;
      height: 25px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .barcode svg {
      width: 100%;
      height: 100%;
    }

    .right-section {
      flex: 1;
    }

    .info-row {
      display: flex;
      margin-bottom: 6px;
      align-items: center;
    }

    .info-label {
      font-weight: 600;
      color: #333;
      width: 80px;
      font-size: 10px;
    }

    .info-separator {
      margin: 0 5px;
      font-weight: bold;
      color: #666;
      font-size: 10px;
    }

    .info-value {
      color: #555;
      font-size: 10px;
      font-weight: 500;

      max-width: 100%;
      white-space: normal;

      overflow-wrap: break-word;
      word-break: break-word;
    }

    .card-footer {
      background: #2A9A53;
      padding: 8px 15px;
      text-align: center;
      height: 25px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .footer-text {
      color: white;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
      .card-content {
        flex-direction: column;
        padding: 30px 20px;
        gap: 30px;
      }

      .hospital-info {
        position: static;
        margin-top: 15px;
        justify-content: center;
      }

      .hospital-name {
        text-align: center;
      }

      .hospital-name h2 {
        font-size: 20px;
      }

      .card-title {
        text-align: center;
        font-size: 24px;
      }

      .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
      }

      .info-label, .info-value {
        font-size: 16px;
      }

      .info-separator {
        display: none;
      }

      .left-section {
        align-items: center;
      }
    }
  </style>
</head>
<body>
<div class="card-container" id="card-container">
  <div class="card-header">
    <div class="card-title">Assurance Cart</div>
    <div class="hospital-info">
      <div class="logo"></div>
      <img src="{{asset('assets/img/logo/logo4.png')}}" alt="logo" style="width: auto; height: 40px">
    </div>
  </div>

  <div class="card-content">
    <div class="left-section">
      <div class="photo-placeholder">
        @if($user->avatar)
          <img src="{{ $user->avatar_url }}" alt="User Photo"
               style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">
        @endif
      </div>
      <div class="barcode">
        <svg id="barcode"></svg>
      </div>
    </div>

    <div class="right-section">
      <div class="info-row">
        <span class="info-label">Nom prénom</span>
        <span class="info-separator">:</span>
        <span class="info-value">{{ $user->fullname }}</span>
      </div>

      <div class="info-row">
        <span class="info-label">Assurance ID</span>
        <span class="info-separator">:</span>
        <span class="info-value">{{ $user->insurance_number }}</span>
      </div>

      <div class="info-row">
        <span class="info-label">Adresse</span>
        <span class="info-separator">:</span>
        <span class="info-value">{{ $user->full_address }}</span>
      </div>

      <div class="info-row">
        <span class="info-label">Téléphone</span>
        <span class="info-separator">:</span>
        <span class="info-value">{{ $user->phone }}</span>
      </div>

      <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-separator">:</span>
        <span class="info-value">{{ $user->email }}</span>
      </div>

      {{--                <div class="info-row">--}}
      {{--                    <span class="info-label">Date de fin</span>--}}
      {{--                    <span class="info-separator">:</span>--}}
      {{--                    <span class="info-value">11-12-2026</span>--}}
      {{--                </div>--}}
    </div>
  </div>

  <div class="card-footer">
    <div class="footer-text">Votre santé compte pour nous</div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    generateBarcode("{{ $user->insurance_number }}");
    setTimeout(() => {
      downloadPDF(); // Auto-download after 1 second
    }, 1000);
  });


  // Generate Bar Code
  function generateBarcode(value) {
    JsBarcode('#barcode', value, {
      format: 'CODE128',
      width: 1,
      height: 25,
      displayValue: false,
      margin: 0
    });
  }

  // Download the certificate as PDF
  async function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const element = document.getElementById('card-container');

    await loadImages(element);

    html2canvas(element, {
      scale: window.devicePixelRatio * 2,
      useCORS: true,
      allowTaint: false,
      backgroundColor: null
    }).then(canvas => {
      const imgData = canvas.toDataURL('image/png', 1.0);

      const pdfWidth = canvas.width * 0.264583; // px to mm
      const pdfHeight = canvas.height * 0.264583;

      const pdf = new jsPDF({
        orientation: pdfWidth > pdfHeight ? 'landscape' : 'portrait',
        unit: 'mm',
        format: [pdfWidth, pdfHeight]
      });

      pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
      pdf.save('assurance-card.pdf');
    }).catch(error => {
      console.error('Error generating PDF:', error);
    });
  }


  // Load images
  function loadImages(element) {
    return Promise.all(
      Array.from(element.getElementsByTagName('img'))
        .filter(img => !img.complete)
        .map(img => new Promise(resolve => {
          img.onload = img.onerror = resolve;
        }))
    );
  }

</script>
</body>
</html>
