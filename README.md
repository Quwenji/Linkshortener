# Quwenji Linkshortener

Ein **einfacher Linkshortener** in PHP, der URLs verkürzt und beim Aufruf automatisch auf die ursprüngliche Adresse weiterleitet. Ideal für alle, die eigene Kurzlinks verwalten wollen, ohne auf externe Dienste angewiesen zu sein.

![Logo](assets/profil.gif)

---

## Inhaltsverzeichnis

- [Features](#features)
- [Verzeichnisstruktur](#verzeichnisstruktur)
- [Voraussetzungen](#voraussetzungen)
- [Installation](#installation)
  - [1. Repository klonen](#1-repository-klonen)
  - [2. Datenbank einrichten](#2-datenbank-einrichten)
  - [3. Konfiguration](#3-konfiguration)
  - [4. Webserver konfigurieren](#4-webserver-konfigurieren)
- [Verwendung](#verwendung)
- [Fehlerbehebung](#fehlerbehebung)
- [Anpassungen](#anpassungen)
- [Lizenz](#lizenz)
- [Kontakt](#kontakt)

---

## Features

- **Schneller Einstieg**: Minimale Konfiguration, sofort nutzbar  
- **Datenbank**: Speichert Kurzlinks in MySQL/MariaDB  
- **Einfach erweiterbar**: Füge eigene Funktionen (Statistiken, Benutzerkonten, etc.) hinzu  
- **Komplett ohne Composer**: Alles in separaten Dateien, keine externen Abhängigkeiten  
- **Rewrite mit `.htaccess`**: „Schöne“ URLs ohne lange Query-Strings  
- **Modernes Design**: Responsives Layout mit Tailwind CSS  
- **Sicherheitsfeatures**: Schutz der `.env`-Datei und korrekte Handhabung von Benutzereingaben  

---

## Verzeichnisstruktur

Linkshortener/ ├── assets/ │ ├── profil.gif // Dein Profil-GIF │ └── favicon.png // Dein Favicon ├── .env // Umgebungsvariablen ├── .htaccess // Apache-Konfiguration ├── database.php // Datenbank-Verbindung und .env Parsing ├── header.php // Header mit Profil-GIF und Favicon ├── footer.php // Footer ├── index.php // Front Controller & Routing ├── shortenerController.php // Logik für URL-Verkürzung und Weiterleitung └── templates/ └── form.php // Eingabeformular

yaml
Code kopieren

---

## Voraussetzungen

Bevor du mit der Installation beginnst, stelle sicher, dass du die folgenden Voraussetzungen erfüllst:

- **Webserver**: Apache (mit `mod_rewrite` aktiviert) oder Nginx.
- **PHP**: Version 7.4 oder höher.
- **Datenbank**: MySQL oder MariaDB.
- **Tailwind CSS**: Wird via CDN eingebunden.
- **Keine externen PHP-Abhängigkeiten**: Das Projekt verwendet keine Composer-Pakete.

---

## Installation

### 1. Repository klonen

Klone das Repository auf deinen lokalen Rechner oder Server:

```bash
git clone https://github.com/Quwenji/Linkshortener.git
cd Linkshortener