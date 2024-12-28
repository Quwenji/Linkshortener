<?php
// templates/form.php
?>
<div class="bg-white p-6 rounded shadow max-w-md w-full">
    <h2 class="text-2xl font-bold mb-4 text-center">URL verkürzen</h2>

    <form action="/shorten" method="post" class="space-y-4">
        <!-- Lange URL -->
        <div>
            <label for="longUrl" class="block text-sm font-semibold mb-1">Lange URL:</label>
            <input
                type="url"
                id="longUrl"
                name="longUrl"
                class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="https://example.com"
                required
            />
        </div>

        <!-- Alias (optional) -->
        <div>
            <label for="alias" class="block text-sm font-semibold mb-1">Wunsch-Alias (optional):</label>
            <input
                type="text"
                id="alias"
                name="alias"
                class="border rounded w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="z.B. meinlink"
            />
            <p class="text-xs text-gray-500 mt-1">
                Lässt du dieses Feld leer, wird ein zufälliger Code generiert.
            </p>
        </div>

        <!-- Abschicken (roter Button) -->
        <button
            type="submit"
            class="block w-full bg-red-600 text-white font-semibold py-2 rounded hover:bg-red-700 transition-colors"
        >
            Verkürzen
        </button>
    </form>
</div>
