<?php
// header.php
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Quwenji Linkshortener</title>
  <!-- Tailwind per CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <!-- Header -->
  <header class="bg-[#b60c0c] p-4 text-white">
    <div class="container mx-auto">
      <h1 class="text-xl font-bold">Quwenji Linkshortener</h1>
    </div>
  </header>

  <!-- Hauptbereich; flex-grow damit Footer unten bleibt -->
  <main class="flex-grow container mx-auto p-4">
