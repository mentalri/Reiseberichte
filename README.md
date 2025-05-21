# Travelreports PHP-Projekt

## Übersicht

Dieses Projekt ist eine PHP-basierte Webanwendung zum Teilen und Verwalten von Reiseberichten. Benutzer können sich registrieren, anmelden, Berichte erstellen, anderen Benutzern folgen und Profilbilder hochladen.

Der aktuelle Stand ist eine grundlegende Implementierung der Kernfunktionen, mit Verbesserungsmöglichkeiten in Bezug auf Sicherheit, Performance und Benutzererlebnis.

## Setup
1. Stelle sicher, dass das Verzeichnis `uploads/` beschreibbar ist.
2. Installiere die PHP-Erweiterung: `gd`.

## Fehlende Funktionen
- E-Mail-Kommunikation (z.B. Passwort zurücksetzen, Verifizierung, Benachrichtigungen)
- Pagination für Reiseberichte
- Kommentare löschen
- Kommentare liken/entliken & kommentieren

## Bekannte Probleme
- Datenänderungen werden in Teilen nicht übernommen. Diese Fehler sollten automatisch durch das Verwenden einer Datenbank behoben werden.
    - Benutzernamen Änderungen werden in Berichten so wie in Kommentaren nicht übernommen.
    - Wird ein Profil gelöscht, so wird das Profil nicht aus der Following-Liste, der Follower entfernt.
- Die Webseite ist nicht konsistent Web Accessible AA konform.
  - In Teilen verwenden wir mehrere Labels für ein Eingabefeld, für die onClick Funktionalität.
  - Die Sterne für die Bewertung auf der Index-Seite sind kontrastarm.
- Die Webseite zeigt Mängel bei der Responsiveness.
  - Die Vorschau auf Berichte ist nicht konsistent für jeden möglichen Input im Layout für mobile Geräte.
- Die CSS Struktur weist Mängel auf.
  - Die empfohlene Reihenfolge der Elemente wird nicht beachtet.
## Funktionen

- Benutzer-Authentifizierung (Login, Registrierung)
- Reiseberichte erstellen, bearbeiten und löschen
- Reiseberichte anderer Nutzer ansehen
- Reiseberichte nach Filtern durchsuchen (z.B. nach Datum, Ort)
- Reiseberichte kommentieren
- Reiseberichte liken/entliken
- Anderen Nutzern folgen/entfolgen
- Benutzerprofil verwalten (z.B. Profilbild hochladen, Passwort ändern, E-Mail ändern)
- Rückmeldungen für Benutzeraktionen



## Technologien
- PHP
- GD-Bibliothek für Bildverarbeitung

## Credits
Credits für z.B. Bilder und Icons, die wir angeben müssen:

- <a href="https://www.flaticon.com/de/kostenlose-icons/profilbild" title="profilbild Icons">Profilbild Icons erstellt von kliwir art \- Flaticon</a>
- <a href="https://www.flaticon.com/free-icons/delete" title="delete icons">Delete icons erstellt von Kiranshastry \- Flaticon</a>
- <a href="https://www.flaticon.com/free-icons/edit" title="edit icons">Edit icons erstellt von Kiranshastry \- Flaticon</a>
- <a href="https://www.flaticon.com/de/kostenlose-icons/bild" title="bild Icons">Bild Icons erstellt von Freepik - Flaticon</a>
- <a href="https://www.flaticon.com/de/kostenlose-icons/nachster" title="nächster Icons">Nächster Icons erstellt von Roundicons - Flaticon</a>

## Lizenz
Dieses Projekt dient zu Ausbildungszwecken.