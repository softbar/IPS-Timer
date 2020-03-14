<a href="https://www.symcon.de"><img src="https://img.shields.io/badge/IP--Symcon-5.1-blue.svg?style=flat-square"/></a>
<a href="https://styleci.io/repos/116687340/"><img src="https://styleci.io/repos/116687340/shield" alt="StyleCI"></a>
<a href="https://travis-ci.org/symcon/Alexa"><img src="https://img.shields.io/travis/symcon/Alexa/master.svg?style=flat-square" alt="Build status"></a>

# Modul Timer v1.0

Funktion:

Timer Modul
- Ersteller: Xavier
- Version..: 1.0

**Inhaltsverzeichnis**
1. [Einleitung](#1-einleitung)
2. [Funktionsumfang](#2-funktionsumfang)
3. [Voraussetzungen](#3-voraussetzungen)
4. [Installation](#4-installation)
5. [Funktionsreferenz](#5-funktionsreferenz)
    - [TIMER_Add](#50-timer_add)
    - [TIMER_StartScript](#51-timer_startscript)
    - [TIMER_StartVariable](#52-timer_startvariable)
    - [TIMER_Exists](#53-timer_exists)
    - [TIMER_Start](#54-timer_start)
    - [TIMER_Stop](#55-timer_stop)
    - [TIMER_Remove](#56-timer_remove)
    - [TIMER_SetPermanent](#57-timer_setpermanent)
    - [TIMER_SetRepeats](#58-timer_setrepeats)
    - [TIMER_Get](#59-timer_get)
    - [TIMER_Set](#510-timer_set)
6. [Beispiele](#6-beispiele)

## 1. Einleitung

Wie oft habe ich es gebraucht, so eine einfach Funktion wie ausschalten nach xx Sekunden
und immer habe ich irgendwie mit Ereignissen gebastelt.
Nun, jetzt habe ich mir dafür das "Timer" Modul gebaut.
Zugegeben mit Ereignissen von IPS lässt sich viel mehr machen, dies brauche ich jedoch nicht überall und arbeite, zugegeben, lieber mit Skripten da dies für mich einfach übersichtlicher ist.

## 2. Funktionsumfang
## 3. Voraussetzungen
 - IP-Synmcon 5.2
 
## 4. Installation

## 5. Funktionsreferenz
### 50. TIMER_Add


Funktion:

Fügt der Liste einen neuen, deaktivierten, Timer hinzu

```php
  TIMER_Add ( int $TimerID, string $Ident, int $Seconds, int $Repeats, bool $Permanent, int $VariableID, string $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Seconds** Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
3. **$Repeats** Anzahl der Wiederholungen des Timers, Standard ist 1 mal
4. **$Permanent** Legt fest, ob der Timer nach Ablauf beibehalten und deaktiert, oder gelöscht wird. Standard ist das löschen nach der letzten Ausführung.
5. **$VariableID** ObjectID der zu schaltenden Variable, erlaubt sind Boolean, eger und float
6. **$ScriptContent** Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
7. **$VariableEndValue** Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
8. **$VariableStartValue** Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , keine Wert

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, andernfalls einen String mit dem Fehler.

Anmerkung:
>- ScriptContent kann den Quellcode ohne **< ?php ? >** und mit abschließendem **;** enthalten oder auch die **ID** eines IPS Skriptes
>- Mit Add darf der Timer nicht existieren andernfalls liefert die Funktion eine Fehlermeldung

---

### 51. TIMER_StartScript


Funktion:

Ändert und startet einen permanenten Skript Timer oder erstellt einen neuen normalen Skript Timer und startet diesen einmalig

```php
  TIMER_StartScript ( int $TimerID, string $Ident, int $Seconds, string $ScriptContent, $VariableEndValue, $VariableStartValue)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Seconds** Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
3. **$ScriptContent** Entweder reiner Quellkode oder die ObjectID eine vorhandenen IPS Skript
4. **$VariableEndValue** Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
5. **$VariableStartValue** Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard null , keine Wert

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

Anmerkung:
>- ScriptContent kann den Quellcode ohne **< ?php ? >** und mit abschließendem **;** enthalten oder auch die **ID** eines IPS Skriptes
>- Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( **0**, **''** oder **null** ) ist

---

### 52. TIMER_StartVariable


Funktion:

Ändert und startet einen permanenten Variable Timer oder erstellt einen neuen normalen Variable Timer und startet diesen einmalig

```php
  TIMER_StartVariable ( int $TimerID, string $Ident, int $Seconds, int $VariableID, $VariableEndValue, $VariableStartValue)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Seconds** Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden
3. **$VariableID** ObjectID der zu schaltenden Variable, erlaubt sind Boolean, eger und float
4. **$VariableEndValue** Der Wert der beim auslößen des Timers an die Variable oder das Skript übergeben wird
5. **$VariableStartValue** Der Wert der beim Start des Timers an die Variable oder das Skript übergeben wird, Standard **null** , kein Wert

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

Anmerkung:
>- Ist der Timer vorhanden wird er aktualisiert wenn es ein Permanenter Timer ist und der jeweilig angegebene Parameter NICHT leer ( **0**, **''** oder **null** ) ist

---

### 53. TIMER_Exists


Funktion:

Überprüft ob ein Timer bereits existiert

```php
  TIMER_Exists ( int $TimerID, string $Ident)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Liefert als Ergebnis: boolean Wird $Ident gefunden liefert die Funktion true, sonst false.

---

### 54. TIMER_Start


Funktion:

Startet einen permanenten oder gestoppten Timer neu

```php
  TIMER_Start ( int $TimerID, string $Ident, int $Seconds)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Seconds** Die Zeitspanne wann der Timer ausgelößt wird. Minimum sind 5 , Standard 120 Sekunden

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler.

---

### 55. TIMER_Stop


Funktion:

Stoppt den mit $Ident angegeben Timer

```php
  TIMER_Stop ( int $TimerID, string $Ident, bool $SendEndEvent)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$SendEndEvent** Wenn true wird vor den Stoppen, unabhängig von der Restlaufzeit, der Timer noch einmal ausgeführt

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

---

### 56. TIMER_Remove


Funktion:

Stoppt und löscht den mit $Ident angegeben Timer aus der Liste

```php
  TIMER_Remove ( int $TimerID, string $Ident)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

---

### 57. TIMER_SetPermanent


Funktion:

Wird ein Timer von Permanent auf nicht Permanent gestellt und die Wiederholungen bereits 0 sind wird er sofort gelöscht

Ein Permanenter Timer wird nach Ablauf der Wiederholungen nicht gelöscht sondern die Wiederholungen, sowie die Zeit, starten wieder von Anfang an. Damit kann der Timer mit Start einfach wieder von vorn beginnen.

```php
  TIMER_SetPermanent ( int $TimerID, string $Ident, bool $Permanent)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Permanent** Der neue Status von Permanent

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

Anmerkung:
>- Wenn ein neuer Timer erstellt wird ist er, per Vorgabe, nicht Permanent und wird nach Ablauf der Wiederholungen gelöscht

---

### 58. TIMER_SetRepeats


Funktion:

Ändert die Anzahl der Wiederholungen für einen aktiven oder permanenten Timer

```php
  TIMER_SetRepeats ( int $TimerID, string $Ident, int $Repeats)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Repeats** Anzahl der Wiederholungen des Timers, Standard ist 1 mal

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

---

### 59. TIMER_Get


Funktion:

Liefert ein Array mit Schlüsseln und Werten des Timers

```php
  TIMER_Get ( int $TimerID, string $Ident)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!

Liefert als Ergebnis: array Ein Array mit Schlüsseln und Werten des Timers names $Ident oder ein leeres Array wenn der Timer nicht gefunden wurde

---

### 510. TIMER_Set


Funktion:

Setzt ein Array mit Schlüsseln und Werten des Timers

```php
  TIMER_Set ( int $TimerID, string $Ident, array $Timer)
```

Parameter:
1. **$Ident** Eindeutige Timer Identifikation, frei wählbar und wird bei allen Aufrufen benötigt!
2. **$Timer** Ein Array mit Schlüsseln und Werten des Timers der geändert werden soll

Liefert als Ergebnis: boolean|string Bei Erfolg liefert die Funktion true, sonst einen String mit dem Fehler

Anmerkung:
>- Nur hiermit ist es nachträglich möglich einen Timer Ident zu ändern.
>- Schlüssel die nicht verwendet werden können vor dem setzten entfernt werden, ungültige Schlüssel werden ignoriert

---
## 6. Beispiele

- __Mit Permanenten Timern arbeiten__

mit einem Skript einmalig die Timer setzen

~~~~~~~~~~~~~php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_Add ( $TimerID , $Ident='Mein Timer', $Seconds=60, $Repeats=1,$Permanent=true, $VariableID, $ScriptContent='', $VariableEndValue=false, $VariableStartValue=true);
TIMER_Add ( $TimerID , 'Mein Script Timer',120, 1,true, 0, 'echo "Hallo ich bins";', null, null);

// nun kann in jedem Scrit oder im IPS Ereignis,als PHP code, der folgende Befehl eingegen werden
TIMER_Start( $TimerID, 'Mein Timer',0); // Sartet den Timer mit vorgabe 60 Sekunen , schaltet die $VariableID beim start auf $VariableStartValue (true) und beim beenden auf $VariableEndValue (false)
// oder
TIMER_Start( $TimerID, 'Mein Timer',120);// Sartet den Timer mit 120 Sekunen , alles andere wie oben
?>
~~~~~~~~~~~~~

- __Mit einfachen Timern arbeiten__

In einem beliebigen Script einen einfachen Timer starten

~~~~~~~~~~~~~php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_StartScript	( $TimerID, 'mein flur timer', 300, "echo 'Hallo';" , null,null);
//oder einen Timer nach 240 sek ausschalten
TIMER_StartVariable	( $TimerID, 'mein Bad timer', 240, $VariableID , false,true); 
?>
~~~~~~~~~~~~~

- __Timer verwalten__

Ein Timer kann vor dem beenden, oder wenn permanent, immer angehalten, gestartet oder gelöscht werden

~~~~~~~~~~~~~php
<?php
$TimerID = 51049; // ID meines Timer Moduls
$VariableID = 19272; // ID meiner Bool Variable STATE von einem HM Switch 

TIMER_Stop( $TimerID, 'mein Bad timer',false); // anhalten
TIMER_Start($TimerID,  'mein Bad timer',0 );// fortsetzen
TIMER_Remove( $TimerID, 'mein Bad timer',true); // löschen
?>
~~~~~~~~~~~~~

>Alle aktiven oder permanenten Timer können auch in der Modul Konfiguration angehalten,gestartet, gelöscht oder minimal geändert werden.

